<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class poproduct extends Model
{
    //
    protected $table = 'po_productlist';
    public $timestamps = false;
    protected $guard = ['updated_at'];
    protected $fillable = ['po_no','prod_sys_code','product','qty','date_modified'];
}
