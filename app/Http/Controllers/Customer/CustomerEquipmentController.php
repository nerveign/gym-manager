<?php

namespace App\Http\Controllers\Customer;  // ← Pastikan ini benar!

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;

class CustomerEquipmentController extends Controller
{
    public function show($id)
    {
        $equipment = Equipment::findOrFail($id);
        $user = Auth::user();
        
        return view('customer.equipment-detail', compact('equipment', 'user'));
    }
}
