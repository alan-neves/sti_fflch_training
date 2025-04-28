<!DOCTYPE html>
<html>
  <head>
    <title>Books by Year</title>
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
    <h1>Books by Year</h1>

    <a href="/books/stats">← Back</a>

    <table style="margin-top: 20px;">
      <tr>
        <th>Year</th>
        <th>Quantity</th>
      </tr>
      @foreach($byYear as $item)
      <tr>
        <td>{{ $item->year ?? '-' }}</td>
        <td>{{ $item->total }}</td>
      </tr>
      @endforeach
    </table>
  </body>
</html>