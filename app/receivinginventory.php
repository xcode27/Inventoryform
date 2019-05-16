<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class receivinginventory extends Model
{
    //
    protected $table = 'receivinginventories';
    public $timestamps = false;
    protected $guard = ['updated_at'];
    protected $fillable = ['control_no','store','supervisor','promo','inventory_date','received_by', 'created_at','store_name'];
}
