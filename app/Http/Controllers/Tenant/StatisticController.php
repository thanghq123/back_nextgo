<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StatisticRequest;
use App\Models\Tenant\OrderDetail;
use App\Models\Tenant\Payment;
use App\Models\Tenant\Order;
use App\Models\Tenant\Product;

class StatisticController extends Controller
{
    public function __construct(
        private Order $orderModel,
        private OrderDetail $orderDetailModel,
        private Payment $paymentModel,
        private StatisticRequest $request
    ) {
    }

    public function income(){
        try {
            $option = $this->request->option;
            $locationId = $this->request->location ?? 0;
//            $inventoryId = $this->request->inventory_id ?? 0;
            $startDate = $this->request->start_date;
            $endDate = $this->request->end_date;

            return responseApi([
                $option => $this->orderModel::query()->whereCreatedAt([$option, $startDate, $endDate], $locationId)
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
            $option = $this->request->option;
            $locationId = $this->request->location ?? 0;
//            $inventoryId = $this->request->inventory_id ?? 0;
            $startDate = $this->request->start_date ?? null;
            $endDate = $this->request->end_date ?? null;

            return responseApi([
                "cash" => $this->paymentModel::query()->whereMethod(0,[$option, $startDate, $endDate], $locationId),
                "transfer" => $this->paymentModel::query()->whereMethod(1,[$option, $startDate, $endDate], $locationId),
                "debit" => $this->paymentModel::query()->whereMethod(2,[$option, $startDate, $endDate], $locationId)
            ], true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function customers(){
        try {
            $option = $this->request->option;
            $locationId = $this->request->location ?? 0;
//            $inventoryId = $this->request->inventory_id ?? 0;
            $startDate = $this->request->start_date ?? null;
            $endDate = $this->request->end_date ?? null;

            return responseApi([
                "cash" => $this->orderModel::query()->whereMethod(0,[$option, $startDate, $endDate], $locationId),
                "transfer" => $this->orderModel::query()->whereMethod(1,[$option, $startDate, $endDate], $locationId),
                "debit" => $this->orderModel::query()->whereMethod(2,[$option, $startDate, $endDate], $locationId)
            ], true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
