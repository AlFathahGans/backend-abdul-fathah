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
<form id="form-edit-product" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT">

    <div class="form-group mt-3">
        <label for="name">
            Nama Product
            <i class="text-danger">*</i>
        </label>
        <input type="text" name="name" id="name" value="<?= $product['name'] ?>" class="block w-full rounded-md py-2.5 pl-2.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6" placeholder="Ketik nama product ..." required>
    </div>

    <div class="form-group mt-3">
        <label for="description">
            Deskripsi
            <i class="text-danger">*</i>
        </label>
        <textarea name="description" id="description" value="<?= $product['description'] ?>" class="block w-full rounded-md py-2.5 pl-2.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6" placeholder="Ketik deskripsi product ..." required><?= $product['description'] ?></textarea>
    </div>

    <div class="form-group mt-3">
        <label for="image">
            Foto
        </label>
		<div class="custom-upload">
        	<input type="file" name="image" id="image" accept="image/*" class="block w-full rounded-md py-2.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6">
		</div>
		<?php if(!empty($product['image'])){ ?>
            <div class="mt-2 p-2 border border-gray-300 rounded-md"> <!-- Pembungkus dengan border -->
                <img src="<?= base_url($product['image']) ?>" alt="Foto Product" class="w-32 h-auto rounded-md">
            </div>
        <?php } ?>
    </div>

    <div class="form-group mt-3">
        <label for="price">
            Harga
            <i class="text-danger">*</i>
        </label>
        <input type="text" name="price" id="price" value="<?= $product['price'] ?>" class="block w-full rounded-md py-2.5 pl-2.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6" required>
    </div>
    <div class="form-group mt-3">
        <label for="stock">
            Stok
            <i class="text-danger">*</i>
        </label>
        <input type="number" name="stock" id="stock" value="<?= $product['stock'] ?>" class="block w-full rounded-md py-2.5 pl-2.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 focus:border-transparent outline-none sm:text-sm sm:leading-6" required>
    </div>
</form>

<script type="text/javascript">
    $('#modal-edit-product .modal-title').text(`<?= $title ?>`)
    $('#modal-edit-product .modal-footer').html(`
        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-500" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-600" form="form-edit-product">Update</button>
    `)
</script>

<script type="text/javascript">
    $('#form-edit-product').on('submit', function (e) {
        e.preventDefault()

        // Ambil token dari localStorage
        const token = localStorage.getItem('jwt_token');
        if (!token) {
            Swal.fire('Gagal', 'Token tidak ditemukan. Harap login terlebih dahulu.', 'error');
            return;
        }

        Swal.fire({
            title: `Perbarui Product?`,
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Perbarui Sekarang",
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

                // Cek apakah file dipilih
                if (!$('#image')[0].files.length) {
                    // Jika tidak ada file gambar, hapus file dari formData
                    formData.delete('image');
                }

                $.ajax({
                    url: '<?= site_url('api/products/') ?>' + '<?= $product['id'] ?>',
                    method: "POST",
                    headers: {
                        Authorization: 'Bearer ' + token,
                    },
                    data: formData,
                    processData: false,
                    contentType: false,

                    beforeSend: () => {
                        Swal.fire({
                            title: "Memproses data ...",
                            onOpen: () => {
                                Swal.showLoading()
                            }
                        })
                    },

                    success: (output) => {
                        Swal.fire({
                            title: "Product berhasil diperbarui!",
                            text: output.message,
                            type: "success"
                        }).then(() => {
                            if (output.status) {
                                $('#modal-edit-product').modal('hide');
                                $('#table-product').DataTable().ajax.reload(null, false);
                            }
                        })
                    },

                    error: (error, status, code) => {
						console.log(error);
                        Swal.fire('Gagal', error.responseJSON.message, status)
                    }
                })
            }
        });
    })
</script>
