<?php

namespace App\Commands\Item\Type;

use App\Models\Item\Type;
use Illuminate\Http\Request;
use App\Models\Item\Lost as LostItem;

class UpdateItem
{
    protected $request;
    protected $id;

	public function __construct(Request $request, $id)
	{
        $this->request = $request;
        $this->id = $id;
	}

	public function handle(Type $type)
	{
        $request = $this->request;
        $claimant = $request->get('claimant');
        $dateclaimed = Carbon::now();
        $status = 'claimed';

        LostItem::find($this->id)->update([

        ]);
	}
}