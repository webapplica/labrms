<?php

namespace App\Models\Item;

use Illuminate\Database\Eloquent\Model;

class Lost extends Model
{
    protected $table = 'lost_items';
    protected $primaryKey = 'id';
}
