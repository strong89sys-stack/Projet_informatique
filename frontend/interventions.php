<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interventions — Système de Gestion de Péage</title>
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        .view-toggle { display: flex; gap: 4px; }
        .view-btn {
            display: flex; align-items: center; gap: 6px;
            padding: 7px 14px;
            border: 1px solid var(--grey-200);
            background: var(--white);
            border-radius: 7px;
            font-family: var(--font); font-size: 12.5px; font-weight: 600;
            color: var(--grey-500); cursor: pointer;
            transition: all var(--transition);
        }
        .view-btn svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2; }
        .view-btn.active { background: var(--navy); color: var(--white); border-color: var(--navy); }

        /* KANBAN */
        .kanban { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .kanban-col { display: flex; flex-direction: column; gap: 10px; }
        .kanban-col-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 8px 0 10px;
        }
        .col-title { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: var(--text-main); }
        .col-dot { width: 9px; height: 9px; border-radius: 50%; }
        .col-dot.red { background: var(--red); }
        .col-dot.blue { background: var(--accent); }
        .col-dot.grey { background: var(--grey-400); }
        .col-count { font-size: 11px; font-weight: 600; color: var(--grey-400); background: var(--grey-100); border-radius: 20px; padding: 1px 7px; }
        .col-more { background: none; border: none; cursor: pointer; color: var(--grey-400); font-size: 16px; letter-spacing: 2px; line-height: 1; }

        .kanban-card {
            background: var(--white);
            border: 1px solid var(--grey-200);
            border-radius: var(--radius-lg);
            padding: 16px;
            box-shadow: var(--shadow);
            cursor: pointer;
            transition: box-shadow var(--transition), transform var(--transition);
        }
        .kanban-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
        .kanban-card.resolved { opacity: .7; }
        .kanban-card.resolved .card-title-int { text-decoration: line-through; color: var(--grey-400); }

        .card-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
        .card-title-int { font-size: 14px; font-weight: 700; color: var(--navy); margin: 6px 0 8px; }
        .card-desc { font-size: 12.5px; color: var(--text-muted); line-height: 1.5; }
        .card-num { font-family: var(--mono); font-size: 11px; color: var(--grey-400); }

        .progress-wrap { margin: 10px 0 6px; }
        .progress-bar-bg { height: 5px; background: var(--grey-100); border-radius: 3px; overflow: hidden; }
        .progress-bar-fill { height: 100%; background: var(--accent); border-radius: 3px; transition: width .4s; }
        .progress-pct { font-size: 11px; color: var(--accent); font-weight: 600; text-align: right; margin-top: 3px; }

        .card-footer { display: flex; align-items: center; justify-content: space-between; margin-top: 10px; }
        .avatars { display: flex; }
        .av { width: 24px; height: 24px; background: var(--navy-pale); border-radius: 50%; border: 2px solid var(--white); margin-left: -6px; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 700; color: var(--white); }
        .av:first-child { margin-left: 0; }
        .card-meta { display: flex; align-items: center; gap: 12px; font-size: 11.5px; color: var(--grey-400); }
        .card-meta span { display: flex; align-items: center; gap: 4px; }
        .card-meta svg { width: 12px; height: 12px; stroke: currentColor; fill: none; stroke-width: 1.8; }

        .resolved-info { display: flex; align-items: center; gap: 5px; font-size: 11.5px; color: var(--green); margin-top: 8px; }
        .resolved-info svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2; }

        /* KPI bottom */
        .kpi-bottom { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
        .kpi-bottom-card {
            background: var(--white);
            border: 1px solid var(--grey-200);
            border-radius: var(--radius-lg);
            padding: 20px;
            display: flex; align-items: center; gap: 14px;
            box-shadow: var(--shadow);
        }
        .kpi-icon-wrap {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .kpi-icon-wrap svg { width: 20px; height: 20px; stroke: currentColor; fill: none; stroke-width: 1.8; }
        .kpi-icon-wrap.blue { background: var(--blue-pale); color: var(--accent); }
        .kpi-icon-wrap.red  { background: var(--red-pale);  color: var(--red); }
        .kpi-icon-wrap.green { background: var(--green-pale); color: var(--green); }
        .kpi-icon-wrap.purple { background: #ede9fe; color: #7c3aed; }
        .kpi-bottom-label { font-size: 10.5px; font-weight: 600; color: var(--grey-400); text-transform: uppercase; letter-spacing: .07em; }
        .kpi-bottom-value { font-size: 22px; font-weight: 700; color: var(--navy); }

        /* LIST VIEW */
        #list-view { display: none; }
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
            <a href="interventions.php" class="nav-item active">
                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Interventions
            </a>
            <a href="rapports.php" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
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
                    <span class="notif-dot"></span>
                </button>
                <button class="icon-btn">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </button>
                <button class="icon-btn">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                </button>
                <div class="admin-avatar-sm">AD</div>
            </div>
        </header>

        <main class="content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Gestion des Interventions</h1>
                    <p class="page-sub">Suivi en temps réel des incidents techniques et de la maintenance préventive.</p>
                </div>
                <div class="view-toggle">
                    <button class="view-btn active" id="btn-kanban">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        Kanban
                    </button>
                    <button class="view-btn" id="btn-liste">
                        <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                        Liste
                    </button>
                </div>
            </div>

            <!-- KANBAN VIEW -->
            <div id="kanban-view" class="kanban">
                <!-- EN ATTENTE -->
                <div class="kanban-col">
                    <div class="kanban-col-header">
                        <div class="col-title">
                            <span class="col-dot red"></span>
                            EN ATTENTE
                            <span class="col-count">3</span>
                        </div>
                        <button class="col-more">···</button>
                    </div>
                    <div class="kanban-card">
                        <div class="card-top">
                            <span class="badge badge-urgent">URGENT</span>
                            <span class="card-num">#INT-8842</span>
                        </div>
                        <div class="card-title-int">Panne Barrière – Voie 4</div>
                        <div class="card-desc">La barrière automatique ne se lève plus après validation du paiement. Capteur de...</div>
                        <div class="card-footer">
                            <div class="avatars"><div class="av">JD</div></div>
                            <div class="card-meta">
                                <span><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>15m</span>
                                <span><svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>2</span>
                            </div>
                        </div>
                    </div>
                    <div class="kanban-card">
                        <div class="card-top">
                            <span class="badge badge-normal">NORMAL</span>
                            <span class="card-num">#INT-8845</span>
                        </div>
                        <div class="card-title-int">Erreur Système Lecteur QR</div>
                        <div class="card-desc">Le lecteur de QR code de la Gare Nord rejette les titres valides par intermittence.</div>
                        <div class="card-footer">
                            <div class="avatars"><div class="av">NB</div></div>
                            <div class="card-meta">
                                <span><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>1h</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EN COURS -->
                <div class="kanban-col">
                    <div class="kanban-col-header">
                        <div class="col-title">
                            <span class="col-dot blue"></span>
                            EN COURS
                            <span class="col-count">2</span>
                        </div>
                        <button class="col-more">···</button>
                    </div>
                    <div class="kanban-card" style="border-left: 3px solid var(--accent);">
                        <div class="card-top">
                            <span class="badge badge-maint">MAINTENANCE</span>
                            <span class="card-num">#INT-8839</span>
                        </div>
                        <div class="card-title-int">Mise à jour Firmware Gares</div>
                        <div class="progress-wrap">
                            <div class="progress-bar-bg"><div class="progress-bar-fill" style="width:65%"></div></div>
                            <div class="progress-pct">65%</div>
                        </div>
                        <div class="card-footer">
                            <div class="avatars"><div class="av">AL</div><div class="av">MK</div></div>
                            <div class="card-meta"></div>
                        </div>
                    </div>
                </div>

                <!-- TERMINÉ -->
                <div class="kanban-col">
                    <div class="kanban-col-header">
                        <div class="col-title">
                            <span class="col-dot grey"></span>
                            TERMINÉ
                            <span class="col-count">5</span>
                        </div>
                        <button class="col-more">···</button>
                    </div>
                    <div class="kanban-card resolved">
                        <div class="card-top">
                            <span class="badge badge-resolu">RÉSOLU</span>
                            <span class="card-num">#INT-8830</span>
                        </div>
                        <div class="card-title-int">Remplacement Caméra LAPI</div>
                        <div class="card-desc">Intervention terminée avec succès par J. Dupont. Test de lecture concluant à 99%.</div>
                        <div class="resolved-info">
                            <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Fermé le 24/05
                        </div>
                    </div>
                </div>
            </div>

            <!-- LIST VIEW -->
            <div id="list-view" class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Titre</th><th>Type</th><th>Statut</th><th>Assigné</th><th>Durée</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span style="font-family:var(--mono);font-size:12px;color:var(--accent)">#INT-8842</span></td>
                            <td style="font-weight:600">Panne Barrière – Voie 4</td>
                            <td><span class="badge badge-urgent">URGENT</span></td>
                            <td><span class="badge badge-wait">EN ATTENTE</span></td>
                            <td>J. Dupont</td>
                            <td>15m</td>
                            <td><button class="action-btn"><svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button></td>
                        </tr>
                        <tr>
                            <td><span style="font-family:var(--mono);font-size:12px;color:var(--accent)">#INT-8839</span></td>
                            <td style="font-weight:600">Mise à jour Firmware Gares</td>
                            <td><span class="badge badge-maint">MAINTENANCE</span></td>
                            <td><span class="badge badge-ok">EN COURS</span></td>
                            <td>A. Luc / M. Kone</td>
                            <td>—</td>
                            <td><button class="action-btn"><svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button></td>
                        </tr>
                        <tr>
                            <td><span style="font-family:var(--mono);font-size:12px;color:var(--accent)">#INT-8830</span></td>
                            <td style="font-weight:600;text-decoration:line-through;color:var(--grey-400)">Remplacement Caméra LAPI</td>
                            <td><span class="badge badge-resolu">RÉSOLU</span></td>
                            <td><span class="badge badge-resolu">TERMINÉ</span></td>
                            <td>J. Dupont</td>
                            <td>2h</td>
                            <td><button class="action-btn"><svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button></td>
                        </tr>
                        <tr>
                            <td><span style="font-family:var(--mono);font-size:12px;color:var(--accent)">#INT-8845</span></td>
                            <td style="font-weight:600">Erreur Système Lecteur QR</td>
                            <td><span class="badge badge-normal">NORMAL</span></td>
                            <td><span class="badge badge-wait">EN ATTENTE</span></td>
                            <td>N. Ba</td>
                            <td>1h</td>
                            <td><button class="action-btn"><svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- KPI BOTTOM -->
            <div class="kpi-bottom">
                <div class="kpi-bottom-card">
                    <div class="kpi-icon-wrap blue"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                    <div>
                        <div class="kpi-bottom-label">Temps de Réponse</div>
                        <div class="kpi-bottom-value">18m</div>
                    </div>
                </div>
                <div class="kpi-bottom-card">
                    <div class="kpi-icon-wrap red"><svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
                    <div>
                        <div class="kpi-bottom-label">Incidents Critiques</div>
                        <div class="kpi-bottom-value">2</div>
                    </div>
                </div>
                <div class="kpi-bottom-card">
                    <div class="kpi-icon-wrap purple"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
                    <div>
                        <div class="kpi-bottom-label">Interventions / Jour</div>
                        <div class="kpi-bottom-value">12.4</div>
                    </div>
                </div>
                <div class="kpi-bottom-card">
                    <div class="kpi-icon-wrap green"><svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                    <div>
                        <div class="kpi-bottom-label">Taux de Résolution</div>
                        <div class="kpi-bottom-value">94%</div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
const btnKanban = document.getElementById('btn-kanban');
const btnListe  = document.getElementById('btn-liste');
const kanbanView = document.getElementById('kanban-view');
const listView   = document.getElementById('list-view');

btnKanban.addEventListener('click', () => {
    kanbanView.style.display = 'grid';
    listView.style.display   = 'none';
    btnKanban.classList.add('active');
    btnListe.classList.remove('active');
});

btnListe.addEventListener('click', () => {
    kanbanView.style.display = 'none';
    listView.style.display   = 'block';
    btnListe.classList.add('active');
    btnKanban.classList.remove('active');
});
</script>
</body>
</html>