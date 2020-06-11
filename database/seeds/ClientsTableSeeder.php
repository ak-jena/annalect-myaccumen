<?php

use Flynsarmy\CsvSeeder\CsvSeeder;

class ClientsTableSeeder extends CsvSeeder
{
    public function __construct()
    {

//        var_dump(base_path().'/database/seeds/csvs/accuen-clients.csv');die;
        $this->table = 'clients';
        $this->filename = base_path().'/database/seeds/csvs/accuen-clients.csv';
        $this->mapping = [
            0 => 'agency_id',
            1 => 'name',
            2 => 'model',
        ];
        $this->offset_rows = 1;

    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        parent::run();
    }
}
