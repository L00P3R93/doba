<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KenyaWardSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->warn(PHP_EOL.'Importing Kenya geography data...');

        // Disable foreign key constraints
        Schema::disableForeignKeyConstraints();

        // Truncate existing data in reverse order of dependencies
        DB::table('wards')->truncate();
        DB::table('sub_counties')->truncate();
        DB::table('counties')->truncate();

        // Import a sample of the data for demonstration purposes
        // In production, you might want to use a more efficient import method
        $this->importSampleData();

        // Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();

        $countyCount = DB::table('counties')->count();
        $subCountyCount = DB::table('sub_counties')->count();
        $wardCount = DB::table('wards')->count();

        $this->command->info("Imported {$countyCount} counties, {$subCountyCount} sub-counties, and {$wardCount} wards.");
    }

    private function importSampleData(): void
    {
        // Import sample counties
        $counties = [
            ['uid' => '047', 'name' => 'Nairobi'],
            ['uid' => '001', 'name' => 'Mombasa'],
            ['uid' => '022', 'name' => 'Kiambu'],
            ['uid' => '011', 'name' => 'Kajiado'],
            ['uid' => '032', 'name' => 'Nakuru'],
            ['uid' => '042', 'name' => 'Kisumu'],
            ['uid' => '016', 'name' => 'Machakos'],
            ['uid' => '027', 'name' => 'Uasin Gishu'],
        ];

        foreach ($counties as $county) {
            DB::table('counties')->insert([
                'uid' => $county['uid'],
                'name' => $county['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Import sample sub-counties for Nairobi
        $nairobiSubCounties = [
            ['uid' => '047001', 'name' => 'Westlands', 'county_id' => 1],
            ['uid' => '047002', 'name' => 'Dagoretti', 'county_id' => 1],
            ['uid' => '047003', 'name' => 'Langata', 'county_id' => 1],
            ['uid' => '047004', 'name' => 'Kamukunji', 'county_id' => 1],
            ['uid' => '047005', 'name' => 'Starehe', 'county_id' => 1],
        ];

        foreach ($nairobiSubCounties as $subCounty) {
            DB::table('sub_counties')->insert([
                'uid' => $subCounty['uid'],
                'name' => $subCounty['name'],
                'county_id' => $subCounty['county_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Import sample wards for Westlands
        $westlandsWards = [
            ['uid' => '047001001', 'name' => 'Kilimani', 'sub_county_id' => 1, 'county_id' => 1, 'population' => 45000],
            ['uid' => '047001002', 'name' => 'Lavington', 'sub_county_id' => 1, 'county_id' => 1, 'population' => 32000],
            ['uid' => '047001003', 'name' => 'Kileleshwa', 'sub_county_id' => 1, 'county_id' => 1, 'population' => 28000],
        ];

        foreach ($westlandsWards as $ward) {
            DB::table('wards')->insert([
                'uid' => $ward['uid'],
                'name' => $ward['name'],
                'sub_county_id' => $ward['sub_county_id'],
                'county_id' => $ward['county_id'],
                'population' => $ward['population'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
