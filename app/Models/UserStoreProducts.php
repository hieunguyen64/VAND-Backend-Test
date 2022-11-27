<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStoreProducts extends Model {
    use HasFactory;
    protected $table = 'user_store_products';
    protected $primaryKey = 'id';
    protected $fillable = [
        "store_id",
        "name",
        "price",
        "quantity",
        "created_at",
        "updated_at"
    ];
}