<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pocreated_details extends Model
{
    //
    protected $table = 'po_created_details';
    public $timestamps = false;
    protected $guard = ['updated_at'];
    protected $fillable = [
                            'tran_no',
    						'head_id',
    						'controlno',
    						'product_code',
    						'product_name',
    						'qty'
    					  ];
}
