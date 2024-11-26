<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CorsFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Mengatur header CORS
        $response = \Config\Services::response();

        // Mengizinkan akses dari domain tertentu (ubah sesuai kebutuhan)
        $response->setHeader('Access-Control-Allow-Origin', '*'); // Atau gunakan domain spesifik
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->setHeader('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Requested-With, Authorization');
        $response->setHeader('Access-Control-Allow-Credentials', 'true');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa setelah request selesai
    }
}
