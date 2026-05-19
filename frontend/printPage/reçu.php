<?php
session_start();
    require './../../backend/config.php';

    $immat = htmlspecialchars($_SESSION['immat'] ?? '', ENT_QUOTES, 'UTF-8');
    $marque = htmlspecialchars($_SESSION['marque'] ?? '', ENT_QUOTES, 'UTF-8');
    $montant = htmlspecialchars($_SESSION['amount'] ?? '', ENT_QUOTES, 'UTF-8');
    $agent = htmlspecialchars($_SESSION['Agent'] ?? '', ENT_QUOTES, 'UTF-8');
    $guichet = htmlspecialchars($_SESSION['Guichet'] ?? '', ENT_QUOTES, 'UTF-8');
    $date = date("Y-m-d H:i:s", time());
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu HKB — <?= $immat ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
 
        body {
            font-family: 'Courier New', monospace;
            background: #f1f5f9;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 30px 16px;
            min-height: 100vh;
        }
 
        .recu {
            width: 320px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        }
 
        .recu-header {
            background: #0f172a;
            padding: 22px 20px 18px;
            text-align: center;
        }
        .recu-logo {
            font-family: Arial, sans-serif;
            font-size: 30px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #f8fafc;
        }
        .recu-logo span { color: #38bdf8; }
        .recu-tagline {
            font-size: 10px;
            letter-spacing: 2px;
            color: #94a3b8;
            font-family: Arial, sans-serif;
            margin-top: 4px;
            text-transform: uppercase;
        }
        .recu-badge {
            display: inline-block;
            margin-top: 10px;
            background: rgba(56,189,248,0.15);
            border: 1px solid rgba(56,189,248,0.4);
            border-radius: 99px;
            padding: 3px 14px;
            font-size: 10px;
            color: #38bdf8;
            font-family: Arial, sans-serif;
            letter-spacing: 1px;
        }
 
        .dashed { border: none; border-top: 1px dashed #cbd5e1; margin: 0; }
 
        .recu-body { padding: 18px 20px; }
 
        .recu-section-title {
            font-size: 10px;
            letter-spacing: 1.5px;
            color: #64748b;
            text-align: center;
            margin-bottom: 14px;
            font-family: Arial, sans-serif;
            text-transform: uppercase;
        }
 
        .recu-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 6px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 12px;
        }
        .recu-row:last-of-type { border-bottom: none; }
        .recu-key { color: #64748b; }
        .recu-val { font-weight: bold; text-align: right; max-width: 55%; word-break: break-all; color: #0f172a; }
 
        .recu-amount-block {
            text-align: center;
            margin: 16px 0 4px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px;
        }
        .recu-amount-label {
            font-size: 10px;
            color: #94a3b8;
            font-family: Arial, sans-serif;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .recu-amount-val {
            font-size: 30px;
            font-weight: bold;
            color: #0f172a;
            font-family: Arial, sans-serif;
            line-height: 1.1;
        }
        .recu-amount-cur {
            font-size: 14px;
            color: #64748b;
            font-weight: normal;
        }
 
        .recu-footer {
            background: #0f172a;
            padding: 16px 20px;
            text-align: center;
        }
        .recu-footer-thank {
            font-size: 13px;
            color: #f8fafc;
            font-family: Arial, sans-serif;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .recu-footer-text {
            font-size: 10px;
            color: #94a3b8;
            font-family: Arial, sans-serif;
            letter-spacing: 0.5px;
        }
        .recu-dots {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 10px;
        }
        .recu-dot { width: 4px; height: 4px; border-radius: 50%; background: #334155; }
        .recu-dot.on { background: #38bdf8; }
 
        @media print {
            body { background: none; padding: 0; min-height: unset; }
            .recu { box-shadow: none; border-radius: 0; width: 100%; }
        }
    </style>
</head>
<body onload="window.print()">
 
    <div class="recu">
 
        <div class="recu-header">
            <div class="recu-logo">H<span>K</span>B</div>
            <div class="recu-tagline">Système de Gestion du Péage</div>
            <div class="recu-badge">Reçu d'encaissement</div>
        </div>
 
        <hr class="dashed">
 
        <div class="recu-body">
            <div class="recu-section-title">Détails du passage</div>
 
            <div class="recu-row">
                <span class="recu-key">Immatriculation</span>
                <span class="recu-val"><?= $immat ?: '—' ?></span>
            </div>
            <div class="recu-row">
                <span class="recu-key">Marque</span>
                <span class="recu-val"><?= $marque ?: '—' ?></span>
            </div>
            <div class="recu-row">
                <span class="recu-key">Guichet</span>
                <span class="recu-val"><?= $guichet ?: '—' ?></span>
            </div>
            <div class="recu-row">
                <span class="recu-key">Agent</span>
                <span class="recu-val"><?= $agent ?: '—' ?></span>
            </div>
            <div class="recu-row">
                <span class="recu-key">Date passage</span>
                <span class="recu-val"><?= $date ?: '—' ?></span>
            </div>
            <div class="recu-row">
                <span class="recu-key">Imprimé le</span>
                <span class="recu-val"><?= $date ?></span>
            </div>
 
            <div class="recu-amount-block">
                <div class="recu-amount-label">Montant encaissé</div>
                <div class="recu-amount-val">
                    <?= number_format((int)$montant, 0, ',', ' ') ?>
                    <span class="recu-amount-cur">FCFA</span>
                </div>
            </div>
        </div>
 
        <hr class="dashed">
 
        <div class="recu-footer">
            <div class="recu-footer-thank">Merci pour votre passage !</div>
            <div class="recu-footer-text">Bonne route — HKB Infrastructure Routière</div>
            <div class="recu-dots">
                <div class="recu-dot on"></div>
                <div class="recu-dot on"></div>
                <div class="recu-dot"></div>
                <div class="recu-dot on"></div>
                <div class="recu-dot"></div>
                <div class="recu-dot on"></div>
                <div class="recu-dot on"></div>
            </div>
        </div>
 
    </div>
 
</body>
</html>