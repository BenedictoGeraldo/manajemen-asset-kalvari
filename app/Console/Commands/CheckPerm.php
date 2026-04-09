<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckPerm extends Command
{
    protected $signature = 'check:perm';

    public function handle()
    {
        $u = User::find(2);
        if ($u) {
            $u->role_id = \App\Models\Role::where('slug', 'peminjam')->first()->id;
            $u->save();
            $this->info("Assigned Peminjam role to user ID 2.");
        }
    }
}
