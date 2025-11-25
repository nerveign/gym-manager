@echo off
echo Menghapus PaymentController duplikat yang tidak digunakan...

cd /d c:\xampp\htdocs\ProwebGym\gym-manager\app\Http\Controllers\Customer

if exist PaymentControllerNew.php (
    del PaymentControllerNew.php
    echo PaymentControllerNew.php dihapus
)

if exist PaymentControllerFixed.php (
    del PaymentControllerFixed.php
    echo PaymentControllerFixed.php dihapus
)

if exist PaymentControllerClean.php (
    del PaymentControllerClean.php
    echo PaymentControllerClean.php dihapus
)

if exist PaymentController.php.backup (
    del PaymentController.php.backup
    echo PaymentController.php.backup dihapus
)

echo.
echo Cleanup selesai! Hanya PaymentController.php yang tersisa.
pause
