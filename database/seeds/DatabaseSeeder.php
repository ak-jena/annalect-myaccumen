<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(SectionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AgenciesTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(DspsTableSeeder::class);
        $this->call(BookingStatusesTableSeeder::class);
        $this->call(UpdateSectionTitleSeeder::class);
        $this->call(UpdateUsersDefaultThemeSeeder::class);
        $this->call(VodRoleSeeder::class);
        $this->call(SystemCommentUserSeeder::class);
        $this->call(UpdateDbmDspNameSeeder::class);
        $this->call(BrightrollDspSeeder::class);
        $this->call(CancelledCompletedStatusesSeeder::class);
        $this->call(LinkDBMToMobileSeeder::class);

        Model::reguard();
    }
}
