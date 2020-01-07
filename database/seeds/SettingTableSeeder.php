<?php

use App\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'php_name' => 'php',
            'docudex_path' => '/var/www/html/php56/docudex',
            'files_path' => base_path() . '/public/uploads',
            'config_path' => '/var/www/html/php56/docudex/cbl_config.yml'
        ]);
    }
}
