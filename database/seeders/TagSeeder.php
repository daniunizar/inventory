<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'label'=>'Cartas',
                'description'=>'',
            ],
            [
                'label'=>'Dados',
                'description'=>'',
            ],
            [
                'label'=>'Miniaturas',
                'description'=>'',
            ],
            [
                'label'=>'DeckBuilding',
                'description'=>'',
            ],
            [
                'label'=>'Eurogame',
                'description'=>'',
            ],
            [
                'label'=>'Ameritrash',
                'description'=>'',
            ],
        ];
        foreach($items as $item){
            Tag::create($item);
        }
    }
}
