<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WorkRequest extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'ref',
        'vin',
        'item_description',
        'item_part_number',
        'qty',
        'make',
        'model',
        'year',
        'webhook_url_at',
    ];

    protected $hidden = [
        'webhook_url_at',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function getFnzPriceAttribute($price)
    {
        return number_format($price, 2);
    }
}
