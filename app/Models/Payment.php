<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LicenseType;

class Payment extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    // public function type(){
    //     return $this->belongsTo(LicenseType::class);
    // }

    public function license(){
        return $this->belongsTo(License::class);
    }

    public function type()
    {
        return $this->hasOneThrough(Payment::class, LicenseType::class, 'id', 'licensetype_id');
    }
}
