<form method="POST" action="/books">
    @csrf
    Title: <input type="text" name="title">
    Author: <input type="text" name="author"> 
    ISBN: <input type="text" name="isbn"> 
    <button type="submit">Submit</button>
</form>