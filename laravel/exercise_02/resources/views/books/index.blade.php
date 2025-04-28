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