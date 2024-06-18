<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(TermTableSeeder::class);
        $this->call(FormTableSeeder::class);
        $this->call(ServiceTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        Artisan::call('cache:clear');
    }

}
