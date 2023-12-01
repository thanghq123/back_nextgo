<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\OrderRequest;
use App\Models\Tenant\Order;
use App\Models\Tenant\OrderDetail;
use App\Models\Tenant\OrderDetailBatch;
use App\Models\Tenant\VariationQuantity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        private Order $model,
        private OrderDetail $orderDetailModel,
        private VariationQuantity $variationQuantityModel,
        private OrderDetailBatch $orderDetailBatchModel,
        private OrderRequest $request
    ) {
    }

    /**
     * @path /tenant/api/v1/orders
     * @method POST
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function list()
    {
        try {
            $orderData = $this->model::with([
                'orderDetails.variant',
                'orderDetails.orderDetailBatch.batch',
                'customer',
                'location',
                'createdBy',
                'payments'
            ])->orderBy('id', 'desc')->paginate(10);

            $data = $orderData->getCollection()->transform(function ($orderData) {
                return [
                    'id' => $orderData->id,
                    'location_id' => $orderData->location_id,
                    'location_data' => [
                        'name' => $orderData->location->name,
                        'image' => $orderData->location->image,
                        'description' => $orderData->location->description,
                        'tel' => $orderData->location->tel,
                        'email' => $orderData->location->email,
                        'province_code' => $orderData->location->province_code,
                        'district_code' => $orderData->location->district_code,
                        'ward_code' => $orderData->location->ward_code,
                        'address_detail' => $orderData->location->address_detail,
                        'status' => $orderData->location->status,
                        'is_main' => $orderData->location->is_main,
                        'created_by' => $orderData->location->created_by,
                        'created_at' => $orderData->created_at ?
                            Carbon::make($orderData->created_at)->format('d/m/Y H:i') : null,
                        'updated_at' => $orderData->updated_at ?
                            Carbon::make($orderData->updated_at)->format('d/m/Y H:i') : null
                    ],
                    'customer_id' => $orderData->customer_id,
                    'customer_data' => [
                        'group_customer_id' => $orderData->customer->group_customer_id,
                        'type' => $orderData->customer->type,
                        'name' => $orderData->customer->name,
                        'gender' => $orderData->customer->gender,
                        'dob' => $orderData->customer->dob,
                        'email' => $orderData->customer->email,
                        'tel' => $orderData->customer->tel,
                        'status' => $orderData->customer->status,
                        'province_code' => $orderData->customer->province_code,
                        'district_code' => $orderData->customer->district_code,
                        'ward_code' => $orderData->customer->ward_code,
                        'address_detail' => $orderData->customer->address_detail,
                        'note' => $orderData->customer->note,
                        'customer_type' => $orderData->customer->customer_type,
                        'created_at' => $orderData->created_at ?
                            Carbon::make($orderData->created_at)->format('d/m/Y H:i') : null,
                        'updated_at' => $orderData->updated_at ?
                            Carbon::make($orderData->updated_at)->format('d/m/Y H:i') : null
                    ],
                    'created_by' => $orderData->created_by,
                    'created_data' => [
                        'name' => $orderData->createdBy->name,
                        'email' => $orderData->createdBy->email,
                        'email_verified_at' => $orderData->createdBy->email_verified_at,
                        'password' => $orderData->createdBy->password,
                        'remember_token' => $orderData->createdBy->remember_token,
                        'location_id' => $orderData->createdBy->location_id,
                        'username' => $orderData->createdBy->username,
                        'tel' => $orderData->createdBy->tel,
                        'status' => $orderData->createdBy->status,
                        'created_by' => $orderData->createdBy->created_by,
                        'created_at' => $orderData->created_at ?
                            Carbon::make($orderData->created_at)->format('d/m/Y H:i') : null,
                        'updated_at' => $orderData->updated_at ?
                            Carbon::make($orderData->updated_at)->format('d/m/Y H:i') : null
                    ],
                    'discount' => $orderData->discount,
                    'discount_type' => $orderData->discount_type,
                    'tax' => $orderData->tax,
                    'service_charge' => $orderData->service_charge,
                    'total_product' => $orderData->total_product,
                    'total_price' => $orderData->total_price,
                    'status' => $orderData->status,
                    'payment_status' => $orderData->payment_status,
                    'created_at' => $orderData->created_at ?
                        Carbon::make($orderData->created_at)->format('d/m/Y H:i') : null,
                    'updated_at' => $orderData->updated_at ?
                        Carbon::make($orderData->updated_at)->format('d/m/Y H:i') : null,
                    'oder_details' => $orderData->orderDetails
                        ? collect($orderData->orderDetails)->map(function ($orderDetails) {
                            return [
                                'id' => $orderDetails->id,
                                'order_id' => $orderDetails->order_id,
                                'variation_id' => $orderDetails->variation_id,
                                'variation_data' => $orderDetails->variant ? [
                                    'product_id' => $orderDetails->variant->product_id,
                                    'sku' => $orderDetails->variant->sku,
                                    'barcode' => $orderDetails->variant->barcode,
                                    'variation_name' => $orderDetails->variant->variation_name,
                                    'display_name' => $orderDetails->variant->display_name,
                                    'image' => $orderDetails->variant->image,
                                    'price_import' => $orderDetails->variant->price_import,
                                    'price_export' => $orderDetails->variant->price_export,
                                    'status' => $orderDetails->variant->status,
                                    'created_at' => $orderDetails->variant->created_at ?
                                        Carbon::make($orderDetails->variant->created_at)->format('d/m/Y H:i') : null,
                                    'updated_at' => $orderDetails->variant->updated_at ?
                                        Carbon::make($orderDetails->variant->updated_at)->format('d/m/Y H:i') : null,
                                ] : [],
                                'batches' => $orderDetails->orderDetailBatch ?
                                    collect($orderDetails->orderDetailBatch)->map(function ($batches){
                                        return $batches->batch ? [
                                            'id' => $batches->batch->id,
                                            'code' => $batches->batch->code,
                                            'variation_id' => $batches->batch->variation_id,
                                            'manufacture_date' => $batches->batch->manufacture_date ?
                                                Carbon::make($batches->batch->manufacture_date)->format('d/m/Y H:i') : null,
                                            'expiration_date' => $batches->batch->expiration_date ?
                                                Carbon::make($batches->batch->expiration_date)->format('d/m/Y H:i') : null,
                                            'created_at' => $batches->batch->created_at ?
                                                Carbon::make($batches->batch->created_at)->format('d/m/Y H:i') : null,
                                            'updated_at' => $batches->batch->updated_at ?
                                                Carbon::make($batches->batch->updated_at)->format('d/m/Y H:i') : null
                                        ] : [];
                                    }) : [],
                                'discount' => $orderDetails->discount,
                                'discount_type' => $orderDetails->discount_type,
                                'quantity' => $orderDetails->quantity,
                                'tax' => $orderDetails->tax,
                                'total_price' => $orderDetails->total_price
                            ];
                        }) : [],
                    'payment' => $orderData->payments ?
                        collect($orderData->payments)->map(function ($payment){
                            return [
                                'paymentable_type' => $payment->paymentable_type,
                                'paymentable_id' => $payment->paymentable_id,
                                'amount' => $payment->amount,
                                'amount_in' => $payment->amount_in,
                                'amount_refund' => $payment->amount_refund,
                                'payment_method' => $payment->payment_method,
                                'payment_at' => $payment->payment_at ?
                                    Carbon::make($payment->payment_at)->format('d/m/Y H:i') : null,
                                'reference_code' => $payment->reference_code,
                                'note' => $payment->note,
                                'created_by' => $payment->created_by,
                                'created_at' => $payment->created_at ?
                                    Carbon::make($payment->created_at)->format('d/m/Y H:i') : null,
                                'updated_at' => $payment->updated_at ?
                                    Carbon::make($payment->updated_at)->format('d/m/Y H:i') : null
                            ];
                        }) : []
                ];
            });

            return responseApi(paginateCustom($data, $orderData), true);
        } catch (\Throwable $throwable) {
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
    public function store()
    {
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

            foreach ($this->request->order_details as $order_detail) {
                $orderDetail = $this->orderDetailModel::create([
                    'order_id' => $order->id,
                    'variation_id' => $order_detail['id'],
                    'price' => $order_detail['price_export'],
                    'discount' => $order_detail['priceModal']['result'],
                    'discount_type' => $order_detail['priceModal']['radioDiscount'],
                    'quantity' => $order_detail['quanity'],
                    'tax' => $order_detail['priceModal']['tax'],
                    'total_price' => $order_detail['result'],
                ]);
                if ($order_detail['batchs']) {
                    foreach ($order_detail['batches_focus']['batches'] as $data){
                        $this->orderDetailBatchModel::create([
                            'order_detail_id' => $orderDetail->id,
                            'batch_id' => $data['id'],
                            'quantity' => $order_detail['quanity']
                        ]);
                        $this->variationQuantityModel::query()
                            ->where('batch_id', $data['id'])
                            ->where('variation_id', $order_detail['id'])
                            ->decrement('quantity', $order_detail['quanity']);
                    }
                } else {
                    $this->orderDetailBatchModel::create([
                        'order_detail_id' => $orderDetail->id,
                        'quantity' => $order_detail['quanity']
                    ]);
                    $idVariationQuantities = $this->variationQuantityModel::query()
                        ->where('variation_id', $order_detail['id'])
                        ->where('quantity', '>', 0)
                        ->select('id')
                        ->first();
                    if($idVariationQuantities){
                        $this->variationQuantityModel::query()
                            ->where('id', $idVariationQuantities->id)
                            ->decrement('quantity', $order_detail['quanity']);
                    }
                }
            }

            DB::commit();
            return responseApi(['id' =>  $order->id], true);
        } catch (\Throwable $throwable) {
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
            $orderData = $this->model::with([
                'orderDetails.variant',
                'orderDetails.orderDetailBatch.batch',
                'customer',
                'location',
                'createdBy',
                'payments'
            ])
                ->where('id', $this->request->id)
                ->get();

            $data = $orderData->map(function ($orderData) {
                return [
                    'id' => $orderData->id,
                    'location_id' => $orderData->location_id,
                    'location_data' => [
                        'name' => $orderData->location->name,
                        'image' => $orderData->location->image,
                        'description' => $orderData->location->description,
                        'tel' => $orderData->location->tel,
                        'email' => $orderData->location->email,
                        'province_code' => $orderData->location->province_code,
                        'district_code' => $orderData->location->district_code,
                        'ward_code' => $orderData->location->ward_code,
                        'address_detail' => $orderData->location->address_detail,
                        'status' => $orderData->location->status,
                        'is_main' => $orderData->location->is_main,
                        'created_by' => $orderData->location->created_by,
                        'created_at' => $orderData->created_at ?
                            Carbon::make($orderData->created_at)->format('d/m/Y H:i') : null,
                        'updated_at' => $orderData->updated_at ?
                            Carbon::make($orderData->updated_at)->format('d/m/Y H:i') : null
                    ],
                    'customer_id' => $orderData->customer_id,
                    'customer_data' => [
                        'group_customer_id' => $orderData->customer->group_customer_id,
                        'type' => $orderData->customer->type,
                        'name' => $orderData->customer->name,
                        'gender' => $orderData->customer->gender,
                        'dob' => $orderData->customer->dob,
                        'email' => $orderData->customer->email,
                        'tel' => $orderData->customer->tel,
                        'status' => $orderData->customer->status,
                        'province_code' => $orderData->customer->province_code,
                        'district_code' => $orderData->customer->district_code,
                        'ward_code' => $orderData->customer->ward_code,
                        'address_detail' => $orderData->customer->address_detail,
                        'note' => $orderData->customer->note,
                        'customer_type' => $orderData->customer->customer_type,
                        'created_at' => $orderData->created_at ?
                            Carbon::make($orderData->created_at)->format('d/m/Y H:i') : null,
                        'updated_at' => $orderData->updated_at ?
                            Carbon::make($orderData->updated_at)->format('d/m/Y H:i') : null
                    ],
                    'created_by' => $orderData->created_by,
                    'created_data' => [
                        'name' => $orderData->createdBy->name,
                        'email' => $orderData->createdBy->email,
                        'email_verified_at' => $orderData->createdBy->email_verified_at,
                        'password' => $orderData->createdBy->password,
                        'remember_token' => $orderData->createdBy->remember_token,
                        'location_id' => $orderData->createdBy->location_id,
                        'username' => $orderData->createdBy->username,
                        'tel' => $orderData->createdBy->tel,
                        'status' => $orderData->createdBy->status,
                        'created_by' => $orderData->createdBy->created_by,
                        'created_at' => $orderData->created_at ?
                            Carbon::make($orderData->created_at)->format('d/m/Y H:i') : null,
                        'updated_at' => $orderData->updated_at ?
                            Carbon::make($orderData->updated_at)->format('d/m/Y H:i') : null
                    ],
                    'discount' => $orderData->discount,
                    'discount_type' => $orderData->discount_type,
                    'tax' => $orderData->tax,
                    'service_charge' => $orderData->service_charge,
                    'total_product' => $orderData->total_product,
                    'total_price' => $orderData->total_price,
                    'status' => $orderData->status,
                    'payment_status' => $orderData->payment_status,
                    'created_at' => $orderData->created_at ?
                        Carbon::make($orderData->created_at)->format('d/m/Y H:i') : null,
                    'updated_at' => $orderData->updated_at ?
                        Carbon::make($orderData->updated_at)->format('d/m/Y H:i') : null,
                    'oder_details' => $orderData->orderDetails
                        ? collect($orderData->orderDetails)->map(function ($orderDetails) {
                            return [
                                'id' => $orderDetails->id,
                                'order_id' => $orderDetails->order_id,
                                'variation_id' => $orderDetails->variation_id,
                                'variation_data' => $orderDetails->variant ? [
                                    'product_id' => $orderDetails->variant->product_id,
                                    'sku' => $orderDetails->variant->sku,
                                    'barcode' => $orderDetails->variant->barcode,
                                    'variation_name' => $orderDetails->variant->variation_name,
                                    'display_name' => $orderDetails->variant->display_name,
                                    'image' => $orderDetails->variant->image,
                                    'price_import' => $orderDetails->variant->price_import,
                                    'price_export' => $orderDetails->variant->price_export,
                                    'status' => $orderDetails->variant->status,
                                    'created_at' => $orderDetails->variant->created_at ?
                                        Carbon::make($orderDetails->variant->created_at)->format('d/m/Y H:i') : null,
                                    'updated_at' => $orderDetails->variant->updated_at ?
                                        Carbon::make($orderDetails->variant->updated_at)->format('d/m/Y H:i') : null,
                                ] : [],
                                'batches' => $orderDetails->orderDetailBatch ?
                                    collect($orderDetails->orderDetailBatch)->map(function ($batches){
                                        return $batches->batch ? [
                                            'id' => $batches->batch->id,
                                            'code' => $batches->batch->code,
                                            'variation_id' => $batches->batch->variation_id,
                                            'manufacture_date' => $batches->batch->manufacture_date ?
                                                Carbon::make($batches->batch->manufacture_date)->format('d/m/Y H:i') : null,
                                            'expiration_date' => $batches->batch->expiration_date ?
                                                Carbon::make($batches->batch->expiration_date)->format('d/m/Y H:i') : null,
                                            'created_at' => $batches->batch->created_at ?
                                                Carbon::make($batches->batch->created_at)->format('d/m/Y H:i') : null,
                                            'updated_at' => $batches->batch->updated_at ?
                                                Carbon::make($batches->batch->updated_at)->format('d/m/Y H:i') : null
                                        ] : [];
                                    }) : [],
                                'discount' => $orderDetails->discount,
                                'discount_type' => $orderDetails->discount_type,
                                'quantity' => $orderDetails->quantity,
                                'tax' => $orderDetails->tax,
                                'total_price' => $orderDetails->total_price
                            ];
                        }) : [],
                    'payment' => $orderData->payments ?
                        collect($orderData->payments)->map(function ($payment){
                            return [
                                'paymentable_type' => $payment->paymentable_type,
                                'paymentable_id' => $payment->paymentable_id,
                                'amount' => $payment->amount,
                                'amount_in' => $payment->amount_in,
                                'amount_refund' => $payment->amount_refund,
                                'payment_method' => $payment->payment_method,
                                'payment_at' => $payment->payment_at ?
                                    Carbon::make($payment->payment_at)->format('d/m/Y H:i') : null,
                                'reference_code' => $payment->reference_code,
                                'note' => $payment->note,
                                'created_by' => $payment->created_by,
                                'created_at' => $payment->created_at ?
                                    Carbon::make($payment->created_at)->format('d/m/Y H:i') : null,
                                'updated_at' => $payment->updated_at ?
                                    Carbon::make($payment->updated_at)->format('d/m/Y H:i') : null
                            ];
                        }) : []
                ];
            });
            return responseApi(collect($data)->collapse(), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function update()
    {
        try {
            $this->model::find($this->request->id)->update($this->request->all());
            return responseApi("Cập nhật thành công!", true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
