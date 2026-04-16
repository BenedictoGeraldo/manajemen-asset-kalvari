<!DOCTYPE html>
<html>
<head>
    <title>Cetak Label Asset - {{ $aset->kode_aset }}</title>
    <style>
        @page { size: 80mm 50mm; margin: 0; }
        body { font-family: sans-serif; margin: 0; padding: 5mm; }
        .label-container { text-align: center; border: 1px solid #ccc; padding: 5px; height: 38mm; border-radius: 5px; }
        .title { font-size: 10pt; font-weight: bold; margin-bottom: 5px; }
        .qr-code { margin: 5px auto; width: 25mm; height: 25mm; }
        .aset-name { font-size: 8pt; font-weight: bold; margin-top: 5px; }
        .aset-code { font-size: 9pt; color: #555; }
        .print-btn { @media print { display: none; } position: fixed; top: 10px; right: 10px; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Cetak Label</button>
    
    <div class="label-container">
        <div class="title">MANAJEMEN ASET KALVARI</div>
        <div class="qr-code">
            {!! QrCode::size(100)->generate(url('/data-aset/'.$aset->id)) !!}
        </div>
        <div class="aset-name">{{ $aset->nama_aset }}</div>
        <div class="aset-code">{{ $aset->kode_aset }}</div>
    </div>

    <script>
        // Auto print window
        window.onload = function() {
            // Uncomment below if you want auto-print
            // window.print();
        }
    </script>
</body>
</html>
