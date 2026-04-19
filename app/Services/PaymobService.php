<?php

namespace App\Services;

use GuzzleHttp\Client;
use Exception;

class PaymobService
{
    protected $client;
    protected $apiKey;
    protected $merchantId;
    protected $authToken;
    protected $integrationId;

    public function __construct()
    {
        $verifySsl = env('PAYMOB_VERIFY_SSL', config('app.env') !== 'local');
        $verifySsl = filter_var($verifySsl, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        $this->client = new Client([
            'verify' => $verifySsl === null ? config('app.env') !== 'local' : $verifySsl,
        ]);
        $this->apiKey = config('services.paymob.api_key');
        $this->merchantId = config('services.paymob.merchant_id');
        $this->integrationId = config('services.paymob.integration_id');
    }

    /**
     * Authenticate with Paymob API
     */
    public function authenticate()
    {
        try {
            $response = $this->client->post('https://accept.paymobsolutions.com/api/auth/tokens', [
                'json' => [
                    'api_key' => $this->apiKey
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $this->authToken = $data['token'];
            return $this->authToken;
        } catch (Exception $e) {
            throw new Exception('Paymob Authentication Failed: ' . $e->getMessage());
        }
    }

    /**
     * Create payment order
     */
    public function createOrder($amount, $bookingId, $email, $phone, $customerName)
    {
        try {
            if (!$this->authToken) {
                $this->authenticate();
            }

            $response = $this->client->post('https://accept.paymobsolutions.com/api/ecommerce/orders', [
                'json' => [
                    'auth_token' => $this->authToken,
                    'delivery_needed' => false,
                    'merchant_id' => $this->merchantId,
                    'amount_cents' => $amount * 100, // Convert to cents
                    'items' => [
                        [
                            'name' => "Booking #{$bookingId}",
                            'amount_cents' => $amount * 100,
                            'quantity' => 1
                        ]
                    ]
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $orderId = $data['id'];

            // Create payment intent/token
            $paymentResponse = $this->client->post('https://accept.paymobsolutions.com/api/acceptance/payment_keys', [
                'json' => [
                    'auth_token' => $this->authToken,
                    'amount_cents' => $amount * 100,
                    'expiration' => 3600, // 1 hour
                    'order_id' => $orderId,
                    'billing_data' => [
                        'apartment' => 'N/A',
                        'email' => $email,
                        'floor' => 'N/A',
                        'first_name' => $customerName,
                        'last_name' => 'N/A',
                        'phone_number' => $phone,
                        'postal_code' => '00000',
                        'city' => 'Cairo',
                        'country' => 'EG',
                        'state' => 'EG',
                        'street' => 'N/A',
                        'building' => 'N/A'
                    ],
                    'currency' => 'EGP',
                    'integration_id' => $this->integrationId
                ]
            ]);

            $paymentData = json_decode($paymentResponse->getBody(), true);
            
            return [
                'order_id' => $orderId,
                'payment_key' => $paymentData['token'],
                'amount' => $amount,
                'currency' => 'EGP'
            ];
        } catch (Exception $e) {
            throw new Exception('Failed to create order: ' . $e->getMessage());
        }
    }

    /**
     * Verify payment
     */
    public function verifyPayment($orderId)
    {
        try {
            if (!$this->authToken) {
                $this->authenticate();
            }

            $response = $this->client->get("https://accept.paymobsolutions.com/api/ecommerce/orders/{$orderId}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->authToken}"
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return $data;
        } catch (Exception $e) {
            throw new Exception('Failed to verify payment: ' . $e->getMessage());
        }
    }

    /**
     * Refund payment
     */
    public function refundPayment($transactionId, $amount)
    {
        try {
            if (!$this->authToken) {
                $this->authenticate();
            }

            $response = $this->client->post("https://accept.paymobsolutions.com/api/acceptance/void_transactions/{$transactionId}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->authToken}"
                ],
                'json' => [
                    'auth_token' => $this->authToken
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            // If void failed, try refund
            if (!isset($data['success']) || !$data['success']) {
                return $this->refundTransaction($transactionId, $amount);
            }

            return $data;
        } catch (Exception $e) {
            throw new Exception('Failed to refund payment: ' . $e->getMessage());
        }
    }

    /**
     * Refund transaction (partial or full)
     */
    public function refundTransaction($transactionId, $amount)
    {
        try {
            if (!$this->authToken) {
                $this->authenticate();
            }

            $response = $this->client->post("https://accept.paymobsolutions.com/api/acceptance/refund_transactions/{$transactionId}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->authToken}"
                ],
                'json' => [
                    'auth_token' => $this->authToken,
                    'amount_cents' => $amount * 100
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            throw new Exception('Failed to refund transaction: ' . $e->getMessage());
        }
    }

    /**
     * Get transaction details
     */
    public function getTransaction($transactionId)
    {
        try {
            if (!$this->authToken) {
                $this->authenticate();
            }

            $response = $this->client->get("https://accept.paymobsolutions.com/api/acceptance/transactions/{$transactionId}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->authToken}"
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            throw new Exception('Failed to get transaction: ' . $e->getMessage());
        }
    }
}
