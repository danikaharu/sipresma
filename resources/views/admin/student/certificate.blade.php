<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .certificate {
            border: 5px solid black;
            padding: 30px;
            width: 80%;
            margin: auto;
        }

        .certificate h1 {
            font-size: 36px;
            margin-top: 50px;
        }

        .certificate .details {
            margin-top: 30px;
            font-size: 18px;
        }

        .certificate .footer {
            margin-top: 50px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="certificate">
        <h1>SERTIFIKAT</h1>
        <p>Ini adalah sertifikat yang diberikan kepada:</p>
        <h2>{{ $student->name }}</h2>
        <p class="details">Untuk prestasi dengan total poin: {{ $totalPoints }} poin</p>
        <p class="footer">Tanggal: {{ now()->format('d F Y') }}</p>
    </div>
</body>

</html>
