<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\OrderRequest;
use App\Models\Tenant\Order;
use App\Models\Tenant\VariationQuantity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        private Order $model,
        private VariationQuantity $variationModel,
        private OrderRequest $request
    )
    {
    }

    /**
     * @path /tenant/api/v1/orders
     * @method POST
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function list(){
        try {
            $orderData = $this->model::with(['orderDetails' ,'customer', 'location', 'createdBy'])->paginate(10);

            $data = $orderData->getCollection()->transform(function ($orderData){
                return [
                    'id' => $orderData->id,
                    'location_id' => $orderData->location_id,
                    'location_name' => $orderData->location->name,
                    'customer_id' => $orderData->customer_id,
                    'customer_name' => $orderData->customer->name,
                    'created_by' => $orderData->created_by,
                    'created_name' => $orderData->createdBy->name,
                    'discount' => $orderData->discount,
                    'discount_type' => $orderData->discount_type,
                    'tax' => $orderData->tax,
                    'service_charge' => $orderData->service_charge,
                    'total_product' => $orderData->total_product,
                    'total_price' => $orderData->total_price,
                    'status' => $orderData->status,
                    'payment_status' => $orderData->payment_status,
                    'oder_details' => collect($orderData->orderDetails)->map(function ($orderDetails){
                        return [
                            'id' => $orderDetails->id,
                            'order_id' => $orderDetails->order_id,
                            'variation_id' => $orderDetails->variation_id,
                            'batch_id' => $orderDetails->batch_id,
                            'discount' => $orderDetails->discount,
                            'discount_type' => $orderDetails->discount_type,
                            'tax' => $orderDetails->tax,
                            'quantity' => $orderDetails->quantity,
                            'total_price' => $orderDetails->total_price
                        ];
                    }),
                    'created_at'=>Carbon::make($orderData->created_at)->format('d/m/Y H:i'),
                    'updated_at'=>Carbon::make($orderData->updated_at)->format('d/m/Y H:i')
                ];
            });

            return responseApi(paginateCustom($data, $orderData), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/orders/store
     * @method POST
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(){
        DB::beginTransaction();
        try {
            $order = $this->model::create([
                "location_id" => $this->request->location_id,
                "customer_id" => $this->request->customer_id,
                "created_by" => $this->request->created_by,
                "discount" => $this->request->discount,
                "discount_type" => $this->request->discount_type,
                "tax" => $this->request->tax,
                "service_charge" => $this->request->service_charge,
                "total_product" => $this->request->total_product,
                "total_price" => $this->request->total_price,
                "status" => $this->request->status,
                "payment_status" => $this->request->payment_status
            ]);

            $order_details = collect($this->request->order_details)->toArray();
            $order->orderDetails()->createMany($order_details);

//            foreach ($order_details as $order_details){
//                $this->variationModel::find('id',);
//            }

            DB::commit();
            return responseApi("Tạo thành công!", true);
        }catch (\Throwable $throwable)
        {
            DB::rollBack();
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/orders/show
     * @method POST
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function show()
    {
        try {
            $orderData = $this->model::with(['orderDetails' ,'customer', 'location', 'createdBy'])
                ->where('id', $this->request->id)
                ->get();
            $data = $orderData->map(function ($orderData){
                return [
                    'id' => $orderData->id,
                    'location_id' => $orderData->location_id,
                    'location_name' => $orderData->location->name,
                    'customer_id' => $orderData->customer_id,
                    'customer_name' => $orderData->customer->name,
                    'created_by' => $orderData->created_by,
                    'created_name' => $orderData->createdBy->name,
                    'discount' => $orderData->discount,
                    'discount_type' => $orderData->discount_type,
                    'tax' => $orderData->tax,
                    'service_charge' => $orderData->service_charge,
                    'total_product' => $orderData->total_product,
                    'total_price' => $orderData->total_price,
                    'status' => $orderData->status,
                    'payment_status' => $orderData->payment_status,
                    'oder_details' => collect($orderData->orderDetails)->map(function ($orderDetails){
                        return [
                            'id' => $orderDetails->id,
                            'order_id' => $orderDetails->order_id,
                            'variation_id' => $orderDetails->variation_id,
                            'batch_id' => $orderDetails->batch_id,
                            'discount' => $orderDetails->discount,
                            'discount_type' => $orderDetails->discount_type,
                            'tax' => $orderDetails->tax,
                            'quantity' => $orderDetails->quantity,
                            'total_price' => $orderDetails->total_price
                        ];
                    }),
                    'created_at'=>Carbon::make($orderData->created_at)->format('d/m/Y H:i'),
                    'updated_at'=>Carbon::make($orderData->updated_at)->format('d/m/Y H:i')
                ];
            });
            return responseApi(collect($data)->collapse(), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

//    public function update()
//    {
//        try {
//            $this->model::find($this->request->id)->update($this->request->all());
//            return responseApi("Cập nhật thành công!", true);
//        }catch (\Throwable $throwable)
//        {
//            return responseApi($throwable->getMessage(), false);
//        }
//    }
}
