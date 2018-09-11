<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Model;

class Lost extends Model
{
    protected $table = 'lost_items';
    protected $primaryKey = 'id';

    public static $rules = [
    	'Identifier' => 'required|unique:lost_items,identifier',
    	'Description' => 'required',
    	'Date Found' => 'required'
    ];

    public function rules() {
    	return self::$rules;
    }

    public static $isExisting = [
        'Record' => 'required|exists:lost_items,id'
    ];

    public static $updateRules = [
        'Identifier' => 'required',
        'Description' => 'required',
        'Date Found' => 'required'
    ];

    public function updateRules() {
    	return self::$rules;
    }

    public static $claimRules = [
    	'ID' => 'required|exists:lost_items,id',
    	'Claimant' => 'required'
    ];

    public function claimRules() {
    	return self::$rules;
    }
}
