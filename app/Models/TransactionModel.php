<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'customer_id',
        'product_id',
        'merchant_id',
        'quantity',
        'total_price',
        'shipping_fee',
        'discount',
    ];
    protected $useTimestamps = true; // Menggunakan kolom created_at dan updated_at
}
