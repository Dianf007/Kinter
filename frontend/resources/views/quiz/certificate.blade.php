<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { text-align: center; font-family: sans-serif; }
        .header { margin-top: 50px; }
        .title { font-size: 48px; font-weight: bold; }
        .subtitle { font-size: 24px; margin-top: 20px; }
        .content { margin-top: 50px; font-size: 18px; }
        .footer { margin-top: 80px; font-size: 16px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">SERTIFIKAT</div>
        <div class="subtitle">Telah Menyelesaikan Kuis</div>
    </div>
    <div class="content">
        <p><strong>{{ $quiz->title }}</strong></p>
        <p>Nama: <strong>{{ $userName }}</strong></p>
        <p>Skor: <strong>{{ $attempt->score }}%</strong></p>
        <p>Tanggal: <strong>{{ \Carbon\Carbon::parse($attempt->finished_at)->format('d M Y') }}</strong></p>
    </div>
    <div class="footer">
        <p>Terima kasih telah berpartisipasi.</p>
    </div>
</body>
</html>
