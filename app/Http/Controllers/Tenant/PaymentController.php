<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order;
use App\Models\Tenant\Payment;
use App\Models\Tenant\Debt;
use Illuminate\Http\Request;
use App\Http\Requests\Tenant\PaymentRequest;

class PaymentController extends Controller
{
    public function __construct(
        protected Payment $model,
        protected Debt    $debtModel,
        protected Order   $orderModel,
    )
    {
    }

    /**
     * @path /tenant/api/v1/payment
     * @desciption Danh sách thanh toán
     * @method POST
     * @param PaymentRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function index()
    {
        try {
            $payment = $this->model::with('paymentable', 'createdBy')->paginate(10);
            $data = $payment->getCollection()->transform(function ($item) {
                return [
                    "id" => $item->id,
                    "paymentable_type" => $item->paymentable_type,
                    "paymentable_id" => $item->paymentable_id,
                    "amount" => $item->amount,
                    "amount_in" => $item->amount_in,
                    "amount_refund" => $item->amount_refund,
                    "payment_method" => $item->payment_method,
                    "payment_at" => $item->payment_at,
                    "reference_code" => $item->reference_code,
                    "note" => $item->note,
                    "created_by" => $item->createdBy->name,
                    "created_at" => $item->created_at,
                    "paymentable" => collect($item->paymentable)->merge(['partner_name'=>$item->paymentable->partner->name])->all(),
                ];
            });
            return responseApi(paginateCustom($data,$payment), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/payment/store/debt/{id}
     * @desciption Thanh toán công nợ
     * @method POST
     * @param PaymentRequest $request id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function storeDebt(PaymentRequest $request, $id)
    {
        try {
            $debt = $this->debtModel::find($id);
            $payment = $debt->payments()->create([
                'amount' => $request->amount,
                'amount_in' => $request->amount_in,
                'amount_refund' => $request->amount_refund,
                'payment_method' => $request->payment_method,
                'payment_at' => now(),
                'reference_code' => $request->reference_code,
                'note' => $request->note,
                'created_by' => 1
            ]);
            return responseApi($payment->id, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/payment/store/order/{id}
     * @desciption Thanh toán đơn hàng
     * @method POST
     * @param PaymentRequest $request id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function storeOrder(PaymentRequest $request, $id)
    {
        try {
            $order = $this->orderModel::find($id);
            $payment = $order->payments()->create([
                'amount' => $request->amount,
                'amount_in' => $request->amount_in,
                'amount_refund' => $request->amount_refund,
                'payment_method' => $request->payment_method,
                'payment_at' => now(),
                'reference_code' => $request->reference_code,
                'note' => $request->note,
                'created_by' => 1
            ]);
            return responseApi($payment->id, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
