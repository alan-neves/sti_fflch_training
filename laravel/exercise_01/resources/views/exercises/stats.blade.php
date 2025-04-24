<!DOCTYPE html> 
<html> 
<head> 
<title>Statistics</title> 
<style> 
table { border-collapse: collapse; width: 80%; margin: 20px auto; } 
th, td { border: 1px solid #ddd; padding: 8px; text-align: center; } 
th { background-color: #f2f2f2; }
</style>
</head>
<body>
<h1 style="text-align: center;">Statistics</h1>

<table>
<tr>
<th>exercise.csv</th>
<th>rest</th>
<th>walking</th>
<th>running</th>
</tr>
<tr>
<td>Number of rows</td>
<td>{{ $data['rest']['amount'] }}</td>
<td>{{ $data['walking']['amount'] }}</td>
<td>{{ $data['running']['amount'] }}</td>
</tr>
<tr>
<td>Pulse Average</td>
<td>{{ round($data['rest']['average_pulse'], 1) }}</td>
<td>{{ round($data['walking']['average_pulse'], 1) }}</td>
<td>{{ round($data['running']['average_pulse'], 1) }}</td>
</tr>
</table>
</body>
</html>