<?php

namespace App\Http\Controllers\Maintenance;

use DB;
use App;
use Carbon;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReceiptsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {

            if($request->has('term'))
            {
                $term = $this->sanitizeString($request->get('term'));
                $receipt = App\Receipt::where('number', 'like', "%$term%")->pluck('number');
                return json_encode($receipt);
            }

            $receipt = App\Receipt::all();
            return datatables($receipt)->toJson();
        }

        return view('receipt.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('receipt.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $number = $this->sanitizeString($request->get('number'));
        $invoice_date = Carbon\Carbon::parse($this->sanitizeString($request->get('invoice_date')));
        $invoice_number = $this->sanitizeString($request->get('invoice_number'));
        $purchaseorder_date = Carbon\Carbon::parse($this->sanitizeString($request->get('purchaseorder_date')));
        $purchaseorder_number = $this->sanitizeString($request->get('purchaseorder_number'));
        $fund_code = $this->sanitizeString($request->get('fund_code'));

        $receipt = new App\Receipt;
        
        $validator = Validator::make([
            'Property Acknowledgement Receipt' => $number,
            'Purchase Order Number' => $purchaseorder_number,
            'Purchase Order Date' => $purchaseorder_date,
            'Invoice Number' => $invoice_number,
            'Invoice Date' => $invoice_date,
            'Fund Code' => $fund_code
        ], $receipt->rules());

        if($validator->fails())
        {
            Session::flash('error-message', 'Error ocurred while processing your data');
            return redirect('receipt/create')->withInput()->withErrors($validator);
        }

        $receipt->number = $number;
        $receipt->purchaseorder_number = $purchaseorder_number;
        $receipt->purchaseorder_date = $purchaseorder_date;
        $receipt->invoice_date = $invoice_date;
        $receipt->invoice_number = $invoice_number;
        $receipt->fund_code = $fund_code;
        $receipt->save();

        Session::flash('success-message', 'Receipt Created!');
        return redirect('receipt');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $id = $this->sanitizeString($id);
        $receipt = App\Receipt::with('inventory.itemtype')->find($id);

        if($request->ajax())
        {
            $inventory = $receipt->inventory;
            return datatables($inventory)->toJson(); 
        }  

        return view('receipt.show')
                    ->with('receipt', $receipt);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $id = $this->sanitizeString($id);

        $receipt = App\Receipt::find($id);

        return view('receipt.edit')
                ->with('receipt', $receipt);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = $this->sanitizeString($id);
        $number = $this->sanitizeString($request->get('number'));
        $invoice_date = Carbon\Carbon::parse($this->sanitizeString($request->get('invoice_date')));
        $invoice_number = $this->sanitizeString($request->get('invoice_number'));
        $purchaseorder_date = Carbon\Carbon::parse($this->sanitizeString($request->get('purchaseorder_date')));
        $purchaseorder_number = $this->sanitizeString($request->get('purchaseorder_number'));
        $fund_code = $this->sanitizeString($request->get('fund_code'));

        $receipt = App\Receipt::find($id);
        
        $validator = Validator::make([
            'Property Acknowledgement Receipt' => $number,
            'Purchase Order Number' => $purchaseorder_number,
            'Purchase Order Date' => $purchaseorder_date,
            'Invoice Number' => $invoice_number,
            'Invoice Date' => $invoice_date,
            'Fund Code' => $fund_code
        ], $receipt->updateRules());

        if($validator->fails())
        {
            Session::flash('error-message', 'Error ocurred while processing your data');
            return redirect("receipt/$id/edit")->withInput()->withErrors($validator);
        }

        $receipt->number = $number;
        $receipt->purchaseorder_number = $purchaseorder_number;
        $receipt->purchaseorder_date = $purchaseorder_date;
        $receipt->invoice_date = $invoice_date;
        $receipt->invoice_number = $invoice_number;
        $receipt->fund_code = $fund_code;
        $receipt->save();

        Session::flash('success-message', 'Receipt Updated!');
        return redirect('receipt');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $id = $this->sanitizeString($id);
        $receipt = App\Receipt::find($id);

        if($request->ajax())
        {

            if(count($receipt) > 0)
            {
                $receipt->delete();
                return json_encode('success');
            }
            else
            {
                return json_encode('error');
            }
        }

        if(count($receipt) > 0)
        {
            $receipt->delete();
            Session::flash('success-message', 'Receipt removed!');
        }
        else
        {
            Session::flash('error-message', 'Problem encountered while processing your request');
        }

        return redirect('receipt');
    }
}
