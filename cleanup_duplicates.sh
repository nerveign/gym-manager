#!/bin/bash
# Script untuk menghapus PaymentController duplikat

cd "c:/xampp/htdocs/ProwebGym/gym-manager/app/Http/Controllers/Customer"

echo "Menghapus PaymentController duplikat..."

# Hapus file duplikat
rm -f "PaymentController.php.backup"
echo "- PaymentController.php.backup dihapus"

rm -f "PaymentControllerClean.php"
echo "- PaymentControllerClean.php dihapus"

rm -f "PaymentControllerFixed.php"
echo "- PaymentControllerFixed.php dihapus"

rm -f "PaymentControllerNew.php"
echo "- PaymentControllerNew.php dihapus"

echo ""
echo "✅ Cleanup selesai! Hanya PaymentController.php yang tersisa."
echo ""
ls -la Payment*
