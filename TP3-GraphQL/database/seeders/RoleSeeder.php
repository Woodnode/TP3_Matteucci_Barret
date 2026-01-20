<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enumerations\Roles;
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
        $enumCases = Roles::cases();
        foreach($enumCases as $enumCase)
        {
            Role::create(['name' => $enumCase->name]);
        }

        /*
        $sql = file_get_contents(database_path() . '/seeders/roles.sql');
        DB::statement($sql);
        */

    }
}
