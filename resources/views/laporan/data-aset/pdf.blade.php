<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Aset</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { text-align: right; margin-top: 20px; font-size: 8pt; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DATA INVENTARIS ASET</h2>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Aset</th>
                <th>Nama Aset</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Kondisi</th>
                <th>Jumlah</th>
                <th>Nilai Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanAset as $index => $aset)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $aset->kode_aset }}</td>
                <td>{{ $aset->nama_aset }}</td>
                <td>{{ $aset->kategori->nama_kategori }}</td>
                <td>{{ $aset->lokasi->nama_lokasi }} {{ $aset->lokasi->sub_lokasi ? '(' . $aset->lokasi->sub_lokasi . ')' : '' }}</td>
                <td>{{ $aset->kondisi?->nama_kondisi ?? '-' }}</td>
                <td>{{ $aset->jumlah_barang }}</td>
                <td>Rp {{ number_format($aset->nilai_pengadaan_per_unit, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name }}
    </div>
</body>
</html>
