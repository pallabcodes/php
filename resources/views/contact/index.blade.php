<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

{{-- If needed to write php within a blade file then has to be written like below; i.e. similart to <?php ?> --}}
{{-- @php
    $title = 'Contact';
@endphp --}}

<body>
    <h2>{{ $title }}</h2>
    <ul>
        {{-- @foreach ($books as $book)
            <li>{{ $book }}</li>
        @endforeach --}}


        @for ($i = 0; $i < count($books); $i++)
            <li>{{ $book[$i] }}</li>
        @endfor
    </ul>
</body>

</html>
