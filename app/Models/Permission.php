<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditColumns;

class Permission extends Model
{
    use HasFactory, SoftDeletes, HasAuditColumns;

    protected $fillable = [
        'name',
        'slug',
        'display_name',
        'group',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Get the roles that have this permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role')
                    ->withPivot(['can_create', 'can_read', 'can_update', 'can_delete'])
                    ->withTimestamps();
    }

    /**
     * Users yang memiliki permission ini (legacy compatibility)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions');
    }

    /**
     * Get and group permissions by group and sub-group, sorting .view permissions first.
     */
    public static function getGroupedPermissions()
    {
        $permissions = self::orderBy('group')->get();
        $groupedPermissions = [];

        $subGroupMap = [
            'Kategori' => 'Master Kategori',
            'Lokasi' => 'Master Lokasi',
            'Kondisi' => 'Master Kondisi',
            'Pengelola' => 'Master Pengelola',
            'Data Aset' => 'Data Aset',
            'Pembelian' => 'Pembelian',
            'Peminjaman' => 'Peminjaman',
            'Pemeliharaan' => 'Pemeliharaan',
            'Mutasi Aset' => 'Mutasi Aset',
            'Pemusnahan' => 'Pemusnahan Aset',
            'User Management' => 'Manajemen User',
            'Departments' => 'Manajemen Departemen',
            'Roles' => 'Manajemen Role',
            'Permissions' => 'Hak Akses',
            'Print Qr' => 'Cetak QR Code',
            'Dashboard' => 'Dashboard',
            'Settings' => 'Profil Organisasi'
        ];

        foreach ($permissions as $permission) {
            $group = $permission->group;
            $parts = explode('.', $permission->name);
            
            $subGroup = 'Lainnya';
            if (count($parts) >= 2 && in_array($parts[0], ['master', 'transaksi', 'laporan'])) {
                $subGroup = \Illuminate\Support\Str::title(str_replace('_', ' ', $parts[1]));
            } else {
                $subGroup = \Illuminate\Support\Str::title(str_replace('-', ' ', $parts[0]));
            }

            if (isset($subGroupMap[$subGroup])) {
                $subGroup = $subGroupMap[$subGroup];
            }
            
            $groupedPermissions[$group][$subGroup][] = $permission;
        }

        // Sort permissions inside each subGroup: .view first, .approve second, others alphabetically
        foreach ($groupedPermissions as $groupName => &$subGroups) {
            foreach ($subGroups as $subGroupName => &$perms) {
                usort($perms, function ($a, $b) {
                    $aIsView = str_ends_with($a->name, '.view');
                    $bIsView = str_ends_with($b->name, '.view');
                    $aIsApprove = str_ends_with($a->name, '.approve');
                    $bIsApprove = str_ends_with($b->name, '.approve');

                    if ($aIsView && !$bIsView) return -1;
                    if (!$aIsView && $bIsView) return 1;
                    if ($aIsApprove && !$bIsApprove) return -1;
                    if (!$aIsApprove && $bIsApprove) return 1;

                    return strcmp($a->name, $b->name);
                });
            }
        }

        return $groupedPermissions;
    }
}
