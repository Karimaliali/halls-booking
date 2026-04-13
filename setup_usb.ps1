$scriptRoot = Split-Path -Parent $MyInvocation.MyCommand.Path

if (Test-Path (Join-Path $scriptRoot 'halls-booking\artisan')) {
    $projectRoot = Join-Path $scriptRoot 'halls-booking'
} elseif (Test-Path (Join-Path $scriptRoot 'artisan')) {
    $projectRoot = $scriptRoot
} else {
    Write-Host "Error: cannot detect Laravel project root."
    Write-Host "Script root: $scriptRoot"
    exit 1
}

$source = Join-Path $projectRoot 'storage\app\public'
if (-Not (Test-Path $source)) {
    Write-Host "Error: source storage path not found: $source"
    exit 1
}

$target = Join-Path $projectRoot 'public\storage'
if (-Not (Test-Path $target)) {
    New-Item -ItemType Directory -Path $target | Out-Null
}

Write-Host "Setting up USB permissions and copying storage files..."

icacls "$projectRoot\storage" /grant "Everyone:F" /T /C | Out-Null
Copy-Item "$source\*" $target -Recurse -Force

Write-Host "Setup complete!"
