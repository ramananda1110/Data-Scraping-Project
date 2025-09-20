<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DemonstratorResult extends Model
{
    use HasFactory;

    // Define which attributes are mass assignable
    protected $fillable = ['roll_no', 'core_data'];
}
