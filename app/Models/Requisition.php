<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;  // This trait needs to be imported

    protected $fillable = [
        'index_id', 'etin_id', 'name_of_institute', 'district', 
        'thana', 'post_name', 'subject', 'vacancy', 'type', 'apply_for'
    ];
}