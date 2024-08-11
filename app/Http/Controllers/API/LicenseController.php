<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LicenseController extends Controller
{
    public function validate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'domain' => 'required|url',
                'license_key' => 'required|exists:licenses,key',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $message = $errors->first('license_key') ?? $errors->first('domain');
                return response()->json([
                    'status' => false,
                    'message' => $message,
                    'error' => $errors
                ], 400);
            }

            $key = $request->license_key;
            $link = $request->domain;
            $check = License::where('key', $key)
                ->where('link', $link)
                ->where('active', 1)
                ->first();


            if ($check) {
                //update number of access times and last access as now
                $number_of_accesses = $check->number_of_accesses + 1;

                $check->update(['number_of_accesses' => $number_of_accesses, 'last_accessed_at' => now()]);

                $check->save();


                return response()->json([
                    'status' => true,
                    'message' => 'Horray, Your activation has been confirmed'
                ], 200);


            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Whoops, something isn\'t right, Kindly recheck your license or switch to a registered domain'
                ], 401);
            }
        }catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => "Licensing API! Unexpected ERROR: " .$exception->getMessage(),
                'error'  => $exception->getMessage(),
            ]);
        }
    }
}
