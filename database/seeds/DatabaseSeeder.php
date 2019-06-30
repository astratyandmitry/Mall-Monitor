<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * @return void
     */
    public function run(): void
    {
        $this->call(DevelopersTableSeeder::class);

        $this->call(RolesTableSeeder::class);

        $this->call(CountriesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);

        $this->call(StoreTypesTableSeeder::class);

        $this->call(ChequeTypesTableSeeder::class);
        $this->call(ChequePaymentsTableSeeder::class);
    }

}
