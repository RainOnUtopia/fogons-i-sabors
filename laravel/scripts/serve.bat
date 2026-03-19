@echo off
setlocal

pushd "%~dp0.."

php artisan serve

popd
endlocal
