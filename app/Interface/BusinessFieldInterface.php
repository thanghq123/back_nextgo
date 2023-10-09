<?php

namespace App\Interface;

interface BusinessFieldInterface
{
    public function list();
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
