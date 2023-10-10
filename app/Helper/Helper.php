<?php

use Illuminate\Support\Facades\Storage;

function createFolder($folderName, $cloud = 'google')
{
    $googleDisk = Storage::disk($cloud)->makeDirectory($folderName);
    return $googleDisk;
}

function createDoc($folderName, $fileName, $content, $cloud = 'google')
{
    $googleDisk = Storage::disk($cloud)->put($folderName . '/' . $fileName, $content);
    if ($googleDisk) {
        $id = Storage::disk($cloud)->getAdapter()
            ->getMetadata($folderName . '/' . $fileName)
            ->extraMetadata()['id'];
    }
    return $id;
}

function createFile($folderName, $file, $fileName, $cloud = 'google')
{
    $googleDisk = Storage::disk($cloud)->putFileAs($folderName, $file, $fileName);
    if ($googleDisk) {
        $id = Storage::disk($cloud)->getAdapter()
            ->getMetadata($folderName . '/' . $fileName)
            ->extraMetadata()['id'];
    }
    return $id;
}

function deleteFile($folderName, $fileName, $cloud = 'google')
{
    if (Storage::disk($cloud)->exists($folderName . '/' . $fileName) == false) {
        return "File not found";
    }
    $googleDisk = Storage::disk($cloud)->delete($folderName . '/' . $fileName);
    return $googleDisk;
}

function deleteFolder($folderName, $cloud = 'google')
{
    if (Storage::disk($cloud)->exists($folderName) == false) {
        return "Folder not found";
    }
    $googleDisk = Storage::disk($cloud)->deleteDirectory($folderName);
    return $googleDisk;
}

function renameFile($folderName, $oldName, $newName, $cloud = 'google')
{
    if (Storage::disk($cloud)->exists($folderName . '/' . $oldName) == false) {
        return "File not found";
    }
    $googleDisk = Storage::disk($cloud)->move($folderName . '/' . $oldName, $folderName . '/' . $newName);
    return $googleDisk;
}

function renameFolder($oldName, $newName, $cloud = 'google')
{
    if (Storage::disk($cloud)->exists($oldName) == false) {
        return "Folder not found";
    }
    $googleDisk = Storage::disk($cloud)->move($oldName, $newName);
    return $googleDisk;
}

function listFile($folderName = '/', $cloud = 'google')
{
    if (Storage::disk($cloud)->exists($folderName) == false) {
        return "Folder not found";
    }
    $googleDisk = collect(Storage::disk($cloud)->listContents($folderName, true));
    return $googleDisk;
}

function getLinkImage($folderName, $fileName, $cloud = 'google')
{
    if (Storage::disk($cloud)->exists($folderName . '/' . $fileName)) {
        $id = Storage::disk($cloud)->getAdapter()
            ->getMetadata($folderName . '/' . $fileName)
            ->extraMetadata()['id'];
        $url = 'https://lh3.google.com/d/' . $id;
        return $url;
    }
    return "File not found";
}

function getContent($folderName, $fileName, $cloud = 'google')
{
    if (Storage::disk($cloud)->exists($folderName . '/' . $fileName) == false) {
        return "File not found";
    }
    $content = Storage::disk($cloud)->get($folderName . '/' . $fileName);
    return $content;
}

if (!function_exists('responseApi')) {
    function responseApi(mixed $data = "Not found", bool $status = false, int $code = 200, mixed $dataAppend = []): \Illuminate\Http\JsonResponse
    {
        if (!$status) $data = ['status' => $status, 'meta' => $data];

        if ($status) $data = ['status' => $status, 'payload' => $data];

        if ($status) $data = array_merge($data, $dataAppend);

        return response()->json(
            $data,
            $code
        );
    }
}
