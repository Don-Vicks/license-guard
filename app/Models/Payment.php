<?php

namespace App\Models;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LicenseType;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_id',
        'trx_ref',
        'gateway',
        'amount',
        'info'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    // public function type(){
    //     return $this->belongsTo(LicenseType::class);
    // }

    public function license(){
        return $this->belongsTo(License::class);
    }

    public static function filamentTableColumns()
    {
        return [
            TextColumn::make('user.name'),
            TextColumn::make('license.type.name'),
            TextColumn::make('license.key'),
            TextColumn::make('amount'),
            TextColumn::make('created_at'),

        ];
    }
}
