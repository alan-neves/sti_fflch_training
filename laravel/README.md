# Day 1

## Installation

Before installing Laravel on Debian, you need to make sure that all the dependencies are installed.  
Laravel depends on PHP and some extensions, as well as a database such as MariaDB or Sqlite. Here are the main packages that should be installed on Debian:

```bash
sudo apt-get install php php-common php-cli php-gd php-curl php-xml php-mbstring php-zip php-sybase php-mysql php-sqlite3
sudo apt-get install mariadb-server sqlite3 git
```

Composer is a dependency manager that allows you to easily install, update, and manage libraries and packages, ensuring that a project has all the necessary dependencies.  
In Laravel, Composer is used to install the framework and its libraries.

```bash
curl -s https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

In addition, it is important to configure the database, as it will be used to install Laravel. Let's initially create an admin user with admin password and create a database called training:

```bash
sudo mariadb
GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%' IDENTIFIED BY 'admin' WITH GRANT OPTION;
CREATE DATABASE training;
quit
```

Create a folder where you will do this training, you can call it training or projects:

```bash
mkdir training
cd training
```

The following command creates a new Laravel project in the day_01 folder, downloading the basic structure of the framework and installing all the necessary dependencies via Composer, ensuring that the environment is ready for development:

```bash
composer create-project laravel/laravel day_01
cd day_01
php artisan serve
```

## MVC

A route is how the framework defines and manages URLs to access different parts of the application. Routes are configured in the routes/web.php (for web pages) or routes/api.php (for APIs) file and determine which code will be executed when a user accesses a specific URL. Example:

```php
Route::get('/example-of-route', function () {
echo "A route without a controller, not good!";
});
```

A controller is a class responsible for organizing the application logic, separating the business rules from the routes.
Instead of defining all the logic directly in the routes, controllers group related functionalities, making the code cleaner and more modular. The naming convention for controllers follows the PascalCase standard, where the name must be descriptive, in the singular and always end with “Controller”, such as ProductController or UserController. Let's create the EstagiarioController with the following command that automatically generates the corresponding file within app/Http/Controllers:

```bash
php artisan make:controller InternController
```

Next, we create the trainees route and point it to the traineeController controller, previously importing the namespace App\Http\Controllers\EstagiarioController.

The namespace is a way of organizing classes, functions and constants to avoid name conflicts in large projects. It allows you to group related elements within the same scope, facilitating code reuse and maintenance.

```php
use App\Http\Controllers\InternController;

Route::get('/interns', [InternController::class,'index']);
```

The View layer is responsible for displaying the application interface, separating the presentation logic from the business logic (controller). It uses Blade, a template language that allows you to create dynamic pages efficiently. Views are stored in the resources/views folder and can be returned from a controller using return view('view_name').

```bash
mkdir resources/views/interns
touch resources/views/interns/index.blade.php
```

In the controller:

```php
public function index()
{
    return view('interns.index');
}
```

Minimal content of index.blade.php:

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Interns</title>
  </head>
  <body>
    John<br />
    Mary
  </body>
</html>
```

The Model is a representation of a table in the database and is responsible for interacting with the data in that table. It encapsulates the logic of accessing and manipulating data, allowing operations such as inserting, updating, deleting and reading records to be performed in a simple and intuitive way. Laravel uses Eloquent ORM (Object-Relational Mapping) to map database data to PHP objects, which allows you to work with tables as if they were an object class.

Creating the model called Intern:

```bash
php artisan make:model Intern -m
```

Migrations are a way to version and manage the database schema, allowing you to create, change and remove tables in a controlled and traceable way. They work as a history of changes to the database, helping to maintain version control between different development and production environments.

Each migration is a PHP class that defines the operations to be performed on the database. Migrations are stored in the database/migrations folder. Migrations make the database management process more organized and flexible, especially in projects with multiple developers.

Let's add three columns to the Intern model: name, age and email.

```php
$table->string('name');
$table->string('email');
$table->integer('age');
```

Migrate to the database.

```bash
php artisan migrate
```

## Challenge

Create a route called interns/create pointing to the create method in InternController, which must also be created.

In the create method of InternController, insert the interns:

```php
public function create(){
    $intern1 = new \App\Models\Intern;
    $intern1->name = "John";
    $intern1->email = "john@usp.br";
    $intern1->age = 26;
    $intern1->save();

    $intern2 = new \App\Models\Intern;
    $intern2->name = "Mary";
    $intern2->email = "mary@usp.br";
    $intern2->age = 27;
    $intern2->save();

    return redirect("/interns");
}
```

**Tip**

_Every time the interns/create route is accessed, the registrations will be made. You can delete everything before the insertions with the function: App\Models\Intern::truncate()_

Finally, in the index view we can search for the registered interns and pass them as a variable to the template:

```php
public function index(){
    return view('interns.index', [
        'interns' => App\Models\Intern::all()
    ]);
}
```

In the blade, we list the interns:

```html
<ul>
  @foreach($interns as $interns)
  <li>
    {{ $interns->name }} - {{ $interns->email }} - {{ $interns->age }} years
  </li>
  @endforeach
</ul>
```

## Exercise 1 - Importing Data and Statistics with Laravel

Objective: Create a basic system in Laravel to import data from a CSV file and display statistics from that data in a view.

[https://raw.githubusercontent.com/mwaskom/seaborn-data/master/exercise.csv](https://raw.githubusercontent.com/mwaskom/seaborn-data/master/exercise.csv)

1. Create the Model and Migration:

Create a model called Exercise with a corresponding migration.  
In the migration, define the necessary fields based on the columns in the exercise.csv file.  
Run the migration to create the table in the database.

2. Create the Controller and Route for Importing

Create a controller called ExerciseController with the importCsv method.  
Define a route exercises/importcsv that points to the importCsv method of the controller.  
In the importCsv method, implement the logic to read the exercise.csv file and save the data to the database using the Exercise model.

**Tip:** _You can use the League\Csv\Reader class (available via Composer) to make it easier to read the CSV._

3. Create the Route and Method for Statistics

In the same ExerciseController, create a method called stats.  
Define a route exercises/stats that points to the stats method.  
In the stats method, calculate the average of the pulse column for the rests, walking and running cases, as shown in the table below.  
Pass this data to a view called resources/views/exercises/stats.blade.php and finally assemble the table with HTML.

Example output:

| exercise.csv   | rest | walking | running |
| -------------- | ---- | ------- | ------- |
| Number of rows | XX   | XX      | XXX     |
| Average Pulse  | XX   | XX      | XXX     |

### Step-by-step resolution

1. Initial Preparation

Install the League\CSV library:

```bash
composer require league/csv
```

2. Download and Prepare the CSV File

Access the link in the browser:

https://raw.githubusercontent.com/mwaskom/seaborn-data/master/exercise.csv

Right-click and select "Save as"

Save the file as exercise.csv in the storage/app folder of your project

3. Create Model and Migration

Create the model with migration:

```bash
php artisan make:model Exercise -m
```

Edit the migration in (database/migrations/xxxx_create_exercises_table.php):

```php
$table->string('diet');
$table->integer('pulse');
$table->string('time');
$table->string('kind');
```

Run the migration:

```bash
php artisan migrate
```

4. Create the Controller

Create the controller with the name ExerciseController:

```bash
php artisan make:controller ExerciseController
```

Edit the controller in (app/Http/Controllers/ExerciseController.php):

```php
use App\Models\Exercise;
use League\Csv\Reader;
```

Then create the importCsv method:

```php
public function importCsv()
{
    // Configure the CSV reader
    $csv = Reader::createFromPath(storage_path('app/exercise.csv'), 'r');
    $csv->setHeaderOffset(0); // Ignore the header

    // Clear the table before importing
    Exercise::truncate();

    // Import each row
    foreach ($csv as $line) {
        $exercise = new Exercise();
        $exercise->diet = $line['diet'];
        $exercise->pulse = $line['pulse'];
        $exercise->time = $line['time'];
        $exercise->kind = $line['kind'];
        $exercise->save();
    }

    return redirect('/exercises/stats'); }
```

Now create the stats method:

```php
public function stats()
{
// Calculation of statistics
$statistic = [
    'rest' => [
        'amount' => Exercise::where('kind', 'rest')->count(),
        'average_pulse' => Exercise::where('kind', 'rest')->avg('pulse')
    ],
    'walking' => [
        'amount' => Exercise::where('kind', 'walking')->count(),
        'average_pulse' => Exercise::where('kind', 'walking')->avg('pulse')
    ],
    'running' => [
        'amount' => Exercise::where('kind', 'running')->count(),
        'average_pulse' => Exercise::where('kind', 'running')->avg('pulse')
    ]
    ];

    return view('exercises.stats', ['data' => $statistic]);
}
```

5. Configure Routes

Edit the routes in (routes/web.php):

```php
use App\Http\Controllers\ExerciseController;

Route::get('/exercises/importcsv', [ExerciseController::class, 'importCsv']);
Route::get('/exercises/stats', [ExerciseController::class, 'stats']);
```

6. Create the Statistics View

Create the folder for the views:

```bash
mkdir -p resources/views/exercises
```

Create the resources/views/exercises/stats.blade.php file:

```bash
touch resources/views/exercises/stats.blade.php
```

Edit the stats.blade.php view:

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Statistics</title>
    <style>
      table {
        border-collapse: collapse;
        width: 80%;
        margin: 20px auto;
      }
      th,
      td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
      }
      th {
        background-color: #f2f2f2;
      }
    </style>
  </head>
  <body>
    <h1 style="text-align: center;">Statistics</h1>

    <table>
      <tr>
        <th>exercise.csv</th>
        <th>rest</th>
        <th>walking</th>
        <th>running</th>
      </tr>
      <tr>
        <td>Number of rows</td>
        <td>{{ $data['rest']['amount'] }}</td>
        <td>{{ $data['walking']['amount'] }}</td>
        <td>{{ $data['running']['amount'] }}</td>
      </tr>
      <tr>
        <td>Pulse Average</td>
        <td>{{ round($data['rest']['average_pulse'], 1) }}</td>
        <td>{{ round($data['walking']['average_pulse'], 1) }}</td>
        <td>{{ round($data['running']['average_pulse'], 1) }}</td>
      </tr>
    </table>
  </body>
</html>
```

7. Test the Application

Start the development server:

```bash
php artisan serve
```

Access in the browser:

To import the data:

http://localhost:8000/exercises/importcsv

To see the statistics:

http://localhost:8000/exercises/stats

# Day 2

## CRUD

CRUD is an acronym for the four basic operations used to manipulate data in web systems: Create, Read, Update and Delete. These operations interact with databases, allowing, for example, users to register new information, view existing records, modify data already saved and remove records.

Let's create a new model called Book and the corresponding migration:

```bash
php artisan make:model Book -m
```

Edit the migration (database/migrations/xxxx_create_books_table.php):

```php
$table->string('title');
$table->string('author')->nullable();
$table->string('isbn');
```

Run the migration:

```bash
php artisan migrate
```

## Create

Two routes are usually needed to save a record in a CRUD operation because the process is divided into two steps: displaying the form and processing the submitted data. The GET route is used to display the creation form and the POST route is used to process the data sent by the form in the controller:

```php
use App\Http\Controllers\BookController;

// CREATE
Route::get('/books/create', [BookController::class, 'create']);
Route::post('/books', [BookController::class, 'store']);
```

To display the HTML form we use the create and store methods.

Create a controller called LivroController:

```bash
php artisan make:controller BookController
```

add the create and store methods to the controller:

```php
use App\Models\Book;

class BookController extends Controller
{
    // CREATE
    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->save();

        return redirect('/books');
    }
}
```

Create the view create:

```bash
mkdir resources/views/books
touch resources/views/books/create.blade.php
```

HTML form in view create:

```html
<form method="POST" action="/books">
  @csrf Title: <input type="text" name="title" /> Author:
  <input type="text" name="author" /> ISBN: <input type="text" name="isbn" />
  <button type="submit">Submit</button>
</form>
```

## Read

Let's implement two ways to access book records. Access to a specific book record and a list of all books:

```php
// READ
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{book}', [BookController::class, 'show']);
```

Respective controllers:

```php
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
```

Create the index view:

```bash
touch resources/views/books/index.blade.php
```

Html for index view:

```html
@forelse($books as $books)
<ul>
  <li><a href="/books/{{$book->id}}">{{ $book->title }}</a></li>
  <li>{{ $book->author }}</li>
  <li>{{ $book->isbn }}</li>
</ul>
@empty There are no books registered @endforelse
```

Create the show view:

```bash
touch resources/views/books/show.blade.php
```

Html for show view:

```html
<h1>{{ $book->title }}</h1>
<p>Author: {{ $book->author }}</p>
<p>ISBN: {{ $book->isbn }}</p>
<a href="/books/{{ $book->id }}/edit">Edit</a>
<form action="/books/{{ $book->id }}" method="post">
  @csrf @method('DELETE')
  <button type="submit" onclick="return confirm('Are you sure?')">
    Delete
  </button>
</form>
<a href="/books">Back to list</a>
```

## Update

Again we need two routes to update a record, one to display the form and one to process the submitted data:

```php
// UPDATE
Route::get('/books/{book}/edit', [BookController::class, 'edit']);
Route::put('/books/{book}', [BookController::class, 'update']);
```

Implementation in the controller:

```php
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
```

Create the edit view:

```bash
touch resources/views/books/edit.blade.php
```

Html for editing:

```html
<form method="POST" action="/books">
  @csrf Title:
  <input type="text" name="title" value="{{ $book->title }}" /> Author:
  <input type="text" name="author" value="{{ $book->author }}" /> ISBN:
  <input type="text" name="isbn" value="{{ $book->isbn }}" />
  <button type="submit">Submit</button>
</form>
```

## Delete

Route to delete:

```php
// DELETE
Route::delete('/books/{book}', [BookController::class,'destroy']);
```

Controller to delete:

```php
// DELETE
public function destroy(Book $book)
{
    $book->delete();
    return redirect('/books'); }
```

Html button to delete inside the index view:

```html
<li>
  <form action="/books/{{ $book->id }} " method="post">
    @csrf @method('delete')
    <button type="submit" onclick="return confirm('Are you sure?');">
      Delete
    </button>
  </form>
</li>
```

In the end the index file should look similar to the following:

```html
@forelse($books as $book)
<ul>
  <li><a href="/books/{{$book->id}}">{{ $book->title }}</a></li>
  <li>{{ $book->author }}</li>
  <li>{{ $book->isbn }}</li>
  <li>
    <form action="/books/{{ $book->id }}" method="post">
      @csrf @method('DELETE')
      <button type="submit" onclick="return confirm('Are you sure?')">
        Delete
      </button>
    </form>
  </li>
</ul>
@empty
<p>There are no books registered</p>
@endforelse
<a href="/books/create">Add new book</a>
```

## Exercise 2 - Importing Data and Statistics with Laravel

Objectives:

1. Create a complete CRUD for registering books: [https://github.com/zygmuntz/goodbooks-10k/blob/master/samples/books.csv](https://github.com/zygmuntz/goodbooks-10k/blob/master/samples/books.csv)

- Create an import routine, as done in exercise 1, to import the csv: [https://raw.githubusercontent.com/zygmuntz/goodbooks-10k/master/books.csv](https://raw.githubusercontent.com/zygmuntz/goodbooks-10k/master/books.csv) (this is the complete one!)

2. Create a route, controller and view that will display:

- table with the number of books per year
- a table with the number of books per author
- a table with the number of books per language

### Step-by-step resolution

1. Initial preparation

Create a new Laravel project:

```bash
composer create-project laravel/laravel exercise2
cd exercise2
```

Install the League/CSV library:

```bash
composer require league/csv
```

2. Create Model and Migration

Create Book model with migration:

```bash
php artisan make:model Book -m
```

Edit migration (database/migrations/xxxx_create_books_table.php):

```php
// Mandatory fields
$table->string('title');
$table->text('authors');
$table->string('isbn')->unique();

// Optional fields
$table->integer('book_id')->nullable();
$table->text('original_title')->nullable();
$table->float('original_publication_year')->nullable(); $table->string('language_code', 10)->nullable();
$table->string('publisher')->nullable();
$table->float('average_rating')->nullable();
$table->integer('ratings_count')->default(0)->nullable();
$table->integer('work_ratings_count')->default(0)->nullable();
$table->integer('work_text_reviews_count')->default(0)->nullable();
$table->integer('ratings_1')->default(0)->nullable();
$table->integer('ratings_2')->default(0)->nullable();
$table->integer('ratings_3')->default(0)->nullable();
$table->integer('ratings_4')->default(0)->nullable();
$table->integer('ratings_5')->default(0)->nullable();
$table->text('image_url')->nullable();
$table->text('small_image_url')->nullable();
$table->unsignedBigInteger('goodreads_book_id')->nullable();
$table->unsignedBigInteger('best_book_id')->nullable();
$table->unsignedBigInteger('work_id')->nullable();
$table->integer('books_count')->nullable();
```

Run migration:

```bash
php artisan migrate
```

3. Create Controller and Routes

Create controller:

```bash
php artisan make:controller BookController
```

Configure routes (routes/web.php):

```php
use App\Http\Controllers\BookController;

Route::get('/books', [BookController::class, 'index']);

Route::get('/books/stats', [BookController::class, 'stats']);
Route::get('/books/stats/year', [BookController::class, 'statsYear']);
Route::get('/book/stats/author', [BookController::class, 'statsAuthor']);
Route::get('/books/stats/language', [LivroController::class, 'statsLanguage']);

Route::get('/books/importcsv', [BookController::class, 'importCsv']);

Route::get('/books/create', [BookController::class, 'create']);
Route::post('/books', [BookController::class, 'store']);
Route::get('/books/{book}', [BookController::class, 'show']);
Route::get('/books/{book}/edit', [BookController::class, 'edit']);
Route::put('/books/{book}', [BookController::class, 'update']);
Route::delete('/books/{book}', [BookController::class, 'destroy']);
```

4. Implement CRUD in the Controller and import the csv file

Edit app/Http/Controllers/BookController.php:

```php
<<?php

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
        $request->validity([
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
        $book->title = $request->title;
        $book->authors = $request->authors;
        $book->isbn = $request->isbn;
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
        $byLanguage = Book::selectRaw('language_code as idiom, count(*) as total')
            ->groupBy('language_code')
            ->orderBy('total', 'desc')
            ->get();

        return view('books.stats_language', [
            'byLanguage' => $byLanguage,
            'totalBooks' => Book::count()
        ]);
    }
}
```

5. Create Views

Folder structure:

```bash
mkdir -p resources/views/books
touch resources/views/books/{index,create,edit,show,stats,stats_year,stats_author,stats_language}.blade.php
```

resources/views/books/index.blade.php:

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Book List</title>
  </head>
  <body>
    <h1>Books</h1>

    <a href="/books/create">Add book</a> | <a href="/books/stats">Statistics</a>

    @forelse($books as $book)
    <div style="margin: 20px 0;">
      <h3><a href="/books/{{$book->id}}">{{ $book->title }}</a></h3>
      <p>Author: {{ $book->authors }}</p>
      <p>ISBN: {{ $book->isbn }}</p>

      <a href="/books/{{$book->id}}/edit">Edit</a> |
      <form
        action="/books/{{$book->id}}"
        method="post"
        style="display: inline;"
      >
        @csrf @method('DELETE')
        <button type="submit" onclick="return confirm('Are you sure?')">
          Delete
        </button>
      </form>
    </div>
    @empty
    <p>There are no books registered</p>
    @endforelse
  </body>
</html>
```

resources/views/books/create.blade.php (Registration Form)

```html
<!DOCTYPE html>
<html>
  <head>
    <title>New Book</title>
  </head>
  <body>
    <h1>New Book</h1>

    <a href="/books">← Back</a>

    <form method="POST" action="/books" style="margin-top: 20px;">
      @csrf

      <div style="margin: 10px 0;">
        <label>Title: <input type="text" name="title" required /></label>
      </div>

      <div style="margin: 10px 0;">
        <label>Author: <input type="text" name="authors" required /></label>
      </div>

      <div style="margin: 10px 0;">
        <label>ISBN: <input type="text" name="isbn" required /></label>
      </div>

      <div style="margin: 10px 0;">
        <label>Publisher: <input type="text" name="editor" /></label>
      </div>

      <div style="margin: 10px 0;">
        <label
          >Year: <input type="number" name="original_publication_year"
        /></label>
      </div>

      <div style="margin: 10px 0;">
        <label>Language: <input type="text" name="language_code" /></label>
      </div>

      <button type="submit">Save</button>
    </form>
  </body>
</html>
```

resources/views/books/edit.blade.php (Edit Form)

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Edit {{ $book->title }}</title>
  </head>
  <body>
    <h1>Edit Book</h1>

    <a href="/books/{{ $book->id }}">← Back</a>

    <form
      method="POST"
      action="/books/{{ $book->id }}"
      style="margin-top: 20px;"
    >
      @csrf @method('PUT')

      <div style="margin: 10px 0;">
        <label
          >Title:
          <input type="text" name="title" value="{{ $book->title }}" required
        /></label>
      </div>

      <div style="margin: 10px 0;">
        <label
          >Author:
          <input
            type="text"
            name="authors"
            value="{{ $book->authors }}"
            required
        /></label>
      </div>

      <div style="margin: 10px 0;">
        <label
          >ISBN:
          <input type="text" name="isbn" value="{{ $book->isbn }}" required
        /></label>
      </div>

      <div style="margin: 10px 0;">
        <label
          >Publisher:
          <input type="text" name="publisher" value="{{ $book->publisher }}"
        /></label>
      </div>

      <div style="margin: 10px 0;">
        <label
          >Year:
          <input
            type="number"
            name="original_publication_year"
            value="{{ $book->original_publication_year }}"
        /></label>
      </div>

      <div style="margin: 10px 0;">
        <label
          >Language:
          <input
            type="text"
            name="language_code"
            value="{{ $book->language_code }}"
        /></label>
      </div>

      <button type="submit">Update</button>
    </form>
  </body>
</html>
```

resources/views/books/show.blade.php (Book Details)

```html
<!DOCTYPE html>
<html>
  <head>
    <title>{{ $book->title }}</title>
  </head>
  <body>
    <h1>{{ $book->title }}</h1>

    <p>Author: {{ $book->authors }}</p>
    <p>ISBN: {{ $book->isbn }}</p>
    <p>Publisher: {{ $book->publisher ?? '-' }}</p>
    <p>Year: {{ $book->original_publication_year ?? '-' }}</p>
    <p>Language: {{ $book->language_code ?? '-' }}</p>

    <a href="/books/{{$book->id}}/edit">Edit</a> |
    <form action="/books/{{$book->id}}" method="post" style="display: inline;">
      @csrf @method('DELETE')
      <button type="submit" onclick="return confirm('Are you sure?')">
        Delete
      </button>
    </form>
    | <a href="/books">Back</a>
  </body>
</html>
```

resources/views/books/stats.blade.php:

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Statistics</title>
  </head>
  <body>
    <h1>Statistics</h1>

    <a href="/books">← Back</a>

    <div style="margin: 20px 0;">
      <h2><a href="/books/stats/year">Books by Year</a></h2>
    </div>

    <div style="margin: 20px 0;">
      <h2><a href="/books/stats/author">Books by Author</a></h2>
    </div>

    <div style="margin: 20px 0;">
      <h2><a href="/books/stats/language">Books by Language</a></h2>
    </div>
  </body>
</html>
```

resources/views/books/stats_year.blade.php:

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Books by Year</title>
    <style>
      table,
      th,
      td {
        border: 1px solid black;
        border-collapse: collapse;
      }
      th,
      td {
        padding: 5px;
      }
    </style>
  </head>
  <body>
    <h1>Books by Year</h1>

    <a href="/books/stats">← Back</a>

    <table style="margin-top: 20px;">
      <tr>
        <th>Year</th>
        <th>Quantity</th>
      </tr>
      @foreach($byYear as $item)
      <tr>
        <td>{{ $item->year ?? '-' }}</td>
        <td>{{ $item->total }}</td>
      </tr>
      @endforeach
    </table>
  </body>
</html>
```

resources/views/books/stats_author.blade.php:

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Books by Author</title>
    <style>
      table,
      th,
      td {
        border: 1px solid black;
        border-collapse: collapse;
      }
      th,
      td {
        padding: 5px;
      }
    </style>
  </head>
  <body>
    <h1>Books by Author</h1>

    <a href="/books/stats">← Back</a>

    <table style="margin-top: 20px;">
      <tr>
        <th>Author</th>
        <th>Quantity</th>
      </tr>
      @foreach($byAuthor as $item)
      <tr>
        <td>{{ $item->author ?? '-' }}</td>
        <td>{{ $item->total }}</td>
      </tr>
      @endforeach
    </table>
  </body>
</html>
```

resources/views/books/stats_language.blade.php:

```html
<!DOCTYPE html>
<html>
  <head>
    <title>Books by Language</title>
    <style>
      table,
      th,
      td {
        border: 1px solid black;
        border-collapse: collapse;
      }
      th,
      td {
        padding: 5px;
      }
    </style>
  </head>
  <body>
    <h1>Books by Language</h1>

    <a href="/books/stats">← Back</a>

    <table style="margin-top: 20px;">
      <tr>
        <th>Language</th>
        <th>Quantity</th>
      </tr>
      @foreach($byLanguage as $item)
      <tr>
        <td>{{ $item->language ?? '-' }}</td>
        <td>{{ $item->total }}</td>
      </tr>
      @endforeach
    </table>
  </body>
</html>
```

6. Download and Prepare the CSV File

Access the link in the browser:

[https://raw.githubusercontent.com/zygmuntz/goodbooks-10k/master/books.csv](https://raw.githubusercontent.com/zygmuntz/goodbooks-10k/master/books.csv)

Save as books.csv in the project's storage/app folder

7. Test the Application

Start the server:

```bash
php artisan serve
```

Access in the browser:

CRUD: [http://localhost:8000/books](http://localhost:8000/books)

Import: [http://localhost:8000/books/importcsv](http://localhost:8000/books/importcsv)

Statistics: [http://localhost:8000/books/stats](http://localhost:8000/books/stats)
