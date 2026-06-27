<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota Pembayaran #{{ $booking->id }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ─── Base ─────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            color: #111827;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 32px 16px 80px;
        }

        /* ─── Toolbar (screen only) ─────────────────────────────── */
        .toolbar {
            width: 100%;
            max-width: 680px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 12px;
            flex-wrap: wrap;
        }
        .toolbar a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #6b7280;
            text-decoration: none;
            padding: 8px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            transition: all 0.15s;
        }
        .toolbar a:hover { border-color: #d1fae5; color: #059669; }
        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #059669, #10b981);
            border: none;
            padding: 10px 22px;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(5,150,105,0.35);
            transition: all 0.15s;
        }
        .btn-print:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(5,150,105,0.4); }
        .btn-print svg { width: 16px; height: 16px; }

        /* ─── Receipt Card ───────────────────────────────────────── */
        .receipt {
            width: 100%;
            max-width: 680px;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0,0,0,0.10);
        }

        /* Header strip */
        .receipt-header {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%);
            padding: 32px 36px 28px;
            position: relative;
            overflow: hidden;
        }
        .receipt-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .receipt-header::after {
            content: '';
            position: absolute;
            bottom: -30px; left: 40px;
            width: 100px; height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .receipt-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
        }
        .receipt-brand-icon {
            width: 44px; height: 44px;
            border-radius: 14px;
            background: rgba(255,255,255,0.15);
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(8px);
        }
        .receipt-brand-icon svg { width: 22px; height: 22px; color: #fff; stroke: #fff; }
        .receipt-brand-name { font-size: 1.1rem; font-weight: 800; color: #fff; }
        .receipt-brand-sub { font-size: 0.7rem; font-weight: 600; color: rgba(255,255,255,0.55); letter-spacing: 0.08em; text-transform: uppercase; }

        .receipt-title { font-size: 0.75rem; font-weight: 700; color: rgba(255,255,255,0.55); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 4px; }
        .receipt-id { font-size: 2rem; font-weight: 800; color: #fff; letter-spacing: -0.02em; }
        .receipt-date { font-size: 0.8rem; color: rgba(255,255,255,0.55); margin-top: 4px; }

        /* Status chip */
        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 16px;
        }
        .status-chip .dot { width: 7px; height: 7px; border-radius: 50%; }
        .status-pending  { background: rgba(245,158,11,0.15);  color: #f59e0b; }
        .status-pending .dot  { background: #f59e0b; }
        .status-paid     { background: rgba(59,130,246,0.15);  color: #3b82f6; }
        .status-paid .dot     { background: #3b82f6; }
        .status-completed{ background: rgba(16,185,129,0.15);  color: #10b981; }
        .status-completed .dot{ background: #10b981; }
        .status-cancelled{ background: rgba(107,114,128,0.15); color: #9ca3af; }
        .status-cancelled .dot{ background: #9ca3af; }

        /* Tear line */
        .tear-line {
            display: flex;
            align-items: center;
            padding: 0 28px;
            margin: 0;
        }
        .tear-line-circle {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: #f3f4f6;
            flex-shrink: 0;
        }
        .tear-line-circle.right { margin-left: auto; }
        .tear-line-dashes {
            flex: 1;
            border: none;
            border-top: 2px dashed #e5e7eb;
            margin: 0 6px;
        }

        /* Body */
        .receipt-body { padding: 28px 36px 32px; }

        /* Section label */
        .section-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #9ca3af;
            margin-bottom: 12px;
        }

        /* Court block */
        .court-block {
            background: #f9fafb;
            border: 1px solid #f3f4f6;
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 24px;
        }
        .court-name { font-size: 1rem; font-weight: 800; color: #111827; }
        .court-desc { font-size: 0.78rem; color: #6b7280; margin-top: 3px; }
        .facility-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px; }
        .facility-tag {
            font-size: 0.65rem;
            font-weight: 600;
            background: #dbeafe;
            color: #2563eb;
            padding: 3px 10px;
            border-radius: 999px;
        }

        /* Detail rows */
        .detail-rows { margin-bottom: 20px; }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.85rem;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-row dt { color: #6b7280; font-weight: 500; }
        .detail-row dd { font-weight: 600; color: #374151; text-align: right; }

        /* Total bar */
        .total-bar {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1.5px solid #a7f3d0;
            border-radius: 16px;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .total-label { font-size: 0.78rem; font-weight: 700; color: #065f46; text-transform: uppercase; letter-spacing: 0.08em; }
        .total-amount { font-size: 1.8rem; font-weight: 900; color: #059669; letter-spacing: -0.03em; }

        /* Customer info */
        .customer-block {
            background: #f9fafb;
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 24px;
        }
        .customer-name { font-size: 0.9rem; font-weight: 700; color: #111827; }
        .customer-email { font-size: 0.78rem; color: #6b7280; margin-top: 2px; }

        /* Footer note */
        .receipt-footer {
            border-top: 1px solid #f3f4f6;
            padding-top: 18px;
            text-align: center;
        }
        .receipt-footer p { font-size: 0.75rem; color: #9ca3af; line-height: 1.6; }
        .receipt-footer strong { color: #6b7280; }

        /* Booking code badge */
        .booking-code {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            background: #f3f4f6;
            border-radius: 12px;
            padding: 12px 20px;
            margin-bottom: 18px;
        }
        .booking-code-label { font-size: 0.62rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.1em; }
        .booking-code-value { font-size: 1.1rem; font-weight: 800; color: #111827; font-family: monospace; letter-spacing: 0.12em; }

        /* ─── Print Styles ──────────────────────────────────────── */
        @media print {
            body { background: #fff; padding: 0; }
            .toolbar { display: none !important; }
            .receipt {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }
            .receipt-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .total-bar { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }

        /* ─── Responsive ────────────────────────────────────────── */
        @media (max-width: 600px) {
            .receipt-header { padding: 24px 22px 20px; }
            .receipt-body { padding: 20px 22px 24px; }
            .receipt-id { font-size: 1.5rem; }
            .total-amount { font-size: 1.4rem; }
        }
    </style>
</head>
<body>

    {{-- ── Toolbar (screen only) ── --}}
    <div class="toolbar">
        <a href="{{ route('customer.bookings.show', $booking) }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Detail
        </a>
        <button class="btn-print" onclick="window.print()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak / Simpan PDF
        </button>
    </div>

    {{-- ── Receipt Card ── --}}
    <div class="receipt">

        {{-- Header --}}
        <div class="receipt-header">
            <div class="receipt-brand">
                <div class="receipt-brand-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                    </svg>
                </div>
                <div>
                    <div class="receipt-brand-name">{{ config('app.name') }}</div>
                    <div class="receipt-brand-sub">Nota Pembayaran Resmi</div>
                </div>
            </div>

            <div class="receipt-title">Nomor Booking</div>
            <div class="receipt-id">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
            <div class="receipt-date">Diterbitkan: {{ $booking->created_at->format('d F Y, H:i') }} WIB</div>

            {{-- Status chip --}}
            @php
                $statusMap = [
                    'pending'   => ['label' => 'Menunggu Pembayaran', 'class' => 'status-pending'],
                    'paid'      => ['label' => 'Sudah Dibayar',       'class' => 'status-paid'],
                    'completed' => ['label' => 'Selesai',             'class' => 'status-completed'],
                    'cancelled' => ['label' => 'Dibatalkan',          'class' => 'status-cancelled'],
                ];
                $s = $statusMap[$booking->status] ?? ['label' => $booking->status, 'class' => 'status-pending'];
            @endphp
            <div class="status-chip {{ $s['class'] }}">
                <span class="dot"></span>
                {{ $s['label'] }}
            </div>
        </div>

        {{-- Tear line --}}
        <div class="tear-line">
            <div class="tear-line-circle"></div>
            <hr class="tear-line-dashes">
            <div class="tear-line-circle right"></div>
        </div>

        {{-- Body --}}
        <div class="receipt-body">

            {{-- Customer info --}}
            <p class="section-label">Pemesan</p>
            <div class="customer-block" style="margin-bottom:20px;">
                <div class="customer-name">{{ $booking->user->name }}</div>
                <div class="customer-email">{{ $booking->user->email }}</div>
            </div>

            {{-- Court info --}}
            <p class="section-label">Lapangan</p>
            <div class="court-block">
                <div class="court-name">{{ $booking->court->name }}</div>
                @if($booking->court->description)
                    <div class="court-desc">{{ $booking->court->description }}</div>
                @endif
                @if($booking->court->facilities)
                    <div class="facility-tags">
                        @foreach($booking->court->facilities as $f)
                            <span class="facility-tag">{{ $f }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Booking details --}}
            <p class="section-label">Rincian Sewa</p>
            <dl class="detail-rows">
                <div class="detail-row">
                    <dt>Tanggal Main</dt>
                    <dd>{{ $booking->start_time->translatedFormat('l, d F Y') }}</dd>
                </div>
                <div class="detail-row">
                    <dt>Jam Mulai</dt>
                    <dd>{{ $booking->start_time->format('H:i') }} WIB</dd>
                </div>
                <div class="detail-row">
                    <dt>Jam Selesai</dt>
                    <dd>{{ $booking->end_time->format('H:i') }} WIB</dd>
                </div>
                <div class="detail-row">
                    <dt>Durasi</dt>
                    @php
                        $minutes = abs($booking->start_time->diffInMinutes($booking->end_time));
                        $hours   = floor($minutes / 60);
                        $mins    = $minutes % 60;
                    @endphp
                    <dd>{{ $hours > 0 ? $hours.' jam' : '' }}{{ $mins > 0 ? ' '.$mins.' menit' : '' }}</dd>
                </div>
                <div class="detail-row">
                    <dt>Harga per Jam</dt>
                    <dd>Rp {{ number_format($booking->court->price_per_hour, 0, ',', '.') }}</dd>
                </div>
            </dl>

            {{-- Total --}}
            <div class="total-bar">
                <div>
                    <div class="total-label">Total Pembayaran</div>
                    @if($booking->status === 'paid' || $booking->status === 'completed')
                        <div style="font-size:0.7rem;color:#059669;margin-top:3px;font-weight:600;">✓ Pembayaran telah diterima</div>
                    @elseif($booking->status === 'pending')
                        <div style="font-size:0.7rem;color:#d97706;margin-top:3px;font-weight:600;">⏳ Menunggu pembayaran</div>
                    @else
                        <div style="font-size:0.7rem;color:#9ca3af;margin-top:3px;font-weight:600;">Dibatalkan</div>
                    @endif
                </div>
                <div class="total-amount">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
            </div>

            {{-- Booking code --}}
            <div style="text-align:center;margin-bottom:18px;">
                <div class="booking-code">
                    <span class="booking-code-label">Kode Booking</span>
                    <span class="booking-code-value">BH-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}-{{ strtoupper(substr(md5($booking->id.$booking->created_at), 0, 6)) }}</span>
                </div>
            </div>

            {{-- Footer note --}}
            <div class="receipt-footer">
                <p>
                    Nota ini diterbitkan secara otomatis oleh sistem <strong>{{ config('app.name') }}</strong>.<br>
                    Harap simpan sebagai bukti pembayaran yang sah.<br>
                    Jika ada pertanyaan, hubungi pengelola gedung.
                </p>
            </div>

        </div>
    </div>

</body>
</html>
