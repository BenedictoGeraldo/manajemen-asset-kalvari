<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembelian Aset</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PEMBELIAN ASET</h2>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Vendor</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanPembelian as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->tanggal_pembelian->format('d/m/Y') }}</td>
                <td>{{ $p->kode_transaksi }}</td>
                <td>{{ $p->nama_barang }}</td>
                <td>{{ $p->vendor }}</td>
                <td>{{ $p->jumlah }} {{ $p->satuan }}</td>
                <td>Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
