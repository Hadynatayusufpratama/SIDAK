<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Konservasi SIDAK BKSDA</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #15803d;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #15803d;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0 0 0;
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #cbd5e1;
        }
        th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
            padding: 8px 5px;
            font-size: 10px;
        }
        td {
            padding: 6px 5px;
            font-size: 10px;
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Sistem Informasi Data Konservasi (SIDAK)</h2>
        <p>Balai Konservasi Sumber Daya Alam (BKSDA) - Rekapitulasi Data Terintegrasi</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Bidang Utama</th>
                <th width="25%">Sub-Bidang / Kategori</th>
                <th width="12%">Bulan / Tahun</th>
                <th width="10%">Jumlah</th>
                <th width="28%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($datas as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->subBidang->bidang->nama_bidang ?? '-' }}</td>
                    <td>{{ $item->subBidang->nama_sub_bidang ?? '-' }}</td>
                    <td class="text-center">
                        @php
                            $months = [
                                '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April',
                                '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus',
                                '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember',
                                '1'=>'Januari', '2'=>'Februari', '3'=>'Maret', '4'=>'April',
                                '5'=>'Mei', '6'=>'Juni', '7'=>'Juli', '8'=>'Agustus',
                                '9'=>'September'
                            ];
                            $namaBulan = $months[$item->bulan] ?? $item->bulan;
                        @endphp
                        {{ $namaBulan }} {{ $item->tahun }}
                    </td>
                    <td class="text-center">{{ number_format($item->jumlah ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data konservasi yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis pada: {{ date('d-m-Y H:i') }} WIB
    </div>

</body>
</html>