<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class InventoryLog extends Model
{
    protected $table = 'inventory_transactions';
    protected $primaryKey = 'id';

    function __construct()
    {
    	$this->user_id = Auth::user()->id;
    }

    public $fillable = [
    	'details',
    	'quantity',
    	'inventory_id'
    ];

    public function inventory()
    {
    	return $this->belongsTo('App\Inventory', 'inventory_id', 'id');
    }
}
