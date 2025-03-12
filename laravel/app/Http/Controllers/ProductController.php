<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ---Patch /api/products/{productId}
    public function updateProduct($productId){
        return ["message" => "Updating 1 product base on given productId"];
    }
    // ---Delete /api/products/{productId}
    public function deleteProduct($productId){
        return ["message" => "Deleting 1 product base on given productId"];
    }
    // ---Get
}
