<?php

namespace App\Http\Controllers;
use Carbon\Carbon;


use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use function PHPUnit\Framework\returnSelf;

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

        $book= Http::get('catalog:8000/api/book/'.$request->post('id'));

        if($book->status()==404)
     {
        Log::error('(Order server) book is not found".', ['id' => $request->post('id')]);
        Log::channel('stderr')->error('(Order server) book is not found".', ['id' => $request->post('id')]);
        return "book is not found";
    }
        else
       {Log::info('(Order server) book found.', ['id' => $book['id']]);
        Log::channel('stderr')->info('(Order server) book found.', ['id' => $book['id']]);
    }
        if($book['count']<=0)
       {  Log::info('(Order server) book out of stock!.', ['id' => $book['id']]);
        Log::channel('stderr')->info('(Order server) book out of stock!.', ['id' => $book['id']]);
       return "this book is out of stock!";
       }
        else{
        $book= Http::put('catalog:8000/api/removeBook/'.$request->post('id'));
 $order =Purchase::create([
        'purchase_date'=>date('Y-m-d H:i:s'),
        'book_id'=>$request->post('id'),
    ]);
    Log::info('(Order server) Purchase successfully order #.', ['id' => $order->id]);
    Log::channel('stderr')->info('(Order server) Purchase successfully order #.', ['id' => $order->id]);
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
