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
        'type_id',
        'key'
    ];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type()
    {
        return $this->belongsTo(LicenseType::class, 'type_id');
    }

}
