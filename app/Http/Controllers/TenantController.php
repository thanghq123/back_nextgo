<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantRequest;
use App\Models\BusinessField;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Pricing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function __construct(
        protected TenantRequest $request
    )
    {
    }

    public function index()
    {
        $tenants = Tenant::with(['user', 'business_field'])->orderBy('created_at', 'desc')->get();
        $businessField = BusinessField::all();
        $users = User::all();
        $pricing = Pricing::all();
        return view('admin.tenant.index', compact('tenants', 'businessField', 'users', 'pricing'));
    }

    public function store()
    {

        DB::beginTransaction();
        try {
            if (!empty($this->request->validated())) {
                $filterDatabase = Tenant::where('database', $this->request->input('name_tenant'))->get();
                if (count($filterDatabase) > 0) return responseApi('Cơ sở đã tổn tại');
                else {
                    $tenant = new Tenant();
                    $tenant->business_name = $this->request->input('business_name');
                    $tenant->address = $this->request->input('address');
                    $tenant->name = $this->request->input('name_tenant');
                    $tenant->domain = $this->request->input('name_tenant') . ".com";
                    $tenant->database = $this->request->input('name_tenant');
                    $tenant->user_id = $this->request->input('user_id');
                    $tenant->business_field_id = $this->request->input('business_field');
                    $tenant->pricing_id = $this->request->pricing_id;
                    $tenant->due_at = Carbon::now()->addDays($this->request->input('due_at'))->format('Y-m-d');
                    $tenant->status = 1;
                    $tenant->save();
                    return responseApi('Tạo chi nhánh thành công', true);
                }
            }
            DB::commit();
            return responseApi("Tạo thất bại", true);

        } catch (\Throwable $throwable) {
            DB::rollBack();
            return responseApi($throwable->getMessage());
        }
    }
}
