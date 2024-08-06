<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LicenseController extends Controller
{
    public function validate(Request $request){
        $validator = Validator::make($request->all(), [
            'domain' => 'required', //'domain' => 'active_url|required',
            'license_key' => 'required|exists:licenses,key',
        ]);

        if ($validator->fails()) {
            // You can customize the response when validation fails
            return response()->json(['error' => $validator->errors()], 400);
        }

        $key = $request->license_key;
        $link = $request->domain;
        $check = DB::table('licenses')
        ->where('key', $key)
        ->where('link', $link)
        ->where('active', 1)
        ->first();


        if($check){
            return response()->json(['message' => 'Horray, Your activation has been confirmed'], 200);
        } else{
            return response()->json(['message' => 'Whoops, something isn\'t right, Kindly recheck the submitted details'], 401);
        }
    }
}
