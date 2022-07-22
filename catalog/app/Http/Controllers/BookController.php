<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


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
        Log::channel('stderr')->info("(Catalog Server) -> getting all books -> " . $books."\n");
        Log::info("(Catalog Server) -> getting all books -> " . $books);
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
        log::error('(Catalog Server) -> no books found (search='.$topic.')');
        Log::channel('stderr')->error('(Catalog Server) -> no books found (search='.$topic.")\n");

        return response()->json(['error'=>'no books found']);
    }
        else
       { log::info('(Catalog Server) -> books found (search='.$topic .') books-> '.$books);
        Log::channel('stderr')->info('(Catalog Server) -> books found (search='.$topic .') books-> '.$books."\n");
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
        Log::info('(Catalog server) book found.', ['id' => $book->id]);
        Log::channel('stderr')->info('(Catalog server) book found.', ['id' => $book->id]);
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
        Log::info('(Catalog server) the book has been updated, the new cost is '.  $request->post('cost'), ['id' =>  $book->id]);
        Log::channel('stderr')->info('(Catalog server) the book has been updated, the new cost is '.  $request->post('cost'), ['id' => $book->id]);
        return response()->json(['cost'=>$request->post('cost')]);

    }
    public function countD(Book $book)
    {
        Log::info('(Catalog server) removing book.', ['id' => $book->id]);
        Log::channel('stderr')->info('(Catalog server) removing book.', ['id' => $book->id]);
        $book->decrement('count');
    }

        public function countI(Book $book)
    {  log::info('(Catalog Server) -> the book has been added',['id'=>$book->id]);
        Log::channel('stderr')->info('(Catalog Server) -> the book has been added',['id'=>$book->id]);
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
}
