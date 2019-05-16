<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class prodservelist extends Model
{
    //
    protected $table = 'po_item_serve';
    public $timestamps = false;
    protected $guard = ['updated_at'];
    protected $fillable = ['po_item_serve','po_item_serve','po_item_serve','po_item_serve'];
}
