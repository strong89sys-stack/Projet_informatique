<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Système de Gestion de Péage</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --navy: #0a1628;
    --navy-mid: #0f2044;
    --navy-light: #162d5c;
    --blue: #1a4eaa;
    --blue-bright: #2563eb;
    --accent: #3b82f6;
    --gold: #f59e0b;
    --text: #e8edf5;
    --text-muted: #8fa3c8;
    --text-dim: #5a7aa8;
    --white: #ffffff;
    --border: rgba(59,130,246,0.18);
    --card: rgba(15,32,68,0.85);
    --sidebar-w: 220px;
    --topbar-h: 60px;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
  }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--navy);
    color: var(--text);
    min-height: 100vh;
    display: flex;
    overflow-x: hidden;
  }

  /* SIDEBAR */
  .sidebar {
    width: var(--sidebar-w);
    min-height: 100vh;
    background: linear-gradient(180deg, #0d1f45 0%, #0a1628 100%);
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0; left: 0; bottom: 0;
    z-index: 100;
  }

  .sidebar-brand {
    padding: 22px 20px 18px;
    border-bottom: 1px solid var(--border);
  }
  .sidebar-brand h2 {
    font-family: 'Syne', sans-serif;
    font-size: 15px;
    font-weight: 700;
    color: var(--white);
    line-height: 1.2;
  }
  .sidebar-brand span {
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 400;
    display: block;
    margin-top: 2px;
  }

  .sidebar-nav {
    flex: 1;
    padding: 14px 0;
  }
  .nav-item {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 10px 20px;
    cursor: pointer;
    color: var(--text-muted);
    font-size: 13.5px;
    font-weight: 400;
    border-left: 3px solid transparent;
    transition: all 0.18s;
  }
  .nav-item:hover { color: var(--text); background: rgba(59,130,246,0.07); }
  .nav-item.active {
    color: var(--accent);
    background: rgba(59,130,246,0.12);
    border-left-color: var(--accent);
    font-weight: 500;
  }
  .nav-item svg { width: 17px; height: 17px; opacity: 0.85; flex-shrink: 0; }

  .sidebar-footer {
    padding: 14px 14px 20px;
  }
  .btn-new {
    width: 100%;
    background: var(--blue-bright);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-family: 'Syne', sans-serif;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    letter-spacing: 0.01em;
    transition: background 0.15s, transform 0.12s;
  }
  .btn-new:hover { background: #1d4ed8; transform: translateY(-1px); }

  /* TOPBAR */
  .topbar {
    position: fixed;
    top: 0;
    left: var(--sidebar-w);
    right: 0;
    height: var(--topbar-h);
    background: rgba(10,22,40,0.97);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 28px;
    z-index: 90;
    backdrop-filter: blur(10px);
  }

  .topbar-title {
    font-family: 'Syne', sans-serif;
    font-size: 17px;
    font-weight: 800;
    color: var(--accent);
    letter-spacing: -0.01em;
  }

  .topbar-right {
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .search-bar {
    display: flex;
    align-items: center;
    background: var(--navy-mid);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 6px 13px;
    gap: 8px;
    width: 200px;
  }
  .search-bar input {
    background: none;
    border: none;
    outline: none;
    color: var(--text);
    font-size: 13px;
    font-family: 'DM Sans', sans-serif;
    width: 100%;
  }
  .search-bar input::placeholder { color: var(--text-dim); }

  .topbar-icons {
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .icon-btn {
    width: 34px; height: 34px;
    border-radius: 8px;
    background: var(--navy-mid);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: background 0.15s;
    color: var(--text-muted);
  }
  .icon-btn:hover { background: var(--navy-light); color: var(--text); }
  .icon-btn svg { width: 16px; height: 16px; }

  .topbar-user {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
  }
  .user-info { text-align: right; }
  .user-info strong { display: block; font-size: 13px; font-weight: 600; color: var(--text); }
  .user-info span { font-size: 10px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em; }
  .user-avatar {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #2563eb, #1e40af);
    border: 2px solid var(--accent);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: #fff;
    overflow: hidden;
  }
  .user-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; }

  /* MAIN */
  .main {
    margin-left: var(--sidebar-w);
    margin-top: var(--topbar-h);
    flex: 1;
    padding: 28px;
    min-height: calc(100vh - var(--topbar-h));
  }

  .page-header {
    margin-bottom: 26px;
  }
  .page-header h1 {
    font-family: 'Syne', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: var(--white);
    letter-spacing: -0.02em;
  }
  .page-header p {
    font-size: 13px;
    color: var(--text-muted);
    margin-top: 4px;
  }
  .page-actions {
    display: flex;
    gap: 10px;
    margin-top: 16px;
  }
  .btn {
    padding: 9px 20px;
    border-radius: 9px;
    font-family: 'Syne', sans-serif;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
    border: none;
  }
  .btn-outline {
    background: transparent;
    border: 1.5px solid var(--border);
    color: var(--text-muted);
  }
  .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
  .btn-primary {
    background: var(--blue-bright);
    color: #fff;
  }
  .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }

  /* GRID */
  .grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
  }
  .grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 20px;
  }

  /* CARD */
  .card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 22px;
    backdrop-filter: blur(8px);
  }
  .card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
  }
  .card-title {
    display: flex;
    align-items: center;
    gap: 9px;
    font-family: 'Syne', sans-serif;
    font-size: 15px;
    font-weight: 700;
    color: var(--white);
  }
  .card-title svg { width: 18px; height: 18px; color: var(--accent); }

  /* PROFILE CARD */
  .profile-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 28px 22px;
  }
  .profile-avatar {
    width: 88px; height: 88px;
    border-radius: 18px;
    background: linear-gradient(135deg, #1a4eaa, #0f2044);
    border: 2px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-size: 28px;
    font-weight: 800;
    color: var(--accent);
    margin-bottom: 14px;
    overflow: hidden;
    position: relative;
  }
  .profile-avatar::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(37,99,235,0.2), transparent);
    border-radius: 16px;
  }
  .profile-name {
    font-family: 'Syne', sans-serif;
    font-size: 17px;
    font-weight: 700;
    color: var(--white);
  }
  .profile-role {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 3px;
  }
  .profile-fields {
    width: 100%;
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    text-align: left;
  }
  .field-label {
    font-size: 11px;
    color: var(--text-dim);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 4px;
  }
  .field-input {
    background: rgba(10,22,40,0.6);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 9px 13px;
    color: var(--text);
    font-size: 13px;
    font-family: 'DM Sans', sans-serif;
    width: 100%;
    outline: none;
    transition: border-color 0.15s;
  }
  .field-input:focus { border-color: var(--accent); }
  .field-select {
    background: rgba(10,22,40,0.6);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 9px 13px;
    color: var(--text);
    font-size: 13px;
    font-family: 'DM Sans', sans-serif;
    width: 100%;
    outline: none;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238fa3c8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
  }

  /* TARIF TABLE */
  .tarif-table { width: 100%; border-collapse: collapse; }
  .tarif-table thead th {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-dim);
    text-transform: uppercase;
    letter-spacing: 0.07em;
    padding: 0 12px 12px;
    text-align: left;
    border-bottom: 1px solid var(--border);
  }
  .tarif-table thead th:last-child { text-align: center; }
  .tarif-row td {
    padding: 13px 12px;
    border-bottom: 1px solid rgba(59,130,246,0.08);
    font-size: 13.5px;
    vertical-align: middle;
  }
  .tarif-row:last-child td { border-bottom: none; }
  .classe-badge {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    color: var(--white);
    font-size: 13px;
  }
  .tarif-desc { color: var(--text-muted); font-size: 12.5px; }
  .tarif-input {
    background: rgba(10,22,40,0.6);
    border: 1px solid var(--border);
    border-radius: 7px;
    padding: 6px 10px;
    color: var(--text);
    font-size: 13px;
    font-family: 'DM Sans', sans-serif;
    width: 70px;
    outline: none;
    text-align: right;
    transition: border-color 0.15s;
  }
  .tarif-input:focus { border-color: var(--accent); }
  .edit-btn {
    background: none;
    border: none;
    color: var(--text-dim);
    cursor: pointer;
    padding: 5px;
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    transition: color 0.15s, background 0.15s;
  }
  .edit-btn:hover { color: var(--accent); background: rgba(59,130,246,0.1); }
  .edit-btn svg { width: 15px; height: 15px; }

  .add-link {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    color: var(--accent);
    cursor: pointer;
    font-weight: 500;
    transition: opacity 0.15s;
  }
  .add-link:hover { opacity: 0.75; }

  /* ROLES */
  .role-item {
    background: rgba(10,22,40,0.5);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
  }
  .role-item:last-child { margin-bottom: 0; }
  .role-name {
    font-family: 'Syne', sans-serif;
    font-size: 13.5px;
    font-weight: 700;
    color: var(--white);
    margin-bottom: 3px;
  }
  .role-desc { font-size: 12px; color: var(--text-muted); }
  .badge-default {
    background: var(--blue-bright);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    font-family: 'Syne', sans-serif;
    letter-spacing: 0.05em;
    padding: 3px 9px;
    border-radius: 6px;
    text-transform: uppercase;
  }
  .settings-btn {
    width: 30px; height: 30px;
    border-radius: 8px;
    background: rgba(59,130,246,0.1);
    border: 1px solid var(--border);
    color: var(--text-muted);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all 0.15s;
  }
  .settings-btn:hover { background: rgba(59,130,246,0.2); color: var(--accent); }
  .settings-btn svg { width: 14px; height: 14px; }

  .btn-manage {
    width: 100%;
    margin-top: 14px;
    background: rgba(59,130,246,0.1);
    border: 1.5px solid var(--border);
    color: var(--accent);
    border-radius: 9px;
    padding: 10px;
    font-family: 'Syne', sans-serif;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
  }
  .btn-manage:hover { background: rgba(59,130,246,0.2); border-color: var(--accent); }

  /* ALERTS */
  .alert-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 13px 0;
    border-bottom: 1px solid rgba(59,130,246,0.08);
  }
  .alert-item:last-child { border-bottom: none; padding-bottom: 0; }
  .alert-name {
    font-size: 13.5px;
    font-weight: 500;
    color: var(--white);
    margin-bottom: 3px;
  }
  .alert-desc { font-size: 12px; color: var(--text-muted); }

  /* TOGGLE */
  .toggle { position: relative; display: inline-flex; }
  .toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
  .toggle-track {
    width: 42px; height: 24px;
    background: rgba(59,130,246,0.2);
    border-radius: 12px;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    padding: 3px;
    border: 1px solid var(--border);
  }
  .toggle input:checked + .toggle-track { background: var(--blue-bright); border-color: var(--blue-bright); }
  .toggle-thumb {
    width: 18px; height: 18px;
    background: #fff;
    border-radius: 50%;
    transition: transform 0.2s;
    box-shadow: 0 1px 4px rgba(0,0,0,0.3);
  }
  .toggle input:checked + .toggle-track .toggle-thumb { transform: translateX(18px); }

  /* API INTEGRATIONS */
  .api-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-top: 4px;
  }
  .api-card {
    background: rgba(10,22,40,0.6);
    border: 1px solid var(--border);
    border-radius: 11px;
    padding: 16px;
  }
  .api-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
  }
  .api-name {
    font-family: 'Syne', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: var(--white);
  }
  .api-version {
    background: rgba(59,130,246,0.15);
    border: 1px solid rgba(59,130,246,0.3);
    color: var(--accent);
    font-size: 10px;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 5px;
    font-family: 'Syne', sans-serif;
  }
  .api-version.beta {
    background: rgba(245,158,11,0.15);
    border-color: rgba(245,158,11,0.3);
    color: var(--gold);
  }
  .api-desc { font-size: 12px; color: var(--text-muted); margin-bottom: 12px; line-height: 1.5; }
  .api-status {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 500;
  }
  .status-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--success);
    box-shadow: 0 0 6px var(--success);
    flex-shrink: 0;
  }
  .status-dot.suspended { background: var(--text-dim); box-shadow: none; }
  .status-connected { color: var(--success); }
  .status-suspended { color: var(--text-dim); }

  .section-badge {
    background: rgba(16,185,129,0.15);
    border: 1px solid rgba(16,185,129,0.3);
    color: var(--success);
    font-size: 10px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 6px;
    font-family: 'Syne', sans-serif;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .section-badge::before {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--success);
    box-shadow: 0 0 6px var(--success);
  }

  /* FOOTER */
  .footer {
    text-align: center;
    padding: 20px;
    font-size: 12px;
    color: var(--text-dim);
    border-top: 1px solid var(--border);
    margin-top: 10px;
  }

  /* ANIMATIONS */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .card { animation: fadeUp 0.35s ease both; }
  .card:nth-child(1) { animation-delay: 0.05s; }
  .card:nth-child(2) { animation-delay: 0.10s; }
  .card:nth-child(3) { animation-delay: 0.15s; }

  /* NOTIFICATION DOT */
  .notif-dot {
    width: 8px; height: 8px;
    background: var(--danger);
    border-radius: 50%;
    position: absolute;
    top: 6px; right: 6px;
    box-shadow: 0 0 5px var(--danger);
  }
  .icon-btn { position: relative; }

  @media (max-width: 900px) {
    .grid-2, .grid-3, .api-grid { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-brand">
    <h2>Administration</h2>
    <span>Infrastructure Routière</span>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-item" onclick="setActive(this)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Tableau de bord
    </div>
    <div class="nav-item" onclick="setActive(this)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Agents
    </div>
    <div class="nav-item" onclick="setActive(this)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
      Gares de péage
    </div>
    <div class="nav-item" onclick="setActive(this)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
      Paiements
    </div>
    <div class="nav-item" onclick="setActive(this)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
      Interventions
    </div>
    <div class="nav-item" onclick="setActive(this)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/></svg>
      Rapports
    </div>
    <div class="nav-item active" onclick="setActive(this)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07M8.46 8.46a5 5 0 0 0 0 7.07"/></svg>
      Paramètres
    </div>
  </nav>
  <div class="sidebar-footer">
    <button class="btn-new">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Nouvelle Intervention
    </button>
  </div>
</aside>

<!-- TOPBAR -->
<header class="topbar">
  <div class="topbar-title">Système de Gestion de Péage</div>
  <div class="topbar-right">
    <div class="search-bar">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#8fa3c8" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" placeholder="Rechercher...">
    </div>
    <div class="topbar-icons">
      <div class="icon-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <div class="notif-dot"></div>
      </div>
      <div class="icon-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      </div>
      <div class="icon-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07M8.46 8.46a5 5 0 0 0 0 7.07"/></svg>
      </div>
    </div>
    <div class="topbar-user">
      <div class="user-info">
        <strong>Admin Principal</strong>
        <span>Super Utilisateur</span>
      </div>
      <div class="user-avatar">JD</div>
    </div>
  </div>
</header>

<!-- MAIN -->
<main class="main">
  <div class="page-header">
    <h1>Paramètres du Système</h1>
    <p>Configuration de l'infrastructure et gestion des accès.</p>
    <div class="page-actions">
      <button class="btn btn-outline">Réinitialiser</button>
      <button class="btn btn-primary">Enregistrer les modifications</button>
    </div>
  </div>

  <!-- ROW 1: Profile + Tarifs -->
  <div class="grid-2">

    <!-- Profile -->
    <div class="card profile-card">
      <div class="profile-avatar">JD</div>
      <div class="profile-name">Jean-Claude Durand</div>
      <div class="profile-role">Administrateur Système Sénior</div>
      <div class="profile-fields">
        <div>
          <div class="field-label">Email Professionnel</div>
          <input class="field-input" type="email" value="jc.durand@autoroute-infra.fr">
        </div>
        <div>
          <div class="field-label">Zone de Responsabilité</div>
          <select class="field-select">
            <option>Toute l'infrastructure</option>
            <option>Zone Nord</option>
            <option>Zone Sud</option>
            <option>Zone Est</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Tarif -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          Grille Tarifaire par Catégorie
        </div>
        <div class="add-link" onclick="addTarifRow()">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Ajouter une catégorie
        </div>
      </div>
      <table class="tarif-table">
        <thead>
          <tr>
            <th>Catégorie de Véhicule</th>
            <th>Description</th>
            <th>Tarif Base (€)</th>
            <th>Heures de Pointe (€)</th>
            <th style="text-align:center">Actions</th>
          </tr>
        </thead>
        <tbody id="tarifBody">
          <tr class="tarif-row">
            <td><span class="classe-badge">Classe 1</span></td>
            <td><span class="tarif-desc">Véhicules légers (VP)</span></td>
            <td><input class="tarif-input" type="number" value="4.20" step="0.10"></td>
            <td><input class="tarif-input" type="number" value="5.50" step="0.10"></td>
            <td style="text-align:center"><button class="edit-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button></td>
          </tr>
          <tr class="tarif-row">
            <td><span class="classe-badge">Classe 2</span></td>
            <td><span class="tarif-desc">Véhicules intermédiaires</span></td>
            <td><input class="tarif-input" type="number" value="6.80" step="0.10"></td>
            <td><input class="tarif-input" type="number" value="8.20" step="0.10"></td>
            <td style="text-align:center"><button class="edit-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button></td>
          </tr>
          <tr class="tarif-row">
            <td><span class="classe-badge">Classe 3</span></td>
            <td><span class="tarif-desc">Poids lourds (2 essieux)</span></td>
            <td><input class="tarif-input" type="number" value="12.50" step="0.10"></td>
            <td><input class="tarif-input" type="number" value="15.00" step="0.10"></td>
            <td style="text-align:center"><button class="edit-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button></td>
          </tr>
          <tr class="tarif-row">
            <td><span class="classe-badge">Classe 4</span></td>
            <td><span class="tarif-desc">Poids lourds (+ de 2 essieux)</span></td>
            <td><input class="tarif-input" type="number" value="18.90" step="0.10"></td>
            <td><input class="tarif-input" type="number" value="22.50" step="0.10"></td>
            <td style="text-align:center"><button class="edit-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ROW 2: Roles + Alerts -->
  <div class="grid-2">

    <!-- Roles -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Rôles &amp; Permissions
        </div>
      </div>
      <div class="role-item">
        <div>
          <div class="role-name">Super Administrateur</div>
          <div class="role-desc">Accès complet à tous les modules.</div>
        </div>
        <span class="badge-default">DÉFAUT</span>
      </div>
      <div class="role-item">
        <div>
          <div class="role-name">Agent de Garez</div>
          <div class="role-desc">Consultation et gestion des passages locaux.</div>
        </div>
        <button class="settings-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
        </button>
      </div>
      <div class="role-item">
        <div>
          <div class="role-name">Analyste Financier</div>
          <div class="role-desc">Rapports d'audit et exportations de données.</div>
        </div>
        <button class="settings-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
        </button>
      </div>
      <button class="btn-manage">Gérer les groupes d'utilisateurs</button>
    </div>

    <!-- Alerts -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          Alertes &amp; Notifications
        </div>
      </div>
      <div class="alert-item">
        <div>
          <div class="alert-name">Détection de fraude</div>
          <div class="alert-desc">Alerte immédiate en cas de plaque masquée.</div>
        </div>
        <label class="toggle">
          <input type="checkbox" checked onchange="toggleAlert(this)">
          <div class="toggle-track"><div class="toggle-thumb"></div></div>
        </label>
      </div>
      <div class="alert-item">
        <div>
          <div class="alert-name">Maintenance préventive</div>
          <div class="alert-desc">Rapport hebdomadaire sur l'état des barrières.</div>
        </div>
        <label class="toggle">
          <input type="checkbox" checked onchange="toggleAlert(this)">
          <div class="toggle-track"><div class="toggle-thumb"></div></div>
        </label>
      </div>
      <div class="alert-item">
        <div>
          <div class="alert-name">Seuil de transaction</div>
          <div class="alert-desc">Notifier si le volume dépasse 10k/heure.</div>
        </div>
        <label class="toggle">
          <input type="checkbox" onchange="toggleAlert(this)">
          <div class="toggle-track"><div class="toggle-thumb"></div></div>
        </label>
      </div>
    </div>
  </div>

  <!-- ROW 3: API Integrations -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        Intégrations API &amp; Connecteurs Externes
      </div>
      <span class="section-badge">Services Opérationnels</span>
    </div>
    <div class="api-grid">
      <div class="api-card">
        <div class="api-card-header">
          <span class="api-name">Système Bancaire</span>
          <span class="api-version">V1.4.2</span>
        </div>
        <div class="api-desc">Synchronisation en temps réel des transactions CB.</div>
        <div class="api-status">
          <div class="status-dot"></div>
          <span class="status-connected">Connecté</span>
        </div>
      </div>
      <div class="api-card">
        <div class="api-card-header">
          <span class="api-name">Base CGN (Immatriculation)</span>
          <span class="api-version">V3.0.0</span>
        </div>
        <div class="api-desc">Validation automatique des plaques d'immatriculation.</div>
        <div class="api-status">
          <div class="status-dot"></div>
          <span class="status-connected">Connecté</span>
        </div>
      </div>
      <div class="api-card">
        <div class="api-card-header">
          <span class="api-name">Système GPS Transport</span>
          <span class="api-version beta">BETA</span>
        </div>
        <div class="api-desc">Optimisation des flux de trafic via données GPS.</div>
        <div class="api-status">
          <div class="status-dot suspended"></div>
          <span class="status-suspended">Suspendu</span>
        </div>
      </div>
    </div>
  </div>

  <div class="footer">
    © 2024 Système de Gestion de Péage - Infrastructure Nationale. Version Logicielle 4.12.5-STABLE
  </div>
</main>

<script>
  function setActive(el) {
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    el.classList.add('active');
  }

  let classeCount = 4;
  function addTarifRow() {
    classeCount++;
    const tbody = document.getElementById('tarifBody');
    const tr = document.createElement('tr');
    tr.className = 'tarif-row';
    tr.style.animation = 'fadeUp 0.3s ease both';
    tr.innerHTML = `
      <td><span class="classe-badge">Classe ${classeCount}</span></td>
      <td><input class="tarif-input" style="width:120px" type="text" placeholder="Description" value=""></td>
      <td><input class="tarif-input" type="number" value="0.00" step="0.10"></td>
      <td><input class="tarif-input" type="number" value="0.00" step="0.10"></td>
      <td style="text-align:center">
        <button class="edit-btn" onclick="this.closest('tr').remove(); classeCount--">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
        </button>
      </td>`;
    tbody.appendChild(tr);
  }

  function toggleAlert(checkbox) {
    // visual feedback already handled by CSS toggle
  }

  // Save button
  document.querySelector('.btn-primary').addEventListener('click', function() {
    this.textContent = '✓ Enregistré';
    this.style.background = '#10b981';
    setTimeout(() => {
      this.textContent = 'Enregistrer les modifications';
      this.style.background = '';
    }, 2000);
  });
</script>
</body>
</html>