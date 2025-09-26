<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function createWithProfile(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'      => $data['first_name'] . ' ' . $data['last_name'],
                'email'     => $data['email'],
                'password'  => bcrypt($data['password']),
            ]);

            $cityName    = City::where('id', $data['city'])->value('name');
            $stateName   = State::where('id', $data['state'])->value('name');
            $countryName = Country::where('id', $data['country'])->value('name');

            UserProfile::create([
                'user_id'           => $user->id,
                'first_name'        => $data['first_name'],
                'middle_name'       => $data['middle_name'],
                'last_name'         => $data['last_name'],
                'phone'             => $data['phone'],
                'street_address'    => $data['street_address'],
                'city'              => $cityName,
                'state'             => $stateName,
                'zip'               => $data['zip'],
                'country'           => $countryName,
                'file_base_name'    => $data['file_base_name'] ?? null,
                'file_extension'    => $data['file_extension'] ?? null,
            ]);

            return $user;
        });
    }
}
