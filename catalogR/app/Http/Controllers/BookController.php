<?php

namespace App\Http\Controllers;

use App\Models\Book;
use GuzzleHttp\Client;
use function Ramsey\Uuid\v1;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books=Book::all();
        Log::channel('stderr')->info("(CatalogR Server) -> getting all books -> " . $books."\n");
        Log::info("(CatalogR Server) -> getting all books -> " . $books);
        return $books;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function search(Request $request)
    {

        $topic = $request->get('topic');
        $books = Book::Where('topic', 'LIKE', "%{$topic}%")
        ->get();
        if($books->count()==0)
        {
        log::error('(CatalogR Server) -> no books found (search='.$topic.')');
        Log::channel('stderr')->error('(CatalogR Server) -> no books found (search='.$topic.")\n");

        return response()->json(['error'=>'no books found']);
    }
        else
       { log::info('(CatalogR Server) -> books found (search='.$topic .') books-> '.$books);
        Log::channel('stderr')->info('(CatalogR Server) -> books found (search='.$topic .') books-> '.$books."\n");
        return $books;}
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        Log::info('(CatalogR server) book found.', ['id' => $book->id]);
        Log::channel('stderr')->info('(CatalogR server) book found.', ['id' => $book->id]);
        return $book;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $book->cost=$request->post('cost');
        $book->save();
        Http::put('catalog:8000/api/bookcostR/'.$book->id,['cost'=>$request->post('cost')]);
        Log::info('(CatalogR server) the book has been updated, the new cost is '.  $request->post('cost'), ['id' =>  $book->id]);
        Log::channel('stderr')->info('(CatalogR server) the book has been updated, the new cost is '.  $request->post('cost'), ['id' => $book->id]);
        return response()->json(['cost'=>$request->post('cost'),'invalidate'=>true]);


    }
    public function updateR(Request $request, Book $book)
    {
        $book->cost=$request->post('cost');
        $book->save();

        Log::info('(CatalogR server) the book has been updated, the new cost is '.  $request->post('cost'), ['id' =>  $book->id]);
        Log::channel('stderr')->info('(CatalogR server) the book has been updated, the new cost is '.  $request->post('cost'), ['id' => $book->id]);

    }
    public function countD(Book $book)
    {
        Log::info('(CatalogR server) removing book.', ['id' => $book->id]);
        Log::channel('stderr')->info('(CatalogR server) removing book.', ['id' => $book->id]);
        $book->decrement('count');
        Http::put('catalog:8000/api/removeBookR/'.$book->id);
        return response()->json(['invalidate'=>true]);
    }
    public function countDR(Book $book)
    {
        Log::info('(CatalogR server) removing book.', ['id' => $book->id]);
        Log::channel('stderr')->info('(CatalogR server) removing book.', ['id' => $book->id]);
        $book->decrement('count');

    }
        public function countI(Book $book)
    {
        log::info('(CatalogR Server) -> the book has been added',['id'=>$book->id]);
        Log::channel('stderr')->info('(CatalogR Server) -> the book has been added',['id'=>$book->id]);
          $book->increment('count');
          Http::put('catalog:8000/api/addBookR/'.$book->id);
          return response()->json(['invalidate'=>true]);

    }
    public function countIR(Book $book)
    {  log::info('(CatalogR Server) -> the book has been added',['id'=>$book->id]);
        Log::channel('stderr')->info('(CatalogR Server) -> the book has been added',['id'=>$book->id]);
          $book->increment('count');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        //
    }
    public function f()
    {

      return  Http::delete('front:8000/api/invalidate/1');

    }
}
