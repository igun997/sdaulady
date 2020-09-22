
<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        //$this->call(SiswaTableSeeder::class);
        //$this->call(GuruTableSeeder::class);
        //$this->call(BanksoalTableSeeder::class);
        //$this->call(JawabanTableSeeder::class);
        //$this->call(KelasTableSeeder::class);
        //$this->call(MatpelTableSeeder::class);
        //$this->call(RombelTableSeeder::class);
        //$this->call(UjianTableSeeder::class);
        //$this->call(UjianItemTableSeeder::class);
    }
}
