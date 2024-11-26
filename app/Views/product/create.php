<style>
	.custom-upload {
		border: 3px solid #084cdf;
		border-radius: 10px;
		text-align: left;
		cursor: pointer;
		position: relative;
	}

	.custom-upload input[type="file"] {
		display: block;
		width: 100%;
		cursor: pointer;
		padding: 5px;
	}

	input[type=file]::file-selector-button {
		display: inline-block;
		margin-right: 20px;
		background: #084cdf;
		border: none;
		border-radius: 10px;
		padding: 4px;
		color: #fff;
		cursor: pointer;
		transition: background .2s ease-in-out;
	}

	input[type=file]::file-selector-button:hover {
		background: #0d45a5;
	}
</style>
<form id="form-tambah-product" enctype="multipart/form-data">
    <div class="form-group mt-3">
        <label for="name">
            Nama Product
            <i class="text-danger">*</i>
        </label>
        <input type="text" name="name" id="name" class="block w-full rounded-md py-2.5 pl-2.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6" placeholder="Ketik nama product ..." required>
    </div>

    <div class="form-group mt-3">
        <label for="description">
            Deskripsi
            <i class="text-danger">*</i>
        </label>
        <textarea name="description" id="description" class="block w-full rounded-md py-2.5 pl-2.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6" placeholder="Ketik deskripsi product ..." required></textarea>
    </div>

    <div class="form-group mt-3">
        <label for="image">
            Foto
            <i class="text-danger">*</i>
        </label>
		<div class="custom-upload">
        	<input type="file" name="image" id="image" accept="image/*" class="block w-full rounded-md py-2.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6" required>
		</div>
    </div>

    <div class="form-group mt-3">
        <label for="price">
            Harga
            <i class="text-danger">*</i>
        </label>
        <input type="text" name="price" id="price" class="block w-full rounded-md py-2.5 pl-2.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6" required>
    </div>

    <div class="form-group mt-3">
        <label for="stock">
            Stok
            <i class="text-danger">*</i>
        </label>
        <input type="number" name="stock" id="stock" class="block w-full rounded-md py-2.5 pl-2.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6" required>
    </div>
</form>

<script type="text/javascript">
    $('#modal-tambah-product .modal-title').text(`<?= $title ?>`)
    $('#modal-tambah-product .modal-footer').html(`
        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-500" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-600" form="form-tambah-product">Simpan</button>
    `)
</script>

<script type="text/javascript">
   $('#form-tambah-product').on('submit', function (e) {
    e.preventDefault();

    // Ambil token dari localStorage
    const token = localStorage.getItem('jwt_token');
    if (!token) {
        Swal.fire('Gagal', 'Token tidak ditemukan. Harap login terlebih dahulu.', 'error');
        return;
    }

    Swal.fire({
        title: `Simpan Product?`,
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Ya, Simpan Sekarang",
        cancelButtonText: "Tidak",
        reverseButtons: true,
        customClass: {
            confirmButton: "btn-primary",
            cancelButton: "btn-secondary",
        },
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
    }).then((result) => {
        if (result.value) {
            // Buat FormData dari formulir
            let formData = new FormData(this);

            $.ajax({
                url: '<?= site_url('api/products') ?>',
                method: "POST",
                headers: {
                    Authorization: 'Bearer ' + token // Tambahkan header Authorization dengan token
                },
                contentType: false, // Jangan set contentType karena FormData akan menangani ini
                processData: false, // Jangan memproses data
                data: formData, // Kirim FormData

                beforeSend: () => {
                    Swal.fire({
                        title: "Memproses data ...",
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                },

                success: (output) => {
                    Swal.fire({
                        title: "Product berhasil disimpan!",
                        text: output.message,
                        type: "success"
                    }).then(() => {
                        if (output.status) {
                            $('#modal-tambah-product').modal('hide');
                            $('#table-product').DataTable().ajax.reload(null, false);
                        }
                    });
                },

                error: (error, status, code) => {
                    console.error(error);
                    Swal.fire('Gagal', error.responseJSON.message, 'error');
                }
            });
        }
    });
});
</script>
