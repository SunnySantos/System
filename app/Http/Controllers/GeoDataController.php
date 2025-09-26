<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;

class GeoDataController extends Controller
{
    // Fetches all countries for the initial dropdown
    public function getCountries()
    {
        // Return only the ID and Name fields, formatted for Select2 if needed
        return Country::select('id', 'name')->get();
    }

    // Fetches states belonging to the selected country
    public function getStates(Country $country)
    {
        // $country is automatically resolved by Laravel based on the route parameter
        return $country->states()->select('id', 'name')->get();
    }

    // Fetches cities belonging to the selected state
    public function getCities(State $state)
    {
        // $state is automatically resolved by Laravel based on the route parameter
        return $state->cities()->select('id', 'name')->get();
    }
}
