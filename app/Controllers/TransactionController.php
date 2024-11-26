<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\CustomerModel;
use App\Models\MerchantModel;

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

    public function index()
    {
        $ses_user_id = session()->get('id');

        // Ambil data customer berdasarkan user_id dari JWT
        $customerModel = new CustomerModel();
        $customer = $customerModel->where('user_id', $ses_user_id)->first();

        if (!$customer) {
            return redirect()->to('/')->with('error', 'Data customer tidak ditemukan.');
        }

        // Ambil semua transaksi milik customer dengan nama produk
        $transactions = $this->model
            ->select('transactions.*, products.name as product_name')
            ->join('products', 'products.id = transactions.product_id')
            ->where('transactions.customer_id', $customer['id'])
            ->orderBy('transactions.created_at', 'DESC')
            ->findAll();

        return view('transactions/index', [
            'title' => 'Daftar Transaksi',
            'transactions' => $transactions
        ]);
    }

    public function merchant()
    {
        $ses_user_id = session()->get('id'); // Ambil ID user dari sesi

        // Pastikan model merchant tersedia
        $merchantModel = new MerchantModel();
        $merchant = $merchantModel->where('user_id', $ses_user_id)->first();

        if (!$merchant) {
            return redirect()->to('/')->with('error', 'Data merchant tidak ditemukan.');
        }

        // Ambil transaksi berdasarkan merchant_id
        $transactions = $this->model
            ->select('transactions.*, products.name as product_name, customers.full_name as customer_name')
            ->join('products', 'products.id = transactions.product_id')
            ->join('customers', 'customers.id = transactions.customer_id') // Tambahkan customer
            ->where('transactions.merchant_id', $merchant['id'])
            ->orderBy('transactions.created_at', 'DESC')
            ->findAll();

        return view('transactions/merchant', [
            'title' => 'Daftar Transaksi Merchant',
            'transactions' => $transactions
        ]);
    }

    public function store()
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

        // Pastikan JSON didecode menjadi array
        $transactionData = (array) $this->request->getJSON(true);

        // Debug log
        log_message('debug', 'Data diterima: ' . print_r($transactionData, true));

        if (!isset($transactionData['products']) || empty($transactionData['products'])) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['message' => 'No products provided for the transaction']);
        }

        $totalPrice = 0;
        $productModel = new ProductModel();

        foreach ($transactionData['products'] as $product) {
            $productDetails = $productModel->find($product['product_id']);
            if (!$productDetails) {
                return $this->response
                    ->setStatusCode(404)
                    ->setJSON(['message' => "Product with ID {$product['product_id']} not found"]);
            }

            $totalPrice += $productDetails['price'] * $product['quantity'];
        }

        $shippingFee = ($totalPrice > 15000) ? 0 : 10000;
        $discount = ($totalPrice > 50000) ? ($totalPrice * 0.1) : 0;

        $customerModel = new CustomerModel();
        $customer = $customerModel->where('user_id', $decoded['id'])->first();

        if (!$customer) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['message' => 'Customer not found']);
        }

        $transaction = [
            'customer_id' => $customer['id'],
            'merchant_id' => $transactionData['merchant_id'],
            'product_id' => $transactionData['products'][0]['product_id'],
            'quantity' => $transactionData['products'][0]['quantity'],
            'total_price' => $totalPrice - $discount,
            'shipping_fee' => $shippingFee,
            'discount' => $discount,
            'status' => 'pending',
        ];

        $this->model->insert($transaction);

        return $this->response
            ->setStatusCode(201)
            ->setJSON([
                'status' => true,
                'message' => 'Transaction created successfully',
                'transaction_detail' => $transaction
            ]);
    }

}
