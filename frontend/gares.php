<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gares de Péage — Système de Gestion de Péage</title>
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="stylesheet" href="styles/gares.css">
</head>
<body>

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
                <svg viewBox="0 0 24 24"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0-3-3.87"/></svg>
                Agents
            </a>
            <a href="#" class="nav-item active">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Gares de péage
            </a>
            <a href="paiements.php" class="nav-item">
                <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                Paiements
            </a>
            <a href="interventions.php" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
                Interventions
            </a>
            <a href="rapports.php" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
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

    <div class="main-wrapper">
        <header class="topbar">
            <h1 class="topbar-title">Système de Gestion de Péage</h1>
            <div class="topbar-right">
                <button class="icon-btn"><svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></button>
                <button class="icon-btn"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></button>
                <button class="icon-btn"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg></button>
                <div class="admin-profile">
                    <div class="admin-avatar">JD</div>
                    <div class="admin-info">
                        <span class="admin-name">Jean Dupont</span>
                        <span class="admin-role">Administrateur Senior</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="content">
            <!-- Page header -->
            <div class="page-header">
                <div>
                    <h2 class="page-title">Gares de péage</h2>
                    <p class="page-sub">Surveillance et configuration des points de collecte du réseau.</p>
                </div>
                <div class="gares-header-actions">
                    <div class="toggle-view">
                        <button class="toggle-btn toggle-btn--active">
                            <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            Liste
                        </button>
                        <button class="toggle-btn">
                            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                            Carte
                        </button>
                    </div>
                    <button class="btn-ajouter-gare">
                        <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Ajouter une gare
                    </button>
                </div>
            </div>

            <!-- KPI -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-header">
                        <span class="kpi-label">TOTAL GARES</span>
                        <div class="kpi-icon kpi-icon--blue"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg></div>
                    </div>
                    <div class="kpi-value">24</div>
                    <div class="kpi-trend kpi-trend--up">&#8599; +2 cette année</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-header">
                        <span class="kpi-label">EN SERVICE</span>
                        <div class="kpi-icon kpi-icon--green"><svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                    </div>
                    <div class="kpi-value">21</div>
                    <div class="kpi-info kpi-info--green">92% du réseau</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-header">
                        <span class="kpi-label">MAINTENANCE</span>
                        <div class="kpi-icon kpi-icon--orange"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
                    </div>
                    <div class="kpi-value">3</div>
                    <div class="kpi-info kpi-info--orange">Intervention en cours</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-header">
                        <span class="kpi-label">ALERTES FLUX</span>
                        <div class="kpi-icon kpi-icon--red"><svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
                    </div>
                    <div class="kpi-value">02</div>
                    <div class="kpi-info kpi-info--red">Congestion critique</div>
                </div>
            </div>

            <!-- Search + Table -->
            <div class="table-card">
                <div class="gares-search-bar">
                    <div class="search-input-wrap">
                        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" placeholder="Rechercher une gare ou localisation...">
                    </div>
                    <div class="gares-search-actions">
                        <button class="btn-filter">
                            <svg viewBox="0 0 24 24"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
                            Filtrer
                        </button>
                        <button class="btn-export-gare">
                            <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Exporter
                        </button>
                    </div>
                </div>

                <table class="gares-table">
                    <thead>
                        <tr>
                            <th>NOM DE LA GARE</th>
                            <th>LOCALISATION</th>
                            <th>STATUT</th>
                            <th>VOIES</th>
                            <th>TRAFIC (24H)</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="gare-cell">
                                    <div class="gare-icon"><svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></div>
                                    <div>
                                        <div class="gare-name">Grand-Bassam Nord</div>
                                        <div class="gare-id">ID: TOLL-GB-001</div>
                                    </div>
                                </div>
                            </td>
                            <td class="gare-loc">Grand-Bassam, A100</td>
                            <td><span class="badge-status badge-actif">&#9679; ACTIF</span></td>
                            <td>12 Voies</td>
                            <td>
                                <div class="trafic-cell">
                                    <span class="trafic-val">15,402 veh.</span>
                                    <div class="trafic-bar"><div class="trafic-fill trafic-fill--blue" style="width:68%"></div></div>
                                </div>
                            </td>
                            <td class="actions-cell">
                                <button class="action-btn" title="Modifier"><svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></button>
                                <button class="action-btn" title="Historique"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></button>
                                <button class="action-btn action-btn--delete" title="Supprimer"><svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="gare-cell">
                                    <div class="gare-icon"><svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></div>
                                    <div>
                                        <div class="gare-name">Yamoussoukro Sud</div>
                                        <div class="gare-id">ID: TOLL-YK-004</div>
                                    </div>
                                </div>
                            </td>
                            <td class="gare-loc">Yamoussoukro, A1</td>
                            <td><span class="badge-status badge-maintenance">&#9679; MAINTENANCE</span></td>
                            <td>8 Voies<br><span class="voies-sub">(2 fermées)</span></td>
                            <td>
                                <div class="trafic-cell">
                                    <span class="trafic-val">8,920 veh.</span>
                                    <div class="trafic-bar"><div class="trafic-fill trafic-fill--blue" style="width:40%"></div></div>
                                </div>
                            </td>
                            <td class="actions-cell">
                                <button class="action-btn"><svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></button>
                                <button class="action-btn"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></button>
                                <button class="action-btn action-btn--delete"><svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="gare-cell">
                                    <div class="gare-icon"><svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></div>
                                    <div>
                                        <div class="gare-name">Abidjan Pont-Est</div>
                                        <div class="gare-id">ID: TOLL-AB-012</div>
                                    </div>
                                </div>
                            </td>
                            <td class="gare-loc">Plateau, Boulevard Lagunaire</td>
                            <td><span class="badge-status badge-actif">&#9679; ACTIF</span></td>
                            <td>16 Voies</td>
                            <td>
                                <div class="trafic-cell">
                                    <span class="trafic-val">45,110 veh.</span>
                                    <div class="trafic-bar"><div class="trafic-fill trafic-fill--red" style="width:95%"></div></div>
                                </div>
                            </td>
                            <td class="actions-cell">
                                <button class="action-btn"><svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></button>
                                <button class="action-btn"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></button>
                                <button class="action-btn action-btn--delete"><svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg></button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="pagination-bar">
                    <span class="pagination-info">Affichage 1-10 sur 24 gares</span>
                    <div class="pagination-controls">
                        <button class="page-btn page-btn--arrow" disabled><svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg></button>
                        <button class="page-btn page-btn--active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn page-btn--arrow"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></button>
                    </div>
                </div>
            </div>

            <!-- Bottom: Map + Rappel -->
            <div class="gares-bottom">
                <div class="map-card">
                    <div class="map-visual">
                        <svg class="map-svg" viewBox="0 0 500 260" xmlns="http://www.w3.org/2000/svg">
                            <rect width="500" height="260" fill="#0a1628"/>
                            <!-- Grid lines -->
                            <line x1="0" y1="60" x2="500" y2="60" stroke="#0d2a4a" stroke-width="1"/>
                            <line x1="0" y1="120" x2="500" y2="120" stroke="#0d2a4a" stroke-width="1"/>
                            <line x1="0" y1="180" x2="500" y2="180" stroke="#0d2a4a" stroke-width="1"/>
                            <line x1="100" y1="0" x2="100" y2="260" stroke="#0d2a4a" stroke-width="1"/>
                            <line x1="200" y1="0" x2="200" y2="260" stroke="#0d2a4a" stroke-width="1"/>
                            <line x1="300" y1="0" x2="300" y2="260" stroke="#0d2a4a" stroke-width="1"/>
                            <line x1="400" y1="0" x2="400" y2="260" stroke="#0d2a4a" stroke-width="1"/>
                            <!-- Roads -->
                            <path d="M50 200 Q150 150 250 130 Q350 110 450 80" stroke="#00d4ff" stroke-width="2" fill="none" opacity="0.7"/>
                            <path d="M80 260 Q120 180 180 140 Q240 100 300 80" stroke="#00d4ff" stroke-width="1.5" fill="none" opacity="0.5"/>
                            <path d="M0 130 Q100 120 200 100 Q300 80 400 60 Q450 50 500 45" stroke="#00d4ff" stroke-width="1.5" fill="none" opacity="0.4"/>
                            <path d="M150 260 Q200 200 250 130" stroke="#00d4ff" stroke-width="1" fill="none" opacity="0.4"/>
                            <path d="M300 260 Q320 200 340 130 Q360 80 380 40" stroke="#00d4ff" stroke-width="1" fill="none" opacity="0.35"/>
                            <!-- Nodes -->
                            <circle cx="130" cy="190" r="8" fill="#00d4ff" opacity="0.9"/>
                            <circle cx="130" cy="190" r="14" fill="#00d4ff" opacity="0.2"/>
                            <circle cx="250" cy="130" r="8" fill="#00d4ff" opacity="0.9"/>
                            <circle cx="250" cy="130" r="14" fill="#00d4ff" opacity="0.2"/>
                            <circle cx="370" cy="85" r="8" fill="#00d4ff" opacity="0.9"/>
                            <circle cx="370" cy="85" r="14" fill="#00d4ff" opacity="0.2"/>
                            <circle cx="180" cy="140" r="6" fill="#00bcd4" opacity="0.7"/>
                            <circle cx="310" cy="100" r="6" fill="#00bcd4" opacity="0.7"/>
                            <!-- Labels -->
                            <text x="138" y="186" fill="#00d4ff" font-size="9" font-family="monospace">GB-001</text>
                            <text x="258" y="126" fill="#00d4ff" font-size="9" font-family="monospace">AB-012</text>
                            <text x="378" y="81" fill="#00d4ff" font-size="9" font-family="monospace">YK-004</text>
                        </svg>
                    </div>
                    <div class="map-footer">
                        <div>
                            <div class="map-title">Visualisation du réseau</div>
                            <div class="map-sub">Dernière synchronisation GPS : Il y a 2 min</div>
                        </div>
                        <button class="map-pin-btn"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></button>
                    </div>
                </div>

                <div class="rappel-card">
                    <div class="rappel-header">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>Rappel Maintenance</span>
                    </div>
                    <p class="rappel-desc">3 gares nécessitent un étalonnage des capteurs de poids d'ici les 48 prochaines heures.</p>
                    <div class="rappel-items">
                        <button class="rappel-item">Gare ID: 004 <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></button>
                        <button class="rappel-item">Gare ID: 012 <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></button>
                    </div>
                    <button class="btn-planning">Voir le planning complet</button>
                </div>
            </div>

        </main>
    </div>

</body>
</html>