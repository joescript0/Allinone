<?php

use App\Models\Groupes;
use App\Models\Writes;
use App\Models\User;
use App\Models\Activites;
use App\Models\Tables;
use Illuminate\Support\Facades\Auth;

?>

<!-- ===== STYLE POUR LE FORMULAIRE D'ÉDITION ===== -->
<style>
/* ============================================================
   VARIABLES ET STYLES DE BASE (extraits du design global)
   ============================================================ */
:root {
    --bleu-nuit: #0a192f;
    --bleu-nuit-gradient: linear-gradient(135deg, #0a192f, #1e3a5f);
    --bleu-secondaire-gradient: linear-gradient(135deg, #2c5282, #1a365d);
    --rouge-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
}

/* --- En-tête du bloc --- */
#bloc_3 h4 {
    font-weight: 700;
    border-left: 6px solid #e31b23;
    padding-left: 18px;
    margin-bottom: 16px;
    margin-top: 0;
    color: var(--bleu-nuit);
}
#bloc_3 h4 i.zmdi {
    background: var(--bleu-nuit-gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent !important;
}

/* --- Structure du formulaire --- */
#form_edit .row {
    display: flex;
    flex-wrap: wrap;
}
#form_edit .col-6 {
    margin-bottom: 0.8rem;
}
.form-group {
    width: 100%;
    margin-bottom: 0;
}
.form-group label {
    display: block;
    font-weight: 700;
    color: var(--bleu-nuit);
    margin-bottom: 4px;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.form-group label i {
    color: #e31b23;
    margin-right: 6px;
}

/* --- Champs de saisie --- */
.form-control,
input.form-control,
select.form-control,
textarea.form-control,
.input-mask {
    width: 100% !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important;
    padding: 8px 12px !important;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s;
    box-sizing: border-box;
    height: 38px !important;
    line-height: 1.4;
}
.form-control:focus,
select.form-control:focus,
textarea.form-control:focus {
    border-color: var(--bleu-nuit) !important;
    box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15) !important;
    transform: translateY(-1px);
}
select.form-control {
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23e31b23" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>');
    background-repeat: no-repeat;
    background-position: right 14px center;
}
.input-mask {
    font-family: monospace;
    background: #fff9ef !important;
}

/* --- Messages --- */
#edit_msg {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
    border: 0 !important;
    background: transparent !important;
    box-shadow: none !important;
    min-height: 0 !important;
    height: 0 !important;
    overflow: hidden !important;
}
#edit_msg:not(:empty) {
    display: inline-flex !important;
    visibility: visible !important;
    opacity: 1 !important;
    height: auto !important;
    margin-top: 16px !important;
    padding: 10px 18px !important;
    background: white !important;
    border-radius: 50px !important;
    box-shadow: var(--shadow-light) !important;
    gap: 10px;
    font-weight: 600;
    font-size: 0.8rem;
    animation: slideInMsg 0.3s ease-out;
}
#edit_msg.msg-success {
    background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important;
    color: #065f46 !important;
    border-left: 4px solid #10b981 !important;
}
#edit_msg.msg-error {
    background: linear-gradient(95deg, #fee2e2, #fecaca) !important;
    color: #991b1b !important;
    border-left: 4px solid #ef4444 !important;
}
#edit_msg.msg-info {
    background: linear-gradient(95deg, #dbeafe, #bfdbfe) !important;
    color: #1e3a8a !important;
    border-left: 4px solid #3b82f6 !important;
}
@keyframes slideInMsg {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* --- Boutons --- */
#edit_save,
#edit_annuler {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 6px 16px !important;
    font-weight: 600;
    font-size: 0.85rem;
    border-radius: 40px !important;
    transition: all 0.25s ease;
    border: none;
    cursor: pointer;
    box-shadow: var(--shadow-light);
    white-space: nowrap;
    line-height: 1.5;
}
#edit_save {
    background: var(--bleu-secondaire-gradient) !important;
    color: white !important;
}
#edit_save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(44, 82, 130, 0.3);
}
#edit_annuler {
    background: var(--rouge-gradient) !important;
    color: white !important;
}
#edit_annuler:hover {
    transform: translateY(-2px);
    background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
    box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3);
}

/* --- Responsive --- */
@media (max-width: 768px) {
    #form_edit .col-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    .form-group label {
        font-size: 0.65rem;
    }
    .form-control,
    input.form-control,
    select.form-control {
        height: 34px !important;
        font-size: 0.75rem;
    }
    #edit_save,
    #edit_annuler {
        padding: 4px 12px !important;
        font-size: 0.7rem;
    }
}
@media (max-width: 480px) {
    #bloc_3 h4 {
        font-size: 1.1rem;
    }
    #bloc_3 h4 i {
        font-size: 24px !important;
    }
    #edit_save,
    #edit_annuler {
        padding: 3px 8px !important;
        font-size: 0.65rem;
    }
}
</style>

<!-- ===== FORMULAIRE D'ÉDITION ===== -->
<h4>
        <i style="font-size: 40px;" class="zmdi zmdi-edit text-info"></i> Modifier
    </h4>
    <form id="form_edit" action="#" method="post" style="margin-bottom: 100px;">
        @csrf

        <!-- Champ caché ID -->
        <div style="display: none;">
            <input type="text" id="id" name="id" value="<?= $listesdesinvites->id ?>">
        </div>

        <!-- Ligne 1 : Nom et Email -->
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                        <i class="zmdi zmdi-account"></i> Nom <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="edit_nom" name="edit_nom"
                        class="form-control"
                        style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                        placeholder="Nom (Ex : Mgm congo)"
                        value="<?= htmlspecialchars($listesdesinvites->name) ?>">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                        <i class="zmdi zmdi-email"></i> E-mail
                    </label>
                    <input type="text" id="edit_email" name="edit_email"
                        class="form-control"
                        style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                        placeholder="Email (Ex : mgm@gmail.com)"
                        value="<?= htmlspecialchars($listesdesinvites->email) ?>">
                </div>
            </div>
        </div>

        <!-- Ligne 2 : Téléphone et Table -->
        <div style="margin-top: -20px;" class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                        <i class="zmdi zmdi-phone"></i> Téléphone
                    </label>
                    <input type="text" id="edit_phone" name="edit_phone"
                        class="form-control"
                        style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                        placeholder="Téléphone (Ex : +243974743675)"
                        value="<?= htmlspecialchars($listesdesinvites->phone) ?>">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                        <i class="zmdi zmdi-grid"></i> Table
                    </label>
                    <select id="edit_table_id" name="edit_table_id"
                        class="form-control"
                        style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);">
                        <option value="0">Aucune table</option>
                        @foreach ($tables as $table)
                            <option value="{{ $table->id }}" {{ $table->id == $listesdesinvites->table_id ? 'selected' : '' }}>
                                {{ $table->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Boutons -->
        <div class="row" style="margin-top: 16px;">
            <div class="col-12">
                <button id="edit_save" class="btn btn-info btn-sm">
                    Modifier <i class="zmdi zmdi-edit"></i>
                </button>
                <button id="edit_annuler" class="btn btn-danger btn-sm">
                    Annuler <i class="zmdi zmdi-close-circle"></i>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: center;">
                <span style="font-weight: bold;" id="edit_msg"></span>
            </div>
        </div>
    </form>

<script src="{{ asset('assets/vendors/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script>
    // ============================================================
    // Gestion des messages d'édition
    // ============================================================

    $("#edit_annuler").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        $('#edit_msg').html('').removeClass('msg-success msg-error msg-info');
    });

    $("#edit_save").click(function(e) {
        e.preventDefault();
        var nom = $("#edit_nom").val();
        var data = $("#form_edit").serialize();
        if (nom.trim().length === 0) {
            showEditMsg('error', '<i class="zmdi zmdi-close-circle"></i> Completez le nom', 9000);
        } else {
            $("#edit_save").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "/edit_invite",
                data: data,
                success: function(response) {
                    $("#edit_save").attr("disabled", false);
                    showEditMsg('success',
                        '<i class="zmdi zmdi-check-circle"></i> Invité modifié avec succès',
                        9000);

                    $("#content_utilisateur").html(response);

                    if (typeof filterClients === 'function') {
                        setTimeout(filterClients, 300);
                    }
                }
            });
        }
    });

    function showEditMsg(type, html, delay) {
        var msg = $('#edit_msg');
        msg.removeClass('msg-success msg-error msg-info');
        if (type === 'success') msg.addClass('msg-success');
        else if (type === 'error') msg.addClass('msg-error');
        else if (type === 'info') msg.addClass('msg-info');
        msg.html(html);
        if (delay) {
            clearTimeout(msg.data('timer'));
            var timer = setTimeout(function() {
                msg.html('').removeClass('msg-success msg-error msg-info');
            }, delay);
            msg.data('timer', timer);
        }
    }
</script>