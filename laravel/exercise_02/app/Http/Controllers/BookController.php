<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use League\Csv\Reader;

class BookController extends Controller
{
    // List all books
    public function index()
    {
        $books = Book::all();
        return view('books.index', ['books' => $books]);
    }

    // Show creation form
    public function create()
    {
        return view('books.create');
    }

    // Save new book (POST)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'authors' => 'required',
            'isbn' => 'required|unique:books',
        ]);

        $book = new Book();
        $book->title = $request->title;
        $book->authors = $request->authors;
        $book->isbn = $request->isbn;
        $book->publisher = $request->publisher;
        $book->original_publication_year = $request->original_publication_year;
        $book->language_code = $request->language_code;
        $book->save();

        return redirect('/books');
    }

    // Show a specific book
    public function show(Book $book)
    {
        return view('books.show', ['book' => $book]);
    }

    // Show editing form
    public function edit(Book $book)
    {
        return view('books.edit', ['book' => $book]);
    }

    // Update book (PUT)
    public function update(Request $request, Book $book)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'authors' => 'required',
            'isbn' => 'required|unique:books,isbn,'.$book->id,
        ]);
    
        $book->title = $validatedData['title'];
        $book->authors = $validatedData['authors'];
        $book->isbn = $validatedData['isbn'];
        $book->publisher = $request->publisher;
        $book->original_publication_year = $request->original_publication_year;
        $book->language_code = $request->language_code;
        $book->image_url = $request->image_url;
        $book->save();
        return redirect('/books/' . $book->id);
    }

    // Delete book
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect('/books');
    }

    // Import CSV
    public function importCsv()
    {
        $csv = Reader::createFromPath(storage_path('app/books.csv'), 'r'); // read csv file
        $csv->setHeaderOffset(0); // Ignore first line

        $imported = 0;
        $duplicates = 0;

        foreach ($csv as $line) {
            // Check if the ISBN already exists
            if (!Book::where('isbn', $line['isbn'])->exists()) {
                $book = new Book();
                $book->title = $line['title'] ?? '';
                $book->authors = $line['authors'] ?? '';
                $book->isbn = $line['isbn'] ?? '';
                $book->publisher = $line['publisher'] ?? $line['publisher'] ?? null;
                $book->original_publication_year = $line['original_publication_year'] ?? null;
                $book->language_code = $line['language_code'] ?? null;
                $book->save();
                $imported++;
            } else {
                $duplicates++;
            }
        }

        return redirect('/books')->with([
            'success' => "Import completed! $imported new records, $duplicates ignored."
        ]);
    }

    // Statistics central page
    public function stats()
    {
        return view('books.stats', [
            'totalBooks' => Book::count() // Sends the total to the view
        ]);
    }

    // Statistics by year
    public function statsYear()
    {
        $byYear = Book::selectRaw('original_publication_year as year, count(*) as total')
            ->groupBy('original_publication_year')
            ->orderBy('original_publication_year')
            ->get();

        return view('books.stats_year', [
            'byYear' => $byYear,
            'totalBooks' => Book::count()
        ]);
    }

    // Statístics by author
    public function statsAuthor()
    {
        $byAuthor = Book::selectRaw('authors as author, count(*) as total')
            ->groupBy('authors')
            ->orderBy('total', 'desc')
            ->get();

        return view('books.stats_author', [
            'byAuthor' => $byAuthor,
            'totalBooks' => Book::count()
        ]);
    }

    // Statístics by idiom
    public function statsLanguage()
    {
        $byLanguage = Book::selectRaw('
            CASE 
                WHEN language_code IS NULL OR language_code = "" THEN "Unknown"
                ELSE language_code 
            END as language, 
            COUNT(*) as total
        ')
        ->groupBy('language')
        ->orderBy('total', 'desc')
        ->get();

        return view('books.stats_language', [
            'byLanguage' => $byLanguage,
            'totalBooks' => Book::count()
        ]);
    }
}
