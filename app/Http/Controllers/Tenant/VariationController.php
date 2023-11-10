<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Variation;

class VariationController extends Controller
{
    public function getListVariation()
    {
        try {
            return responseApi(Variation::with('variationQuantities')->get(),true);
        }catch (\Throwable $throwable){
            return responseApi($throwable->getMessage());
        }

    }
}
