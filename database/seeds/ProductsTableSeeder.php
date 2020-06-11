<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $products_data = array(
            'Display',
            'Rich Media',
            'Mobile',
            'Audio',
            'VOD'
        );

        foreach($products_data as $product_name){
            DB::table('products')->insert([
                'name' => $product_name,
                'is_active' => 1
            ]);
        }


    }
}
