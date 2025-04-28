<form method="POST" action="/books">
    @csrf
    Title: <input type="text" name="title" value="{{ $book->title }}">
    Author: <input type="text" name="author" value="{{ $book->author }}">
    ISBN: <input type="text" name="isbn" value="{{ $book->isbn }}">
    <button type="submit">Submit</button>
</form>