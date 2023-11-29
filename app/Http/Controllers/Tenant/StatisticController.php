<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StatisticRequest;
use App\Models\Tenant\Customer;
use App\Models\Tenant\OrderDetail;
use App\Models\Tenant\Payment;
use App\Models\Tenant\Order;
use Carbon\Carbon;

class StatisticController extends Controller
{
    public function __construct(
        private Order $orderModel,
        private OrderDetail $orderDetailModel,
        private Customer $customerModel,
        private Payment $paymentModel,
        private StatisticRequest $request
    ) {
    }

    public function income(){
        try {
            return responseApi([
                $this->request->option => $this->orderModel::query()->whereCreatedAt(
                    [
                        $this->request->option,
                        $this->request->start_date,
                        $this->request->end_date],
                    $this->request->location)
            ], true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function products(){
        try {
            $startDate = $this->request->startDate;
            $endDate = $this->request->endDate;
            $model = $this->orderDetailModel::query()->with([
                'variant:id,sku,display_name,price_import,price_export',
                'orderDetailBatch' => function($query){
                    $query->sum('quantity');
                }
            ])
                ->get();
            return $model;

            return responseApi([], true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function general(){
        try {
            return responseApi([
                'total' => $this->orderModel::query()->whereCreatedAt(['today']),
                'order_completed' => $this->orderModel::query()->orderCompleted()
            ], true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function paymentMethods(){
        try {
            return responseApi([
                "cash" => $this->paymentModel::query()->whereMethod(0,
                    [
                        $this->request->option,
                        $this->request->start_date,
                        $this->request->end_date
                    ],
                    $this->request->location
                ),
                "transfer" => $this->paymentModel::query()->whereMethod(1,
                    [
                        $this->request->option,
                        $this->request->start_date,
                        $this->request->end_date
                    ],
                    $this->request->location
                ),
                "debit" => $this->paymentModel::query()->whereMethod(2,
                    [
                        $this->request->option,
                        $this->request->start_date,
                        $this->request->end_date
                    ],
                    $this->request->location
                )
            ], true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function customers(){
        try {
            $locationId = $this->request->location;
            $inventoryId = $this->request->inventory_id;
            return $this->customerModel::query()
                ->with(['order' => function ($query) use ($locationId, $inventoryId) {
                    $query->where('location_id', $locationId)
                        ->whereHas('location', function ($query) use ($inventoryId) {
                            $query->whereHas('inventories', function ($query) use ($inventoryId) {
                                $query->where('id', $inventoryId);
                            });
                        })
                        ->whereDate('created_at', '>=', '2023-11-27');
                }])
                ->whereHas('order', function ($query) use ($locationId, $inventoryId) {
                    $query->where('location_id', $locationId)
                        ->whereHas('location', function ($query) use ($inventoryId) {
                            $query->whereHas('inventories', function ($query) use ($inventoryId) {
                                $query->where('id', $inventoryId);
                            });
                        })
                        ->whereDate('created_at', '>=', '2023-11-27');
                })
                ->withSum('order', 'total_price')
                ->get();
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
