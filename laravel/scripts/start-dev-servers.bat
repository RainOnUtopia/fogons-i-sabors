@echo off
setlocal

pushd "%~dp0.."

start "Vite" cmd /k "cd /d %CD% && npm run dev"
start "Laravel" cmd /k "cd /d %CD% && php artisan serve"

popd
endlocal
