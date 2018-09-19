<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TicketType extends \Eloquent{

   protected $table = 'ticket_types';
   protected $primaryKey = 'id';
   public $timestamps = true;
   public $fillable = ['name','details'];

   public function scopeFindByName($query, $value)
   {
      $query->whereIn('name', [$value, ucfirst($value), ucwords($value)])->first();
   }

}
