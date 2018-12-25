<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * @return void
     */
    public function run(): void
    {
        $this->call(CountriesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(IntegrationSystemsTableSeeder::class);
        $this->call(ChequeTypesTableSeeder::class);
        $this->call(ChequePaymentsTableSeeder::class);
        $this->call(MallsTableSeeder::class);
        $this->call(StoresTableSeeder::class);
        $this->call(MallIntegrationsTableSeeder::class);
    }

}
