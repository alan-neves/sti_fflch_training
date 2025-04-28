@forelse($books as $book)
    <ul>
        <li><a href="/books/{{$book->id}}">{{ $book->title }}</a></li>
        <li>{{ $book->author }}</li>
        <li>{{ $book->isbn }}</li>
        <li>
        <form action="/books/{{ $book->id }} " method="post">
        @csrf
        @method('delete')
        <button type="submit" onclick="return confirm('Are you sure?');">Delete</button>
        </form>
        </li>
    </ul>
@empty
    There are no books registered <br>
@endforelse
    <br> <a href="/books/create">Add new book</a>