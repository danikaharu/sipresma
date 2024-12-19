2

<html>

<head>
    <title>Laporan | {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <style type="text/css">
        * {
            font-size: 12pt;
        }

        .table-border {
            border: 1px solid black;
            border-collapse: collapse;
            width: 100%;
        }

        .table-border tr td,
        .table-border tr th {
            border: 1px solid black;
            padding: 10px;
            width: auto;
        }
    </style>

    <center>
        <h6 style="margin: 0;">LAPORAN KEGIATAN MAHASISWA</h6>
    </center>

    <div style="margin: 2% 0;">
        <table class='table-border'>
            <thead>
                <tr>
                    <th style="text-align:center;vertical-align:middle;">No.</th>
                    <th style="text-align:center;vertical-align:middle;">NAMA</th>
                    <th style="text-align:center;vertical-align:middle;">NIM
                    </th>
                    <th style="text-align:center;vertical-align:middle;">Prodi</th>
                    <th style="text-align:center;vertical-align:middle;">Nama Kegiatan</th>
                    <th style="text-align:center;vertical-align:middle;">Capaian Prestasi</th>
                    <th style="text-align:center;vertical-align:middle;">Poin</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($activities as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->student->name }}</td>
                        <td>{{ $data->student->student_number }}</td>
                        <td>{{ $data->student->program() }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->award->name }}</td>
                        <td>{{ $data->award->point }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center">DATA BELUM ADA</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>

</html>
