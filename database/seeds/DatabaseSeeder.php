<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * @return void
     */
    public function run(): void
    {
        $this->call(IntegrationSystemsTableSeeder::class);
        $this->call(ChequeTypesTableSeeder::class);
        $this->call(ChequePaymentsTableSeeder::class);
    }

}
