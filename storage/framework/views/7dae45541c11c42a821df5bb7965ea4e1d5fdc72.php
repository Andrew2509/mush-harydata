

<?php $__env->startSection('content'); ?>



<style>
/* ============================================
   DASHBOARD-SPECIFIC STYLES
   ============================================ */

/* --- Welcome Hero --- */
.dash-hero {
    position: relative;
    background: linear-gradient(135deg, rgba(0,255,255,0.08) 0%, rgba(179,0,255,0.06) 50%, rgba(57,255,20,0.04) 100%);
    border: 1px solid rgba(0,255,255,0.12);
    border-radius: 20px;
    padding: 2rem 2.5rem;
    overflow: hidden;
    margin-bottom: 2rem;
}
.dash-hero::before {
    content: '';
    position: absolute;
    top: -60%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(0,255,255,0.12), transparent 70%);
    border-radius: 50%;
    animation: heroFloat 6s ease-in-out infinite;
}
.dash-hero::after {
    content: '';
    position: absolute;
    bottom: -50%;
    left: 5%;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(179,0,255,0.1), transparent 70%);
    border-radius: 50%;
    animation: heroFloat 8s ease-in-out infinite reverse;
}
@keyframes  heroFloat {
    0%, 100% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-15px) scale(1.05); }
}
.dash-hero-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--accent-cyan);
    text-shadow: 0 0 20px rgba(0,255,255,0.3);
    margin: 0 0 8px 0;
    position: relative;
    z-index: 1;
}
.dash-hero-sub {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin: 0;
    position: relative;
    z-index: 1;
    line-height: 1.6;
}
.dash-hero-date {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
    padding: 6px 16px;
    background: rgba(0,255,255,0.08);
    border: 1px solid rgba(0,255,255,0.15);
    border-radius: 20px;
    font-size: 0.78rem;
    color: var(--accent-cyan);
    font-weight: 500;
    position: relative;
    z-index: 1;
}

/* --- Section Headers --- */
.dash-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'Orbitron', sans-serif;
    font-size: 0.8rem;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--text-secondary);
    margin-bottom: 1.2rem;
    margin-top: 0.5rem;
}
.dash-section-title .section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 10px;
    font-size: 0.85rem;
}
.dash-section-title .section-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, var(--border-glass), transparent);
}

/* --- Stat Cards (Refined & Sleek) --- */
.stat-card {
    position: relative;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 18px;
    padding: 1.5rem;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    overflow: hidden;
    backdrop-filter: blur(12px);
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.stat-card:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(0, 255, 255, 0.3);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), 0 0 15px rgba(0, 255, 255, 0.05);
}

.stat-card .stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-secondary);
    transition: all 0.3s ease;
}
.stat-card:hover .stat-icon {
    background: rgba(0, 255, 255, 0.1);
    color: var(--accent-cyan);
    transform: scale(1.05);
}

.stat-label {
    font-family: 'Inter', sans-serif;
    font-size: 0.68rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--text-muted);
    margin-bottom: 8px;
    opacity: 0.8;
}
.stat-value {
    font-family: 'Orbitron', sans-serif;
    font-size: 1.35rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 6px;
    letter-spacing: -0.5px;
}
.stat-card.cyan .stat-value { color: var(--accent-cyan); text-shadow: 0 0 15px rgba(0, 255, 255, 0.2); }

.stat-sub {
    font-size: 0.72rem;
    color: var(--text-muted);
    font-weight: 400;
}
.stat-sub i {
    opacity: 0.6;
    margin-right: 4px;
}

/* Remove old color-specific variants that were too loud */
.stat-card::before { display: none; }

/* --- Card animation on load --- */
.stat-card {
    animation: cardSlideUp 0.5s ease-out both;
}
.stat-card:nth-child(1) { animation-delay: 0.05s; }
.stat-card:nth-child(2) { animation-delay: 0.1s; }
.stat-card:nth-child(3) { animation-delay: 0.15s; }
.stat-card:nth-child(4) { animation-delay: 0.2s; }
@keyframes  cardSlideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

/* --- Chart containers --- */
.chart-card {
    background: var(--bg-card);
    border: 1px solid var(--border-glass);
    border-radius: 16px;
    backdrop-filter: blur(16px);
    overflow: hidden;
    transition: all 0.3s ease;
}
.chart-card:hover {
    border-color: var(--border-glow);
    box-shadow: 0 8px 32px rgba(0,0,0,0.3), 0 0 20px rgba(0,255,255,0.08);
}
.chart-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.2rem 1.5rem;
    border-bottom: 1px solid var(--border-glass);
}
.chart-card-header .chart-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'Orbitron', sans-serif;
    font-size: 0.72rem;
    letter-spacing: 0.8px;
    color: var(--text-secondary);
}
.chart-card-header .chart-title i {
    font-size: 1rem;
    color: var(--accent-cyan);
}
.chart-card-body {
    padding: 1.5rem;
}

/* --- Transactions Table --- */
.tx-table-wrap {
    background: var(--bg-card);
    border: 1px solid var(--border-glass);
    border-radius: 16px;
    backdrop-filter: blur(16px);
    overflow: hidden;
    transition: all 0.3s ease;
}
.tx-table-wrap:hover {
    border-color: var(--border-glow);
}
.tx-table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.2rem 1.5rem;
    border-bottom: 1px solid var(--border-glass);
    flex-wrap: wrap;
    gap: 10px;
}
.tx-table-header .tx-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Orbitron', sans-serif;
    font-size: 0.72rem;
    letter-spacing: 0.8px;
    color: var(--text-secondary);
}
.tx-table {
    width: 100%;
    border-collapse: collapse;
}
.tx-table thead th {
    background: rgba(0,255,255,0.04);
    color: var(--accent-cyan);
    font-family: 'Orbitron', sans-serif;
    font-size: 0.65rem;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    padding: 0.9rem 1.2rem;
    text-align: left;
    border-bottom: 1px solid rgba(0,255,255,0.1);
    white-space: nowrap;
    font-weight: 600;
}
.tx-table tbody td {
    padding: 0.85rem 1.2rem;
    font-size: 0.82rem;
    color: var(--text-primary);
    border-bottom: 1px solid rgba(255,255,255,0.03);
    vertical-align: middle;
}
.tx-table tbody tr {
    transition: all 0.2s ease;
}
.tx-table tbody tr:hover {
    background: rgba(0,255,255,0.03);
}
.tx-table tbody tr:nth-child(even) {
    background: rgba(255,255,255,0.012);
}
.tx-table tbody tr:nth-child(even):hover {
    background: rgba(0,255,255,0.04);
}

/* Status badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.3px;
}
.status-badge.completed {
    background: rgba(57,255,20,0.12);
    color: #39ff14;
    border: 1px solid rgba(57,255,20,0.2);
}
.status-badge.pending {
    background: rgba(238,247,11,0.1);
    color: #eef70b;
    border: 1px solid rgba(238,247,11,0.2);
}
.status-badge.failed {
    background: rgba(255,51,102,0.1);
    color: #ff3366;
    border: 1px solid rgba(255,51,102,0.2);
}
.status-badge .status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}
.status-badge.completed .status-dot { background: #39ff14; box-shadow: 0 0 6px #39ff14; }
.status-badge.pending .status-dot { background: #eef70b; box-shadow: 0 0 6px #eef70b; }
.status-badge.failed .status-dot { background: #ff3366; box-shadow: 0 0 6px #ff3366; }

/* --- Quick Stats Row --- */
.quick-stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-glass);
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}
.quick-stat-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
}
.quick-stat-card:hover {
    transform: translateY(-4px);
    border-color: var(--border-glow);
}
.quick-stat-card .qs-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    font-size: 1.4rem;
    transition: all 0.3s ease;
}
.quick-stat-card:hover .qs-icon {
    transform: scale(1.12) rotate(-8deg);
}
.quick-stat-card .qs-value {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 4px;
}
.quick-stat-card .qs-label {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.65rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--text-muted);
}

/* Color variants for quick stats */
.quick-stat-card.qs-cyan::after { background: linear-gradient(90deg, #00ffff, #0088ff); }
.quick-stat-card.qs-cyan .qs-icon { background: rgba(0,255,255,0.1); color: #00ffff; }
.quick-stat-card.qs-cyan .qs-value { color: #00ffff; text-shadow: 0 0 12px rgba(0,255,255,0.2); }
.quick-stat-card.qs-cyan:hover { box-shadow: 0 8px 30px rgba(0,255,255,0.12); }

.quick-stat-card.qs-purple::after { background: linear-gradient(90deg, #b300ff, #ff00e5); }
.quick-stat-card.qs-purple .qs-icon { background: rgba(179,0,255,0.1); color: #b300ff; }
.quick-stat-card.qs-purple .qs-value { color: #b300ff; text-shadow: 0 0 12px rgba(179,0,255,0.2); }
.quick-stat-card.qs-purple:hover { box-shadow: 0 8px 30px rgba(179,0,255,0.12); }

.quick-stat-card.qs-green::after { background: linear-gradient(90deg, #39ff14, #00ff88); }
.quick-stat-card.qs-green .qs-icon { background: rgba(57,255,20,0.1); color: #39ff14; }
.quick-stat-card.qs-green .qs-value { color: #39ff14; text-shadow: 0 0 12px rgba(57,255,20,0.2); }
.quick-stat-card.qs-green:hover { box-shadow: 0 8px 30px rgba(57,255,20,0.12); }

/* --- Popular Games --- */
.game-card {
    background: var(--bg-card);
    border: 1px solid var(--border-glass);
    border-radius: 14px;
    padding: 1.2rem;
    text-align: center;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}
.game-card:hover {
    transform: translateY(-6px) scale(1.03);
    border-color: rgba(0,255,255,0.3);
    box-shadow: 0 12px 36px rgba(0,0,0,0.3), 0 0 20px rgba(0,255,255,0.1);
}
.game-card .game-icon {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    margin: 0 auto 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    background: linear-gradient(135deg, rgba(0,255,255,0.08), rgba(179,0,255,0.06));
    border: 1px solid rgba(0,255,255,0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}
.game-card .game-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 14px;
}
.game-card:hover .game-icon {
    box-shadow: 0 0 20px rgba(0,255,255,0.15);
    border-color: rgba(0,255,255,0.3);
}
.game-card .game-name {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.game-card .game-topups {
    font-size: 0.7rem;
    color: var(--accent-cyan);
    font-weight: 500;
}

/* --- Responsive grid --- */
.dash-grid-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.2rem;
}
.dash-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.2rem;
}
.dash-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.2rem;
}
.dash-grid-5 {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
}

@media (max-width: 1200px) {
    .dash-grid-4 { grid-template-columns: repeat(2, 1fr); }
    .dash-grid-3 { grid-template-columns: repeat(2, 1fr); }
    .dash-grid-5 { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 768px) {
    .dash-grid-4 { grid-template-columns: 1fr; }
    .dash-grid-3 { grid-template-columns: 1fr; }
    .dash-grid-2 { grid-template-columns: 1fr; }
    .dash-grid-5 { grid-template-columns: repeat(2, 1fr); }
    .dash-hero { padding: 1.5rem; }
    .dash-hero-title { font-size: 1.1rem; }
    .tx-table-wrap { overflow-x: auto; }
}

/* --- Pulse animation for live indicator --- */
@keyframes  livePulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.5); }
}
.live-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #39ff14;
    box-shadow: 0 0 8px #39ff14;
    animation: livePulse 2s ease-in-out infinite;
    margin-right: 6px;
}

/* --- Print button --- */
.btn-print {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 16px;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 500;
    background: rgba(0,255,255,0.08);
    border: 1px solid rgba(0,255,255,0.15);
    color: var(--accent-cyan);
    cursor: pointer;
    transition: all 0.3s ease;
}
.btn-print:hover {
    background: rgba(0,255,255,0.15);
    box-shadow: 0 0 12px rgba(0,255,255,0.15);
}

/* --- Light Theme Overrides for Dashboard --- */
[data-theme="light"] .dash-hero {
    background: linear-gradient(135deg, rgba(0,119,204,0.06) 0%, rgba(200,0,161,0.04) 50%, rgba(0,153,92,0.03) 100%);
    border-color: rgba(0,119,204,0.12);
}
[data-theme="light"] .dash-hero-title {
    color: #0077cc;
    text-shadow: none;
}
[data-theme="light"] .dash-hero-date {
    background: rgba(0,119,204,0.06);
    border-color: rgba(0,119,204,0.12);
    color: #0077cc;
}
[data-theme="light"] .stat-card,
[data-theme="light"] .chart-card,
[data-theme="light"] .tx-table-wrap,
[data-theme="light"] .quick-stat-card,
[data-theme="light"] .game-card {
    background: var(--bg-card);
    border-color: var(--border-glass);
}
[data-theme="light"] .stat-card.cyan .stat-value { color: #0077cc; }
[data-theme="light"] .stat-card.green .stat-value { color: #00995c; }
[data-theme="light"] .stat-card.yellow .stat-value { color: #c5a600; }
[data-theme="light"] .stat-card.red .stat-value { color: #dd1144; }
[data-theme="light"] .stat-card.purple .stat-value { color: #c800a1; }
[data-theme="light"] .quick-stat-card.qs-cyan .qs-value { color: #0077cc; text-shadow: none; }
[data-theme="light"] .quick-stat-card.qs-purple .qs-value { color: #c800a1; text-shadow: none; }
[data-theme="light"] .quick-stat-card.qs-green .qs-value { color: #00995c; text-shadow: none; }
[data-theme="light"] .status-badge.completed { background: rgba(0,153,92,0.1); color: #00995c; border-color: rgba(0,153,92,0.2); }
[data-theme="light"] .status-badge.pending { background: rgba(197,166,0,0.1); color: #c5a600; border-color: rgba(197,166,0,0.2); }
[data-theme="light"] .status-badge.failed { background: rgba(221,17,68,0.08); color: #dd1144; border-color: rgba(221,17,68,0.15); }
[data-theme="light"] .tx-table thead th { background: rgba(0,119,204,0.04); color: #0077cc; border-bottom-color: rgba(0,119,204,0.1); }
[data-theme="light"] .tx-table tbody tr:hover { background: rgba(0,119,204,0.03); }
</style>

<div class="container-xxl flex-grow-1 container-p-y">

    
    <div class="dash-hero">
        <div class="dash-hero-title">
            <i class="fas fa-gamepad" style="margin-right:8px;"></i> ADMIN DASHBOARD
        </div>
        <p class="dash-hero-sub">
            Selamat datang kembali, Admin! Berikut ringkasan performa toko topup game Anda hari ini.
        </p>
        <div class="dash-hero-date">
            <i class="fas fa-calendar-alt"></i>
            <span id="dashDate"></span>
            <span style="margin-left:8px;">
                <span class="live-dot"></span>
                <span style="font-size:0.7rem;">LIVE</span>
            </span>
        </div>
    </div>

    
    <div class="dash-section-title">
        <div class="section-icon" style="background:rgba(0,255,255,0.08); color:#00ffff;">
            <i class="fas fa-chart-line"></i>
        </div>
        <span>Transaksi Hari Ini</span>
        <div class="section-line"></div>
    </div>

    <div class="dash-grid-4 mb-4">
        
        <div class="stat-card cyan">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <div class="stat-label">Total Transaksi</div>
                    <div class="stat-value">Rp <?php echo e(number_format($total_pembelian, 0, '.', '.')); ?></div>
                    <div class="stat-sub"><i class="fas fa-shopping-cart"></i> <?php echo e($banyak_pembelian); ?> Pesanan Hari Ini</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>

        
        <div class="stat-card green">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <div class="stat-label">Transaksi Berhasil</div>
                    <div class="stat-value">Rp <?php echo e(number_format($total_pembelian_success, 0, '.', '.')); ?></div>
                    <div class="stat-sub" style="color:#39ff14;"><i class="fas fa-check-circle"></i> <?php echo e($banyak_pembelian_success); ?> Sukses</div>
                </div>
                <div class="stat-icon" style="color:#39ff14; background:rgba(57,255,20,0.05);">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        </div>

        
        <div class="stat-card">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <div class="stat-label">Transaksi Pending</div>
                    <div class="stat-value">Rp <?php echo e(number_format($total_pembelian_pending, 0, '.', '.')); ?></div>
                    <div class="stat-sub"><i class="fas fa-clock"></i> <?php echo e($banyak_pembelian_pending); ?> Menunggu</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>

        
        <div class="stat-card red">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <div class="stat-label">Batal / Gagal</div>
                    <div class="stat-value">Rp <?php echo e(number_format($total_pembelian_batal, 0, '.', '.')); ?></div>
                    <div class="stat-sub" style="color:#ff3366;"><i class="fas fa-times-circle"></i> <?php echo e($banyak_pembelian_batal); ?> Gagal</div>
                </div>
                <div class="stat-icon" style="color:#ff3366; background:rgba(255,51,102,0.05);">
                    <i class="fas fa-ban"></i>
                </div>
            </div>
        </div>
    </div>

    
    <div class="dash-section-title">
        <div class="section-icon" style="background:rgba(255,255,255,0.05); color:var(--text-secondary);">
            <i class="fas fa-database"></i>
        </div>
        <span>Laporan Keseluruhan</span>
        <div class="section-line"></div>
    </div>

    <div class="dash-grid-4 mb-2">
        <div class="stat-card cyan">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <div class="stat-label">Total Seluruh Transaksi</div>
                    <div class="stat-value" style="font-size:1.2rem;">Rp <?php echo e(number_format($total_keseluruhan_pembelian, 0, '.', '.')); ?></div>
                    <div class="stat-sub"><?php echo e($banyak_keseluruhan_pembelian); ?>x pemesanan</div>
                </div>
                <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
            </div>
        </div>
        <div class="stat-card">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <div class="stat-label">Seluruh Berhasil</div>
                    <div class="stat-value" style="font-size:1.2rem;">Rp <?php echo e(number_format($total_keseluruhan_pembelian_berhasil, 0, '.', '.')); ?></div>
                    <div class="stat-sub"><?php echo e($banyak_keseluruhan_pembelian_berhasil); ?>x pemesanan</div>
                </div>
                <div class="stat-icon"><i class="fas fa-check-double"></i></div>
            </div>
        </div>
        <div class="stat-card">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <div class="stat-label">Seluruh Pending</div>
                    <div class="stat-value" style="font-size:1.2rem;">Rp <?php echo e(number_format($total_keseluruhan_pembelian_pending, 0, '.', '.')); ?></div>
                    <div class="stat-sub"><?php echo e($banyak_keseluruhan_pembelian_pending); ?>x pemesanan</div>
                </div>
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
        <div class="stat-card">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <div class="stat-label">Seluruh Batal</div>
                    <div class="stat-value" style="font-size:1.2rem;">Rp <?php echo e(number_format($total_keseluruhan_pembelian_batal, 0, '.', '.')); ?></div>
                    <div class="stat-sub"><?php echo e($banyak_keseluruhan_pembelian_batal); ?>x pemesanan</div>
                </div>
                <div class="stat-icon"><i class="fas fa-ban"></i></div>
            </div>
        </div>
    </div>

    
    <div style="margin-bottom:2rem;">
        <div class="stat-card cyan" style="max-width:400px; border-color: rgba(0,255,255,0.2) !important; background: rgba(0,255,255,0.03) !important;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <div class="stat-label">💰 Keuntungan Bersih</div>
                    <div class="stat-value" style="font-size:1.5rem;">Rp <?php echo e(number_format($keuntungan_bersih, 0, '.', '.')); ?></div>
                    <div class="stat-sub">
                        <i class="fas fa-trending-up"></i> Estimasi Keuntungan Tahun Ini
                    </div>
                </div>
                <div class="stat-icon" style="color:var(--accent-cyan); background:rgba(0,255,255,0.1);"><i class="fas fa-crown"></i></div>
            </div>
        </div>
    </div>

    
    <div class="dash-section-title">
        <div class="section-icon" style="background:rgba(57,255,20,0.08); color:#39ff14;">
            <i class="fas fa-bolt"></i>
        </div>
        <span>Statistik Cepat</span>
        <div class="section-line"></div>
    </div>

    <div class="dash-grid-3 mb-4">
        <div class="quick-stat-card">
            <div class="qs-icon" style="background:rgba(0,255,255,0.05); color:var(--accent-cyan);"><i class="fas fa-users"></i></div>
            <div class="qs-value"><?php echo e($total_users); ?></div>
            <div class="qs-label">Total Pengguna</div>
        </div>
        <div class="quick-stat-card">
            <div class="qs-icon" style="background:rgba(255,255,255,0.05); color:var(--text-secondary);"><i class="fas fa-briefcase"></i></div>
            <div class="qs-value"><?php echo e($total_services); ?></div>
            <div class="qs-label">Total Layanan</div>
        </div>
        <div class="quick-stat-card">
            <div class="qs-icon" style="background:rgba(0,255,255,0.05); color:var(--accent-cyan);"><i class="fas fa-gamepad"></i></div>
            <div class="qs-value"><?php echo e($total_games); ?></div>
            <div class="qs-label">Total Kategori Game</div>
        </div>
    </div>

    
    <div class="dash-section-title">
        <div class="section-icon" style="background:rgba(0,255,136,0.08); color:#00ff88;">
            <i class="fas fa-chart-area"></i>
        </div>
        <span>Grafik & Analitik</span>
        <div class="section-line"></div>
    </div>

    
    <div class="chart-card mb-4">
        <div class="chart-card-header">
            <div class="chart-title">
                <i class="fas fa-chart-bar"></i>
                <span>GRAFIK PESANAN 7 HARI TERAKHIR</span>
            </div>
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
        </div>
        <div class="chart-card-body">
            <div id="order-chart" style="min-height:280px;"></div>
        </div>
    </div>

    
    <div class="dash-grid-2 mb-4">
        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-title">
                    <i class="fas fa-user-plus" style="color:#00ffff;"></i>
                    <span>GRAFIK PENGGUNA</span>
                </div>
            </div>
            <div class="chart-card-body">
                <div id="user-chart" style="min-height:250px;"></div>
            </div>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-title">
                    <i class="fas fa-eye" style="color:#ff3366;"></i>
                    <span>GRAFIK PENGUNJUNG</span>
                </div>
            </div>
            <div class="chart-card-body">
                <div id="visitor-chart" style="min-height:250px;"></div>
            </div>
        </div>
    </div>

    
    <div class="dash-section-title">
        <div class="section-icon" style="background:rgba(255,136,0,0.08); color:#ff8800;">
            <i class="fas fa-exchange-alt"></i>
        </div>
        <span>Transaksi Terbaru</span>
        <div class="section-line"></div>
    </div>

    <div class="tx-table-wrap mb-4">
        <div class="tx-table-header">
            <div class="tx-title">
                <i class="fas fa-receipt" style="color:var(--accent-cyan);"></i>
                <span>RIWAYAT PESANAN TERAKHIR</span>
            </div>
            <a href="<?php echo e(route('pesanan')); ?>" class="btn-print">
                <i class="fas fa-external-link-alt"></i> Lihat Semua
            </a>
        </div>
        <div style="overflow-x:auto;">
            <table class="tx-table" id="recentTxTable">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Layanan</th>
                        <th>Tujuan</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody id="recentTxBody">
                    
                    <?php if(isset($recent_transactions) && count($recent_transactions) > 0): ?>
                        <?php $__currentLoopData = $recent_transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="font-family:'Orbitron',sans-serif; font-size:0.72rem; color:var(--accent-cyan);"><?php echo e($tx->order_id ?? $tx->id); ?></td>
                            <td>
                                <div style="font-weight:600;"><?php echo e($tx->nickname ?? 'Player'); ?></div>
                                <div style="font-size:0.65rem; color:var(--text-muted);"><?php echo e($tx->user_id); ?><?php echo e($tx->zone ? ' ('.$tx->zone.')' : ''); ?></div>
                            </td>
                            <td><?php echo e($tx->layanan ?? '-'); ?></td>
                            <td style="font-size:0.78rem; color:var(--text-secondary);"><?php echo e($tx->nickname ?? '-'); ?></td>
                            <td style="font-weight:600;">Rp <?php echo e(number_format($tx->harga ?? 0, 0, '.', '.')); ?></td>
                            <td>
                                <?php
                                    $statusClass = 'pending';
                                    $statusLabel = $tx->status ?? 'Pending';
                                    if(in_array(strtolower($statusLabel), ['success', 'berhasil', 'completed', 'sukses'])) $statusClass = 'completed';
                                    elseif(in_array(strtolower($statusLabel), ['failed', 'gagal', 'batal', 'canceled', 'error'])) $statusClass = 'failed';
                                ?>
                                <span class="status-badge <?php echo e($statusClass); ?>">
                                    <span class="status-dot"></span>
                                    <?php echo e(ucfirst($statusLabel)); ?>

                                </span>
                            </td>
                            <td style="font-size:0.78rem; color:var(--text-muted);"><?php echo e($tx->created_at ? \Carbon\Carbon::parse($tx->created_at)->format('d M Y H:i') : '-'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        
                        <tr>
                            <td style="font-family:'Orbitron',sans-serif; font-size:0.72rem; color:var(--accent-cyan);">INV-20260301001</td>
                            <td>player123</td>
                            <td>86 Diamonds</td>
                            <td style="font-size:0.78rem; color:var(--text-secondary);">123456789</td>
                            <td style="font-weight:600;">Rp 22.000</td>
                            <td><span class="status-badge completed"><span class="status-dot"></span> Completed</span></td>
                            <td style="font-size:0.78rem; color:var(--text-muted);">01 Mar 2026 10:32</td>
                        </tr>
                        <tr>
                            <td style="font-family:'Orbitron',sans-serif; font-size:0.72rem; color:var(--accent-cyan);">INV-20260301002</td>
                            <td>gamer_pro</td>
                            <td>Weekly Pass</td>
                            <td style="font-size:0.78rem; color:var(--text-secondary);">987654321</td>
                            <td style="font-weight:600;">Rp 28.000</td>
                            <td><span class="status-badge pending"><span class="status-dot"></span> Pending</span></td>
                            <td style="font-size:0.78rem; color:var(--text-muted);">01 Mar 2026 11:15</td>
                        </tr>
                        <tr>
                            <td style="font-family:'Orbitron',sans-serif; font-size:0.72rem; color:var(--accent-cyan);">INV-20260301003</td>
                            <td>noob_slayer</td>
                            <td>100 UC</td>
                            <td style="font-size:0.78rem; color:var(--text-secondary);">112233445</td>
                            <td style="font-weight:600;">Rp 16.500</td>
                            <td><span class="status-badge failed"><span class="status-dot"></span> Failed</span></td>
                            <td style="font-size:0.78rem; color:var(--text-muted);">01 Mar 2026 12:08</td>
                        </tr>
                        <tr>
                            <td style="font-family:'Orbitron',sans-serif; font-size:0.72rem; color:var(--accent-cyan);">INV-20260228004</td>
                            <td>rafa_ff</td>
                            <td>100 Diamonds</td>
                            <td style="font-size:0.78rem; color:var(--text-secondary);">556677889</td>
                            <td style="font-weight:600;">Rp 15.000</td>
                            <td><span class="status-badge completed"><span class="status-dot"></span> Completed</span></td>
                            <td style="font-size:0.78rem; color:var(--text-muted);">28 Feb 2026 22:47</td>
                        </tr>
                        <tr>
                            <td style="font-family:'Orbitron',sans-serif; font-size:0.72rem; color:var(--accent-cyan);">INV-20260228005</td>
                            <td>legend_ml</td>
                            <td>Starlight Pass</td>
                            <td style="font-size:0.78rem; color:var(--text-secondary);">334455667</td>
                            <td style="font-weight:600;">Rp 150.000</td>
                            <td><span class="status-badge completed"><span class="status-dot"></span> Completed</span></td>
                            <td style="font-size:0.78rem; color:var(--text-muted);">28 Feb 2026 21:30</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="dash-section-title">
        <div class="section-icon" style="background:rgba(0,255,255,0.08); color:#00ffff;">
            <i class="fas fa-fire"></i>
        </div>
        <span>Game Populer</span>
        <div class="section-line"></div>
    </div>

    <div class="dash-grid-5 mb-4">
        <?php if(isset($popular_games) && count($popular_games) > 0): ?>
            <?php $__currentLoopData = $popular_games; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="game-card">
                <div class="game-icon">
                    <?php if(isset($game->thumbnail) && $game->thumbnail): ?>
                        <img src="<?php echo e($game->thumbnail); ?>" alt="<?php echo e($game->nama); ?>">
                    <?php else: ?>
                        <i class="fas fa-gamepad"></i>
                    <?php endif; ?>
                </div>
                <div class="game-name"><?php echo e($game->nama ?? 'Game'); ?></div>
                <div class="game-topups"><?php echo e(number_format($game->total_topups ?? 0, 0, '.', '.')); ?> topups</div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>feather.replace();</script>


<script>
(function() {
    var now = new Date();
    var days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    var months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    var el = document.getElementById('dashDate');
    if (el) {
        el.textContent = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
    }
})();
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script>
$(function () {
    var grafikData = <?php echo $grafik_chart; ?>;
    var visitorData = <?php echo $visitor_chart; ?>;

    // --- Grafik pengguna ---
    new Morris.Area({
        element: 'user-chart',
        data: grafikData,
        xkey: 'tanggal',
        ykeys: ['pengguna'],
        labels: ['Pengguna Terdaftar'],
        lineColors: ['#00f0ff'],
        pointSize: 5,
        lineWidth: 2.5,
        fillOpacity: 0.12,
        behaveLikeLine: true,
        smooth: true,
        gridTextSize: 11,
        gridTextColor: '#5a6380',
        gridLineColor: 'rgba(0,240,255,0.06)',
        hideHover: 'auto',
        resize: true,
        parseTime: false,
        hoverCallback: function (index, options, content, row) {
            var date = new Date(row.tanggal);
            var month = date.toLocaleString('id-ID', { month: 'long' });
            return '<div class="morris-hover-row-label">' + month + ' ' + date.getDate() + '</div>' +
                '<div class="morris-hover-point">Pengguna: ' + row.pengguna + '</div>';
        }
    });

    // --- Grafik pengunjung ---
    new Morris.Area({
        element: 'visitor-chart',
        data: visitorData,
        xkey: 'tanggal',
        ykeys: ['pengunjung'],
        labels: ['Pengunjung'],
        lineColors: ['#ff3366'],
        pointSize: 5,
        lineWidth: 2.5,
        fillOpacity: 0.12,
        behaveLikeLine: true,
        smooth: true,
        gridTextSize: 11,
        gridTextColor: '#5a6380',
        gridLineColor: 'rgba(255,51,102,0.06)',
        hideHover: 'auto',
        resize: true,
        parseTime: false,
        hoverCallback: function (index, options, content, row) {
            var date = new Date(row.tanggal);
            var month = date.toLocaleString('id-ID', { month: 'long' });
            return '<div class="morris-hover-row-label">' + month + ' ' + date.getDate() + '</div>' +
                '<div class="morris-hover-point">Pengunjung: ' + row.pengunjung + '</div>';
        }
    });

    // --- Grafik pesanan ---
    var orderData = <?php echo $morris_data; ?>;
    new Morris.Area({
        element: 'order-chart',
        data: orderData,
        xkey: 'y',
        ykeys: ['a'],
        labels: ['Pesanan'],
        lineColors: ['#39ff14'],
        pointSize: 5,
        lineWidth: 2.5,
        fillOpacity: 0.12,
        behaveLikeLine: true,
        smooth: true,
        gridTextSize: 11,
        gridTextColor: '#5a6380',
        gridLineColor: 'rgba(57,255,20,0.06)',
        hideHover: 'auto',
        resize: true,
        parseTime: false,
        hoverCallback: function (index, options, content, row) {
            return '<div class="morris-hover-row-label">' + row.y + '</div>' +
                   '<div class="morris-hover-point">Pesanan: ' + row.a + '</div>';
        }
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\muslihinnnn (1)\harydata\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>