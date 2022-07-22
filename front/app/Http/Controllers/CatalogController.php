<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use function PHPUnit\Framework\returnSelf;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $response= Http::get('catalog:8000/api/getBooks');
        Log::info("(Front-end Server) -> getting all books -> " . $response->body());
        Log::channel('stderr')->info("(Front-end Server) -> getting all books -> " . $response->body()."\n");
        return $response->json();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response= Http::get('catalog:8000/api/getBooks');
        return $response->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response= Http::get('catalog:8000/api/book/'.$id);
        if($response->status()==404)
       { Log::error('(Front-end server) book was not found', ['id' => $id]);
        Log::channel('stderr')->error('(Front-end server) book was not found book', ['id' => $id]);
        return "book is not found";}
        else
        {  Log::info('(Front-end server) book was found', ['id' => $id]);
            Log::channel('stderr')->info('(Front-end server) book was found', ['id' => $id]);
            return $response->json();}

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

        $response= Http::put('catalog:8000/api/bookcost/'.$id,['cost'=>$request->post('cost')]);
        if($response->status()==404)
        {
            Log::error('(Front-end server) book was not found', ['id' => $id]);
            Log::channel('stderr')->error('(Front-end server) book was not found book', ['id' => $id]);
            return "book is not found";}
        else
        {
             Log::info('(Front-end server) the book has been updated, the new cost is '.  $response->json('cost'), ['id' => $id]);
             Log::channel('stderr')->info('(Front-end server) the book has been updated, the new cost is '.  $response->json('cost'), ['id' => $id]);
        return "the book has been updated, the new cost is ".  $response->json('cost');
    }

    }

    public function search($topic)
    {

        $response= Http::get('catalog:8000/api/search?topic='.$topic);
        if($response->json('error'))
       {  log::error('(front-end Server) -> no books found (search='.$topic.')');
          Log::channel('stderr')->error('(front-end Server) -> no books found (search='.$topic.")\n");
        return $response->json('error');}
        else
        { log::info('(front-end Server) -> books found (search='.$topic .') books-> '.$response->body());
        Log::channel('stderr')->info('(front-end Server) -> books found (search='.$topic .') books-> '.$response->body()."\n");
        return $response->json();}
    }


        public function countI($id)
    {
        $response= Http::put('catalog:8000/api/addBook/'.$id);
        if($response->status()==404)
        {log::error('(front-end Server) -> book is not found',['id'=>$id]);
            Log::channel('stderr')->error('(front-end Server) -> book is not found',['id'=>$id]);
            return "book is not found";
        }
        else
        {
            log::info('(front-end Server) -> the book has been added',['id'=>$id]);
            Log::channel('stderr')->info('(front-end Server) ->  the book has been added',['id'=>$id]);
        return "the book has been added";
    }
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
