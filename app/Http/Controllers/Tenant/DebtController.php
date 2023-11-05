<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DebtRequest;
use Carbon\Carbon;
use App\Models\Tenant\Debt;
class DebtController extends Controller
{
    //
    public function __construct(
        private Debt        $model,
        private DebtRequest $request
    )
    {
        $this->checkTimeDebt();
        $this->checkAmountDebt();
    }
    /**
     * @path /tenant/api/v1/debt
     * @method POST
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function index()
    {
        try {
            $debt = $this->model::with('partner')->paginate(10);
            $data = $debt->getCollection()->transform(function ($item) {
                return [
                    "partner_name" => $item->partner->name,
                    "debit_at" => $item->debit_at,
                    "due_at" => $item->due_at,
                    "type" => $item->type,
                    "name" => $item->name,
                    "amount_debt" => $item->amount_debt,
                    "amount_paid" => $item->amount_paid,
                    "note" => $item->note,
                    "status" => $item->status,
                ];
            });
            $response = new \Illuminate\Pagination\LengthAwarePaginator(
                $data,
                $debt->total(),
                $debt->perPage(),
                $debt->currentPage(), [
                    'path' => \Request::url(),
                    'query' => [
                        'page' => $debt->currentPage()
                    ]
                ]
            );
            return responseApi($response, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
    /**
     * @path /tenant/api/v1/debt/store
     * @method POST
     * @param DebtRequest $request
     * @requires partner_id,partner_type,debit_at,due_at,type,name,amount_debt
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store()
    {
        try {
            $this->model::create($this->request->all());
            return responseApi("Tạo thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
    /**
     * @path /tenant/api/v1/debt/show
     * @method POST
     * @param DebtRequest $request
     * @requires id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function show($id)
    {
        try {
            $debt = $this->model::with('partner')->where('id', $id)->get();
            $response = $debt->map(function ($item) {
                return [
                    "partner_name" => $item->partner->name,
                    "debit_at" => $item->debit_at,
                    "due_at" => $item->due_at,
                    "type" => $item->type,
                    "name" => $item->name,
                    "amount_debt" => $item->amount_debt,
                    "amount_paid" => $item->amount_paid,
                    "note" => $item->note,
                    "status" => $item->status,
                    "payments" => $item->payments
                ];
            });
            return responseApi(collect($response)->collapse(), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
    /**
     * @path /tenant/api/v1/debt/update
     * @method POST
     * @param DebtRequest $request
     * @requires partner_id,partner_type,debit_at,due_at,type,name,amount_debt
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update()
    {
        try {
            $this->model::find($this->request->id)->update($this->request->all());
            return responseApi("Cập nhật thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
    /**
     * @path /tenant/api/v1/debt/delete
     * @method POST
     * @param DebtRequest $request
     * @requires partner_id,partner_type,debit_at,due_at,type,name,amount_debt
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function delete()
    {
        try {
            $this->model::find($this->request->id)->delete();
            return responseApi("Xóa thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
    public function checkTimeDebt()
    {
        $now = Carbon::now()->format('Y-m-d');
        try {
            $this->model::where('due_at', '<', $now)->update(['status' => 0]);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
    public function checkAmountDebt()
    {
        try {
            $debt = $this->model::where('status', 1)->orWhere('status', 2)->get();
            $debt->map(function ($item) {
                $amount_paid = $item->payments->sum('amount');
                if ($item->payments->sum('amount')>0){
                    $item->update(['amount_paid' => $amount_paid, 'status' => 2]);
                }
                if ($item->amount_debt == $amount_paid) {
                    $item->update(['status' => 3]);
                }
            });
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
