{{-- resources/views/interfaces/check_qr_code.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contrôle QR Code - Accès invités</title>

    {{-- Google Fonts & Icônes --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">

    {{-- jQuery (pour AJAX) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* ============================================================
           STYLE PREMIUM – FOND CLAIR, MÊME ALIGNEMENT
           ============================================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .container {
            max-width: 820px;
            width: 100%;
            margin: 0 auto;
        }

        :root {
            --bleu-nuit: #0a192f;
            --shadow-premium: 0 20px 40px -12px rgba(0, 0, 0, 0.12);
            --shadow-light: 0 4px 16px rgba(0, 0, 0, 0.06);
            --border-radius-xl: 24px;
            --border-radius-lg: 16px;
            --rouge-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --vert-gradient: linear-gradient(135deg, #10b981, #059669);
            --bleu-gradient: linear-gradient(135deg, #3B82F6, #2563eb);
            --jaune-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --gris-gradient: linear-gradient(135deg, #6c757d, #495057);
        }

        .card-qr {
            background: #ffffff;
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-premium);
            padding: 2rem 2.5rem 2.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(2px);
        }

        .card-qr:hover {
            transform: translateY(-3px);
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.15);
        }

        .card-qr::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #3B82F6, #10b981, #f59e0b, #ef4444);
            background-size: 300% 100%;
            animation: gradientMove 5s ease-in-out infinite alternate;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 0%; }
            100% { background-position: 100% 0%; }
        }

        .card-qr h1 {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--bleu-nuit);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-qr h1 i {
            color: #3B82F6;
            font-size: 2rem;
        }

        .card-qr .subtitle {
            color: #64748b;
            font-weight: 500;
            margin-bottom: 1.8rem;
            font-size: 0.95rem;
            border-left: 4px solid #3B82F6;
            padding-left: 16px;
            background: #f8fafc;
            padding: 0.6rem 1rem;
            border-radius: 0 12px 12px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.7rem 0;
            border-bottom: 1px solid #eef2f6;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            border-radius: 8px;
        }

        .info-row:hover {
            background: #f8fafc;
            padding-left: 8px;
            padding-right: 8px;
            border-bottom-color: #d0d9e8;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row .label {
            color: #64748b;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-row .label i {
            width: 22px;
            text-align: center;
            font-size: 1.2rem;
            transition: transform 0.2s;
        }

        .info-row:hover .label i {
            transform: scale(1.1);
        }

        .info-row .label i.text-blue { color: #3B82F6; }
        .info-row .label i.text-green { color: #10b981; }
        .info-row .label i.text-red { color: #ef4444; }
        .info-row .label i.text-yellow { color: #f59e0b; }
        .info-row .label i.text-purple { color: #8b5cf6; }
        .info-row .label i.text-pink { color: #ec4899; }
        .info-row .label i.text-orange { color: #f97316; }

        .info-row .value {
            color: #0a192f;
            font-weight: 600;
            text-align: right;
            word-break: break-word;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 16px 5px 12px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.75rem;
            color: #fff;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.2s ease;
        }

        .badge-status:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .badge-success { background: var(--vert-gradient); }
        .badge-danger { background: var(--rouge-gradient); }
        .badge-warning { background: var(--jaune-gradient); }
        .badge-secondary { background: var(--gris-gradient); }

        .alert {
            border-radius: var(--border-radius-lg);
            padding: 1rem 1.5rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 5px solid transparent;
            box-shadow: var(--shadow-light);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left-color: #ef4444;
        }
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border-left-color: #10b981;
        }
        .alert-info {
            background: #eff6ff;
            color: #1e3a8a;
            border-left-color: #3b82f6;
        }

        .alert i {
            font-size: 1.5rem;
        }

        .actions {
            margin-top: 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }

        .btn {
            border: none;
            padding: 14px 34px;
            border-radius: 60px !important;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            min-width: 180px;
            justify-content: center;
            background: #e2e8f0;
            color: #1e293b;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        }

        .btn-confirm {
            background: var(--vert-gradient) !important;
            color: white !important;
        }

        .btn-confirm:hover:not(:disabled) {
            box-shadow: 0 12px 28px rgba(16, 185, 129, 0.3);
        }

        .btn-confirm:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
            filter: grayscale(0.15);
        }

        .btn-already {
            background: #6c757d !important;
            color: white !important;
            cursor: not-allowed;
        }

        .btn-back-secondary {
            background: #64748b !important;
            color: white !important;
        }

        .btn-back-secondary:hover {
            background: #475569 !important;
            box-shadow: 0 12px 28px rgba(100, 116, 139, 0.3);
        }

        .msg-container {
            margin-top: 1.5rem;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }

        .loading-pulse {
            animation: pulse 1s ease-in-out infinite;
        }

        /* ========== MODALES CORRIGÉES ========== */
        .modal-overlay {
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(6px);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-overlay .modal-box {
            background: white;
            padding: 2rem;
            border-radius: 24px;
            max-width: 420px;
            width: 90%;
            margin: auto;
            text-align: center;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.3);
            animation: modalPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .modal-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--bleu-gradient);
        }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.92) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-box h3 {
            font-size: 2.6rem;
            margin-bottom: 0.5rem;
        }

        .modal-box p {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 1rem;
            color: #0a192f;
            line-height: 1.5;
        }

        .modal-box .input-group {
            margin: 1.2rem 0;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
        }

        .modal-box .input-group label {
            font-weight: 600;
            color: #0a192f;
            font-size: 0.9rem;
            text-align: left;
        }

        .modal-box .input-group input {
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            color: #0a192f;
            transition: border-color 0.3s ease;
            outline: none;
            width: 100%;
            letter-spacing: 4px;
        }

        .modal-box .input-group input:focus {
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .modal-box .btn {
            min-width: 100px;
            padding: 10px 24px;
            font-size: 0.95rem;
        }

        .modal-box .btn-yes {
            background: var(--vert-gradient) !important;
            color: white !important;
        }

        .modal-box .btn-yes:hover {
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .modal-box .btn-no {
            background: #e2e8f0 !important;
            color: #1e293b !important;
        }

        .modal-box .btn-no:hover {
            background: #cbd5e1 !important;
        }

        .modal-box .btn-danger {
            background: var(--rouge-gradient) !important;
            color: white !important;
        }

        .modal-box .btn-danger:hover {
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
        }

        .modal-box .password-error {
            color: #ef4444;
            font-size: 0.9rem;
            font-weight: 500;
            margin-top: 0.5rem;
            display: none;
        }

        .modal-box .password-error.show {
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body { padding: 1rem; }
            .card-qr { padding: 1.5rem; }
            .info-row {
                flex-direction: column;
                gap: 4px;
                align-items: flex-start !important;
            }
            .info-row .value {
                text-align: left;
                width: 100%;
            }
            .actions {
                flex-direction: column;
                align-items: stretch;
            }
            .btn {
                min-width: unset;
                width: 100%;
            }
            .card-qr h1 { font-size: 1.5rem; }
            .modal-box { padding: 1.5rem; }
        }

        @media (max-width: 480px) {
            .card-qr { padding: 1rem; }
            .card-qr h1 { font-size: 1.3rem; }
            .badge-status { font-size: 0.7rem; padding: 4px 12px; }
            .info-row { font-size: 0.85rem; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card-qr">

        @if(isset($error))
            {{-- ===== ERREUR ===== --}}
            <h1><i class="zmdi zmdi-alert-circle"></i> Erreur</h1>
            <div class="alert alert-danger">
                <i class="zmdi zmdi-info"></i>
                <span>{{ $error }}</span>
                @if(isset($code))
                    <br><span style="font-weight:400; margin-top:6px; display:inline-block;">
                        Code scanné : <strong>{{ $code }}</strong>
                    </span>
                @endif
            </div>
            <div class="actions">
                <button onclick="window.location.reload();" class="btn btn-back-secondary">
                    <i class="zmdi zmdi-refresh"></i> Réessayer
                </button>
            </div>

        @elseif(isset($invite))
            {{-- ===== INVITÉ TROUVÉ ===== --}}
            <h1><i class="zmdi zmdi-account-circle"></i> Invité trouvé</h1>
            <div class="subtitle">
                <i class="zmdi zmdi-check-circle" style="color:#10b981;"></i>
                Vérifiez les informations et confirmez l’entrée
            </div>

            @php
                $dejaEntre = ($invite->dans_la_salle ?? 0) == 1;
                $reponse = $invite->reponse ?? 1;
                $presence = $invite->presence ?? '';
                $statutLabel = '';
                $statutClasse = '';
                if ($reponse == 1) {
                    $statutLabel = 'En attente';
                    $statutClasse = 'badge-warning';
                } elseif ($reponse == 2) {
                    $statutLabel = 'Confirmé';
                    $statutClasse = 'badge-success';
                } elseif ($reponse == 3) {
                    $statutLabel = 'Refusé';
                    $statutClasse = 'badge-danger';
                }

                $peutConfirmer = ($presence == 'oui' && $reponse == 2 && !$dejaEntre && $reponse != 3);
                $raisonErreur = '';
                if (!$peutConfirmer) {
                    if ($dejaEntre) {
                        $raisonErreur = 'Cet invité est déjà dans la salle.';
                    } elseif ($reponse == 3) {
                        $raisonErreur = 'L\'invitation a été refusée.';
                    } elseif ($presence != 'oui') {
                        $raisonErreur = 'La présence de l\'invité n\'est pas confirmée (Présence : ' . ucfirst($presence) . ').';
                    } elseif ($reponse != 2) {
                        $raisonErreur = 'Le statut de l\'invitation n\'est pas "Confirmé" (Statut : ' . $statutLabel . ').';
                    }
                }
            @endphp

            {{-- Alertes spécifiques --}}
            @if($dejaEntre)
                <div class="alert alert-success">
                    <i class="zmdi zmdi-check-circle"></i>
                    <span>Cet invité est <strong>déjà dans la salle</strong> (entrée enregistrée).</span>
                </div>
            @endif

            @if($reponse == 3)
                <div class="alert alert-danger">
                    <i class="zmdi zmdi-close-circle"></i>
                    <span>Cette invitation a été <strong>refusée</strong>. L’accès est strictement bloqué.</span>
                </div>
            @endif

            {{-- Détails --}}
            <div class="info-row">
                <span class="label"><i class="zmdi zmdi-account text-blue"></i> Nom complet</span>
                <span class="value">{{ $invite->name }}</span>
            </div>
            <div class="info-row">
                <span class="label"><i class="zmdi zmdi-phone text-green"></i> Téléphone</span>
                <span class="value">{{ $invite->phone }}</span>
            </div>
            @if(!empty($invite->email))
            <div class="info-row">
                <span class="label"><i class="zmdi zmdi-email text-purple"></i> Email</span>
                <span class="value">{{ $invite->email }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="label"><i class="zmdi zmdi-check-circle text-green"></i> Présence</span>
                <span class="value">
                    @if($presence == 'oui')
                        <span class="badge-status badge-success"><i class="zmdi zmdi-check-circle"></i> Oui</span>
                    @elseif($presence == 'non')
                        <span class="badge-status badge-danger"><i class="zmdi zmdi-close"></i> Non</span>
                    @else
                        <span class="badge-status badge-secondary"><i class="zmdi zmdi-help"></i> Indéfini</span>
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="label"><i class="zmdi zmdi-view-list text-yellow"></i> Table</span>
                <span class="value">
                    @php
                        $nomTable = 'Aucune';
                        if ($invite->table_id != 0) {
                            $table = \App\Models\Tables::find($invite->table_id);
                            $nomTable = $table->nom ?? 'Aucune';
                        }
                    @endphp
                    {{ $nomTable }}
                </span>
            </div>
            <div class="info-row">
                <span class="label"><i class="zmdi zmdi-link text-pink"></i> Relation</span>
                <span class="value">{{ $invite->relation }}</span>
            </div>
            @if(!empty($invite->relation_autre))
            <div class="info-row">
                <span class="label"><i class="zmdi zmdi-edit text-orange"></i> Relation autre</span>
                <span class="value">{{ $invite->relation_autre }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="label"><i class="zmdi zmdi-info text-blue"></i> Statut invitation</span>
                <span class="value">
                    <span class="badge-status {{ $statutClasse }}">
                        <i class="zmdi {{ $statutClasse == 'badge-warning' ? 'zmdi-time' : ($statutClasse == 'badge-success' ? 'zmdi-check-circle' : 'zmdi-close-circle') }}"></i>
                        {{ $statutLabel }}
                    </span>
                </span>
            </div>
            <div class="info-row" id="salleRow">
                <span class="label"><i class="zmdi zmdi-home text-green"></i> Dans la salle</span>
                <span class="value" id="salleBadge">
                    @if($dejaEntre)
                        <span class="badge-status badge-success"><i class="zmdi zmdi-check-circle"></i> Oui</span>
                    @else
                        <span class="badge-status badge-danger"><i class="zmdi zmdi-close"></i> Non</span>
                    @endif
                </span>
            </div>
            {{-- La ligne "Code unique" a été supprimée sur demande --}}

            {{-- Bouton "Confirmer l'entrée" --}}
            <div class="actions">
                <button id="btnConfirmEntry" class="btn btn-confirm" data-id="{{ $invite->id }}" 
                    @if($dejaEntre) disabled style="background:#6c757d !important; cursor:not-allowed;" @endif>
                    <i class="zmdi zmdi-check-circle"></i> 
                    @if($dejaEntre) Déjà dans la salle @else Confirmer l’entrée @endif
                </button>
            </div>

            <div id="msgContainer" class="msg-container"></div>

        @else
            {{-- ===== AUCUNE DONNÉE ===== --}}
            <h1><i class="zmdi zmdi-alert-triangle" style="color:#f59e0b;"></i> Aucune donnée</h1>
            <div class="alert alert-info">
                <i class="zmdi zmdi-info"></i>
                <span>Aucun code QR n’a été scanné ou l’invité est introuvable.</span>
            </div>
            <div class="actions">
                <button onclick="window.location.reload();" class="btn btn-back-secondary">
                    <i class="zmdi zmdi-refresh"></i> Réessayer
                </button>
            </div>
        @endif

        {{-- ===== MODALE DE CONFIRMATION (1ère) ===== --}}
        <div id="confirmModal" class="modal-overlay">
            <div class="modal-box">
                <h3><i class="zmdi zmdi-alert-triangle" style="color:#f59e0b;"></i></h3>
                <p>Voulez-vous vraiment confirmer l'entrée de cet invité ?</p>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <button id="confirmYes" class="btn btn-yes"><i class="zmdi zmdi-check-circle"></i> Oui</button>
                    <button id="confirmNo" class="btn btn-no"><i class="zmdi zmdi-close"></i> Non</button>
                </div>
            </div>
        </div>

        {{-- ===== MODALE DE MOT DE PASSE (2ème) ===== --}}
        <div id="passwordModal" class="modal-overlay">
            <div class="modal-box">
                <h3><i class="zmdi zmdi-lock" style="color:#3B82F6;"></i></h3>
                <p>Saisissez le mot de passe de sécurité</p>
                <div class="input-group">
                    <label for="passwordInput">Mot de passe</label>
                    <input type="password" id="passwordInput" placeholder="•••••" maxlength="10" autofocus>
                    <div id="passwordError" class="password-error"><i class="zmdi zmdi-close-circle"></i> Mot de passe incorrect</div>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <button id="passwordValidate" class="btn btn-yes"><i class="zmdi zmdi-check-circle"></i> Valider</button>
                    <button id="passwordCancel" class="btn btn-no"><i class="zmdi zmdi-close"></i> Annuler</button>
                </div>
            </div>
        </div>

        {{-- ===== MODALE D'ERREUR (conditions non remplies) ===== --}}
        <div id="errorModal" class="modal-overlay">
            <div class="modal-box">
                <h3><i class="zmdi zmdi-close-circle" style="color:#ef4444;"></i></h3>
                <p style="color:#991b1b;">Impossible de confirmer l'entrée.</p>
                <p style="font-weight:400; font-size:0.95rem; color:#4b5563;" id="errorReason"></p>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 1rem;">
                    <button id="errorOk" class="btn btn-danger"><i class="zmdi zmdi-check-circle"></i> OK</button>
                </div>
            </div>
        </div>

        {{-- ===== NOUVELLE MODALE DE SUCCÈS ===== --}}
        <div id="successModal" class="modal-overlay">
            <div class="modal-box">
                <h3><i class="zmdi zmdi-check-circle" style="color:#10b981;"></i></h3>
                <p style="color:#065f46;">Entrée confirmée avec succès !</p>
                <p style="font-weight:400; font-size:0.95rem; color:#4b5563;" id="successMessage"></p>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 1rem;">
                    <button id="successOk" class="btn btn-yes"><i class="zmdi zmdi-check-circle"></i> OK</button>
                </div>
            </div>
        </div>

    </div>{{-- fin card-qr --}}
</div>

<script>
$(document).ready(function() {

    var peutConfirmer = {{ $peutConfirmer ?? false ? 'true' : 'false' }};
    var raisonErreur = @json($raisonErreur ?? '');

    var $confirmModal = $('#confirmModal');
    var $passwordModal = $('#passwordModal');
    var $errorModal = $('#errorModal');
    var $successModal = $('#successModal');
    var $btnConfirm = $('#btnConfirmEntry');

    var $passwordInput = $('#passwordInput');
    var $passwordError = $('#passwordError');
    var $passwordValidate = $('#passwordValidate');
    var $passwordCancel = $('#passwordCancel');

    // ---- Fonction pour afficher un message ----
    function showMessage(type, html) {
        var $container = $('#msgContainer');
        $container.html('<div class="alert alert-' + type + '">' + html + '</div>');
        clearTimeout(window.msgTimeout);
        window.msgTimeout = setTimeout(function() {
            $container.fadeOut(500, function() { $(this).html('').show(); });
        }, 8000);
    }

    // ---- Mise à jour dynamique après confirmation ----
    function updateUIAfterConfirmation() {
        // Mettre à jour le badge "Dans la salle"
        $('#salleBadge').html('<span class="badge-status badge-success"><i class="zmdi zmdi-check-circle"></i> Oui</span>');

        // Désactiver le bouton et changer son texte
        $btnConfirm.prop('disabled', true)
                   .removeClass('btn-confirm')
                   .addClass('btn-already')
                   .html('<i class="zmdi zmdi-check-circle"></i> Déjà dans la salle');

        // Mettre à jour les variables pour les clics suivants
        peutConfirmer = false;
        raisonErreur = 'Cet invité est déjà dans la salle.';

        // Ajouter l'alerte "déjà dans la salle" si elle n'existe pas
        if ($('.alert-success').length === 0) {
            $('.subtitle').after(
                '<div class="alert alert-success">' +
                '<i class="zmdi zmdi-check-circle"></i>' +
                '<span>Cet invité est <strong>déjà dans la salle</strong> (entrée enregistrée).</span>' +
                '</div>'
            );
        }
    }

    // ---- Gestion du clic sur le bouton principal ----
    $btnConfirm.on('click', function(e) {
        e.preventDefault();

        if ($(this).prop('disabled')) {
            return;
        }

        if (!peutConfirmer) {
            $('#errorReason').text(raisonErreur || 'Condition(s) non remplies.');
            $errorModal.addClass('active');
            return;
        }

        $confirmModal.addClass('active');
    });

    // ---- Fermeture modale d'erreur ----
    $('#errorOk').on('click', function() {
        $errorModal.removeClass('active');
    });
    $(window).on('click', function(e) {
        if ($(e.target).is($errorModal)) {
            $errorModal.removeClass('active');
        }
    });

    // ---- Fermeture modale de confirmation ----
    $('#confirmNo').on('click', function() {
        $confirmModal.removeClass('active');
    });
    $(window).on('click', function(e) {
        if ($(e.target).is($confirmModal)) {
            $confirmModal.removeClass('active');
        }
    });

    // ---- Fermeture modale de succès ----
    $('#successOk').on('click', function() {
        $successModal.removeClass('active');
    });
    $(window).on('click', function(e) {
        if ($(e.target).is($successModal)) {
            $successModal.removeClass('active');
        }
    });

    // ---- Passage à la modale de mot de passe ----
    $('#confirmYes').on('click', function() {
        $confirmModal.removeClass('active');
        $passwordInput.val('');
        $passwordError.removeClass('show');
        $passwordModal.addClass('active');
        setTimeout(function() { $passwordInput.focus(); }, 300);
    });

    // ---- Fermeture modale de mot de passe ----
    $passwordCancel.on('click', function() {
        $passwordModal.removeClass('active');
        $passwordError.removeClass('show');
    });
    $(window).on('click', function(e) {
        if ($(e.target).is($passwordModal)) {
            $passwordModal.removeClass('active');
            $passwordError.removeClass('show');
        }
    });

    // ---- Validation du mot de passe ----
    $passwordValidate.on('click', function() {
        var password = $passwordInput.val().trim();

        if (password === '00000') {
            $passwordModal.removeClass('active');
            $passwordError.removeClass('show');

            var $btn = $btnConfirm;
            var inviteId = $btn.data('id');

            $btn.prop('disabled', true)
               .html('<i class="zmdi zmdi-spinner zmdi-hc-spin loading-pulse"></i> Traitement...');

            $.ajax({
                url: '{{ url("/confirm_entree") }}',
                method: 'POST',
                data: {
                    id: inviteId,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Afficher le message d'alerte (existant)
                        showMessage('success', '<i class="zmdi zmdi-check-circle"></i> ' + response.message);
                        // Afficher la modale de succès
                        $('#successMessage').text(response.message || 'Entrée confirmée avec succès.');
                        $successModal.addClass('active');
                        // Mise à jour de l'interface
                        updateUIAfterConfirmation();
                    } else {
                        showMessage('danger', '<i class="zmdi zmdi-close-circle"></i> ' + response.message);
                        $btn.prop('disabled', false)
                           .removeClass('btn-already')
                           .addClass('btn-confirm')
                           .html('<i class="zmdi zmdi-check-circle"></i> Confirmer l’entrée');
                        if (response.message.includes('déjà') || response.message.includes('refusée')) {
                            peutConfirmer = false;
                            raisonErreur = response.message;
                            $btn.prop('disabled', true)
                               .removeClass('btn-confirm')
                               .addClass('btn-already')
                               .html('<i class="zmdi zmdi-check-circle"></i> ' + (response.message.includes('déjà') ? 'Déjà dans la salle' : 'Accès refusé'));
                        }
                    }
                },
                error: function(xhr) {
                    var msg = 'Erreur technique. Veuillez réessayer.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    showMessage('danger', '<i class="zmdi zmdi-alert-circle"></i> ' + msg);
                    $btn.prop('disabled', false)
                       .removeClass('btn-already')
                       .addClass('btn-confirm')
                       .html('<i class="zmdi zmdi-check-circle"></i> Confirmer l’entrée');
                }
            });

        } else {
            $passwordError.addClass('show');
            $passwordInput.val('').focus();
        }
    });

    // ---- Valider avec la touche "Entrée" ----
    $passwordInput.on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $passwordValidate.click();
        }
    });

});
</script>

</body>
</html>