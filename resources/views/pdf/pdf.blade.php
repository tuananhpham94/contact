<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $history->user->name }} has changed the address</title>
</head>
<body>
<h1>Hey Guys, {{ $history->user->name }} has changed the address</h1>
<p>ID: {{$history->user->unique_id}}</p>
<p>New Address: {{$history->address}}</p>
</body>
</html>