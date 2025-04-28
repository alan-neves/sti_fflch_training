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