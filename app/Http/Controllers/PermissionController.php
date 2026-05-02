<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::getGroupedPermissions();
        return view('pengaturan.permissions.index', compact('permissions'));
    }
}
