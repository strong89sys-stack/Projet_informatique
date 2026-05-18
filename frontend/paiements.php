<?php
    session_start();
    require '../backend/config.php';

    /* Requête pour calculer les recettes totales */
    $stmt = $conn->prepare("select sum(mtPaie) from paiement");
    $stmt->execute();
    $res_rec = $stmt->fetch(PDO::FETCH_ASSOC);

    /* Requête pour compter les véhicules */
    $stmt = $conn->prepare("select count(idVeh) from vehicule");
    $stmt->execute();
    $res_veh = $stmt->fetch(PDO::FETCH_ASSOC);

    /* Requête pour récupérer l'immatriculation du véhicule dans les interventions récentes */
    $stmt = $conn->prepare("SELECT p.idPaie, i.dtInterv, g.libGui, np.libNatPaie, c.libCat, p.mtPaie
        FROM intervention as i, vehicule as v, guichet as g, agent as a, categorie as c, paiement as p, nature_paiement as np
        WHERE i.idVeh = v.idVeh
        AND i.idGui = g.idGui
        AND i.idAge = a.idAge
        AND v.idCat = c.idCat
        AND p.idVeh = v.idVeh
        AND p.idNatPaie = np.idNatPaie
        AND i.idVeh IS NOT NULL
        ORDER BY i.dtInterv ASC;
    ");
    $stmt->execute();
    $res_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiements — Système de Gestion de Péage</title>
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        .txn-id { font-family: var(--mono); font-size: 12px; color: var(--accent); font-weight: 500; }
        .txn-date { font-size: 12.5px; line-height: 1.4; }
        .methode-cell { display: flex; align-items: center; gap: 6px; }
        .methode-cell svg { width: 14px; height: 14px; stroke: var(--grey-400); fill: none; stroke-width: 1.8; }
        .montant { font-family: var(--mono); font-weight: 600; font-size: 13px; }
    </style>
</head>
<body>
<div class="layout">

    <!-- SIDEBAR -->
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
        <button class="btn-nouvelle">
            <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvelle Intervention
        </button>
    </aside>

    <!-- MAIN -->
    <div class="main-wrapper">

        <!-- TOPBAR -->
        <header class="topbar">
            <span class="topbar-title">Paiements</span>
            <div class="topbar-center">
                <div class="search-bar">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Rechercher une transaction...">
                </div>
            </div>
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

        <!-- CONTENT -->
        <main class="content">

            <!-- KPI -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <span class="kpi-label">Total Collecté</span>
                    <div class="kpi-value"><?= implode($res_rec)  ?> </div>
                    <div class="kpi-trend up">↗ +12.5%</div>
                </div>
                <div class="kpi-card">
                    <span class="kpi-label">Transactions</span>
                    <div class="kpi-value">8 432</div>
                    <div class="kpi-info">Moyenne : 17 000</div>
                </div>
                <div class="kpi-card">
                    <span class="kpi-label">Paiements Badge</span>
                    <div class="kpi-value blue">62%</div>
                    <div class="kpi-info">Méthode dominante</div>
                </div>
                <div class="kpi-card">
                    <span class="kpi-label">Taux d'erreur</span>
                    <div class="kpi-value red">0.00%</div>
                    <div class="kpi-info red">Sous le seuil critique</div>
                </div>
            </div>

            <!-- FILTERS -->
            <div class="filters-bar">
                <div class="filter-field">
                    <span class="filter-label">Période</span>
                    <div class="date-picker">
                        <span>01 Oct 2023 – 31 Oct 2023</span>
                        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                </div>
                <div class="filter-field">
                    <span class="filter-label">Méthode</span>
                    <div class="select-wrap">
                        <select id="f-methode"><option>Tous</option><option>Tag</option><option>Carte</option><option>Espèces</option></select>
                        <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                    </div>
                </div>
                <div class="filter-field">
                    <span class="filter-label">Catégorie</span>
                    <div class="select-wrap">
                        <select id="f-cat"><option>Toutes</option><option>Classe 1 (VL)</option><option>Classe 2 (Inter)</option><option>Classe 3 (PL)</option></select>
                        <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                    </div>
                </div>
                <div class="filter-field">
                    <span class="filter-label">Statut</span>
                    <div class="select-wrap">
                        <select id="f-statut"><option>Tous</option><option>Validé</option><option>Échec</option><option>En attente</option></select>
                        <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                    </div>
                </div>
                <div class="filter-actions">
                    <button class="btn btn-secondary">
                        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Exporter CSV
                    </button>
                    <button class="btn btn-primary">
                        <svg viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        Imprimer
                    </button>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-wrap">
                <table id="txn-table">
                    <thead>
                        <tr>
                            <th>ID Transaction</th>
                            <th>Date &amp; Heure</th>
                            <th>Gare / Voie</th>
                            <th>Méthode</th>
                            <th>Catégorie</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="txn-body">
                        <?php
                            // PHP code for fetching and displaying operation data would go here, but for now we will use static sample data
                            $i = 0;
                            foreach ($res_data as $key => $data) {

                                if ($data['mtPaie'] == 500){
                                    $i = 1;
                                }
                                elseif ($data['mtPaie'] == 1000) {
                                    $i = 2;
                                }
                                elseif ($data['mtPaie'] == 1500) {
                                    $i = 3;
                                }
                                else {
                                    $i = 4;
                                }
                                echo "
                                    <tr>
                                        <td>".md5($data['idPaie'])."</td>
                                        <td>".$data['dtInterv']."</td>
                                        <td>".$data['libGui']."</td>
                                        <td>".$data['libNatPaie']."</td>
                                        <td><span class='badge badge--".$i."'>".substr($data['libCat'], 0, 8)."</span></td>
                                        <td>".$data['mtPaie']."</td>
                                        <td><span class='badge-ok'>VALIDÉ</span></td>
                                        <td>
                                            <button class='action-btn' title='Voir'>
                                                <svg viewBox='0 0 24 24'><path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'/><circle cx='12' cy='12' r='3'/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                ";
                            }    
                        ?>
                    </tbody>
                </table>
                <div class="pagination-bar">
                    <span class="pagination-info" id="pag-info">Affichage de 1–5 sur 5 transactions</span>
                    <div class="pagination-controls">
                        <button class="page-btn page-btn-text">Précédent</button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn page-btn-text">Suivant</button>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
/*const transactions = [
    { id: '#TXN-88219', date: '24/10/2026', heure: '14:32:01', gare: 'Guichet A', voie: 'Voie 03', methode: 'badge',     categorie: 'Classe 1 (VL)',   montant: '500', statut: 'validé' },
    { id: '#TXN-88218', date: '24/10/2026', heure: '14:28:45', gare: 'Guichet B', voie: 'Voie 05', methode: 'badge',   categorie: 'Classe 3 (PL)',   montant: '1500', statut: 'validé' },
    { id: '#TXN-88217', date: '24/10/2026', heure: '14:15:10', gare: 'Guichet C',  voie: 'Voie 12', methode: 'Espèces', categorie: 'Classe 1 (VL)',   montant: '500',  statut: 'echec' },
    { id: '#TXN-88216', date: '24/10/2026', heure: '14:02:55', gare: 'Guichet A', voie: 'Voie 03', methode: 'Mobile Money',     categorie: 'Classe 4 (VL)',   montant: '3000', statut: 'validé' },
    { id: '#TXN-88215', date: '24/10/2026', heure: '13:55:20', gare: 'Guichet A', voie: 'Voie 01', methode: 'Carte',   categorie: 'Classe 2 (Inter)', montant: '500', statut: 'validé' },
];

const statutMap = {
    'validé':  { cls: 'badge-ok',   label: 'VALIDÉ' },
    'echec':   { cls: 'badge-fail', label: 'ÉCHEC' },
    'attente': { cls: 'badge-wait', label: 'EN ATTENTE' },
};

const methodeIcon = {
    'Tag':     '<svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>',
    'Carte':   '<svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>',
    'Espèces': '<svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>',
};

function renderTable(data) {
    const tbody = document.getElementById('txn-body');
    tbody.innerHTML = data.map(t => {
        const s = statutMap[t.statut];
        return `<tr>
            <td><span class="txn-id">${t.id}</span></td>
            <td class="txn-date">${t.date}<br>${t.heure}</td>
            <td>${t.gare} –<br>${t.voie}</td>
            <td><div class="methode-cell">${methodeIcon[t.methode] || ''} ${t.methode}</div></td>
            <td>${t.categorie}</td>
            <td class="montant">${t.montant}</td>
            <td><span class="badge ${s.cls}">${s.label}</span></td>
            <td>
                <button class="action-btn" title="Voir">
                    <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </td>
        </tr>`;
    }).join('');
}

function filterTable() {
    const m  = document.getElementById('f-methode').value;
    const c  = document.getElementById('f-cat').value;
    const s  = document.getElementById('f-statut').value;
    const statutLabel = { 'Validé': 'validé', 'Échec': 'echec', 'En attente': 'attente' };
    let data = transactions.filter(t => {
        if (m !== 'Tous' && t.methode !== m) return false;
        if (c !== 'Toutes' && t.categorie !== c) return false;
        if (s !== 'Tous' && t.statut !== statutLabel[s]) return false;
        return true;
    });
    renderTable(data);
    document.getElementById('pag-info').textContent = `Affichage de 1–${data.length} sur ${data.length} transactions`;
}

document.getElementById('f-methode').addEventListener('change', filterTable);
document.getElementById('f-cat').addEventListener('change', filterTable);
document.getElementById('f-statut').addEventListener('change', filterTable);

renderTable(transactions);*/
</script>
</body>
</html>