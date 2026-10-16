<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat — {{ $certification->nama_sertifikasi }} ({{ $certification->employee?->nama }})</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 32px;
            color: #0f172a;
        }
        .cert-container {
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border: 8px double #1e40af;
            border-radius: 16px;
            padding: 48px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 6px 16px;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        h1 {
            font-size: 28px;
            margin: 16px 0 6px;
            color: #1e3a8a;
        }
        .subtitle {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 28px;
        }
        .recipient {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin: 8px 0;
        }
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            text-align: left;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-top: 28px;
            font-size: 13px;
        }
        .meta-item strong {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 2px;
        }
        .actions {
            margin-top: 24px;
        }
        .btn-print {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .actions { display: none; }
            .cert-container { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="cert-container">
        <span class="badge">{{ $certification->jenis_sertifikasi ?? 'Sertifikasi Resmi' }}</span>
        <h1>{{ $certification->nama_sertifikasi }}</h1>
        <p class="subtitle">Nomor Registrasi Sertifikat: <strong>{{ $certification->nomor_sertifikat ?? '-' }}</strong></p>

        <p style="font-size: 13px; color: #475569;">Diberikan kepada:</p>
        <div class="recipient">{{ $certification->employee?->nama ?? '-' }}</div>
        <p style="font-size: 13px; color: #475569; margin-top: 4px;">
            NIK: <strong>{{ $certification->employee?->nik ?? '-' }}</strong> &bull;
            Departemen: <strong>{{ $certification->employee?->department?->nama_departemen ?? '-' }}</strong> &bull;
            Jabatan: <strong>{{ $certification->employee?->jabatan ?? $certification->employee?->level_jabatan ?? '-' }}</strong>
        </p>

        <div class="meta-grid">
            <div class="meta-item">
                <strong>Tanggal Terbit</strong>
                <span>{{ $certification->tanggal_terbit ? \Carbon\Carbon::parse($certification->tanggal_terbit)->format('d M Y') : '-' }}</span>
            </div>
            <div class="meta-item">
                <strong>Masa Berlaku</strong>
                <span>{{ $certification->is_permanent ? 'Permanen' : ($certification->masa_berlaku ?? '-') }}</span>
            </div>
            <div class="meta-item">
                <strong>Tanggal Kedaluwarsa (Expired)</strong>
                <span>{{ $certification->is_permanent ? 'Seumur Hidup (Permanen)' : ($certification->tanggal_expired ? \Carbon\Carbon::parse($certification->tanggal_expired)->format('d M Y') : '-') }}</span>
            </div>
            <div class="meta-item">
                <strong>Program Pelatihan Terkait</strong>
                <span>{{ $certification->training?->nama_pelatihan ?? 'Sertifikasi Kompetensi' }}</span>
            </div>
        </div>

        <div class="actions">
            <button type="button" class="btn-print" onclick="window.print()">Cetak / Simpan PDF</button>
        </div>
    </div>
</body>
</html>
