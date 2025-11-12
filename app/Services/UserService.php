<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function getPaginatedUser(Request $request): LengthAwarePaginator
    {
        $user = Auth::user();
        $roleId = $user->role_id;

        $query = User::search($request);

        if ($roleId != Role::SUPER_ADMIN_ID) {
            $users = $query->where('role_id', '!=', Role::SUPER_ADMIN_ID);
        }

        return $query->paginate(5)->withQueryString();
    }

    public function createWithProfile(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'      => $data['first_name'] . ' ' . $data['last_name'],
                'email'     => $data['email'],
                'password'  => bcrypt($data['password']),
                'role_id'   => $data['role'],
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
            ]);

            return $user;
        });
    }

    public function updateWithProfile(array $data, User $user): User
    {
        return DB::transaction(function () use ($data, $user) {
            // Update User
            $user->name = $data['first_name'] . ' ' . $data['last_name'];
            $user->email = $data['email'];
            $user->role_id = $data['role'];

            // if (!empty($data['password'])) {
            //     $user->password = bcrypt($data['password']);
            // }

            if (isset($data['file_base_name']) && isset($data['file_extension'])) {
                $user->file_base_name = $data['file_base_name'];
                $user->file_extension = $data['file_extension'];
            }

            $user->save();

            // Update UserProfile
            $cityName    = City::where('id', $data['city'])->value('name');
            $stateName   = State::where('id', $data['state'])->value('name');
            $countryName = Country::where('id', $data['country'])->value('name');

            // Update or create related profile
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name'     => $data['first_name'],
                    'middle_name'    => $data['middle_name'] ?? null,
                    'last_name'      => $data['last_name'],
                    'phone'          => $data['phone'] ?? null,
                    'street_address' => $data['street_address'],
                    'city'           => $cityName,
                    'state'          => $stateName,
                    'zip'            => $data['zip'],
                    'country'        => $countryName,
                ]
            );

            return $user;
        });
    }

    /**
     * Bulk delete multiple roles by their IDs.
     *
     * @param  array<int>  $ids
     * @return int  Number of roles deleted
     */
    public function bulkDelete(array $ids): void
    {
        DB::transaction(function () use ($ids) {
            $chunkSize = 100;

            User::whereIn('id', $ids)
                ->chunkById($chunkSize, function ($models) {
                    foreach ($models as $model) {
                        $model->delete();
                    }
                });
        });
    }
}
