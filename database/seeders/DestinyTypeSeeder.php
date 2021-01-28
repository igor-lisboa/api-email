<?php

namespace Database\Seeders;

use App\Models\DestinyType;
use Illuminate\Database\Seeder;

class DestinyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['slug' => 'to'],
            ['slug' => 'cc'],
            ['slug' => 'bcc']
        ];
        foreach ($data as $record) {
            DestinyType::create($record);
        }
    }
}
