<!DOCTYPE html>
<html>
<head>
    <title>Laporan Mutasi Aset</title>
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
        <h2>LAPORAN MUTASI ASET</h2>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Aset</th>
                <th>Dari Lokasi</th>
                <th>Ke Lokasi</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanMutasi as $index => $m)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $m->tanggal_mutasi->format('d/m/Y') }}</td>
                <td>{{ $m->kode_transaksi }}</td>
                <td>{{ $m->aset->nama_aset }}</td>
                <td>{{ $m->lokasiAsal->nama_lokasi }}</td>
                <td>{{ $m->lokasiTujuan->nama_lokasi }}</td>
                <td>{{ $m->jumlah_mutasi }} unit</td>
                <td>{{ $m->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
