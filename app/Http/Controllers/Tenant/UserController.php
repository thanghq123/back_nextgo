<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\User;

class UserController extends Controller
{
    public function list()
    {
        try {
            $query = User::with(['locations.district', 'locations.commune', 'locations.province'])->paginate(1);
            $return = $query->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'tel' => $data->tel,
                    'address' => $data->locations->commune->name .", ".  $data->locations->district->name . ", ". $data->locations->province->name,
                    'created_at' => $data->created_at->format('H:i d-m-Y'),
                    'updated_at' => $data->updated_at->format('H:i d-m-Y')
                ];
            });
            return responseApi($return, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }

    }
}
