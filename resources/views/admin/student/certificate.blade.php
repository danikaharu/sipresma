<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat {{ $student->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .certificate {
            position: relative;
            width: 600px;
            height: 600px;
            background-image: url('template_admin/img/certificate.jpg');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            text-align: center;
            padding: 50px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        h1 {
            font-family: 'Great Vibes', cursive;
            font-size: 48px;
            color: goldenrod;
            margin-top: 100px;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            color: #555;
        }

        .bold {
            font-weight: bold;
            color: #000;
        }
    </style>
</head>

<body>
    <div class="certificate">
        <h1>SERTIFIKAT PENGHARGAAN</h1>
        <p>
            Diberikan kepada <br>
            <span class="bold">{{ $student->name }}</span> <br><br>
            Sebagai bentuk apresiasi atas pencapaian <span class="bold">30 poin</span> <br>
            sesuai yang telah ditetapkan.
        </p>
        <p>Diselenggarakan pada {{ now()->format('d F Y') }}</p>
    </div>
</body>

</html>
