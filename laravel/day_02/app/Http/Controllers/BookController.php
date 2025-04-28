<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    // Create
    public function create(){
        return view('books.create');
    }

    public function store(Request $request){
        $book = new book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->save();

        return redirect('/books');
    }

    // READ
    public function index()
    {
        $books = Book::all();
        return view('books.index',[
            'books' => $books
        ]);
    }

    public function show(Book $book)
    {
        return view('books.show',[
            'book' => $book
        ]); 
    }

    // UPDATE
    public function edit(Book $book)
    {
        return view('books.edit', ['book' => $book]);
    }

    public function update(Request $request, Book $book)
    {
        $book->title = $request->title; 
        $book->author = $request->author; 
        $book->isbn = $request->isbn; 
        $book->save(); 
        return redirect("/books/{$book->id}");
    }

    // DELETE
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect('/books'); }
}
