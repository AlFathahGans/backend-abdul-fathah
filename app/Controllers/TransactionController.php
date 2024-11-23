<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\CustomerModel;

class TransactionController extends BaseController
{
    // Deklarasikan model langsung sebagai properti
    protected $model;

    public function __construct()
    {
        // Inisialisasi model di konstruktor
        $this->model = new TransactionModel(); // Langsung buat objek model
        helper('jwt_helper');
    }

    public function create()
    {
        // Mendapatkan token dari header
        $token = $this->request->getHeader('Authorization');

        if (!$token) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['message' => 'Token not provided']);
        }

        $decoded = validateJWT(substr($token->getValue(), 7)); // Hapus "Bearer " prefix
        if (!$decoded) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['message' => 'Invalid or missing token']);
        }

        if ($decoded['role'] !== 'customer') {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Only customers can make a purchase']);
        }

        $transactionData = $this->request->getJSON();
        if (!isset($transactionData->products) || empty($transactionData->products)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['message' => 'No products provided for the transaction']);
        }

        $totalPrice = 0;
        $productModel = new ProductModel(); // Model Product untuk validasi produk

        // Periksa produk satu per satu
        foreach ($transactionData->products as $product) {
            $productDetails = $productModel->find($product->product_id);
            if (!$productDetails) {
                return $this->response
                    ->setStatusCode(404)
                    ->setJSON(['message' => "Product with ID {$product->product_id} not found"]);
            }

            // Hitung total harga
            $totalPrice += $productDetails['price'] * $product->quantity;
        }

        // Terapkan diskon dan ongkos kirim
        $shippingFee = ($totalPrice > 15000) ? 0 : 10000; // Ongkos kirim gratis jika lebih dari 15000
        $discount = ($totalPrice > 50000) ? ($totalPrice * 0.1) : 0; // Diskon 10% jika lebih dari 50000

        // Periksa apakah user_id ini terdaftar sebagai customer
        $customerModel = new CustomerModel();
        $customer = $customerModel->where('user_id', $decoded['id'])->first();

        if (!$customer) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['message' => 'Customer not found']);
        }

        // Data transaksi
        $transaction = [
            'customer_id' => $customer['id'], // ID pelanggan dari token
            'merchant_id' => $transactionData->merchant_id,
            'product_id' => $transactionData->products[0]->product_id, // Ambil product_id dari produk pertama
            'quantity' => $transactionData->products[0]->quantity, // Ambil quantity dari produk pertama
            'total_price' => $totalPrice - $discount,
            'shipping_fee' => $shippingFee,
            'discount' => $discount,
            'status' => 'pending',
        ];

        // Simpan transaksi ke database
        $this->model->insert($transaction);

        return $this->response
            ->setStatusCode(201)
            ->setJSON([
                'message' => 'Transaction created successfully',
                'transaction_detail' => $transaction
            ]);
    }
}
