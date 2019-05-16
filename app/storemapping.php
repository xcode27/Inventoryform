<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class storemapping extends Model
{
    //
      protected $table = 'storemapping';
      public $timestamps = false;
      protected $guard = ['updated_at'];
      protected $fillable = ['store_code','store_name','supcode','supervisor','expected_submission','promo','date_created','created_by','date_modified','area'];
}
