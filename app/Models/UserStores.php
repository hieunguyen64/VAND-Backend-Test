<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStores extends Model {
    use HasFactory;
    protected $table = "user_stores";
    protected $primaryKey = "id";
    protected $fillable = [
        "user_id",
        "store_name",
        "created_at",
        "updated_at"
    ];
}