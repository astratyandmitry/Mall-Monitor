<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * @var array
     */
    protected $data = [
        [
            'name' => 'Администратор',
            'system_key' => 'admin',
        ],
        [
            'name' => 'Администратор ТРЦ',
            'system_key' => 'mall',
        ],
        [
            'name' => 'Арендатор',
            'system_key' => 'tenant',
        ],
    ];

    /**
     * @return void
     */
    public function run(): void
    {
        Role::query()->truncate();

        foreach ($this->data as $data) {
            Role::create($data);
        }
    }

}
