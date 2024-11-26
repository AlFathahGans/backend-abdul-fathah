<?= $this->extend('layouts/customer'); ?>

<?= $this->section('content'); ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8"><?= $title ?></h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($products as $product): ?>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <img src="<?= base_url($product['image']) ?>" alt="<?= $product['name'] ?>" class="w-full h-65">
                <div class="p-4">
                    <h2 class="text-lg font-bold mb-2"><?= $product['name'] ?></h2>
                    <p class="text-gray-600 text-sm mb-4"><?= $product['description'] ?></p>
                    
                    <span class="text-green-600 font-bold text-xl">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>

                    <div class="mt-4 flex justify-between items-center">
                        <form class="form-transaction flex items-center space-x-2">
                            <input type="hidden" name="merchant_id" class="merchant_id" value="<?= $product['merchant_id'] ?>">
                            <input type="hidden" name="product_id" class="product_id" value="<?= $product['id'] ?>">
                            <input type="number" name="quantity" value="1" min="1" class="quantity border p-2 rounded w-20 text-center">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">Beli</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script type="text/javascript">
   $('.form-transaction').on('submit', function (e) {
    e.preventDefault();

    const token = localStorage.getItem('jwt_token');
    if (!token) {
        Swal.fire('Gagal', 'Token tidak ditemukan. Harap login terlebih dahulu.', 'error');
        return;
    }

    Swal.fire({
        title: `Yakin untuk membeli produk ini?`,
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Ya, Beli Sekarang",
        cancelButtonText: "Tidak",
    }).then((result) => {
        if (result.value) {
            const form = $(this);
            const merchantId = form.find('.merchant_id').val();
            const productId = form.find('.product_id').val();
            const quantity = form.find('.quantity').val();

            const data = {
                merchant_id: merchantId,
                products: [
                    {
                        product_id: productId,
                        quantity: quantity
                    }
                ]
            };

            $.ajax({
                url: '<?= site_url('api/transactions') ?>',
                method: "POST",
                headers: {
                    Authorization: 'Bearer ' + token
                },
                contentType: 'application/json',
                data: JSON.stringify(data),

                beforeSend: () => {
                    Swal.fire({
                        title: "Memproses data ...",
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },

                success: (output) => {
                    Swal.fire({
                        type: 'success',
                        title: 'Transaksi Berhasil!',
                        text: 'Silahkan membeli produk lagi.',
                    }).then(() => {
                        window.location.reload();
                    });
                },
                
                error: (error) => {
                    console.error(error);
                    Swal.fire('Gagal', error.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                }
            });
        }
    });
});
</script>

<?= $this->endSection(); ?>
