<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DebtRequest;
use Illuminate\Http\Request;
use App\Models\Tenant\Debt;

class DebtController extends Controller
{
    //
    public function __construct(
        private Debt $model,
        private DebtRequest $request
    )
    {
    }

    public function listRecovery(){
        try {
            return responseApi($this->model::query()
                ->select('debts.*')
                ->where('type', '=', 0)
                ->selectRaw('(SELECT name FROM suppliers
                                                   WHERE id = debts.partner_id)
                                                   as partner_name')
                ->orderBy('id', 'desc')
                ->paginate(10), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
    public function listRepay(){
        try {
            return responseApi($this->model::query()
                ->select('debts.*')
                ->where('type', '=', 1)
                ->selectRaw('(SELECT name FROM suppliers
                                                   WHERE id = debts.partner_id)
                                                   as partner_name')
                ->orderBy('id', 'desc')
                ->paginate(10), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function store(){
        try {
            $this->model::create($this->request->all());
            return responseApi("Tạo thành công!", true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function show()
    {
        try {
            return responseApi($this->model::query()
                ->select('debts.*')
                ->selectRaw('(SELECT name FROM suppliers
                                                   WHERE id = debts.partner_id)
                                                   as partner_name')
                ->where('debts.id', $this->request->id)
                ->first(), true);
        }catch (\Throwable $throwable)
        {
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

    public function delete(){
        try {
            $this->model::find($this->request->id)->delete();
            return responseApi("Xóa thành công!", true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
