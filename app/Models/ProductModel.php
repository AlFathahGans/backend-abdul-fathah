<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'merchant_id',
        'name',
        'description',
        'price',
        'stock',
    ];
    protected $useTimestamps = true; // Menggunakan kolom created_at dan updated_at
}
