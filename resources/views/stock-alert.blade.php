<html>
<head>
    <style>
        table, td, th {
            border: 1px solid;
        }
    </style>
</head>
<body>
<h4>Following ingredients are running out of stock:</h4>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Remaining Stock</th>
        <th>Unit</th>
    </tr>
    </thead>
    <tbody>
    @foreach($ingredients_list as $ingredient)
        <tr>
            <td>{{ $ingredient['ingredient_id'] }}</td>
            <td>{{ $ingredient['ingredient_name'] }}</td>
            <td>{{ $ingredient['remaining_stock'] }}</td>
            <td>{{ $ingredient['unit'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
