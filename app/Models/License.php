<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable=[
        'user_id',
        'link',
        'expiry_date',
        'key',
        'number_of_accesses',
        'last_access_at'
    ];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class); 
    }

    public function type()
    {
        return $this->belongsTo(LicenseType::class); 
    }
    
}
