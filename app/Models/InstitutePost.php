<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutePost extends Model
{
    
    protected $fillable = [
    'index_id','eiin','institute_name','authority',
    'subject','post_name','thana','district','level'
    ];

   


}
