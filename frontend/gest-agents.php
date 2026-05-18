<?php
    session_start();
    require '../backend/config.php';

    if (!isset($_COOKIE['admin_name'])) {
        header("Location: admin-login.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM agent;");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT g.libGui, a.numAge, a.statutAge
        FROM intervention as i, agent as a, guichet as g
        WHERE i.idAge =  a.idAge
        AND i.idGui = g.idGui
        AND dte = :dte;
    ");

    $dte = date("Y-m-d", time());
    $stmt->bindParam(':dte', $dte, PDO::PARAM_STR);
    $stmt->execute();
    $res1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $intervention = [];
    foreach ($res1 as $interv){
        $intervention[$interv['numAge']] = $interv;
    }
    
    /*if(@$_SESSION['state'] == 'connecté'){
        header("Location:gest-agents.php");
        exit();
    }*/
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Agents — Système de Gestion de Péage</title>
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="stylesheet" href="styles/agents.css">
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
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
            <a href="#" class="nav-item active">
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

    <!-- ===== MAIN ===== -->
    <div class="main-wrapper">

        <!-- TOPBAR -->
        <header class="topbar">
            <h1 class="topbar-title">Système de Gestion de Péage</h1>
            <div class="topbar-center">
                <div class="search-bar">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Rechercher...">
                </div>
            </div>
            <div class="topbar-right">
                <button class="icon-btn notif-btn" title="Notifications">
                    <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    <span class="notif-dot"></span>
                </button>
                <button class="icon-btn" title="Aide">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </button>
                <button class="icon-btn" title="Paramètres">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                </button>
                <div class="admin-profile admin-profile--topbar">
                    <div class="admin-avatar">
                        <?= substr($_COOKIE['admin_name'], 0, 2) ?>
                    </div>
                    <div class="admin-info">
                        <span class="admin-name">
                            <?= $_COOKIE['admin_name'] ?>
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="content">

            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <span>ADMINISTRATION</span>
                <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                <span class="breadcrumb-active">AGENTS</span>
            </div>

            <!-- Page header -->
            <div class="page-header">
                <h2 class="page-title">Gestion des Agents</h2>
                <div class="page-header-right">
                    <div class="search-bar search-bar--page">
                        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" placeholder="Rechercher un agent par nom ou ma...">
                    </div>
                    <button class="btn-ajouter">
                        <svg viewBox="0 0 24 24"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="16" y1="11" x2="22" y2="11"/></svg>
                        + Ajouter un Agent
                    </button>
                </div>
            </div>

            <!-- Body: filtres + tableau -->
            <div class="agents-body">

                <!-- PANEL FILTRES -->
                <aside class="filters-panel">
                    <div class="filters-header">
                        <span class="filters-title">FILTRES RAPIDES</span>
                        <button class="btn-reinit">Réinitialiser</button>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Gare de péage</label>
                        <div class="select-wrap">
                            <select>
                                <option>Toutes les gares</option>
                                <option>Gare de l'Avenir (A1)</option>
                                <option>Tunnel Nord (M2)</option>
                                <option>Pont de la Paix (RN4)</option>
                            </select>
                            <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Disponibilité</label>
                        <div class="checkbox-list">
                            <label class="checkbox-item">
                                <input type="checkbox" checked>
                                <span class="checkmark"></span>
                                Tous les statuts
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox">
                                <span class="checkmark"></span>
                                Actif
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox">
                                <span class="checkmark"></span>
                                Hors ligne
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox">
                                <span class="checkmark"></span>
                                En intervention
                            </label>
                        </div>
                    </div>

                    <!-- Stats card -->
                    <div class="stats-card">
                        <span class="stats-label">Total Agents</span>
                        <div class="stats-value">
                            <?= count($res) ?>
                        </div>
                        <div class="stats-trend">&#8599; +8 ce mois</div>
                    </div>
                </aside>

                <!-- TABLEAU AGENTS -->
                <div class="agents-table-wrap">
                    <table class="agents-table">
                        <thead>
                            <tr>
                                <th>AGENT</th>
                                <th>MATRICULE</th>
                                <th>GARE ASSIGNÉE</th>
                                <th>STATUT</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($res){
                                    foreach ($res as $key => $agent){
                                        // Récupérer les infos d'intervention si disponibles
                                        $interv = $intervention[$agent['numAge']] ?? null;
                                        
                                        if($agent['statutAge'] == 1 && $interv){
                                            $statut = 'Actif';
                                            $st = 'actif';
                                            $guichet = htmlspecialchars($interv['libGui'], ENT_QUOTES, 'UTF-8');
                                        }
                                        else{
                                            $st = 'hors';
                                            $statut = 'Inactif';
                                            $guichet = 'Aucun';
                                        }
                                        echo "
                                            <tr>
                                                <td>
                                                    <div class='agent-cell'>
                                                        <div class='agent-avatar' style='background:#2a3f6f;'>".substr($agent['prenomAge'], 0, 1)."".substr($agent['nomAge'], 0, 1)."</div>
                                                        <div class='agent-info'>
                                                            <span class='agent-name'>" . htmlspecialchars($agent['prenomAge'], ENT_QUOTES, 'UTF-8')." ".htmlspecialchars($agent['nomAge'], ENT_QUOTES, 'UTF-8')."</span>
                                                            <span class='agent-email'>".htmlspecialchars($agent['contAge'], ENT_QUOTES, 'UTF-8')."</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class='matricule'>".htmlspecialchars($agent['numAge'], ENT_QUOTES, 'UTF-8')."</td>
                                                <td>".$guichet."</td>
                                                <td><span class='badge-status badge-" . $st . "'>&#9679;".$statut."</span></td>
                                                <td class='actions-cell'>
                                                    <button class='action-btn' title='Modifier'>
                                                        <svg viewBox='0 0 24 24'><path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'/><path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'/></svg>
                                                    </button>
                                                    <button class='action-btn action-btn--delete' title='Supprimer'>
                                                        <svg viewBox='0 0 24 24'><polyline points='3 6 5 6 21 6'/><path d='M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6'/><path d='M10 11v6'/><path d='M14 11v6'/><path d='M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2'/></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                                    
                                        ";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination-bar">
                        <span class="pagination-info">Affichage de 1 à 10 sur <?= count($res) ?> agents</span>
                        <div class="pagination-controls">
                            <button class="page-btn page-btn--arrow" disabled>
                                <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                            </button>
                            <button class="page-btn page-btn--active">1</button>
                            <button class="page-btn">2</button>
                            <button class="page-btn">3</button>
                            <button class="page-btn page-btn--arrow">
                                <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        const btnAjouter = document.querySelector('.btn-ajouter');
        btnAjouter.addEventListener('click', () => {
            window.location.href = 'agent.php';
        });
    </script>

</body>
</html>
