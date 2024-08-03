<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LicenseController extends Controller
{
    public function validate(Request $request){
        $validator = Validator::make($request->all(), [
            'domain' => 'active_url|required',
            'license_key' => 'required|exists:license,key',
        ]);
    
        if ($validator->fails()) {
            // Handle validation errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $key = $request->license_key;
        $link = $request->link;
       $check = License::where('key', $key)
                ->where('link', $link)
                ->first();
        
        if($check){
            return response()->json('message', 'Horray, Your activation has been confirmed');
        } else{
            return response()->json('message', 'Whoops, something isn\'t right, Kindly recheck the submitted details');
        }       
    }
}
