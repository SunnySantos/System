<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\File;

class GeoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the path to your single JSON file
        // geo_data.json is countries+states+cities.json in https://github.com/dr5hn/countries-states-cities-database
        $json = File::get(database_path('seeders/geo_data.json'));
        $countriesData = json_decode($json, true);

        // 1. Iterate through Countries
        foreach ($countriesData as $countryRecord) {
            // Extract state data before insertion
            $statesData = $countryRecord['states'] ?? [];

            // Prepare the country data fields
            $countryData = [
                'name'      => $countryRecord['name'],
                'iso2'      => $countryRecord['iso2'],
                'phonecode' => $countryRecord['phonecode'],
            ];

            // Insert the Country record
            $country = Country::create($countryData);

            // 2. Iterate through States of the current Country
            foreach ($statesData as $stateRecord) {
                // Extract city data before insertion
                $citiesData = $stateRecord['cities'] ?? [];

                // Prepare the state data fields, adding the FK
                $stateData = [
                    'country_id' => $country->id, // Set Foreign Key
                    'name'       => $stateRecord['name'],
                    'iso2'       => $stateRecord['iso2'],
                ];

                // Insert the State record
                $state = State::create($stateData);

                // 3. Iterate through Cities of the current State
                foreach ($citiesData as $cityRecord) {
                    // Prepare the city data fields, adding the FK
                    $cityData = [
                        'state_id' => $state->id, // Set Foreign Key
                        'name'     => $cityRecord['name'],
                    ];

                    // Insert the City record
                    City::create($cityData);
                }
            }
        }
    }
}
