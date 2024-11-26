<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\MerchantModel;

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
        $data['title'] = "Products";
        return view('product/index', $data);
    }

    // List products for customer
    public function customer()
    {
        $productModel = new ProductModel(); // Sesuaikan nama model Anda
        $data['title'] = "Products";
        $data['products'] = $productModel->findAll(); // Mengambil semua data produk
        return view('product/customer', $data);
    }

    public function get_data_product()
    {
        $ses_user_id = session()->get('id');

        // Pastikan model merchant tersedia
        $merchantModel = new MerchantModel();
        $merchant = $merchantModel->where('user_id', $ses_user_id)->first();
        
        // log_message('debug', 'User ID: ' . $ses_user_id);  // Cek apakah ID ada


        if (!$merchant) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['message' => 'Merchant not found for this user']);
        }

        // Ambil produk berdasarkan merchant_id
        $products = model('ProductModel')->where('merchant_id', $merchant['id'])->findAll();
        if (!$products) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['message' => 'No products found']);
        }

        return $this->response
            ->setStatusCode(200)
            ->setJSON(['message' => 'Products Found', 'products' => $products]);
    }

    public function create()
    {
        $data['title'] = "Tambah Product";
        return view('product/create', $data);
    }

    // Store product (merchant only)
    public function store()
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
        $merchantModel = new MerchantModel();
        $merchant = $merchantModel->where('user_id', $decoded['id'])->first();

        if (!$merchant) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'User is not a valid merchant']);
        }

        // Cek tipe request: JSON atau FormData
        if ($this->request->getHeaderLine('Content-Type') === 'application/json') {
            $productData = (array) $this->request->getJSON(); // Pastikan data berupa array
        } else {
            $productData = $this->request->getPost(); // Data dari FormData
        }

        // Validasi data JSON tanpa `image`
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ];

        if ($this->request->getFile('image')) {
            // Tambahkan validasi untuk file jika diunggah
            $rules['image'] = [
                'uploaded[image]',
                'is_image[image]',
                'max_size[image,2048]',
                'mime_in[image,image/jpg,image/jpeg,image/gif,image/png]',
            ];
        }

        if (!$this->validate($rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $this->validator->getErrors()]);
        }

        // Handle image upload (jika ada file)
        $imagePath = null; // Default null
        $imageFile = $this->request->getFile('image');

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            if (!$imageFile->move(ROOTPATH . 'public/uploads/products/', $imageFile->getName())) {
                return $this->response
                    ->setStatusCode(500)
                    ->setJSON(['message' => 'File upload failed: ' . $imageFile->getErrorString()]);
            }
            $imagePath = 'uploads/products/' . $imageFile->getName();
        }

        // Simpan data produk
        $product = [
            'merchant_id' => $merchant['id'],
            'name' => $productData['name'],
            'description' => $productData['description'],
            'image' => $imagePath, // Tetap null jika tidak ada gambar diunggah
            'price' => $productData['price'],
            'stock' => $productData['stock'],
        ];

        model($this->modelName)->insert($product);

        return $this->response
            ->setStatusCode(201)
            ->setJSON(['status' => true, 'message' => 'Product created successfully']);
    }

    public function edit($id)
    {
        // Pastikan modelName telah didefinisikan di controller
        $model = model($this->modelName); 

        // Cari produk berdasarkan ID
        $product = $model->find($id);

        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Product with ID $id not found.");
        }

        // Data untuk dikirim ke view
        $data = [
            'title' => "Edit Product",
            'product' => $product, // Tambahkan produk ke data
        ];

        // Tampilkan view
        return view('product/edit', $data);
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

       // Cek tipe request: JSON atau FormData
        if ($this->request->getHeaderLine('Content-Type') === 'application/json') {
            $productData = $this->request->getJSON(); // Decode JSON ke array
        } else {
            $productData = $this->request->getPost(); // Data dari FormData
        }

        // Validasi data JSON tanpa `image`
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ];

        if ($this->request->getFile('image')) {
            // Tambahkan validasi untuk file jika diunggah
            $rules['image'] = [
                'uploaded[image]',
                'is_image[image]',
                'max_size[image,2048]',
                'mime_in[image,image/jpg,image/jpeg,image/gif,image/png]',
            ];
        }

        if (!$this->validate($rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $this->validator->getErrors()]);
        }

        $imagePath = null; // Default null
        $imageFile = $this->request->getFile('image');

        $existingProduct = model($this->modelName)->find($id);

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Delete the old image if a new one is uploaded
            if ($existingProduct['image'] && file_exists(ROOTPATH . 'public/' . $existingProduct['image'])) {
                unlink(ROOTPATH . 'public/' . $existingProduct['image']);
            }

            // Move the new image
            if (!$imageFile->move(ROOTPATH . 'public/uploads/products/', $imageFile->getName())) {
                return $this->response
                    ->setStatusCode(500)
                    ->setJSON(['message' => 'File upload failed: ' . $imageFile->getErrorString()]);
            }

            $imagePath = 'uploads/products/' . $imageFile->getName();
        } else {
            // If no new image is uploaded, retain the old image
            $imagePath = $existingProduct['image'];
        }

        $product = [
            'name' => $productData['name'],
            'description' => $productData['description'],
            'image' => $imagePath, // Tetap null jika tidak ada gambar diunggah
            'price' => $productData['price'],
            'stock' => $productData['stock'],
        ];

        model($this->modelName)->update($id, $product); // Gunakan model() untuk akses model

        return $this->response
            ->setStatusCode(200)
            ->setJSON(['status' => true,'message' => 'Product updated successfully']);
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
    
        $model = model($this->modelName);
        $product = $model->find($id);
    
        if (!$product) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['message' => "Product with ID $id not found"]);
        }
    
        // Hapus file gambar jika ada
        if (!empty($product['image']) && file_exists(ROOTPATH . 'public/uploads/products' . $product['image'])) {
            if (!unlink(ROOTPATH . 'public/' . $product['image'])) {
                return $this->response
                    ->setStatusCode(500)
                    ->setJSON(['message' => 'Failed to delete the image file.']);
            }
        }
    
        // Hapus data produk dari database
        $model->delete($id);
    
        return $this->response
            ->setStatusCode(200)
            ->setJSON(['status' => true, 'message' => 'Product deleted successfully']);
    }
}
