<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $category = new Category;
        $faker = \Faker\Factory::create();

        $category->save(
            [
                'name' => $faker->name(),
                'parent_id' => function() {
                    return factory('App\Models\Category')->create()->id;
                }
            ]
        );
    }
}
