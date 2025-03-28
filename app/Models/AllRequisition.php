<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class AllRequisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'index_id',
        'etin_id',
        'name_of_institute',
        'district',
        'thana',
        'post_name',
        'subject',
        'vacancy',
        'type',
        'apply_for',
    ];
}
