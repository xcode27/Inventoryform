<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class poserve extends Model
{
    //
      protected $table = 'po_serve';
      public $timestamps = false;
      protected $guard = ['updated_at'];
      protected $fillable = ['po_no','store_code','store','po_date','dr_sr','po_serve_date','date_created','tran_no','tran_no','created_by'];
}
