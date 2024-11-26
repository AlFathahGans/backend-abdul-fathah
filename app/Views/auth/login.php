<!DOCTYPE html>
<html class="h-full bg-indigo-600">

<head>
	<title>AMAN TEKNOLOGI SOLUSI - Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="<?= base_url(); ?>assets/images/logo-ATS.webp">
	<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
</head>

<body class="h-full">
	<div class="flex min-h-full flex-col justify-center px-6 py-12 pt-0 lg:px-8">
		<div class="sm:mx-auto sm:w-full sm:max-w-sm">
			<img class="mx-auto h-25 w-auto" src="<?= base_url(); ?>assets/images/logo-ATS.webp"
				alt="Aman Teknologi Solusi">
			<h2 class="mt-8 text-center text-2xl font-bold leading-9 tracking-tight text-white">Silahkan
				masuk untuk melanjutkan</h2>
		</div>

		<div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
			<div class="bg-white shadow-md rounded-lg p-6">
				<!-- Card wrapper -->
				<form class="space-y-4" id="loginForm">
					<div>
						<label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
						<div class="mt-2">
							<input id="email" name="email" type="email" autocomplete="email" required placeholder="Masukkan Email"
								class="block w-full rounded-md py-1.5 pl-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6">
						</div>
					</div>

					<div>
						<div class="flex items-center justify-between">
							<label for="password"
								class="block text-sm font-medium leading-6 text-gray-900">Password</label>
						</div>
						<div class="mt-2">
							<input id="password" name="password" type="password" autocomplete="current-password"
								required placeholder="Masukkan Password"
								class="block w-full rounded-md py-1.5 pl-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6">
						</div>
					</div>

					<div>
						<button type="submit"
							class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
							Masuk
						</button>
					</div>
				</form>
                <p class="mt-4 text-center text-sm text-gray-500">
                    Belum punya akun? 
                    <a href="<?= base_url('register'); ?>" class="font-semibold text-indigo-600 hover:text-indigo-500">
                        Silahkan daftar
                    </a>
                </p>
			</div>
		</div>
	</div>

<script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/jquery/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '<?= site_url('api/auth/login'); ?>',
            data: JSON.stringify({
                email: $('#email').val(),
                password: $('#password').val(),
            }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                // Simpan token di localStorage
                localStorage.setItem('jwt_token', response.token);
                
                Swal.fire({
                    type: 'success',
                    title: 'Login Berhasil!',
                    text: 'Selamat datang!',
                }).then(() => {
                    window.location.href = response.redirect; // Redirect ke URL yang dikirim server
                });
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: response?.message || 'Terjadi kesalahan.',
                });
            }
        });
    });
});
</script>

</body>

</html>
