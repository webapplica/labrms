<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;

class Type extends Model{

   protected $table = 'ticket_types';
   protected $primaryKey = 'id';
   public $timestamps = true;
   public $fillable = ['name','details'];

   /**
    * Filters the query by name
    *
    * @param Builder $query
    * @param string $value
    * @return void
    */
   public function scopeName($query, $value)
   {
        $query->whereIn('name', $this->generateDifferentFormats($value))->first();
   }

   /**
    * List all the possible format the value can have and returns 
    * the array equivalent to the formats
    *
    * @param string $value
    * @return array
    */
   public function generateDifferentFormats($value)
   {
        $upperCaseFirst = ucfirst($value);
        $upperCaseAll = ucwords($value);
        $default = $value;

        return [
            $upperCaseAll, $upperCaseFirst, $default
        ];
   }

   /**
    * Filter query by provided names
    *
    * @param Builder $query
    * @param array $arrayList
    * @return void
    */
   public function scopeFetchOnly($query, $arrayList)
   {
       return $query->whereIn('name', $arrayList);
   }

}
