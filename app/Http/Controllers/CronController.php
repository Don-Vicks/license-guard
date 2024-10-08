<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\License;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\RenewLicense;

class CronController extends Controller
{
    public function job(){
        $license = License::where('active', 1)->where('expiry_date', '<', now())->get();
          if($license){
            foreach ($license as $value) {
            $value->active = 0;
            $value->save();   
            $users = User::where('id', $value->user_id)->get();
            foreach($users as $user){
                Mail::to($user)->queue(new RenewLicense);
            }
           }
          } 
           Artisan::call('queue:work', [
            '--stop-when-empty' => true,
        ]);

        return response()->json(['message' => 'Queue worker started'], 200);
    }
}
