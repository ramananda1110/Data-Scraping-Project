<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeritList extends Model
{
    use HasFactory;

    protected $table = 'merit_lists_bangla';

    protected $fillable = [
        'roll',
        'batch',
        'marks',
        'applicant_name',
        'subject',
        'institute_type',
        'recommend_institute',
    ];
}
