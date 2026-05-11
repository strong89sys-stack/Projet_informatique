<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiements — Système de Gestion de Péage</title>
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="stylesheet" href="styles/paiements.css">
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
                <svg viewBox="0 0 24 24"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
                Agents
            </a>
            <a href="gares.php" class="nav-item">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Gares de péage
            </a>
            <a href="paiements.php" class="nav-item active">
                <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                Paiements
            </a>
            <a href="interventions.php" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                Interventions
            </a>
            <a href="rapports.php" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>
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
        <header class="topbar topbar--paie">
            <h1 class="topbar-title">Paiements</h1>
            <div class="topbar-center">
                <div class="search-bar-top">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Rechercher une transaction...">
                </div>
            </div>
            <div class="topbar-right">
                <button class="icon-btn"><svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></button>
                <button class="icon-btn"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></button>
                <button class="icon-btn"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg></button>
                <div class="admin-avatar" style="width:34px;height:34px;border-radius:50%;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.72rem;font-family:var(--font-head);font-weight:700;">AD</div>
            </div>
        </header>

        <main class="content">
            <!-- KPI -->
            <div class="kpi-grid kpi-grid--paie">
                <div class="kpi-card">
                    <span class="kpi-label">TOTAL COLLECTÉ</span>
                    <div class="kpi-value kpi-value--lg">145 280,00 €</div>
                    <div class="kpi-trend kpi-trend--up">&#8599; +12.5%</div>
                </div>
                <div class="kpi-card">
                    <span class="kpi-label">TRANSACTIONS</span>
                    <div class="kpi-value kpi-value--lg">8,432</div>
                    <div class="kpi-info">Moyenne: 17.22€</div>
                </div>
                <div class="kpi-card">
                    <span class="kpi-label">PAIEMENTS TAG</span>
                    <div class="kpi-value kpi-value--lg kpi-value--blue">62%</div>
                    <div class="kpi-info">Méthode dominante</div>
                </div>
                <div class="kpi-card">
                    <span class="kpi-label">TAUX D'ERREUR</span>
                    <div class="kpi-value kpi-value--lg kpi-value--red">0.04%</div>
                    <div class="kpi-info kpi-info--red">Sous le seuil critique</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-bar">
                <div class="filter-field">
                    <label class="filter-field-label">PÉRIODE</label>
                    <div class="date-picker">
                        <span>01 Oct 2023 - 31 Oct 2023</span>
                        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                </div>
                <div class="filter-field">
                    <label class="filter-field-label">MÉTHODE</label>
                    <div class="select-wrap">
                        <select><option>Tous</option><option>Tag</option><option>Carte</option><option>Espèces</option></select>
                        <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                    </div>
                </div>
                <div class="filter-field">
                    <label class="filter-field-label">CATÉGORIE</label>
                    <div class="select-wrap">
                        <select><option>Toutes</option><option>Classe 1 (VL)</option><option>Classe 2 (Inter)</option><option>Classe 3 (PL)</option></select>
                        <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                    </div>
                </div>
                <div class="filter-field">
                    <label class="filter-field-label">STATUT</label>
                    <div class="select-wrap">
                        <select><option>Tous</option><option>Validé</option><option>Échec</option><option>En attente</option></select>
                        <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                    </div>
                </div>
                <div class="filter-actions">
                    <button class="btn-csv">
                        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Exporter CSV
                    </button>
                    <button class="btn-imprimer">
                        <svg viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        Imprimer
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="table-card">
                <table class="paie-table">
                    <thead>
                        <tr>
                            <th>ID TRANSACTION</th>
                            <th>DATE & HEURE</th>
                            <th>GARE / VOIE</th>
                            <th>MÉTHODE</th>
                            <th>CATÉGORIE</th>
                            <th>MONTANT</th>
                            <th>STATUT</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="txn-id">#TXN-88219</td>
                            <td class="txn-date">24/10/2023<br>14:32:01</td>
                            <td>A1 North -<br>Voie 03</td>
                            <td><div class="methode-cell"><svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Tag</div></td>
                            <td>Classe 1 (VL)</td>
                            <td class="montant">12.50 €</td>
                            <td><span class="txn-badge txn-badge--ok">VALIDÉ</span></td>
                            <td><button class="btn-voir"><svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button></td>
                        </tr>
                        <tr>
                            <td class="txn-id">#TXN-88218</td>
                            <td class="txn-date">24/10/2023<br>14:28:45</td>
                            <td>A1 North -<br>Voie 05</td>
                            <td><div class="methode-cell"><svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Carte</div></td>
                            <td>Classe 3 (PL)</td>
                            <td class="montant">34.20 €</td>
                            <td><span class="txn-badge txn-badge--ok">VALIDÉ</span></td>
                            <td><button class="btn-voir"><svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button></td>
                        </tr>
                        <tr>
                            <td class="txn-id">#TXN-88217</td>
                            <td class="txn-date">24/10/2023<br>14:15:10</td>
                            <td>A4 East -<br>Voie 12</td>
                            <td><div class="methode-cell"><svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>Espèces</div></td>
                            <td>Classe 1 (VL)</td>
                            <td class="montant">8.90 €</td>
                            <td><span class="txn-badge txn-badge--fail">ÉCHEC</span></td>
                            <td><button class="btn-voir"><svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button></td>
                        </tr>
                        <tr>
                            <td class="txn-id">#TXN-88216</td>
                            <td class="txn-date">24/10/2023<br>14:02:55</td>
                            <td>A1 North -<br>Voie 03</td>
                            <td><div class="methode-cell"><svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Tag</div></td>
                            <td>Classe 1 (VL)</td>
                            <td class="montant">12.50 €</td>
                            <td><span class="txn-badge txn-badge--ok">VALIDÉ</span></td>
                            <td><button class="btn-voir"><svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button></td>
                        </tr>
                        <tr>
                            <td class="txn-id">#TXN-88215</td>
                            <td class="txn-date">24/10/2023<br>13:55:20</td>
                            <td>A1 North -<br>Voie 01</td>
                            <td><div class="methode-cell"><svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Carte</div></td>
                            <td>Classe 2 (Inter)</td>
                            <td class="montant">18.10 €</td>
                            <td><span class="txn-badge txn-badge--wait">EN ATTENTE</span></td>
                            <td><button class="btn-voir"><svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button></td>
                        </tr>
                    </tbody>
                </table>

                <div class="pagination-bar">
                    <span class="pagination-info">Affichage de 1-10 sur 8 432 transactions</span>
                    <div class="pagination-controls">
                        <button class="page-btn">Précédent</button>
                        <button class="page-btn page-btn--active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn">Suivant</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>