<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code Aset - {{ date('d-m-Y') }}</title>
    <style>
        @page {
            size: A4;
            margin: 0.5cm;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
            padding: 5px;
        }
        .label-card {
            border: 1px dashed #ddd;
            padding: 8px;
            height: 3.5cm;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            page-break-inside: avoid;
            background: #fff;
        }
        .qr-section {
            flex: 0 0 30%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .info-section {
            flex: 1;
            padding-left: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: left;
            overflow: hidden;
        }
        .church-name {
            font-size: 8pt;
            font-weight: bold;
            color: #4b5563;
            text-transform: uppercase;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .asset-name {
            font-size: 10pt;
            font-weight: 800;
            color: #000;
            margin-bottom: 4px;
            line-height: 1.1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .asset-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            font-weight: bold;
            background: #f3f4f6;
            padding: 2px 4px;
            border-radius: 2px;
            display: inline-block;
            margin-top: 2px;
        }
        .asset-meta {
            font-size: 7pt;
            color: #6b7280;
            margin-top: 4px;
        }

        /* Print Specifics */
        @media print {
            body {
                background: none;
            }
            .no-print {
                display: none;
            }
            .label-card {
                border: 1px solid #eee;
                -webkit-print-color-adjust: exact;
            }
        }

        .no-print-bar {
            background: #1f2937;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-print {
            background: #3b82f6;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }
        .btn-print:hover {
            background: #2563eb;
        }
    </style>
</head>
<body>
    <div class="no-print-bar no-print">
        <div>
            <span class="font-bold">Preview Cetak QR Code</span>
            <span class="ml-4 px-2 py-1 bg-gray-700 rounded text-xs text-gray-300">Total: {{ $assets->count() }} Aset</span>
        </div>
        <div class="flex space-x-3">
            <button onclick="window.print()" class="btn-print">
                Cetak Label
            </button>
            <button onclick="window.close()" class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg font-bold">
                Tutup
            </button>
        </div>
    </div>

    <div class="container">
        @foreach($assets as $asset)
            <div class="label-card">
                <div class="qr-section">
                    {!! QrCode::size(80)->margin(1)->generate(url('/qr/'.$asset->id.'/scan')) !!}
                </div>
                <div class="info-section">
                    <div class="church-name">{{ setting('church_name', 'Gereja Kalvari') }}</div>
                    <div class="asset-name">{{ $asset->nama_aset }}</div>
                    <div class="asset-code">{{ $asset->kode_aset }}</div>
                    <div class="asset-meta">
                        {{ $asset->lokasi?->nama_lokasi ?? '-' }} | {{ $asset->kategori?->nama_kategori ?? '-' }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($assets->isEmpty())
        <div class="flex flex-col items-center justify-center p-20 text-gray-400">
            <svg class="w-20 h-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h2 class="text-xl font-bold text-gray-600">Tidak ada aset ditemukan</h2>
            <p>Silakan tutup halaman ini dan sesuaikan filter Anda.</p>
        </div>
    @endif
</body>
</html>
