<?php

namespace App\Models;

use CodeIgniter\Model;

class MerchantModel extends Model
{
    protected $table      = 'merchants';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'id',
        'user_id', 
        'store_name', 
        'store_address', 
        'store_phone', 
    ];
    
    protected $useTimestamps = true;

    // You can add more methods if needed (e.g., for fetching merchants, etc.)
}
