<?php 
// Mendapatkan session data
$ses_role  = session()->get('role');
$ses_full_name = session()->get('full_name');
$ses_email = session()->get('email');
?>
<html class="h-full">

<head>
    <title><?= $title; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= base_url(); ?>assets/images/logo-ATS.webp">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="<?= base_url(); ?>assets/libs/jquery/jquery.min.js" type="text/javascript"></script>

    <?= $this->renderSection('head') ?>
    <!-- Section Dinamis untuk tambahan head -->
</head>

<body class="h-full">
    <div class="min-h-full">
        <nav class="bg-indigo-600">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img width="60" height="50" src="<?= base_url(); ?>assets/images/logo-ATS.webp"
                                alt="ATS">
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="<?= site_url('products/customer'); ?>"
                                    class="font-medium text-gray-100 hover:text-gray-300 relative">
                                    Produk
                                    <span
                                        class="absolute top-6 left-0 w-full h-0.5 bg-white transition-all duration-300 transform scale-x-0 group-hover:scale-x-100 <?= (service('uri')->getSegment(1) == 'products') ? 'scale-x-100' : '' ?>"></span>
                                </a>
                                <a href="<?= site_url('transactions/customer'); ?>"
                                    class="font-medium text-gray-100 hover:text-gray-300 relative">
                                    Transaksi
                                    <span
                                        class="absolute bottom-0 left-0 w-full h-0.5 bg-white transition-all duration-300 transform scale-x-0 group-hover:scale-x-100 <?= (service('uri')->getSegment(1) == 'transactions') ? 'scale-x-100' : '' ?>"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <div class="relative ml-3">
                                <div class="flex items-center cursor-pointer" id="user-menu-button">
                                    <img class="h-8 w-8 rounded-full" src="<?= base_url() ?>assets/images/user.png"
                                        alt="">
                                    <div class="ml-3 text-base font-medium leading-none text-white"><?= $ses_full_name ?>
                                        <i class="fas fa-chevron-down text-white"></i>
                                    </div>
                                </div>
                                <div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 hidden"
                                    id="user-menu">
                                    <a href="javascript:void(0)" id="logoutHandle"
                                        class="block px-4 py-2 text-sm text-gray-700" role="menuitem"
                                        tabindex="-1">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <button type="button"
                            class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                            id="mobile-menu-button" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="md:hidden" id="mobile-menu" class="hidden">
                <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
                    <a href="<?= site_url('products/customer'); ?>"
                        class="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white"
                        aria-current="page">Produk</a>
                    <a href="<?= site_url('transactions'); ?>"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Transaksi</a>
                </div>
                <div class="border-t border-gray-700 pb-3 pt-4">
                    <div class="flex items-center px-3">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" src="<?= base_url() ?>assets/images/user.png" alt="">
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium leading-none text-white"><?= $ses_full_name ?></div>
                            <div class="text-sm font-medium leading-none text-gray-400"><?= $ses_email ?></div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1 px-2">
                        <a href="javascript:void(0)" id="logoutHandle"
                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <?= $this->renderSection('content') ?>
        <!-- Bagian konten dinamis -->

    </div>

    <script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Toggle user menu
            $('#user-menu-button').click(function () {
                $('#user-menu').toggleClass('hidden');
            });

            // Toggle mobile menu
            $('#mobile-menu-button').click(function () {
                $('#mobile-menu').toggleClass('hidden');
            });

            // Close user menu when clicking outside
            $(document).click(function (event) {
                if (!$(event.target).closest('#user-menu-button, #user-menu').length) {
                    $('#user-menu').addClass('hidden');
                }
            });

            // Close mobile menu when clicking outside
            $(document).click(function (event) {
                if (!$(event.target).closest('#mobile-menu-button, #mobile-menu').length) {
                    $('#mobile-menu').addClass('hidden');
                }
            });
        });
    </script>
    <script>
    $(document).ready(function () {
        $('#logoutHandle').click(function () {
            // Panggil API Logout jika diperlukan
            $.ajax({
                url: '/api/auth/logout',
                method: 'POST',
                success: function (response) {
                    alert(response.message); // Logout successful
                    localStorage.removeItem('jwt_token'); // Hapus token dari localStorage
                    window.location.href = '/'; // Redirect ke halaman login
                },
                error: function () {
                    alert('Gagal logout, silakan coba lagi.');
                }
            });
        });
    });
</script>
</body>

</html>