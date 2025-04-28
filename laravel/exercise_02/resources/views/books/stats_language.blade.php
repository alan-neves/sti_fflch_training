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