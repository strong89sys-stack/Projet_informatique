<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports — Système de Gestion de Péage</title>
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        /* TABS CONTENT */
        .tab-content { display: none; }
        .tab-content.active { display: flex; flex-direction: column; gap: 20px; }

        /* PERIOD SELECTOR */
        .period-sel {
            display: flex; align-items: center; gap: 8px;
            background: var(--grey-100); border: 1px solid var(--grey-200);
            border-radius: 8px; padding: 7px 12px;
            font-size: 13px; cursor: pointer;
        }
        .period-sel svg { width: 13px; height: 13px; stroke: var(--grey-400); fill: none; stroke-width: 2; }

        /* CHART CARD */
        .chart-card {
            background: var(--white);
            border: 1px solid var(--grey-200);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow);
        }
        .chart-card-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px; }
        .chart-legend { display: flex; gap: 16px; }
        .legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--grey-500); }
        .legend-dot { width: 10px; height: 10px; border-radius: 50%; }

        /* SVG CHART */
        .chart-svg-wrap { width: 100%; overflow: hidden; }
        .chart-svg { width: 100%; height: 200px; }
        .chart-x-labels { display: flex; justify-content: space-between; padding: 8px 4px 0; }
        .chart-x-labels span { font-size: 11px; color: var(--grey-400); }

        /* SIDE STAT */
        .charts-row { display: grid; grid-template-columns: 1fr 280px; gap: 16px; }
        .stat-side {
            background: var(--navy);
            border-radius: var(--radius-lg);
            padding: 24px;
            color: var(--white);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .stat-side-label { font-size: 10px; font-weight: 700; color: rgba(255,255,255,.45); text-transform: uppercase; letter-spacing: .08em; }
        .stat-side-value { font-size: 32px; font-weight: 700; line-height: 1.1; }
        .stat-side-sub { font-size: 12px; color: rgba(255,255,255,.5); }
        .stat-side-trend { font-size: 12px; color: var(--green); font-weight: 600; }
        .stat-divider { border: none; border-top: 1px solid rgba(255,255,255,.1); margin: 4px 0; }
        .stat-update { font-size: 11px; color: rgba(255,255,255,.35); display: flex; align-items: center; gap: 5px; }
        .stat-update svg { width: 11px; height: 11px; stroke: currentColor; fill: none; stroke-width: 2; }

        /* GARES TABLE */
        .search-gare {
            display: flex; align-items: center; gap: 8px;
            background: var(--grey-100); border: 1px solid var(--grey-200);
            border-radius: 8px; padding: 7px 12px; width: 220px;
        }
        .search-gare svg { width: 14px; height: 14px; stroke: var(--grey-400); fill: none; stroke-width: 2; flex-shrink: 0; }
        .search-gare input { border: none; background: transparent; font-family: var(--font); font-size: 13px; color: var(--text-main); outline: none; width: 100%; }
        .occ-bar { display: flex; align-items: center; gap: 8px; }
        .occ-track { flex: 1; height: 6px; background: var(--grey-100); border-radius: 3px; overflow: hidden; max-width: 100px; }
        .occ-fill { height: 100%; border-radius: 3px; }
        .occ-fill.high  { background: var(--green); }
        .occ-fill.mid   { background: var(--accent); }
        .occ-fill.low   { background: var(--orange); }
        .occ-pct { font-size: 12px; font-weight: 600; color: var(--text-muted); min-width: 32px; }

        /* BOTTOM ROW */
        .bottom-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* DONUT */
        .donut-wrap { display: flex; align-items: center; gap: 24px; }
        .donut-svg { width: 130px; height: 130px; flex-shrink: 0; }
        .donut-legend { display: flex; flex-direction: column; gap: 10px; }
        .donut-item { display: flex; align-items: center; gap: 8px; font-size: 12.5px; }
        .donut-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .donut-label { color: var(--text-muted); }
        .donut-pct { font-weight: 700; color: var(--navy); margin-left: auto; min-width: 32px; text-align: right; }
        .donut-center { position: relative; }
        .donut-label-center {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
        .donut-label-center .pct { font-size: 22px; font-weight: 700; color: var(--navy); display: block; }
        .donut-label-center .lbl { font-size: 10px; color: var(--grey-400); }

        /* AI CARD */
        .ai-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            border-radius: var(--radius-lg);
            padding: 24px;
            color: var(--white);
            display: flex; flex-direction: column; gap: 16px;
        }
        .ai-title { font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
        .ai-sub { font-size: 12px; color: rgba(255,255,255,.6); margin-top: -10px; }
        .ai-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,.12);
            border-radius: 10px; padding: 12px 14px;
            font-size: 12.5px;
        }
        .ai-badge strong { font-size: 15px; }
        .ai-badge-label { font-size: 10px; color: rgba(255,255,255,.5); margin-top: 2px; }
        .ai-badges { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .ai-rec {
            background: rgba(255,255,255,.08);
            border-radius: 8px; padding: 10px 12px;
            font-size: 12px; color: rgba(255,255,255,.8);
            display: flex; align-items: flex-start; gap: 8px;
        }
        .ai-rec svg { width: 14px; height: 14px; stroke: var(--orange); fill: none; stroke-width: 2; flex-shrink: 0; margin-top: 1px; }
        .btn-appliquer {
            background: var(--white); color: var(--navy);
            border: none; border-radius: 7px;
            padding: 6px 14px; font-family: var(--font);
            font-size: 12px; font-weight: 700; cursor: pointer;
            transition: all var(--transition); white-space: nowrap;
            flex-shrink: 0;
        }
        .btn-appliquer:hover { background: var(--grey-100); }
        .ai-rec-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex: 1; }
    </style>
</head>
<body>
<div class="layout">

    <aside class="sidebar">
        <div class="sidebar-brand">
            <span class="brand-title">Administration</span>
            <span class="brand-sub">Infrastructure Routière</span>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Tableau de bord
            </a>
            <a href="gest-agents.php" class="nav-item">
                <svg viewBox="0 0 24 24"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
                Agents
            </a>
            <a href="gares.php" class="nav-item">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Gares de péage
            </a>
            <a href="paiements.php" class="nav-item">
                <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                Paiements
            </a>
            <a href="interventions.php" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                Interventions
            </a>
            <a href="rapports.php" class="nav-item active">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>
                Rapports
            </a>
            <a href="parametres.php" class="nav-item">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                Paramètres
            </a>
        </nav>
        <button class="btn-nouvelle">
            <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvelle Intervention
        </button>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <span class="topbar-title">Système de Gestion de Péage</span>
            <div class="topbar-right">
                <button class="icon-btn">
                    <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </button>
                <button class="icon-btn">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </button>
                <button class="icon-btn">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                </button>
                <div>
                    <div style="font-size:10px;color:var(--grey-400);text-transform:uppercase;letter-spacing:.06em;text-align:right">Admin</div>
                    <div style="font-size:13px;font-weight:700;color:var(--navy)">Jean-Luc Dupont</div>
                </div>
                <div class="admin-avatar-sm">JL</div>
            </div>
        </header>

        <main class="content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Rapports &amp; Analyses</h1>
                    <p class="page-sub">Analyse approfondie des performances de l'infrastructure.</p>
                </div>
                <div class="page-header-actions">
                    <button class="btn btn-secondary">
                        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Exporter PDF
                    </button>
                    <button class="btn btn-primary">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
                        Exporter Excel
                    </button>
                </div>
            </div>

            <!-- TABS -->
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <div class="tabs">
                    <button class="tab-btn active" data-tab="revenus">Revenus</button>
                    <button class="tab-btn" data-tab="trafic">Volume de Trafic</button>
                    <button class="tab-btn" data-tab="categorie">Analyse par Catégorie</button>
                    <button class="tab-btn" data-tab="maintenance">Maintenance &amp; Incidents</button>
                </div>
                <div class="period-sel">
                    Derniers 30 jours
                    <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
            </div>

            <!-- TAB: REVENUS -->
            <div class="tab-content active" id="tab-revenus">

                <div class="charts-row">
                    <!-- LINE CHART -->
                    <div class="chart-card">
                        <div class="chart-card-header">
                            <div>
                                <div class="card-title">Évolution des Revenus Mensuels</div>
                                <div style="font-size:11.5px;color:var(--text-muted);margin-top:2px">Comparaison entre 2023 et 2024</div>
                            </div>
                            <div class="chart-legend">
                                <div class="legend-item"><span class="legend-dot" style="background:var(--accent)"></span>2024</div>
                                <div class="legend-item"><span class="legend-dot" style="background:var(--grey-300)"></span>2023</div>
                            </div>
                        </div>
                        <div class="chart-svg-wrap">
                            <svg class="chart-svg" viewBox="0 0 700 200" preserveAspectRatio="none">
                                <defs>
                                    <linearGradient id="grad24" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#3b82f6" stop-opacity=".25"/>
                                        <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                                    </linearGradient>
                                    <linearGradient id="grad23" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#94a3b8" stop-opacity=".15"/>
                                        <stop offset="100%" stop-color="#94a3b8" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                                <!-- 2023 fill -->
                                <path fill="url(#grad23)" d="M0,130 C80,120 140,110 220,105 C300,100 360,115 440,108 C520,101 580,90 700,95 L700,200 L0,200 Z"/>
                                <!-- 2023 line -->
                                <path fill="none" stroke="#cbd5e1" stroke-width="2" d="M0,130 C80,120 140,110 220,105 C300,100 360,115 440,108 C520,101 580,90 700,95"/>
                                <!-- 2024 fill -->
                                <path fill="url(#grad24)" d="M0,160 C80,140 140,110 220,80 C300,55 360,70 440,50 C520,30 580,20 700,15 L700,200 L0,200 Z"/>
                                <!-- 2024 line -->
                                <path fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" d="M0,160 C80,140 140,110 220,80 C300,55 360,70 440,50 C520,30 580,20 700,15"/>
                                <!-- Dots 2024 -->
                                <circle cx="220" cy="80" r="4" fill="#3b82f6"/>
                                <circle cx="440" cy="50" r="4" fill="#3b82f6"/>
                                <circle cx="700" cy="15" r="4" fill="#3b82f6"/>
                                <!-- Trend line -->
                                <line x1="440" y1="0" x2="440" y2="200" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4,4"/>
                                <text x="444" y="30" font-size="10" fill="#94a3b8" font-family="Outfit,sans-serif">+14.2% vs l'année dernière</text>
                            </svg>
                        </div>
                        <div class="chart-x-labels">
                            <span>JAN</span><span>FÉV</span><span>MAR</span><span>AVR</span><span>MAI</span><span>JUN</span>
                        </div>
                        <div style="margin-top:12px;font-size:12px;color:var(--green);font-weight:600;display:flex;align-items:center;gap:6px;">
                            ↗ +14.2% vs l'année dernière
                            <a href="#" style="margin-left:auto;font-size:12px;color:var(--accent);text-decoration:none;font-weight:600">Détails complets ›</a>
                        </div>
                    </div>

                    <!-- SIDE STAT -->
                    <div class="stat-side">
                        <div>
                            <div class="stat-side-label">Revenus Totaux (MAI)</div>
                            <div class="stat-side-value">1.42M €</div>
                        </div>
                        <hr class="stat-divider">
                        <div class="stat-side-label">Trafic Moyen / Jour</div>
                        <div style="font-size:28px;font-weight:700;">24 502</div>
                        <div style="margin-top:-8px;">
                            <div style="height:6px;background:rgba(255,255,255,.15);border-radius:3px;overflow:hidden;margin-bottom:4px;">
                                <div style="height:100%;width:82%;background:var(--accent);border-radius:3px;"></div>
                            </div>
                            <div style="font-size:11px;color:rgba(255,255,255,.45);">82% de la capacité nominale</div>
                        </div>
                        <hr class="stat-divider">
                        <div class="stat-update">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Mise à jour : Il y a 12 min
                        </div>
                    </div>
                </div>

                <!-- GARES TABLE -->
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Analyse par Gare de Péage</span>
                        <div style="display:flex;gap:10px;align-items:center;">
                            <div class="search-gare">
                                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" placeholder="Rechercher une gare...">
                            </div>
                            <button class="action-btn"><svg viewBox="0 0 24 24"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg></button>
                        </div>
                    </div>
                    <div style="overflow-x:auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nom de la Gare</th>
                                    <th>Revenus (HT)</th>
                                    <th>Volume Trafic</th>
                                    <th>Taux d'Occupation</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-weight:600">A1 – Paris Nord</td>
                                    <td style="font-family:var(--mono);font-weight:600">428 150.00 €</td>
                                    <td>12 450</td>
                                    <td>
                                        <div class="occ-bar">
                                            <div class="occ-track"><div class="occ-fill high" style="width:92%"></div></div>
                                            <span class="occ-pct">92%</span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-optimal">OPTIMAL</span></td>
                                    <td><a href="#" style="color:var(--accent);font-size:12.5px;font-weight:600;text-decoration:none">Détails</a></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:600">A13 – Mantes-la-Jolie</td>
                                    <td style="font-family:var(--mono);font-weight:600">315 400.00 €</td>
                                    <td>8 900</td>
                                    <td>
                                        <div class="occ-bar">
                                            <div class="occ-track"><div class="occ-fill mid" style="width:75%"></div></div>
                                            <span class="occ-pct">75%</span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-optimal">OPTIMAL</span></td>
                                    <td><a href="#" style="color:var(--accent);font-size:12.5px;font-weight:600;text-decoration:none">Détails</a></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:600">A10 – Orléans Centre</td>
                                    <td style="font-family:var(--mono);font-weight:600">285 900.00 €</td>
                                    <td>7 200</td>
                                    <td>
                                        <div class="occ-bar">
                                            <div class="occ-track"><div class="occ-fill low" style="width:45%"></div></div>
                                            <span class="occ-pct">45%</span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-maintenance-st">MAINTENANCE</span></td>
                                    <td><a href="#" style="color:var(--accent);font-size:12.5px;font-weight:600;text-decoration:none">Détails</a></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:600">A6 – Lyon Sud</td>
                                    <td style="font-family:var(--mono);font-weight:600">390 200.00 €</td>
                                    <td>10 800</td>
                                    <td>
                                        <div class="occ-bar">
                                            <div class="occ-track"><div class="occ-fill high" style="width:88%"></div></div>
                                            <span class="occ-pct">88%</span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-optimal">OPTIMAL</span></td>
                                    <td><a href="#" style="color:var(--accent);font-size:12.5px;font-weight:600;text-decoration:none">Détails</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-bar">
                        <span class="pagination-info">Affichage de 1–4 sur 28 gares</span>
                        <div class="pagination-controls">
                            <button class="page-btn">‹</button>
                            <button class="page-btn">›</button>
                        </div>
                    </div>
                </div>

                <!-- BOTTOM ROW -->
                <div class="bottom-row">
                    <!-- DONUT -->
                    <div class="chart-card">
                        <div class="card-title" style="margin-bottom:20px">Répartition par Catégorie</div>
                        <div class="donut-wrap">
                            <div class="donut-center">
                                <svg class="donut-svg" viewBox="0 0 130 130">
                                    <circle cx="65" cy="65" r="50" fill="none" stroke="#e2e8f0" stroke-width="18"/>
                                    <!-- 64% blue -->
                                    <circle cx="65" cy="65" r="50" fill="none" stroke="#3b82f6" stroke-width="18"
                                        stroke-dasharray="201 314" stroke-dashoffset="78" stroke-linecap="round"/>
                                    <!-- 22% dark -->
                                    <circle cx="65" cy="65" r="50" fill="none" stroke="#1e3a8a" stroke-width="18"
                                        stroke-dasharray="69 314" stroke-dashoffset="-123" stroke-linecap="round"/>
                                    <!-- 14% grey -->
                                    <circle cx="65" cy="65" r="50" fill="none" stroke="#cbd5e1" stroke-width="18"
                                        stroke-dasharray="44 314" stroke-dashoffset="-192" stroke-linecap="round"/>
                                </svg>
                                <div class="donut-label-center">
                                    <span class="pct">64%</span>
                                    <span class="lbl">Léger</span>
                                </div>
                            </div>
                            <div class="donut-legend">
                                <div class="donut-item">
                                    <div class="donut-dot" style="background:var(--accent)"></div>
                                    <span class="donut-label">Véhicules Légers (Classe 1)</span>
                                    <span class="donut-pct">64%</span>
                                </div>
                                <div class="donut-item">
                                    <div class="donut-dot" style="background:var(--navy)"></div>
                                    <span class="donut-label">Poids Lourds (Classe 4)</span>
                                    <span class="donut-pct">22%</span>
                                </div>
                                <div class="donut-item">
                                    <div class="donut-dot" style="background:var(--grey-300)"></div>
                                    <span class="donut-label">Autres (Classe 2 &amp; 3)</span>
                                    <span class="donut-pct">14%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- AI CARD -->
                    <div class="ai-card">
                        <div>
                            <div class="ai-title">✦ Analyse Prédictive IA</div>
                            <div class="ai-sub">Estimations basées sur les tendances historiques et les événements prévus.</div>
                        </div>
                        <div class="ai-badges">
                            <div class="ai-badge" style="flex-direction:column;align-items:flex-start;gap:4px;">
                                <div class="ai-badge-label">Prévision Trafic (Prochaines 24h)</div>
                                <strong>+12% vs normal</strong>
                                <span style="font-size:10.5px;color:rgba(255,255,255,.5)">Cause : Départs en week-end prolongé</span>
                            </div>
                            <div class="ai-badge" style="flex-direction:column;align-items:flex-start;gap:4px;">
                                <div class="ai-badge-label">Pic d'affluence estimé</div>
                                <strong style="font-size:18px;">17:45</strong>
                                <span style="font-size:10.5px;color:rgba(255,255,255,.5)">Gare A1 – Confiance : 94%</span>
                            </div>
                        </div>
                        <div class="ai-rec">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <div class="ai-rec-row">
                                <span>Recommandation : Augmenter le personnel de péage en gare A1 de 16h à 19h.</span>
                                <button class="btn-appliquer">Appliquer</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- end tab revenus -->

            <!-- OTHER TABS (placeholders) -->
            <div class="tab-content" id="tab-trafic">
                <div class="card" style="padding:40px;text-align:center;color:var(--text-muted)">Volume de Trafic — contenu à brancher sur les données réelles.</div>
            </div>
            <div class="tab-content" id="tab-categorie">
                <div class="card" style="padding:40px;text-align:center;color:var(--text-muted)">Analyse par Catégorie — contenu à brancher sur les données réelles.</div>
            </div>
            <div class="tab-content" id="tab-maintenance">
                <div class="card" style="padding:40px;text-align:center;color:var(--text-muted)">Maintenance & Incidents — contenu à brancher sur les données réelles.</div>
            </div>

            <!-- FOOTER -->
            <div style="display:flex;justify-content:space-between;align-items:center;padding-top:8px;border-top:1px solid var(--grey-200);font-size:11.5px;color:var(--grey-400);">
                <span>© 2024 Système de Gestion de Péage – Infrastructure Routière SAS</span>
                <div style="display:flex;gap:20px;">
                    <a href="#" style="color:var(--grey-400);text-decoration:none">Conditions d'utilisation</a>
                    <a href="#" style="color:var(--grey-400);text-decoration:none">Support technique</a>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
    });
});
</script>
</body>
</html>