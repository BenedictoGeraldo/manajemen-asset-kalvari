<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataAsetKolektif;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrAsetController extends Controller
{
    /**
     * Download QR Code image (SVG format)
     */
    public function download($id)
    {
        $aset = DataAsetKolektif::findOrFail($id);
        
        $scanUrl = url('/qr/'.$aset->id.'/scan');
        
        $qrCode = QrCode::format('svg')
                    ->size(300)
                    ->margin(1)
                    ->generate($scanUrl);

        $filename = 'QR_Aset_' . ($aset->kode_aset ?? $aset->id) . '.svg';

        return response($qrCode)
            ->header('Content-type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Scan QR Code result page
     */
    public function scanResult($id)
    {
        // Get aset details along with relations to display
        $aset = DataAsetKolektif::with(['lokasi', 'kategori', 'kondisi', 'pengelola'])->findOrFail($id);
        return view('qr.scan-result', compact('aset'));
    }
}
