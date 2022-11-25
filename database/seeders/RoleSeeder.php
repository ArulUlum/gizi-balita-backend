<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ["SUPER_ADMIN", "ADMIN", "ORANG_TUA", "KADER_POSYANDU", "DESA"];
        foreach ($roles as $role) {
            Role::create([
                'role' => $role,
            ]);
        }
    }
}
