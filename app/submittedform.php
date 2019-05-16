<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class submittedform extends Model
{
    //
     protected $table = 'form_submitted';
      public $timestamps = false;
      protected $guard = ['updated_at'];
      protected $fillable = ['receiving_id','formname','inventory_date','remarks'];
}
