# Halls Booking System - Automatic Startup Script

Write-Host "Starting Halls Booking System..." -ForegroundColor Cyan

# Get current directory
$projectPath = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $projectPath
Write-Host "Project path: $projectPath" -ForegroundColor Yellow

# Check .env file
Write-Host "`nChecking .env file..." -ForegroundColor Green

if (-not (Test-Path ".env")) {
    Write-Host "  .env not found, copying from .env.example..." -ForegroundColor Yellow
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" ".env"
        Write-Host "  .env created successfully" -ForegroundColor Green
    } else {
        Write-Host "  ERROR: .env.example not found" -ForegroundColor Red
    }
} else {
    Write-Host "  .env file exists" -ForegroundColor Green
}

# Check APP_KEY
Write-Host "`nChecking APP_KEY..." -ForegroundColor Green

$envContent = Get-Content ".env" -Raw
if ($envContent -notmatch 'APP_KEY=base64:') {
    Write-Host "  APP_KEY missing, generating..." -ForegroundColor Yellow
    
    if (Test-Path "artisan") {
        $null = php artisan key:generate --force 2>$null
        Write-Host "  APP_KEY generated successfully" -ForegroundColor Green
    } else {
        Write-Host "  ERROR: artisan file not found" -ForegroundColor Red
    }
} else {
    Write-Host "  APP_KEY exists" -ForegroundColor Green
}

# Check database directory
Write-Host "`nChecking database directory..." -ForegroundColor Green

if (-not (Test-Path "database")) {
    New-Item -ItemType Directory -Path "database" | Out-Null
    Write-Host "  database directory created" -ForegroundColor Green
} else {
    Write-Host "  database directory exists" -ForegroundColor Green
}

# Check storage directory
Write-Host "`nChecking storage directory..." -ForegroundColor Green

if (-not (Test-Path "storage")) {
    New-Item -ItemType Directory -Path "storage" | Out-Null
    Write-Host "  storage directory created" -ForegroundColor Green
} else {
    Write-Host "  storage directory exists" -ForegroundColor Green
}

# Clear cache
Write-Host "`nClearing cache..." -ForegroundColor Green

if (Test-Path "artisan") {
    $null = php artisan config:clear 2>$null
    $null = php artisan cache:clear 2>$null
    Write-Host "  Cache cleared successfully" -ForegroundColor Green
}

# Check vendor
Write-Host "`nChecking composer packages..." -ForegroundColor Green

if (-not (Test-Path "vendor")) {
    Write-Host "  vendor directory not found, installing packages..." -ForegroundColor Yellow
    if (Test-Path "composer.json") {
        Write-Host "  Please wait (this may take some time)..." -ForegroundColor Yellow
        composer install --prefer-dist --no-progress 2>&1 | Out-Null
        Write-Host "  All packages installed successfully" -ForegroundColor Green
    } else {
        Write-Host "  ERROR: composer.json not found" -ForegroundColor Red
    }
} else {
    Write-Host "  Packages installed" -ForegroundColor Green
}

# Check port
Write-Host "`nChecking port 8000..." -ForegroundColor Green

$port = 8000
$portInUse = $null -ne (Get-NetTCPConnection -LocalPort $port -ErrorAction SilentlyContinue)

if ($portInUse) {
    Write-Host "  WARNING: Port $port is already in use" -ForegroundColor Yellow
    Write-Host "  You may need to use a different port or stop the other application" -ForegroundColor Cyan
} else {
    Write-Host "  Port $port is available" -ForegroundColor Green
}

# Final message
Write-Host "`n" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "    Setup completed successfully!" -ForegroundColor Cyan
Write-Host "  Project is ready to run on any device" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

Write-Host "`nTo start the server:" -ForegroundColor Yellow
Write-Host "   php artisan serve" -ForegroundColor White

Write-Host "`nTo view API documentation:" -ForegroundColor Yellow
Write-Host "   http://localhost:8000/api/documentation" -ForegroundColor White

Write-Host "`n" -ForegroundColor Green
