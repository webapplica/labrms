<?php

namespace App\Models\Reservation;

use Carbon\Carbon;
use App\Models\Reservation\Reservation;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
  
    protected $table = 'activity_reservation';
    protected $id = 'id';
    public $timestamps = true;
    protected $fillable = [
        'title', 'details', 'reservation_id'
    ];
 
    /**
     * References reservation table
     *
     * @return object
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }

    /**
     * Additional columns on selecting
     *
     * @var array
     */
    protected $appends = [
        'parsed_created_at'
    ];

    /**
     * Parsed created at selecting query
     *
     * @return object
     */
    public function getParsedCreatedAtAttribute()
    {
        return Carbon::parse($this->created_at)->format('M d, Y h:sA');
    }
}
