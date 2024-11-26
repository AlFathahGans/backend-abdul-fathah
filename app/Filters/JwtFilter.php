<?php
 
 namespace App\Filters;

 use CodeIgniter\Filters\FilterInterface;
 use CodeIgniter\HTTP\RequestInterface;
 use CodeIgniter\HTTP\ResponseInterface;
 use Firebase\JWT\JWT;
 use Firebase\JWT\Key;
 use Config\Services;
 
 class JWTFilter implements FilterInterface
 {
     public function before(RequestInterface $request, $arguments = null)
     {
         $authHeader = $request->getHeaderLine('Authorization');
         if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
             return Services::response()
                 ->setJSON(['message' => 'Access denied, missing or invalid token.'])
                 ->setStatusCode(401);
         }
 
         $token = str_replace('Bearer ', '', $authHeader);
         try {
             $key = getenv('JWT_SECRET');
             $decoded = JWT::decode($token, new Key($key, 'HS256'));
             // Token valid, Anda dapat menyimpan data user di request
             $request->user = $decoded;
         } catch (\Exception $e) {
             return Services::response()
                 ->setJSON(['message' => 'Access denied, token invalid.'])
                 ->setStatusCode(401);
         }
     }
 
     public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
     {
         // Tidak ada aksi setelah request selesai
     }
 }
 