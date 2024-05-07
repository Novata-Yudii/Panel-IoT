<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temperature extends Model
{
    protected $table = "temperature";
    protected $fillable = ["temperature","created_at","updated_at"];
}
