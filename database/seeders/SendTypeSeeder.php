<?php

namespace Database\Seeders;

use App\Models\SendType;
use Illuminate\Database\Seeder;

class SendTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['type' => 'Direct'],
            ['type' => 'Individual'],
        ];
        foreach ($data as $record) {
            SendType::create($record);
        }
    }
}
