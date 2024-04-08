<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Faker\Factory as faker;

class ProduksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = faker::create();

        for ($i=0; $i < 5; $i++) { 
            
            $produk = New Produk;
            $produk->name = $faker->name();
            $produk->description = $faker->text(50);
            $produk->qty = rand(1,100);
            $produk->price = rand(10000,200000);
            $produk->status = $faker->randomElement([true ,false]);
            

            $produk->save();
        }
    }
}
