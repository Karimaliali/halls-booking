@echo off
title Eventy Pro Auto-Launcher
echo.
echo Preparing Environment...
echo.

REM This batch file should be in the USB root next to halls-booking and php folders
REM If you're seeing errors, ensure the structure is:
REM F:\halls-booking\
REM F:\php\
REM F\start_QAA'A2.bat

REM Navigate into halls-booking
cd /d "%~dp0halls-booking"
if "%cd%"=="" (
    echo Error: Could not change directory
    pause
    exit /b 1
)

echo Current directory: %cd%
echo.

REM Verify artisan exists
if not exist "artisan" (
    echo Error: artisan file not found in %cd%
    echo Please check that halls-booking folder is complete
    pause
    exit /b 1
)

REM Verify PHP exists
if not exist "..\php\php.exe" (
    echo Error: PHP not found at ..\php\php.exe
    echo Please ensure php folder is in the USB root
    pause
    exit /b 1
)

echo Running USB setup...
powershell -ExecutionPolicy Bypass -File "%~dp0setup_usb.ps1"

echo Skipping storage link creation on USB FAT32.
echo.
echo Starting server at http://127.0.0.1:8000
echo.

REM Start the server
..\php\php.exe artisan serve --host=127.0.0.1 --port=8000

pause