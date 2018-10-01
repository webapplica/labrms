<?php

namespace App\Models\Inventory;

// use Carbon\Carbon;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'inventory_transactions';
    protected $primaryKey = 'id';
    public $fillable = [ 'details', 'quantity', 'inventory_id', 'user_id' ];

    // protected $appends = [
    //     'user_info', 'quantity_issued', 'quantity_received', 'parsed_date'
    // ]; 

    // public function __construct()
    // {
    //     if( !isset($this->user_id)) {
    //        $this->user_id = Auth::user()->id;
    //     }
    // }

    // public function getUserInfoAttribute()
    // {
    //     $user = $this->user;
    //     return $user->lastname . ", " . $user->firstname;
    // }

    // public function getParsedDateAttribute()
    // {
    //     $date = Carbon\Carbon::parse($this->date)->format('M d, Y h:m a');
    //     return $date;
    // }

    // public function getQuantityIssuedAttribute()
    // {
    //     return ( $this->quantity <= 0 ) ? abs($this->quantity) : 0 ;
    // }

    // public function getQuantityReceivedAttribute()
    // {
    //     return ( $this->quantity > 0 ) ? $this->quantity : 0 ;
    // }

    // public function scopeFindByInventoryID($query, $value)
    // {
    //     return $query->where('inventory_id', '=', $value);
    // }

    // public function inventory()
    // {
    // 	return $this->belongsTo('App\Inventory', 'inventory_id', 'id');
    // }

    // public function user()
    // {
    //     return $this->belongsTo('App\User', 'user_id', 'id');
    // }
}
