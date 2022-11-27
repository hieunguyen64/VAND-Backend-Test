<?php

namespace App\Http\Controllers;

use App\Models\UserStores;
use App\Models\UserStoreProducts;

use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoresController extends Controller {
    public function index(Request $request) {
        $user = JWTAuth::parseToken()->authenticate()->toArray();
        $default_paginate = 5;
        $paginate = $request->get("paginate") ?? $default_paginate;
        $user_stores = UserStores::where("user_id", $user["id"])->paginate($paginate);

        return response()->json(compact("user_stores"));
    }

    public function show($id) {
        $user_id =  JWTAuth::parseToken()->authenticate()->id;
        $data = $this->get_store_info($user_id, $id);

        return response()->json(compact("data"), 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            "store_name" => "required|string"
        ]);
        
       
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $duration = $request->get("duration");
        $store_name =  $request->get("store_name");
        $user_store = UserStores::create([
            "user_id" => JWTAuth::parseToken()->authenticate()->id,
            "store_name" => $store_name
        ]);

        $data = $user_store;

        return response()->json(compact("data"), 201);
    }

    public function update($store_id, Request $request) {
        $validator = Validator::make($request->all(), [
            "store_name" => "string"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user_id = JWTAuth::parseToken()->authenticate()->id;
        $store_data = $this->get_store_info($user_id, $store_id);
        if (empty($store_data)) {
            return response()->json(["mess" =>  "Data not found"], 404);
        }

        $new_store_name = $request->get("store_name");
        UserStores::where('id', $store_id)->update(['store_name' => $new_store_name]);
     
        return response()->json(["mess" => "Update succesful"], 200);
    }

    public function destroy($store_id) {
        $user_id = JWTAuth::parseToken()->authenticate()->id;
        $store_data = $this->get_store_info($user_id, $store_id);
        if (empty($store_data)) {
            return response()->json(["mess" =>  "Data not found"], 404);
        }

        UserStores::where('id', $store_id)->delete();
        return response()->json(["mess" => "Delete succesful"], 200);
    }

    public function get_store_info($user_id, $store_id) {
        $store_info = UserStores::where("id", $store_id)->where("user_id", $user_id)->first();
        $store_detail_info = UserStoreProducts::where("store_id", $store_id)->get()->toArray();
        if(!empty($store_info)) {
            $store_info["products"] = $store_detail_info;
        }
        return  $store_info ?? [];
    }
}