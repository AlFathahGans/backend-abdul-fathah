<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Validation\Validation;
use \Firebase\JWT\JWT;

class AuthController extends BaseController
{

    public function __construct()
    {
        // Load helper JWTHelper
        helper('jwt_helper');
    }

    public function login()
    {
        $credentials = $this->request->getJSON();

        $userModel = new UserModel();
        $user = $userModel->where('email', $credentials->email)->first();

        if (!$user || !password_verify($credentials->password, $user['password'])) {
            return $this->response
            ->setStatusCode(401)
            ->setJSON(['message' => 'Invalid credentials']);

        }

        if (!function_exists('generateJWT')) {
            log_message('error', 'Helper JwtHelper tidak berhasil diload.');
        }
        
        // Generate JWT token
        $jwt = generateJWT([
            'id' => $user['id'],
            'role' => $user['role'],
        ]);

        return $this->response
        ->setStatusCode(200)
        ->setJSON(['token' => $jwt, 'message' => 'Login successful']);

    }

    public function register()
    {
        $input = $this->request->getJSON();

        // Validasi input
        if (!$this->validate([
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'role' => 'required|in_list[merchant,customer]', // Role merchant or customer
        ])) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $this->validator->getErrors()]);
        }

        // Hash password
        $passwordHash = password_hash($input->password, PASSWORD_BCRYPT);

        // Siapkan data untuk penyimpanan
        $userModel = new UserModel();
        $userData = [
            'email' => $input->email,
            'password' => $passwordHash,
            'role' => $input->role,  // merchant or customer
        ];

        // Insert data user ke database
        $userId = $userModel->insert($userData);

        if (!$userId) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['message' => 'Failed to register user']);
        }

        // If the user is a merchant, insert into the merchants table
        if ($input->role == 'merchant') {
            $merchantData = [
                'user_id' => $userId,
                'store_name' => $input->store_name,  // Add the relevant fields for merchant
                'store_address' => $input->store_address,
                'store_phone' => $input->store_phone
            ];

            $merchantModel = new \App\Models\MerchantModel();
            $merchantModel->insert($merchantData);
        }
        
        // If the user is a customer, insert into the customers table
        if ($input->role == 'customer') {
            $customerData = [
                'user_id' => $userId,
                'full_name' => $input->full_name,  // Add the relevant fields for customer
                'shipping_address' => $input->shipping_address,
                'phone_number' => $input->phone_number,
            ];

            $customerModel = new \App\Models\CustomerModel();
            $customerModel->insert($customerData);
        }

        return $this->response
            ->setStatusCode(201)
            ->setJSON(['message' => 'User registered successfully']);
    }


}
