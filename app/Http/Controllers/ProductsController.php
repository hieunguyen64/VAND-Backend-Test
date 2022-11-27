<?php

namespace App\Http\Controllers;

use App\Models\UserStores;
use App\Models\UserStoreProducts;

use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller {
    private $userId;
    public function __construct(){
        $user = JWTAuth::parseToken()->authenticate()->toArray();
        
        $this->userId = $user["id"];
    }

    public function show($id) {
        $user_id =  JWTAuth::parseToken()->authenticate()->id;

        return response()->json(compact("data"), 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            "store_id" => "required|integer",
            "name" => "required|string  ",
            "price" => "required|numeric",
            "quantity" => "required|integer",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $store_id = $request->get("store_id");
        $store_info = UserStores::where("id", $store_id)->where("user_id", $this->userId)->first();

        if (empty($store_info)) {
            return response()->json("Parameter '{$store_id}' not found with this user", 403);
        }
        $name =  $request->get("name");
        $price =  $request->get("price");
        $quantity =  $request->get("quantity");

        $store_product = UserStoreProducts::create([
            "store_id" => $store_id,
            "name" => $name,
            "price" => $price,
            "quantity" => $quantity
        ]);

        $data = $store_product;

        return response()->json(compact("data"), 201);
    }

    public function update($product_id, Request $request) {
        $validator = Validator::make($request->all(), [
            "name" => "string",
            "price" => "numeric",
            "quantity" => "integer",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $store_product =  UserStoreProducts::where('id', $product_id)->get()->toArray();
        if (empty($store_product)) {
            return response()->json(["mess" =>  "Data not found"], 404);
        }

        $new_data = [];
        !empty($request->get("name")) ? $new_data["name"] = $request->get("name") : null;
        !empty($request->get("price")) ? $new_data["price"] = $request->get("price") : null;
        !empty($request->get("quantity")) ? $new_data["quantity"] = $request->get("quantity") : null;

        UserStoreProducts::where('id', $product_id)->update($new_data);
        return response()->json(["mess" => "Update succesful"], 200);
    }

    public function getproductlistbystore(Request $request) {
        $validator = Validator::make($request->all(), [
            "store_id" => "required|integer",
            "paginate" =>  "required|integer"
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $paginate = $request->get("paginate");
        $store_id = $request->get("store_id");
        $store_product = UserStoreProducts::where("store_id", $store_id)->paginate($paginate);
        $data = $store_product ?? [];
        return response()->json(compact("data"), 200);
    }
    
    public function destroy($product_id) {
        $store_product =  UserStoreProducts::where('id', $product_id)->get()->toArray();
        if (empty($store_product)) {
            return response()->json(["mess" =>  "Data not found"], 404);
        }

        UserStoreProducts::where('id', $product_id)->delete();
        return response()->json(["mess" => "Delete succesful"], 200);
    }
}