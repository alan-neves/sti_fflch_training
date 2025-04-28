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