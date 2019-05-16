<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pocreated extends Model
{
    //
    protected $table = 'po_created_head';
    public $timestamps = false;
    protected $guard = ['updated_at'];
    protected $fillable = ['controlno','po_no','store','store_name','po_date','received_by', 'created_at'];
}
