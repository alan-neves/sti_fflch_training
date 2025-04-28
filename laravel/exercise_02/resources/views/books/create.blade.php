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
        <label>Publisher: <input type="text" name="publisher" /></label>
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