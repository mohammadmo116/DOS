<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     *  Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Cache::get('flag'))
        {
          $book= Http::get('catalog:8000/api/book/'.$request->post('id'));

         Cache::set('flag',0); }
        else
        {  $book= Http::get('catalogr:8000/api/book/'.$request->post('id'));

            Cache::set('flag',1);  }

        if($book->status()==404)
     {
        Log::error('(OrderR server) book is not found".', ['id' => $request->post('id')]);
        Log::channel('stderr')->error('(OrderR server) book is not found".', ['id' => $request->post('id')]);
        return "book is not found";
    }
        else
       {Log::info('(OrderR server) book found.', ['id' => $book['id']]);
        Log::channel('stderr')->info('(OrderR server) book found.', ['id' => $book['id']]);
    }
        if($book['count']<=0)
       {  Log::info('(OrderR server) book out of stock!.', ['id' => $book['id']]);
        Log::channel('stderr')->info('(OrderR server) book out of stock!.', ['id' => $book['id']]);
       return "this book is out of stock!";
       }
        else{
            if(Cache::get('flag'))
            {
                $book= Http::put('catalog:8000/api/removeBook/'.$request->post('id'));

             Cache::set('flag',0); }
            else
            {    $book= Http::put('catalogr:8000/api/removeBook/'.$request->post('id'));


                Cache::set('flag',1);  }
                $date=date('Y-m-d H:i:s');
 $order =Purchase::create([
        'purchase_date'=>$date,
        'book_id'=>$request->post('id'),

    ]);
    Http::post('order:8000/api/addP/'.$request->post('id'),['date'=>$date]);
    Log::info('(OrderR server) Purchase successfully order #.', ['id' => $order->id]);
    Log::channel('stderr')->info('(OrderR server) Purchase successfully order #.', ['id' => $order->id]);
    return 'Purchase successfully order #'.$order->id;
}
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        Purchase::create([
            'purchase_date'=>$request->post('date'),
            'book_id'=>$id,

        ]);
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
