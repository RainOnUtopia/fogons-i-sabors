@echo off
setlocal

pushd "%~dp0.."

call composer install

echo.
echo Composer ha finalitzat. Prem una tecla per continuar amb npm install...
pause
call npm install

popd
endlocal
