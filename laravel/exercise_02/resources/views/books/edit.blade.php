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