<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Previews</title>
    <style>
        .pdf-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .pdf-container iframe {
            width: 60%;
            height: 400px;
        }

        .fa-duotone {
            font-size: 1.5em;
        }

        .btn {
            margin-top: -1.2em;
            margin-right: -1.2em;
        }
    </style>
</head>
<body>
<div>

    <div>
        <button class="btn float-right">
            <i class="fa-duotone fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="pdf-container">
        @foreach($pdfs as $pdf)
            <h2>{{ htmlspecialchars($pdf['heading'], ENT_QUOTES, 'UTF-8') }}</h2>
            <iframe src="{{ htmlspecialchars($pdf['data'], ENT_QUOTES, 'UTF-8') }}" width="100%" height="500px"></iframe>
        @endforeach
    </div>
</div>
</body>
</html>
