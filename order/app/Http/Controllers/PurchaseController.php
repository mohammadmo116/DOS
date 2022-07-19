<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;


use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $book= Http::get('catalog:8000/api/book/'.$request->get('id'));


        if($book['count']<=0)
        return "this book is out of stock!";

        else{
        $book= Http::put('catalog:8000/api/removeBook/'.$request->get('id'));
 $order =Purchase::create([
        'purchase_date'=>date('Y-m-d H:i:s'),
        'book_id'=>$request->get('id'),
    ]);
    return 'Purchase successfully order #'.$order->id;
}
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
