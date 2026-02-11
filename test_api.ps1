# Test API Endpoints
$baseUrl = "http://127.0.0.1:8000/api"

Write-Host "========== Testing Hall Booking API ==========" -ForegroundColor Cyan

# Test 1: Status Check
Write-Host "`n1. Testing Status Endpoint" -ForegroundColor Yellow
$status = Invoke-WebRequest -Uri "$baseUrl/status" -Method Get
Write-Host "Status: $($status.StatusCode)"
Write-Host $status.Content

# Test 2: Register Owner
Write-Host "`n2. Registering Owner User" -ForegroundColor Yellow
$ownerData = @{
    name = "Owner User"
    email = "owner@example.com"
    password = "password123"
    password_confirmation = "password123"
    role = "owner"
} | ConvertTo-Json

$registerOwner = Invoke-WebRequest -Uri "$baseUrl/register" -Method Post -ContentType "application/json" -Body $ownerData
Write-Host "Status: $($registerOwner.StatusCode)"
$ownerResponse = $registerOwner.Content | ConvertFrom-Json
Write-Host "Owner ID: $($ownerResponse.user.id)"
Write-Host "Owner Token: $($ownerResponse.token)"
$ownerToken = $ownerResponse.token

# Test 3: Create Hall (Owner)
Write-Host "`n3. Creating Hall as Owner" -ForegroundColor Yellow
$hallData = @{
    name = "Grand Hall Wedding"
    price = 5000
    location = "Cairo - Maadi"
    capacity = 300
    main_image = "hall-image.jpg"
} | ConvertTo-Json

$headers = @{Authorization = "Bearer $ownerToken"}
$createHall = Invoke-WebRequest -Uri "$baseUrl/halls" -Method Post -ContentType "application/json" -Body $hallData -Headers $headers
Write-Host "Status: $($createHall.StatusCode)"
$hallResponse = $createHall.Content | ConvertFrom-Json
Write-Host "Hall ID: $($hallResponse.hall.id)"
Write-Host "Hall Name: $($hallResponse.hall.name)"
Write-Host "Hall Price: $($hallResponse.hall.price)"
$hallId = $hallResponse.hall.id

# Test 4: Update Hall (Owner)
Write-Host "`n4. Updating Hall as Owner" -ForegroundColor Yellow
$updateData = @{
    name = "Grand Hall Wedding - Updated"
    price = 6000
} | ConvertTo-Json

$updateHall = Invoke-WebRequest -Uri "$baseUrl/halls/$hallId" -Method Put -ContentType "application/json" -Body $updateData -Headers $headers
Write-Host "Status: $($updateHall.StatusCode)"
$updatedHall = $updateHall.Content | ConvertFrom-Json
Write-Host "Updated Name: $($updatedHall.hall.name)"
Write-Host "Updated Price: $($updatedHall.hall.price)"

# Test 5: Get All Halls
Write-Host "`n5. Getting All Halls" -ForegroundColor Yellow
$getAllHalls = Invoke-WebRequest -Uri "$baseUrl/halls" -Method Get
Write-Host "Status: $($getAllHalls.StatusCode)"
$hallsList = $getAllHalls.Content | ConvertFrom-Json
Write-Host "Total Halls: $($hallsList.Count)"
if ($hallsList.Count -gt 0) {
    Write-Host "First Hall: $($hallsList[0].name) - Price: $($hallsList[0].price)"
}

# Test 6: Register Another User (Customer)
Write-Host "`n6. Registering Customer User" -ForegroundColor Yellow
$customerData = @{
    name = "Customer User"
    email = "customer@example.com"
    password = "password123"
    password_confirmation = "password123"
    role = "customer"
} | ConvertTo-Json

$registerCustomer = Invoke-WebRequest -Uri "$baseUrl/register" -Method Post -ContentType "application/json" -Body $customerData
$customerResponse = $registerCustomer.Content | ConvertFrom-Json
$customerToken = $customerResponse.token
Write-Host "Customer ID: $($customerResponse.user.id)"
Write-Host "Customer Token: $($customerToken.Substring(0, 20))..."

# Test 7: Try to Update Hall as Different User (Should Fail - 403)
Write-Host "`n7. Testing Authorization - Customer Trying to Update Owner's Hall" -ForegroundColor Yellow
$customerHeaders = @{Authorization = "Bearer $customerToken"}
try {
    $unauthorizedUpdate = Invoke-WebRequest -Uri "$baseUrl/halls/$hallId" -Method Put -ContentType "application/json" -Body $updateData -Headers $customerHeaders -ErrorAction Stop
}
catch {
    Write-Host "Expected Error - Status: $($_.Exception.Response.StatusCode)"
    $errorStream = $_.Exception.Response.Content
    $reader = New-Object System.IO.StreamReader($errorStream)
    $errorContent = $reader.ReadToEnd() | ConvertFrom-Json
    Write-Host "Error Message: $($errorContent.message)"
}

# Test 8: Delete Hall (Owner)
Write-Host "`n8. Deleting Hall as Owner" -ForegroundColor Yellow
$deleteHall = Invoke-WebRequest -Uri "$baseUrl/halls/$hallId" -Method Delete -Headers $headers
Write-Host "Status: $($deleteHall.StatusCode)"
$deleteResponse = $deleteHall.Content | ConvertFrom-Json
Write-Host "Message: $($deleteResponse.message)"

# Test 9: Try to Delete Non-existent Hall (Should Fail - 404)
Write-Host "`n9. Testing 404 - Trying to Delete Non-existent Hall" -ForegroundColor Yellow
try {
    $notFound = Invoke-WebRequest -Uri "$baseUrl/halls/99999" -Method Delete -Headers $headers -ErrorAction Stop
}
catch {
    Write-Host "Expected Error - Status: $($_.Exception.Response.StatusCode)"
}

Write-Host "`n========== All Tests Completed Successfully ==========" -ForegroundColor Green
