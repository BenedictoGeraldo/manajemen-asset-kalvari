<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\MasterKategori;
use App\Models\MasterLokasi;
use App\Models\MasterKondisi;
use App\Models\MasterPengelola;
use App\Models\Department;
use App\Models\DataAsetKolektif;
use App\Models\DataAsetUnit;
use App\Services\DataAsetService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataAsetUnitQrTest extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $user;
    protected $kategori;
    protected $lokasi;
    protected $kondisi;
    protected $pengelola;
    protected $department;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(DataAsetService::class);

        // Setup master data
        $this->department = Department::create(['code' => 'K-PMD', 'name' => 'Pemuda', 'type' => 'Komisi']);
        $this->kategori = MasterKategori::create(['nama_kategori' => 'Elektronik', 'kode_kategori' => 'ELK']);
        $this->lokasi = MasterLokasi::create(['nama_lokasi' => 'Ruang Rapat', 'kode_lokasi' => 'RR']);
        $this->kondisi = MasterKondisi::create(['nama_kondisi' => 'Baik', 'kode_kondisi' => 'BK']);
        $this->pengelola = MasterPengelola::create(['nama_pengelola' => 'Seksi Sarpras', 'kode_pengelola' => 'SPR001']);

        $role = Role::create(['name' => 'Super Admin', 'slug' => 'superadmin']);
        $this->user = User::create([
            'name' => 'Admin Test',
            'username' => 'admin.test',
            'email' => 'admin@kalvari.org',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $this->actingAs($this->user);
    }

    public function test_creating_collective_asset_automatically_generates_child_units()
    {
        $data = [
            'nama_aset' => 'Kursi Lipat Chitose',
            'kode_aset' => 'AST-2026-00001',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'kondisi_id' => $this->kondisi->id,
            'pengelola_id' => $this->pengelola->id,
            'department_id' => $this->department->id,
            'jumlah_barang' => 5,
            'tipe_grup' => 'grup',
            'tahun_pengadaan' => 2026,
            'nilai_pengadaan_per_unit' => 250000,
            'nilai_pengadaan_total' => 1250000,
            'is_active' => true,
        ];

        $aset = $this->service->createAset($data);

        $this->assertDatabaseHas('data_aset_kolektif', ['id' => $aset->id, 'jumlah_barang' => 5]);
        $this->assertEquals(5, $aset->units()->count());

        // Check unit codes
        $units = $aset->units()->orderBy('nomor_urut')->get();
        $this->assertEquals('AST-2026-00001-001', $units[0]->kode_unit);
        $this->assertEquals('AST-2026-00001-005', $units[4]->kode_unit);
        $this->assertEquals('tersedia', $units[0]->status);
    }

    public function test_unit_photo_fallback_and_custom_override()
    {
        $masterPhoto = 'data:image/jpeg;base64,' . base64_encode('master_photo_sample');
        $customPhoto = 'data:image/jpeg;base64,' . base64_encode('custom_photo_unit_3');

        $aset = $this->service->createAset([
            'nama_aset' => 'Laptop Asus ROG',
            'kode_aset' => 'AST-2026-00002',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'kondisi_id' => $this->kondisi->id,
            'pengelola_id' => $this->pengelola->id,
            'department_id' => $this->department->id,
            'jumlah_barang' => 3,
            'tahun_pengadaan' => 2026,
            'tipe_grup' => 'individual',
            'nilai_pengadaan_per_unit' => 15000000,
            'nilai_pengadaan_total' => 45000000,
            'gambar_aset_base64' => $masterPhoto,
            'is_active' => true,
        ]);

        $units = $aset->units()->orderBy('nomor_urut')->get();

        // Unit 1 has no custom photo -> falls back to master
        $this->assertEquals($masterPhoto, $units[0]->gambar);
        $this->assertFalse($units[0]->has_custom_gambar);

        // Unit 3 gets custom photo
        $this->service->updateUnit($units[2]->id, [
            'gambar_unit_base64' => $customPhoto,
            'catatan_khusus' => 'Ada stiker Seksi Pemuda di cover depan',
        ]);

        $unit3 = DataAsetUnit::find($units[2]->id);
        $this->assertEquals($customPhoto, $unit3->gambar);
        $this->assertTrue($unit3->has_custom_gambar);
        $this->assertEquals('Ada stiker Seksi Pemuda di cover depan', $unit3->catatan_khusus);

        // Unit 1 still falls back to master photo
        $unit1 = DataAsetUnit::find($units[0]->id);
        $this->assertEquals($masterPhoto, $unit1->gambar);
    }

    public function test_updating_quantity_synchronizes_units()
    {
        $aset = $this->service->createAset([
            'nama_aset' => 'Mic Wireless Shure',
            'kode_aset' => 'AST-2026-00003',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'kondisi_id' => $this->kondisi->id,
            'pengelola_id' => $this->pengelola->id,
            'department_id' => $this->department->id,
            'jumlah_barang' => 2,
            'tahun_pengadaan' => 2026,
            'tipe_grup' => 'set',
            'nilai_pengadaan_per_unit' => 2000000,
            'nilai_pengadaan_total' => 4000000,
            'is_active' => true,
        ]);

        $this->assertEquals(2, $aset->units()->count());

        // Increase quantity to 4
        $this->service->updateAset($aset->id, [
            'nama_aset' => 'Mic Wireless Shure',
            'jumlah_barang' => 4,
        ]);

        $this->assertEquals(4, DataAsetUnit::where('data_aset_kolektif_id', $aset->id)->count());
        $unit4 = DataAsetUnit::where('data_aset_kolektif_id', $aset->id)->where('nomor_urut', 4)->first();
        $this->assertNotNull($unit4);
        $this->assertEquals('AST-2026-00003-004', $unit4->kode_unit);

        // Decrease quantity to 3
        $this->service->updateAset($aset->id, [
            'nama_aset' => 'Mic Wireless Shure',
            'jumlah_barang' => 3,
        ]);

        $this->assertEquals(3, DataAsetUnit::where('data_aset_kolektif_id', $aset->id)->count());
    }

    public function test_public_qr_unit_scan_endpoint_returns_correct_data()
    {
        $aset = $this->service->createAset([
            'nama_aset' => 'Piala Misa Emas',
            'kode_aset' => 'AST-2026-00004',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'kondisi_id' => $this->kondisi->id,
            'pengelola_id' => $this->pengelola->id,
            'department_id' => $this->department->id,
            'jumlah_barang' => 2,
            'tahun_pengadaan' => 2026,
            'tipe_grup' => 'individual',
            'nilai_pengadaan_per_unit' => 5000000,
            'nilai_pengadaan_total' => 10000000,
            'is_active' => true,
        ]);

        $unit2 = $aset->units()->where('nomor_urut', 2)->first();

        // Scan unit 2 publicly
        $response = $this->get('/qr/unit/' . $unit2->kode_unit . '/scan');

        $response->assertStatus(200);
        $response->assertSee('Piala Misa Emas');
        $response->assertSee('AST-2026-00004-002');
        $response->assertSee('Unit #2 dari 2');
    }
}
