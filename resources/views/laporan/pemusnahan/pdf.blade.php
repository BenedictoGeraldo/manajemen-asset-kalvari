<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pemusnahan Aset</title>
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
        <h2>LAPORAN PEMUSNAHAN ASET</h2>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Transaksi</th>
                <th>Nama Aset</th>
                <th>Jumlah</th>
                <th>Metode</th>
                <th>Alasan</th>
                <th>Penanggung Jawab</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanPemusnahan as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->tanggal_pemusnahan->format('d/m/Y') }}</td>
                <td>{{ $p->kode_transaksi }}</td>
                <td>{{ $p->aset->nama_aset }}</td>
                <td>{{ $p->jumlah_dimusnahkan }} unit</td>
                <td>{{ $p->metode_pemusnahan }}</td>
                <td>{{ $p->alasan_pemusnahan }}</td>
                <td>{{ $p->penanggung_jawab }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
