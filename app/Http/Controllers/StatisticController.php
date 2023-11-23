<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Pricing;
class StatisticController extends Controller
{
    public function index()
    {
        $users = User::role('customer')->count();
        foreach (Pricing::get() as $pricing) {
            $plan[$pricing->name] = Tenant::where('pricing_id', $pricing->id)->count();
        }
        $tenants = Tenant::count();
        $pricings = Pricing::count();
        $reponse=[
            'user'=>$users,
            'tenant'=>$tenants,
            'pricing'=>$pricings,
            'plan'=>$plan
        ];
//        return $reponse;
        return view('admin.dashboard.index', compact('reponse'));
    }
}
