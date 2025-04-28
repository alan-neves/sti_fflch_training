<h1>{{ $book->title }}</h1>
<p>Author: {{ $book->author }}</p>
<p>ISBN: {{ $book->isbn }}</p>
<a href="/books/{{ $book->id }}/edit">Edit</a>
<form action="/books/{{ $book->id }}" method="post">
    @csrf
    @method('DELETE')
    <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
</form>
<a href="/books">Back to list</a>