<?php 
    session_start();
    require '../backend/config.php';

    if (!isset($_COOKIE['admin_name'])){
        header("location:admin-login.php");
        exit();
    }
    else{
        $stmt = $conn->prepare("
            SELECT COUNT(*)
            FROM intervention
            WHERE idVeh <> NULL;
        ");
        $stmt->execute();
        $interventionsCount = $stmt->fetchColumn();
    }

    $stmt = $conn->prepare("select sum(mtPaie) from paiement");
    $stmt->execute();
    $res_rec = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare('select count(statutAge) from agent where statutAge = 1');
    $stmt->execute();
    $activeAge = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("select count(idVeh) from vehicule");
    $stmt->execute();
    $res_veh = $stmt->fetch(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord — Système de Gestion de Péage</title>
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <span class="brand-title">Administration</span>
            <span class="brand-sub">Infrastructure Routière</span>
        </div>

        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Tableau de bord
            </a>
            <a href="gest-agents.php" class="nav-item">
                <svg viewBox="0 0 24 24"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0-3-3.87"/></svg>
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
                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Interventions
            </a>
            <a href="rapports.php" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Rapports
            </a>
            <a href="parametres.php" class="nav-item">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                Paramètres
            </a>
        </nav>

        <button class="btn-nouvelle-intervention">
            <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvelle Intervention
        </button>
    </aside>

    <!-- ===== MAIN ===== -->
    <div class="main-wrapper">

        <!-- TOPBAR -->
        <header class="topbar">
            <h1 class="topbar-title">Système de Gestion de Péage</h1>
            <div class="topbar-right">
                <div class="admin-profile">
                    <div class="admin-avatar">
                        <?= substr($_COOKIE['admin_name'], 0, 2) ?>
                    </div>
                    <span class="admin-name">
                        <?= $_COOKIE['admin_name'] ?>
                    </span>
                </div>
                <button class="icon-btn" title="Notifications">
                    <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </button>
                <button class="icon-btn" title="Aide">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </button>
                <a href="logout.php" class="deconnexion-btn">
                    <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Déconnexion
                </a>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="content">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="page-title">Tableau de bord principal</h2>
                    <p class="page-sub">Aperçu en temps réel des opérations de l'infrastructure.</p>
                </div>
                <div class="export-btns">
                    <button class="btn-export btn-pdf">
                        <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Export PDF
                    </button>
                    <button class="btn-export btn-excel">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
                        Export Excel
                    </button>
                </div>
            </div>

            <!-- KPI CARDS -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-header">
                        <span class="kpi-label">VÉHICULES AUJOURD'HUI</span>
                        <div class="kpi-icon kpi-icon--blue">
                            <svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        </div>
                    </div>
                    <div class="kpi-value"><?= implode($res_veh) ?></div>
                    <div class="kpi-trend kpi-trend--up">&#8599; +8.4% vs hier</div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-header">
                        <span class="kpi-label">RECETTES TOTALES</span>
                        <div class="kpi-icon kpi-icon--green">
                            <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                        </div>
                    </div>
                    <div class="kpi-value">
                        <?= implode($res_rec) ?>
                    </div>
                    <div class="kpi-currency">CFA</div>
                    <div class="kpi-trend kpi-trend--up">&#8599; +12.1% ce mois</div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-header">
                        <span class="kpi-label">INTERVENTIONS</span>
                        <div class="kpi-icon kpi-icon--purple">
                            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
                        </div>
                    </div>
                    <div class="kpi-value"><?= $interventionsCount ?></div>
                    <div class="kpi-info">&#10003; Toutes résolues</div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-header">
                        <span class="kpi-label">AGENTS ACTIFS</span>
                        <div class="kpi-icon kpi-icon--orange">
                            <svg viewBox="0 0 24 24"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0-3-3.87"/></svg>
                        </div>
                    </div>
                    <div class="kpi-value">
                        <?= implode($activeAge) ?>
                    </div>
                    <div class="kpi-info">&#10003; Équipe du matin</div>
                </div>
            </div>

            <!-- CHARTS -->
            <div class="charts-grid">
                <!-- Bar chart: Paiements par catégorie -->
                <div class="chart-card chart-card--large">
                    <h3 class="chart-title">Paiements par catégorie</h3>
                    <div class="bar-chart">
                        <div class="bar-group">
                            <div class="bar-wrap">
                                <div class="bar" style="--h: 55%"></div>
                            </div>
                            <span class="bar-label">CAT 1</span>
                        </div>
                        <div class="bar-group">
                            <div class="bar-wrap">
                                <div class="bar bar--accent" style="--h: 80%"></div>
                            </div>
                            <span class="bar-label">CAT 2</span>
                        </div>
                        <div class="bar-group">
                            <div class="bar-wrap">
                                <div class="bar" style="--h: 65%"></div>
                            </div>
                            <span class="bar-label">CAT 3</span>
                        </div>
                        <div class="bar-group">
                            <div class="bar-wrap">
                                <div class="bar bar--accent" style="--h: 45%"></div>
                            </div>
                            <span class="bar-label">CAT 4</span>
                        </div>
                        <div class="bar-group">
                            <div class="bar-wrap">
                                <div class="bar" style="--h: 30%"></div>
                            </div>
                            <span class="bar-label">MOTOS</span>
                        </div>
                    </div>
                </div>

                <!-- Line chart: Passages par heure (SVG) -->
                <div class="chart-card">
                    <h3 class="chart-title">Passages par heure</h3>
                    <div class="line-chart-wrapper">
                        <svg class="line-chart-svg" viewBox="0 0 300 160" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="lineGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#1e3a8a" stop-opacity="0.3"/>
                                    <stop offset="100%" stop-color="#1e3a8a" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                            <!-- Fill area -->
                            <path class="chart-fill" d="M0,140 C20,130 40,110 60,90 C80,70 90,40 120,30 C150,20 160,60 180,80 C200,100 210,55 240,20 C260,5 280,10 300,8 L300,160 L0,160 Z"/>
                            <!-- Line -->
                            <path class="chart-line" d="M0,140 C20,130 40,110 60,90 C80,70 90,40 120,30 C150,20 160,60 180,80 C200,100 210,55 240,20 C260,5 280,10 300,8"/>
                        </svg>
                        <div class="line-chart-labels">
                            <span>08h</span>
                            <span>12h</span>
                            <span>16h</span>
                            <span>20h</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-card">
                <div class="table-header">
                    <h3 class="chart-title">Dernières opérations</h3>
                    <a href="#" class="voir-tout">Voir tout</a>
                </div>
                <div class="table-wrapper">
                    <table class="operations-table">
                        <thead>
                            <tr>
                                <th>Date / Heure</th>
                                <th>Véhicule (Immat)</th>
                                <th>Catégorie</th>
                                <th>Montant</th>
                                <th>Guichet</th>
                                <th>Agent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>14/10/2023 - 10:45</td>
                                <td><strong>AA-123-BB</strong></td>
                                <td><span class="badge badge--2">Classe 2</span></td>
                                <td>1,500 CFA</td>
                                <td>Gare Nord - G3</td>
                                <td>Jean Dupont</td>
                            </tr>
                            <tr>
                                <td>14/10/2023 - 10:42</td>
                                <td><strong>CC-456-DD</strong></td>
                                <td><span class="badge badge--4">Classe 4</span></td>
                                <td>5,000 CFA</td>
                                <td>Gare Sud - G1</td>
                                <td>Marie Kone</td>
                            </tr>
                            <tr>
                                <td>14/10/2023 - 10:38</td>
                                <td><strong>EE-789-FF</strong></td>
                                <td><span class="badge badge--1">Classe 1</span></td>
                                <td>500 CFA</td>
                                <td>Gare Nord - G2</td>
                                <td>Alain Diallo</td>
                            </tr>
                            <tr>
                                <td>14/10/2023 - 10:35</td>
                                <td><strong>GG-012-HH</strong></td>
                                <td><span class="badge badge--2">Classe 2</span></td>
                                <td>1,500 CFA</td>
                                <td>Gare Est - G4</td>
                                <td>Sophie Yao</td>
                            </tr>
                            <tr>
                                <td>14/10/2023 - 10:30</td>
                                <td><strong>II-345-JJ</strong></td>
                                <td><span class="badge badge--3">Classe 3</span></td>
                                <td>3,000 CFA</td>
                                <td>Gare Sud - G1</td>
                                <td>Marie Kone</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

</body>
</html>
