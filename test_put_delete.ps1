# Test PUT and DELETE endpoints
$baseUrl = "http://127.0.0.1:8000/api"

# Register owner
$ownerBody = @{
    name = "Owner Test"
    email = "owner$(Get-Random)@example.com"
    password = "password123"
    password_confirmation = "password123"
    role = "owner"
} | ConvertTo-Json

$ownerRes = Invoke-WebRequest -Uri "$baseUrl/register" -Method Post -ContentType "application/json" -Body $ownerBody
$owner = $ownerRes.Content | ConvertFrom-Json
$token = $owner.token
Write-Host "Owner Token: $($token.Substring(0, 30))..."

# Create a hall
$hallBody = @{
    name = "Test Hall"
    price = 1000
    location = "Test Location"
    capacity = 100
} | ConvertTo-Json

$headers = @{Authorization = "Bearer $token"}
$hallRes = Invoke-WebRequest -Uri "$baseUrl/halls" -Method Post -ContentType "application/json" -Body $hallBody -Headers $headers
$hall = $hallRes.Content | ConvertFrom-Json
$hallId = $hall.hall.id
Write-Host "Hall ID: $hallId"
Write-Host "Hall Name: $($hall.hall.name)"

# Test PUT
Write-Host "`nTesting UPDATE (PUT) /api/halls/$hallId"
$updateBody = @{
    name = "Updated Hall Name"
    price = 2000
} | ConvertTo-Json

try {
    $putRes = Invoke-WebRequest -Uri "$baseUrl/halls/$hallId" -Method Put -ContentType "application/json" -Body $updateBody -Headers $headers
    Write-Host "PUT Status: $($putRes.StatusCode)"
    $putContent = $putRes.Content | ConvertFrom-Json
    Write-Host "Updated Hall: $($putContent.hall.name) - Price: $($putContent.hall.price)"
} catch {
    Write-Host "PUT Error Status: $($_.Exception.Response.StatusCode)"
}

# Test DELETE
Write-Host "`nTesting DELETE /api/halls/$hallId"
try {
    $delRes = Invoke-WebRequest -Uri "$baseUrl/halls/$hallId" -Method Delete -Headers $headers
    Write-Host "DELETE Status: $($delRes.StatusCode)"
    $delContent = $delRes.Content | ConvertFrom-Json
    Write-Host "Message: $($delContent.message)"
} catch {
    Write-Host "DELETE Error Status: $($_.Exception.Response.StatusCode)"
}

# Test 404 on deleted hall
Write-Host "`nTesting GET deleted hall (should be 404)"
try {
    $getRes = Invoke-WebRequest -Uri "$baseUrl/halls/$hallId" -Method Put -ContentType "application/json" -Body $updateBody -Headers $headers
}
catch {
    Write-Host "Expected 404 Status: $($_.Exception.Response.StatusCode)"
}
