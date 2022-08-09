<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {

        if(Cache::has($id))
        {if(Cache::get($id)['count']<=0)
        return "this book is out of stock!";
        }
        if(Cache::get('flag'))
        {

            $response= Http::post('order:8000/api/buyBook',['id'=>$id,'exist'=>Cache::has($id)?Cache::get($id):false]);


         Cache::set('flag',0); }
        else
        {     $response= Http::post('orderr:8000/api/buyBook',['id'=>$id,'exist'=>Cache::has($id)?Cache::get($id):false]);

            Cache::set('flag',1);  }


         if($response->json('p')=="this book is out of stock!"||$response->json('p')=="book is not found"){
            Log::info('(Front-end server) Purchase not successfully book',['id'=>$id]);
            Log::channel('stderr')->info('(Front-end server) Purchase not successfully book',['id'=>$id]);
         }
         else{

                   Log::info('(Front-end server) Purchase successfully book',['id'=>$id]);
            Log::channel('stderr')->info('(Front-end server) Purchase successfully book',['id'=>$id]);}

            if($response->json('invalidate'))
           { if(Cache::has($id))
            Cache::forget($id);}

         return $response->json('p');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
