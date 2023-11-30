<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StatisticRequest;
use App\Models\Tenant\OrderDetail;
use App\Models\Tenant\Payment;
use App\Models\Tenant\Order;
use Carbon\Carbon;

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
            $productData = $this->orderDetailModel::query()->whereProduct([
                $this->request->option,
                $this->request->start_date,
                $this->request->end_date
            ],
                $this->request->location);

            $data = $productData->getCollection()->transform(function ($productData){
                return [
                    'variation_id' => $productData->variation_id,
                    'sku' => $productData->variant->sku,
                    'variation_name' => $productData->variant->variation_name,
                    'total_quantity' => intval($productData->total_quantity),
                    'total_price_sell' => $productData->total_price_sell,
                    'total_price_import' => $productData->total_price_import
                ];
            });

            return responseApi(paginateCustom($data, $productData), true);
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
            $customerData = $this->orderModel::query()->whereCustomer([
                $this->request->option,
                $this->request->start_date,
                $this->request->end_date
            ],
                $this->request->location);
            $data = $customerData->getCollection()->transform(function ($customerData){
                return [
                    'customer_id' => $customerData->customer_id,
                    'total_product' => intval($customerData->total_product),
                    'total_price' => $customerData->total_price,
                    'name' => $customerData->customer->name,
                    'email' => $customerData->customer->email,
                    'tel' => $customerData->customer->tel
                ];
            });

            return responseApi(paginateCustom($data, $customerData), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
