<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Marcas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileUploadController extends Controller {
   
    public function fileUpload($file,$destinationPath) {
        $extenstion = $file->getClientOriginalExtension();
        $fileName = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,10).'.'.$extenstion;
        $file->move($destinationPath, 'luis.ong');
        return $fileName;
    }
}
