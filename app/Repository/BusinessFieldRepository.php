<?php

namespace App\Repository;

use App\Interface\BusinessFieldInterface;
use App\Models\BusinessField;

class BusinessFieldRepository implements BusinessFieldInterface
{
    public function list()
    {
        return responseApi(BusinessField::all(),true);
    }

    public function getById($id)
    {
        $bf = BusinessField::find($id);
        if (!$bf) return responseApi('Có lỗi xảy ra');
        else return responseApi($bf,true);
    }

    public function create(array $data)
    {
        $bf = new BusinessField();
        $bf->name = $data['name'];
        $bf->code = $data['code'];
        $bf->detail = $data['detail'];
        $bf->save();
        return responseApi('Tạo thành công',true);
    }

    public function update($id, array $data)
    {
        $bf = BusinessField::find($id);
        if (!$bf) {
            return responseApi('Có lỗi xảy ra');
        } else {
            $bf->name = $data['name'];
            $bf->code = $data['code'];
            $bf->detail = $data['detail'];
            $bf->save();
            return responseApi('Cập nhật thành công',true);
        }
    }

    public function delete($id)
    {
        $bf = BusinessField::find($id);
        if (!$bf){
            return responseApi('Có lỗi xảy ra');
        }
        else{
            $bf->delete();
            return responseApi('Xóa thành công', true);
        }
    }
}
