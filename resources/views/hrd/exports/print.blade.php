<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }} — Raja Academy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 24px;
            background: #ffffff;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
            color: #1e3a8a;
        }
        .header p {
            font-size: 12px;
            color: #64748b;
            margin: 4px 0 0;
        }
        .btn-print {
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #f1f5f9;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            color: #334155;
        }
        tr:nth-child(even) td {
            background: #f8fafc;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: flex; align-items: center; gap: 16px;">
            <img src="{{ asset('images/Racademy.png') }}" alt="Racademy" style="height: 38px; width: auto; object-fit: contain;">
            <div>
                <h1>{{ $title }}</h1>
                <p>PT Roda Jaya Sakti (Raja Academy) &bull; {{ $subtitle }} &bull; Total Data: <strong>{{ count($rows) }} Baris</strong></p>
            </div>
        </div>
        <div class="no-print">
            <button type="button" class="btn-print" onclick="window.print()">Cetak / Simpan sebagai PDF</button>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                @foreach($headers as $h)
                    <th>{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}" style="text-align: center; padding: 24px; color: #64748b;">
                        Tidak ada data yang ditemukan untuk filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
