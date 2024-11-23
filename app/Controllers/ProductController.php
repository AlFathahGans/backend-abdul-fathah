<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    // Pastikan properti ini sudah benar
    protected $modelName = 'App\Models\ProductModel'; // Deklarasikan model dengan benar

    public function __construct()
    {
        helper('jwt_helper');
    }

    // List products for customer
    public function index()
    {
        // Gunakan $this->modelName untuk mengakses model
        $products = model($this->modelName)->findAll(); // Gunakan fungsi `model()` untuk mengakses model

        if (!$products) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['message' => 'No products found']);
        }

        return $this->response
            ->setStatusCode(200)
            ->setJSON(['message' => 'Products Found','products' => $products]);
    }

    // Create product (merchant only)
    public function create()
    {
        $token = $this->request->getHeader('Authorization');
        if (!$token) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['message' => 'Token not provided']);
        }

        $decoded = validateJWT(substr($token->getValue(), 7)); // Remove "Bearer " prefix
        
        if (!$decoded) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['message' => 'Unauthorized']);
        }

        if ($decoded['role'] !== 'merchant') {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Only merchants can create products']);
        }

        // Periksa apakah user_id ini terdaftar sebagai merchant
        $merchantModel = new \App\Models\MerchantModel();
        $merchant = $merchantModel->where('user_id', $decoded['id'])->first();
        
        if (!$merchant) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'User is not a valid merchant']);
        }

        $productData = $this->request->getJSON();
        if (!$this->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ])) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $this->validator->getErrors()]);
        }

        $product = [
            'merchant_id' => $merchant['id'], // Use user ID from token
            'name' => $productData->name,
            'description' => $productData->description,
            'price' => $productData->price,
            'stock' => $productData->stock,
        ];

        model($this->modelName)->insert($product); // Gunakan model() untuk akses model

        return $this->response
            ->setStatusCode(201)
            ->setJSON(['message' => 'Product created successfully']);
    }

    // Update product (merchant only)
    public function update($id = null)
    {
        $token = $this->request->getHeader('Authorization');
        if (!$token) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['message' => 'Token not provided']);
        }

        $decoded = validateJWT(substr($token->getValue(), 7)); // Remove "Bearer " prefix
        if (!$decoded) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['message' => 'Unauthorized']);
        }

        if ($decoded['role'] !== 'merchant') {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Only merchants can update products']);
        }

        if (!model($this->modelName)->find($id)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['message' => "Product with ID $id not found"]);
        }

        $productData = $this->request->getJSON();
        if (!$this->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ])) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $this->validator->getErrors()]);
        }

        $product = [
            'name' => $productData->name,
            'description' => $productData->description,
            'price' => $productData->price,
            'stock' => $productData->stock,
        ];

        model($this->modelName)->update($id, $product); // Gunakan model() untuk akses model

        return $this->response
            ->setStatusCode(200)
            ->setJSON(['message' => 'Product updated successfully']);
    }

    // Delete product (merchant only)
    public function delete($id = null)
    {
        $token = $this->request->getHeader('Authorization');
        if (!$token) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['message' => 'Token not provided']);
        }

        $decoded = validateJWT(substr($token->getValue(), 7)); // Remove "Bearer " prefix
        if (!$decoded) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['message' => 'Unauthorized']);
        }

        if ($decoded['role'] !== 'merchant') {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Only merchants can delete products']);
        }

        if (!model($this->modelName)->find($id)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['message' => "Product with ID $id not found"]);
        }

        model($this->modelName)->delete($id); // Gunakan model() untuk akses model

        return $this->response
            ->setStatusCode(200)
            ->setJSON(['message' => 'Product deleted successfully']);
    }
}
