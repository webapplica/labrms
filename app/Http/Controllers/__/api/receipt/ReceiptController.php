<?php

namespace App\Http\Controllers\api\receipt;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReceiptController extends Controller
{
	/**
	*
	*	get receipt based on inventory
	*	@param inventory id
	*	@return receipt
	*
	*/
    public function getAll(Request $request, $id) 
    {
        $receipt = Receipt::findThroughInventory($id)->pluck('number', 'id');
        return datatables($receipt);
	}
}
