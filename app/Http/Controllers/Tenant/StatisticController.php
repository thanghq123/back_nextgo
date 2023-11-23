<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Tenant\Order;

class StatisticController extends Controller
{
    public function __construct(
        private Order $orderModel,
    ) {
    }

    public function createQuery($model, $locationId = 0, $inventoryId = 0){
        $query = $model::query();

        if ($locationId != 0) {
            $query->where('location_id', $locationId);
        }

        if ($inventoryId != 0) {
            $query->where('inventory_id', $inventoryId);
        }

        return $query;
    }

    public function income(Request $request){
        try {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $data = [
                'today' => $this->createQuery($this->orderModel)->today(),
                'yesterday' => $this->createQuery($this->orderModel)->yesterday(),
                'sevenDays' => $this->createQuery($this->orderModel)->sevenDays(),
                'thirtyDays' => $this->createQuery($this->orderModel)->thirtyDays(),
                'fromTo' => $this->createQuery($this->orderModel)->fromTo($startDate, $endDate),
            ];

            return responseApi($data, true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
