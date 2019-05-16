<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class form extends Model
{
    //
      protected $table = 'form_mapping';
      public $timestamps = false;
      protected $guard = ['updated_at'];
      protected $fillable = ['supcode','storecode','formcode','formname','form_description','date_created','frequency','date_modified'];
}
