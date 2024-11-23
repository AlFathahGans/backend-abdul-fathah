<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table      = 'customers';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'user_id', 
        'full_name', 
        'shipping_address', 
        'phone_number', 
    ];
    
    protected $useTimestamps = true;

    // You can add more methods if needed (e.g., for fetching customers, etc.)
}
