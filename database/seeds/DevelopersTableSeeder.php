<?php

use App\Models\Developer;
use Illuminate\Database\Seeder;

class DevelopersTableSeeder extends Seeder
{

    /**
     * @var array
     */
    protected $data = [
        [
            'username' => 'dev',
            'password' => 'dev',
            'mall_id' => 1,
            'store_id' => 1,
        ],
    ];


    /**
     * @return void
     */
    public function run(): void
    {
        Developer::query()->truncate();

        foreach ($this->data as $data) {
            $data['password'] = bcrypt($data['password']);

            Developer::query()->create($data);
        }
    }

}
