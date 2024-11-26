
<?php $this->extend('layouts/merchant'); ?>

<?php $this->section('content'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">

<style>
	a {
		text-decoration: none;
	}

	table#table-product {
		font-size: 13px;
	}

</style>
<header class="bg-white shadow">
	<div class="mx-auto max-w-7xl px-4 py-2 sm:px-6 lg:px-8">
		<h1 class="text-2xl text-gray-900">Kelola Data Product</h1>
	</div>
</header>
<main>
	<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
		<div class="bg-white shadow rounded-lg">
			<div class="px-4 py-3 border-b">
				<button type="button" class="bg-green-600 text-white px-4 py-1.5 rounded-md hover:bg-green-700"
					onclick="addproduct()">
					<i class="fa fa-plus"></i>
					Tambah Product
				</button>
			</div>
			<div class="px-3 py-3">
            <?php if (empty($product)): ?>
            <p class="text-center text-gray-600">Belum ada produk..</p>
            <?php else: ?>
			<div class="table-responsive">
				<table id="table-product" class="table table-bordered table-striped table-hover" width="100%">
					<thead>
						<tr>
							<th>Product</th>
							<th>Deskripsi</th>
							<th>Image</th>
							<th>Harga</th>
							<th>Stok</th>
							<th class="text-center">Aksi</th>
						</tr>
					</thead>
				</table>
			</div>
            <?php endif; ?>
			</div>
		</div>
	</div>
</main>
</div>
<div class="modal fade" id="modal-tambah-product" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel"></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-edit-product" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel"></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

<script type="text/javascript">
    let token = null; // Deklarasi token secara global

    $(document).ready(function () {
        // Ambil token JWT dari localStorage
        token = localStorage.getItem('jwt_token');
        console.log('Token:', token);

        if (!token) {
            alert('Token tidak ditemukan. Harap login terlebih dahulu.');
            window.location.href = '/'; // Redirect ke halaman login
            return;
        }

        // Inisialisasi DataTable
        tableproduct();
    });

    function tableproduct() {
        // Pastikan token valid sebelum membuat DataTable
        if (!token) {
            console.error('Token tidak valid. Tidak dapat memuat DataTable.');
            return;
        }

        $('#table-product').DataTable({
            ajax: {
                url: '/api/products',
                method: 'GET',
                headers: {
                    Authorization: 'Bearer ' + token
                },
                dataSrc: 'products', // Path data JSON
                error: function () {
                    alert('Gagal memuat data produk.');
                }
            },
            columns: [
                { data: 'name' },
                { data: 'description' },
                { 
                    data: 'image', // Pastikan field 'image' tersedia di data API
                    render: function (data, type, row) {
                        if (data) {
                            // Menggunakan base URL untuk mengakses gambar
                            return `<div class="text-center"><img src="/${data}" alt="Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"></div>`;
                        }
                        return 'No Image';
                    }
                },
                { data: 'price', render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') },
                { data: 'stock' },
                { 
                    data: null,
                    render: function (data, type, row) {
                        return `
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm" onclick="editproduct(${row.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteproduct(${row.id})">Hapus</button>
                        </div>
                        `;
                    }
                }
            ],

            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
            }
        });
    }

    function addproduct() {
        $.ajax({
            url: "<?= site_url('products/create') ?>",
            method: "GET",
            beforeSend: () => {
                $('#modal-tambah-product').modal('show');
                $('#modal-tambah-product .modal-body').html(`
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                `);
            },
            success: (data) => {
                $('#modal-tambah-product .modal-body').html(data);
            },
            error: (error) => {
                Swal.fire('Gagal', error.responseJSON.message, error.status);
            }
        });
    }

    function editproduct(id) {
        $.ajax({
            url: "<?= site_url('products/edit/') ?>" + id,
            method: "GET",
            beforeSend: () => {
                $('#modal-edit-product').modal('show');
                $('#modal-edit-product .modal-body').html(`
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                `);
            },
            success: (data) => {
                $('#modal-edit-product .modal-body').html(data);
            },
            error: (error) => {
                Swal.fire('Gagal', error.responseJSON.message, error.status);
            }
        });
    }

    function deleteproduct(id) {
        // Ambil token dari localStorage
        const token = localStorage.getItem('jwt_token');
        if (!token) {
            Swal.fire('Gagal', 'Token tidak ditemukan. Harap login terlebih dahulu.', 'error');
            return;
        }

        Swal.fire({
            title: "Hapus product?",
            text: "Data yang telah dihapus tidak dapat dikembalikan!",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus",
            cancelButtonText: "Batal",
            reverseButtons: true,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?= site_url('api/products/') ?>" + id,
                    method: "DELETE",
                    headers: {
                        Authorization: 'Bearer ' + token // Tambahkan header Authorization dengan token
                    },
                    success: (output) => {
                        Swal.fire({
                            title: "Product berhasil dihapus!",
                            text: output.message,
                            type: "success"
                        }).then(() => {
                            if (output.status) {
                                $('#table-product').DataTable().ajax.reload(null, false);
                            }
                        });
                    },
                    error: (error) => {
                        Swal.fire('Gagal', error.responseJSON.message);
                    }
                });
            }
        });
    }
</script>

<?php $this->endSection(); ?>