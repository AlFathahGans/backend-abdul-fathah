<?= $this->extend('layouts/merchant'); ?>

<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">

<style>
    a {
        text-decoration: none;
    }

    table#table-transaction {
        font-size: 13px;
    }
</style>
<header class="bg-white shadow">
    <div class="mx-auto max-w-7xl px-4 py-2 sm:px-6 lg:px-8">
        <h1 class="text-2xl text-gray-900"><?= $title ?></h1>
    </div>
</header>
<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <?php if (empty($transactions)): ?>
            <p class="text-center text-gray-600">Belum ada transaksi yang dilakukan.</p>
            <?php else: ?>
            <div class="px-3 py-3">
                <div class="table-responsive">
                    <table id="table-transaction" class="table table-bordered table-striped table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>Kode Transaksi</th>
                                <th>Customer</th>
                                <th>Produk</th>
                                <th>Kuantitas</th>
                                <th>Total Harga</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td>Transaksi_<?= $transaction['id'] ?></td>
                                <td><?= $transaction['product_name'] ?></td>
                                <td><?= $transaction['customer_name'] ?></td>
                                <td><?= $transaction['quantity'] ?></td>
                                <td>Rp <?= number_format($transaction['total_price'], 0, ',', '.') ?></td>
                                <td><?= date('d M Y H:i', strtotime($transaction['created_at'])) ?></td>
                                <td><?= ucfirst($transaction['status']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

<?= $this->endSection(); ?>