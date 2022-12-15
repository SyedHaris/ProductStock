<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientStock extends Model
{
    protected $fillable = [
        'remaining_stock',
        'alert_email_sent'
    ];
    protected $table = 'ingredient_stock';
}
