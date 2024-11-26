<!DOCTYPE html>
<html class="h-full bg-indigo-600">

<head>
    <title>AMAN TEKNOLOGI SOLUSI - Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= base_url(); ?>assets/images/logo-ATS.webp">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
</head>

<body class="h-full">
    <div class="flex min-h-full flex-col justify-center px-6 py-12 pt-0 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-25 w-auto" src="<?= base_url(); ?>assets/images/logo-ATS.webp" alt="Aman Teknologi Solusi">
            <h2 class="mt-8 text-center text-2xl font-bold leading-9 tracking-tight text-white">Silahkan daftar untuk melanjutkan</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <div class="bg-white shadow-md rounded-lg p-6">
                <!-- Card wrapper -->
                <form class="space-y-4" id="registerForm">
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required placeholder="Masukkan Email"
                                class="block w-full rounded-md py-1.5 pl-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                        </div>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="Masukkan Password"
                                class="block w-full rounded-md py-1.5 pl-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="role" class="block text-sm font-medium leading-6 text-gray-900">Role</label>
                        </div>
                        <div class="mt-2">
                            <select name="role" id="role" class="block w-full rounded-md py-1.5 pl-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6" required>
                                <option value="">[Pilih Role]</option>
                                <option value="merchant">Merchant</option>
                                <option value="customer">Customer</option>
                            </select>
                        </div>
                    </div>

                    <!-- Form fields for merchant -->
                    <div id="merchantFields" class="hidden">
                        <div>
                            <label for="store_name" class="block text-sm font-medium leading-6 text-gray-900">Nama Toko</label>
                            <div class="mt-2">
                                <input id="store_name" name="store_name" type="text" required placeholder="Nama Toko"
                                    class="block w-full rounded-md py-1.5 pl-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div>
                            <label for="store_address" class="block text-sm font-medium leading-6 text-gray-900">Alamat Toko</label>
                            <div class="mt-2">
                                <input id="store_address" name="store_address" type="text" required placeholder="Alamat Toko"
                                    class="block w-full rounded-md py-1.5 pl-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div>
                            <label for="store_phone" class="block text-sm font-medium leading-6 text-gray-900">Nomor Telepon Toko</label>
                            <div class="mt-2">
                                <input id="store_phone" name="store_phone" type="text" required placeholder="Nomor Telepon Toko"
                                    class="block w-full rounded-md py-1.5 pl-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6">
                            </div>
                        </div>
                    </div>

                    <!-- Form fields for customer -->
                    <div id="customerFields" class="hidden">
                        <div>
                            <label for="full_name" class="block text-sm font-medium leading-6 text-gray-900">Nama Lengkap</label>
                            <div class="mt-2">
                                <input id="full_name" name="full_name" type="text" required placeholder="Nama Lengkap"
                                    class="block w-full rounded-md py-1.5 pl-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div>
                            <label for="shipping_address" class="block text-sm font-medium leading-6 text-gray-900">Alamat Pengiriman</label>
                            <div class="mt-2">
                                <input id="shipping_address" name="shipping_address" type="text" required placeholder="Alamat Pengiriman"
                                    class="block w-full rounded-md py-1.5 pl-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div>
                            <label for="phone_number" class="block text-sm font-medium leading-6 text-gray-900">Nomor Telepon</label>
                            <div class="mt-2">
                                <input id="phone_number" name="phone_number" type="text" required placeholder="Nomor Telepon"
                                    class="block w-full rounded-md py-1.5 pl-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6">
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Daftar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/jquery/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Show and hide fields based on selected role
    $('#role').on('change', function() {
        if ($(this).val() == 'merchant') {
            $('#merchantFields').show().find('input').attr('required', true);
            $('#customerFields').hide().find('input').removeAttr('required');
        } else if ($(this).val() == 'customer') {
            $('#customerFields').show().find('input').attr('required', true);
            $('#merchantFields').hide().find('input').removeAttr('required');
        } else {
            $('#merchantFields').hide().find('input').removeAttr('required');
            $('#customerFields').hide().find('input').removeAttr('required');
        }
    });

    // Initial state when page loads
    $('#role').trigger('change');

    $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '<?= site_url('api/auth/register'); ?>',
            data: JSON.stringify({
                email: $('#email').val(),
                password: $('#password').val(),
                role: $('#role').val(),
                store_name: $('#store_name').val(),
                store_address: $('#store_address').val(),
                store_phone: $('#store_phone').val(),
                full_name: $('#full_name').val(),
                shipping_address: $('#shipping_address').val(),
                phone_number: $('#phone_number').val(),
            }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                Swal.fire({
                    type: 'success',
                    title: 'Register Berhasil!',
                    text: 'Anda akan diarahkan ke halaman Login!',
                }).then(() => {
                    window.location.href = response.redirect;
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
