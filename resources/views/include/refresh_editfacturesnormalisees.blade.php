{{-- =========================================================
     FORMULAIRE DE MODIFICATION DE FACTURE
     ========================================================= --}}
@php
    use App\Models\Mois;
    use App\Models\Annees;
    use App\Models\Clients;

    $moisCourant   = Mois::find($facturesnormalisees->moi_id);
    $anneeCourante = Annees::find($facturesnormalisees->annee_id);
    $clientCourant = Clients::find($facturesnormalisees->client_id);

    $moisNomEdit   = $moisCourant->nom ?? '';
    $anneeNomEdit  = $anneeCourante->annees ?? '';
    $clientNomEdit = $clientCourant->name ?? '';

    // Mois affichés par défaut (remplis directement en Blade — AUCUN appel AJAX)
    $moisDeLAnnee = Mois::all();
@endphp

<style>
/* ============================================================
   STYLES IDENTIQUES AU FORMULAIRE D'AJOUT (#form_add)
   ============================================================ */
#form_edit_fact .form-control,
#form_edit_fact select.form-control,
#form_edit_fact input.form-control,
#form_edit_fact input[type="file"].form-control {
    border: 2px solid #94a3b8 !important;
    border-radius: 12px !important;
    background: #ffffff !important;
    height: 42px !important;
    padding: 8px 14px !important;
    font-size: 0.85rem !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    box-shadow: none !important;
    width: 100% !important;
}
#form_edit_fact .form-control:hover,
#form_edit_fact select.form-control:hover,
#form_edit_fact input.form-control:hover,
#form_edit_fact input[type="file"].form-control:hover {
    border-color: #3B82F6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}
#form_edit_fact .form-control:focus,
#form_edit_fact select.form-control:focus,
#form_edit_fact input.form-control:focus,
#form_edit_fact input[type="file"].form-control:focus {
    border-color: var(--bleu-nuit) !important;
    box-shadow: 0 0 0 4px rgba(10, 25, 47, 0.15) !important;
    outline: none !important;
}

/* ====== SELECT2 ====== */
#form_edit_fact .select2-container { width: 100% !important; }
#form_edit_fact .select2-container--default .select2-selection--single {
    border: 2px solid #94a3b8 !important;
    border-radius: 12px !important;
    background: #ffffff !important;
    height: 42px !important;
    padding: 6px 14px !important;
    font-size: 0.85rem !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    display: flex !important;
    align-items: center !important;
}
#form_edit_fact .select2-container--default .select2-selection--single:hover {
    border-color: #3B82F6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}
#form_edit_fact .select2-container--default.select2-container--focus .select2-selection--single,
#form_edit_fact .select2-container--default.select2-container--open .select2-selection--single {
    border-color: var(--bleu-nuit) !important;
    box-shadow: 0 0 0 4px rgba(10, 25, 47, 0.15) !important;
    outline: none !important;
}
#form_edit_fact .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #1e2a3e !important;
    line-height: 28px !important;
    padding-left: 0 !important;
    padding-right: 25px !important;
    font-weight: 500 !important;
}
#form_edit_fact .select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #94a3b8 !important;
}
#form_edit_fact .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
    right: 10px !important;
}
#form_edit_fact .select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #e31b23 transparent transparent transparent !important;
}

/* ====== INPUT FILE ====== */
#form_edit_fact input[type="file"].form-control {
    padding: 6px 12px !important;
    cursor: pointer;
    background: #f8fafc !important;
    color: #1e2a3e;
    display: flex !important;
    align-items: center !important;
}
#form_edit_fact input[type="file"].form-control::file-selector-button {
    background: var(--bleu-nuit-gradient);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 6px 14px;
    margin-right: 12px;
    font-weight: 600;
    font-size: 0.78rem;
    cursor: pointer;
    transition: all 0.2s ease;
    height: 30px;
}
#form_edit_fact input[type="file"].form-control::file-selector-button:hover {
    background: linear-gradient(135deg, #1e3a5f, #0a192f);
    transform: translateY(-1px);
}

#form_edit_fact .form-group label {
    font-size: 0.78rem;
    margin-bottom: 6px;
    color: #0a192f;
    display: block;
}
#form_edit_fact .form-group label i { color: #e31b23; }

/* ============================================================
   APERÇU PDF (nouveau fichier)
   ============================================================ */
#edit_pdf_preview_container {
    display: none;
    margin-top: 25px;
    margin-bottom: 20px;
    padding: 16px;
    background: linear-gradient(135deg, #f8fafc, #eff6ff);
    border: 2px dashed #3b82f6;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    animation: slideInMsg 0.4s ease-out;
}
#edit_pdf_preview_container.show { display: block !important; }

#edit_pdf_preview_header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 2px solid #dbeafe;
}
#edit_pdf_preview_header .pdf-title {
    font-weight: 700;
    color: #0a192f;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
#edit_pdf_preview_header .pdf-title i {
    color: #e31b23;
    font-size: 1.4rem;
}
#edit_pdf_preview_header .pdf-badge {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border-radius: 50px;
    padding: 4px 14px;
    font-size: 0.72rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 8px rgba(16, 185, 129, 0.25);
}
#edit_pdf_preview_close {
    background: #fee2e2;
    color: #b91c1c;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    font-weight: bold;
}
#edit_pdf_preview_close:hover {
    background: #fecaca;
    transform: rotate(90deg);
}
#edit_pdf_preview_frame {
    width: 100%;
    height: 600px;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    background: white;
    box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* ============================================================
   SPINNER
   ============================================================ */
#edit_fact_save.loading,
#edit_fact_save:disabled {
    background: var(--bleu-secondaire-gradient) !important;
    cursor: not-allowed !important;
    opacity: 0.85;
    pointer-events: none;
}
#edit_fact_save .spinner {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 2px solid rgba(255, 255, 255, 0.4);
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: spinSave 0.7s linear infinite;
    margin-left: 6px;
    vertical-align: middle;
}

/* ============================================================
   MESSAGES
   ============================================================ */
#edit_fact_msg {
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
#edit_fact_msg:not(:empty) {
    display: inline-flex !important;
    visibility: visible !important;
    opacity: 1 !important;
    height: auto !important;
    margin-top: 16px !important;
    padding: 10px 18px !important;
    background: white !important;
    border-radius: 50px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
    gap: 10px;
    font-weight: 600;
    font-size: 0.8rem;
    animation: slideInMsg 0.3s ease-out;
}
#edit_fact_msg:not(:empty):has(i.zmdi-check-circle) {
    background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important;
    color: #065f46;
    border-left: 4px solid #10b981;
}
#edit_fact_msg:not(:empty):has(i.zmdi-close-circle) {
    background: linear-gradient(95deg, #fee2e2, #fecaca) !important;
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

/* ============================================================
   BLOC "FICHIER ACTUEL"
   ============================================================ */
.current-file-block {
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
    border-radius: 10px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 25px;
}
.current-file-block .file-link {
    color: #2563eb;
    text-decoration: underline;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.current-file-block .file-link:hover {
    color: #1d4ed8;
    text-decoration: none;
}

.row-space {
    margin-top: 22px;
}

/* ============================================================
   MODALE D'APERÇU ANCIEN FICHIER
   ============================================================ */
#modal_view_file_edit .modal-dialog {
    max-width: 90%;
    height: 90%;
    margin: 1.75rem auto;
}
#modal_view_file_edit .modal-content {
    height: 90vh;
    display: flex;
    flex-direction: column;
    border-radius: 16px;
    border: none;
    box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}
#modal_view_file_edit .modal-body {
    flex: 1;
    padding: 0;
    background: #f1f5f9;
    overflow: hidden;
}
#modal_view_file_edit iframe {
    width: 100%;
    height: 100%;
    border: none;
    background: white;
}
#modal_view_file_edit .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--bleu-nuit-gradient);
    border-bottom: none;
    padding: 1rem 1.5rem;
    color: white;
}
#modal_view_file_edit .modal-header .modal-title {
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
}
#modal_view_file_edit .modal-header .close {
    color: white;
    opacity: 0.8;
    text-shadow: none;
    font-size: 1.8rem;
    line-height: 1;
}
#modal_view_file_edit .modal-header .close:hover { opacity: 1; }
#modal_view_file_edit .file-name {
    color: #cbd5e1;
    font-size: 0.8rem;
    font-weight: 500;
    margin-left: 12px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
#modal_view_file_edit #view_file_details_edit {
    flex-shrink: 0;
    padding: 10px 18px;
    background: linear-gradient(135deg, #eff6ff, #e0f2fe);
    border-bottom: 1px solid #dbeafe;
    display: flex;
    flex-wrap: wrap;
    gap: 8px 22px;
    font-size: 0.82rem;
    color: #0a192f;
    font-weight: 600;
}
#modal_view_file_edit #view_file_details_edit .detail-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
#modal_view_file_edit #view_file_details_edit .detail-item i {
    color: #e31b23;
    font-size: 1.05rem;
}
#modal_view_file_edit .modal-footer {
    background: #f8fafc;
    border-top: 1px solid #eef2f6;
    padding: 1rem 1.5rem;
    justify-content: flex-end;
}

@media (max-width: 768px) {
    #edit_pdf_preview_frame { height: 400px; }
    #edit_pdf_preview_header .pdf-title { font-size: 0.8rem; }
    #modal_view_file_edit .modal-dialog { max-width: 98%; }
    #modal_view_file_edit .modal-content { height: 85vh; }
    #modal_view_file_edit #view_file_details_edit { font-size: 0.75rem; padding: 8px 12px; }
}
</style>

<h4 style="color:rgba(0, 0, 0, 0.6);">
    <i style="font-size: 40px;" class="zmdi zmdi-edit text-info"></i> Modifier la facture
</h4>

<form id="form_edit_fact" action="#" method="post" enctype="multipart/form-data" style="margin-bottom: 60px;">
    @csrf
    {{-- ✅ CORRECTION 1 : name="edit_fact_id" au lieu de name="id" --}}
    <input type="hidden" id="edit_fact_id" name="edit_fact_id" value="{{ $facturesnormalisees->id }}">

    <div class="row">
        {{-- ============== ANNÉE ============== --}}
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                    <i class="zmdi zmdi-calendar"></i> Année
                </label>
                <select id="edit_annee_id" name="edit_annee_id" class="select2 form-control"
                        data-placeholder="Selectionnez une année">
                    <option value="">Selectionnez une année</option>
                    @foreach ($annees as $a)
                        <option value="{{ $a->id }}" {{ $a->id == $facturesnormalisees->annee_id ? 'selected' : '' }}>
                            {{ $a->annees }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ============== MOIS (rempli en Blade, mois enregistré sélectionné) ============== --}}
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                    <i class="zmdi zmdi-calendar"></i> Mois
                </label>
                <select id="edit_moi_id" name="edit_moi_id" class="select2 form-control"
                        data-placeholder="Selectionnez un mois">
                    <option value="">Selectionnez un mois</option>
                    @foreach ($moisDeLAnnee as $m)
                        <option value="{{ $m->id }}" {{ $m->id == $facturesnormalisees->moi_id ? 'selected' : '' }}>
                            {{ $m->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- ============== CLIENT ============== --}}
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                    <i class="zmdi zmdi-account"></i> Client
                </label>
                <select id="edit_client_id" name="edit_client_id" class="select2 form-control"
                        data-placeholder="Selectionnez un client">
                    <option value="">Selectionnez un client</option>
                    @foreach ($clients as $c)
                        <option value="{{ $c->id }}" {{ $c->id == $facturesnormalisees->client_id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ============== NOUVEAU FICHIER ============== --}}
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                    <i class="zmdi zmdi-upload"></i> Nouveau fichier (PDF – laisser vide pour conserver l'actuel)
                </label>
                <input type="file" id="edit_fichier" name="edit_fichier" class="form-control"
                       accept=".pdf,application/pdf">
            </div>
        </div>
    </div>

    {{-- ============== FICHIER ACTUEL ============== --}}
    <div class="row row-space">
        <div class="col-12">
            <div class="current-file-block">
                <i class="zmdi zmdi-collection-pdf" style="color:#e31b23;font-size:24px;"></i>
                <div style="font-weight:600;color:#0a192f;font-size:0.85rem;">
                    Fichier actuel :
                    <a href="#" class="file-link"
                       data-file-url="{{ asset($facturesnormalisees->lien) }}"
                       data-file-name="{{ $facturesnormalisees->fichier_original }}"
                       data-mois="{{ $moisNomEdit }}"
                       data-annee="{{ $anneeNomEdit }}"
                       data-client="{{ $clientNomEdit }}">
                        {{ $facturesnormalisees->fichier_original }}
                    </a>
                </div>
                <span style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;
                             border-radius:50px;padding:3px 12px;font-size:0.7rem;font-weight:700;">
                    <i class="zmdi zmdi-check-circle"></i> Enregistré
                </span>
            </div>
        </div>
    </div>

    {{-- ============== BOUTONS ============== --}}
    <div class="row" style="margin-top:16px;">
        <div class="col-12">
            <button id="edit_fact_save" class="btn btn-info btn-sm">
                <span class="btn-text">Modifier</span>
                <i class="zmdi zmdi-edit btn-icon"></i>
            </button>
            <button id="edit_fact_annuler" type="button" class="btn btn-danger btn-sm">
                Annuler <i class="zmdi zmdi-close-circle"></i>
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12" style="text-align: center;">
            <span style="font-weight: bold;" id="edit_fact_msg"></span>
        </div>
    </div>

    {{-- ============== APERÇU PDF (NOUVEAU FICHIER) ============== --}}
    <div class="row" style="margin-top: 25px;">
        <div class="col-12">
            <div id="edit_pdf_preview_container">
                <div id="edit_pdf_preview_header">
                    <div class="pdf-title">
                        <i class="zmdi zmdi-collection-pdf"></i>
                        Aperçu du nouveau document PDF
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="pdf-badge">
                            <i class="zmdi zmdi-check-circle"></i> Prêt à soumettre
                        </span>
                        <button type="button" id="edit_pdf_preview_close" title="Fermer l'aperçu">×</button>
                    </div>
                </div>
                <iframe id="edit_pdf_preview_frame" src=""></iframe>
            </div>
        </div>
    </div>
</form>

{{-- ================= MODALE APERÇU ANCIEN FICHIER ================= --}}
<div class="modal fade" id="modal_view_file_edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <h5 class="modal-title" style="font-weight: bold;">
                        <i class="zmdi zmdi-collection-pdf"></i> Aperçu du fichier actuel
                    </h5>
                    <span class="file-name"></span>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="view_file_details_edit"></div>
            <div class="modal-body">
                <iframe id="view_file_frame_edit" src="" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                    Fermer <i class="zmdi zmdi-close-circle"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
/* ============================================================
   INITIALISATION DES SELECT2 (PAS D'APPEL AJAX AU CHARGEMENT)
   Les mois sont déjà dans le HTML avec le mois enregistré selected.
   ============================================================ */
$(document).ready(function () {
    $("#edit_annee_id").select2({
        placeholder: "Selectionnez une année",
        allowClear: true
    });

    $("#edit_moi_id").select2({
        placeholder: "Selectionnez un mois",
        allowClear: true
    });

    $("#edit_client_id").select2({
        placeholder: "Selectionnez un client",
        allowClear: true
    });
});

/* ============================================================
   CHANGEMENT D'ANNÉE → SEUL MOMENT OÙ on appelle get_mois_3
   ============================================================ */
$(document).off("change", "#edit_annee_id").on("change", "#edit_annee_id", function () {
    var annee_id = $(this).val();

    // Si l'année est vidée → on remet juste le placeholder
    if (!annee_id) {
        if ($("#edit_moi_id").hasClass("select2-hidden-accessible")) {
            $("#edit_moi_id").select2("destroy");
        }
        $("#edit_moi_id").html('<option value="">Selectionnez un mois</option>');
        $("#edit_moi_id").select2({
            placeholder: "Selectionnez un mois",
            allowClear: true
        });
        return;
    }

    // Appel AJAX uniquement ici
    $.get("{{ route('get_mois_3') }}", {
        annee_id: annee_id
    }, function (response) {
        if ($("#edit_moi_id").hasClass("select2-hidden-accessible")) {
            $("#edit_moi_id").select2("destroy");
        }

        $("#edit_moi_id").html(response);

        $("#edit_moi_id").select2({
            placeholder: "Selectionnez un mois",
            allowClear: true
        });

        // Aucun mois présélectionné (l'utilisateur doit choisir)
        $("#edit_moi_id").val(null).trigger("change.select2");
    });
});

/* ============================================================
   CLIC SUR L'ANCIEN FICHIER → OUVERTURE DANS LA MODALE
   ============================================================ */
$(document).off('click', '.current-file-block .file-link').on('click', '.current-file-block .file-link', function (e) {
    e.preventDefault();

    var fileUrl   = $(this).data('file-url');
    var fileName  = $(this).data('file-name');
    var moisNom   = $(this).data('mois');
    var anneeNom  = $(this).data('annee');
    var clientNom = $(this).data('client');

    $("#modal_view_file_edit .file-name").html(
        '<i class="zmdi zmdi-collection-pdf"></i> ' + fileName
    );

    $("#view_file_details_edit").html(
        '<span class="detail-item"><i class="zmdi zmdi-calendar"></i> Mois : ' + (moisNom || '—') + ' ' + (anneeNom || '') + '</span>' +
        '<span class="detail-item"><i class="zmdi zmdi-account"></i> Client : ' + (clientNom || '—') + '</span>' +
        '<span class="detail-item"><i class="zmdi zmdi-collection-pdf"></i> Fichier : ' + (fileName || '—') + '</span>'
    );

    $("#view_file_frame_edit").attr("src", fileUrl);
    $("#modal_view_file_edit").modal("show");
});

$('#modal_view_file_edit').on('hidden.bs.modal', function () {
    $("#view_file_frame_edit").attr("src", "");
    $("#modal_view_file_edit .file-name").html("");
    $("#view_file_details_edit").html("");
});

/* ============================================================
   APERÇU PDF POUR LE NOUVEAU FICHIER
   ============================================================ */
var currentEditPdfUrl = null;

$(document).off("change", "#edit_fichier").on("change", "#edit_fichier", function () {
    var file = this.files[0];

    if (currentEditPdfUrl) {
        URL.revokeObjectURL(currentEditPdfUrl);
        currentEditPdfUrl = null;
    }

    if (!file) {
        $("#edit_pdf_preview_container").removeClass("show");
        $("#edit_pdf_preview_frame").attr("src", "");
        return;
    }

    var ext = file.name.toLowerCase().split('.').pop();
    if (ext !== 'pdf') {
        $("#edit_fact_msg").html('<i class="zmdi zmdi-close-circle"></i> Seuls les fichiers PDF sont autorisés')
            .css('color', "#ff6b68");
        setTimeout(function () { $("#edit_fact_msg").html(""); }, 9000);
        $(this).val("");
        $("#edit_pdf_preview_container").removeClass("show");
        return;
    }

    if (file.size > 10 * 1024 * 1024) {
        $("#edit_fact_msg").html('<i class="zmdi zmdi-close-circle"></i> Fichier trop volumineux (max 10 Mo)')
            .css('color', "#ff6b68");
        setTimeout(function () { $("#edit_fact_msg").html(""); }, 9000);
        $(this).val("");
        $("#edit_pdf_preview_container").removeClass("show");
        return;
    }

    currentEditPdfUrl = URL.createObjectURL(file);
    $("#edit_pdf_preview_frame").attr("src", currentEditPdfUrl + "#toolbar=1&navpanes=0&scrollbar=1");
    $("#edit_pdf_preview_container").addClass("show");

    setTimeout(function () {
        $('html, body').animate({
            scrollTop: $("#edit_pdf_preview_container").offset().top - 100
        }, 400);
    }, 150);
});

$(document).off("click", "#edit_pdf_preview_close").on("click", "#edit_pdf_preview_close", function () {
    $("#edit_pdf_preview_container").removeClass("show");
    $("#edit_pdf_preview_frame").attr("src", "");
    if (currentEditPdfUrl) {
        URL.revokeObjectURL(currentEditPdfUrl);
        currentEditPdfUrl = null;
    }
});

/* ============================================================
   ANNULATION
   ============================================================ */
$(document).off("click", "#edit_fact_annuler").on("click", "#edit_fact_annuler", function (e) {
    e.preventDefault();

    $("#form_edit_fact")[0].reset();
    $("#edit_pdf_preview_container").removeClass("show");
    $("#edit_pdf_preview_frame").attr("src", "");
    if (currentEditPdfUrl) {
        URL.revokeObjectURL(currentEditPdfUrl);
        currentEditPdfUrl = null;
    }
    $("#edit_fact_msg").html("");

    $("#bloc_1").show();
    $("#bloc_2").hide();
    $("#bloc_3").hide().html("");
    $("#bloc_4").hide();
    $("#bloc_5").hide();
});

/* ============================================================
   SPINNER
   ============================================================ */
function setLoadingEditFact(active) {
    if (active) {
        $("#edit_fact_save").prop("disabled", true).addClass("loading");
        $("#edit_fact_save .btn-text").text("Enregistrement...");
        $("#edit_fact_save .btn-icon").removeClass("zmdi-edit").addClass("spinner");
    } else {
        $("#edit_fact_save").prop("disabled", false).removeClass("loading");
        $("#edit_fact_save .btn-text").text("Modifier");
        $("#edit_fact_save .btn-icon").removeClass("spinner").addClass("zmdi-edit");
    }
}

/* ============================================================
   VALIDATION + ENREGISTREMENT
   ============================================================ */
$(document).off("click", "#edit_fact_save").on("click", "#edit_fact_save", function (e) {
    e.preventDefault();

    var edit_fact_id   = $("#edit_fact_id").val();
    var edit_annee_id  = $("#edit_annee_id").val();
    var edit_moi_id    = $("#edit_moi_id").val();
    var edit_client_id = $("#edit_client_id").val();
    var fichier        = $("#edit_fichier")[0].files[0];

    if (!edit_annee_id || edit_annee_id.trim().length === 0) {
        $("#edit_fact_msg").html('<i class="zmdi zmdi-close-circle"></i> Selectionnez une année').css('color', "#ff6b68");
        setTimeout(function () { $("#edit_fact_msg").html(""); }, 9000);
        return;
    }
    if (!edit_moi_id || edit_moi_id.trim().length === 0) {
        $("#edit_fact_msg").html('<i class="zmdi zmdi-close-circle"></i> Selectionnez un mois').css('color', "#ff6b68");
        setTimeout(function () { $("#edit_fact_msg").html(""); }, 9000);
        return;
    }
    if (!edit_client_id || edit_client_id.trim().length === 0) {
        $("#edit_fact_msg").html('<i class="zmdi zmdi-close-circle"></i> Selectionnez un client').css('color', "#ff6b68");
        setTimeout(function () { $("#edit_fact_msg").html(""); }, 9000);
        return;
    }

    if (fichier) {
        var ext = fichier.name.toLowerCase().split('.').pop();
        if (ext !== 'pdf') {
            $("#edit_fact_msg").html('<i class="zmdi zmdi-close-circle"></i> Seuls les fichiers PDF sont autorisés').css('color', "#ff6b68");
            setTimeout(function () { $("#edit_fact_msg").html(""); }, 9000);
            return;
        }
        if (fichier.size > 10 * 1024 * 1024) {
            $("#edit_fact_msg").html('<i class="zmdi zmdi-close-circle"></i> Fichier trop volumineux (max 10 Mo)').css('color', "#ff6b68");
            setTimeout(function () { $("#edit_fact_msg").html(""); }, 9000);
            return;
        }
    }

    setLoadingEditFact(true);

    /* ============================================================
       ✅ CORRECTION 2 : on envoie edit_fact_id + edit_client_id
       ============================================================ */
    $.get("{{ route('check_solde_edit') }}", {
        edit_fact_id:   edit_fact_id,
        edit_annee_id:  edit_annee_id,
        edit_moi_id:    edit_moi_id,
        edit_client_id: edit_client_id
    }, function (rep) {
        if (rep != 0) {
            setLoadingEditFact(false);
            /* ✅ CORRECTION 3 : message plus précis */
            $("#edit_fact_msg").html('<i class="zmdi zmdi-close-circle"></i> Cette facture existe déjà pour ce client sur cette période')
                .css('color', "#ff6b68");
            setTimeout(function () { $("#edit_fact_msg").html(""); }, 9000);
            return;
        }

        var formData = new FormData($("#form_edit_fact")[0]);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            type: "POST",
            url: "{{ route('edit_charger_facture') }}",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                setLoadingEditFact(false);

                $("#edit_fact_msg").html('<i class="zmdi zmdi-check-circle"></i> Facture modifiée avec succès')
                    .css("color", '#32c787');

                $("#content_groupe").html(response);

                $("#edit_pdf_preview_container").removeClass("show");
                $("#edit_pdf_preview_frame").attr("src", "");
                if (currentEditPdfUrl) {
                    URL.revokeObjectURL(currentEditPdfUrl);
                    currentEditPdfUrl = null;
                }

                setTimeout(function () {
                    $("#edit_fact_msg").html("");
                    $("#bloc_1").show();
                    $("#bloc_2").hide();
                    $("#bloc_3").hide().html("");
                    $("#bloc_4").hide();
                    $("#bloc_5").hide();
                }, 1200);
            },
            /* ============================================================
               ✅ CORRECTION 4 : on affiche le message du serveur (JSON)
               ============================================================ */
            error: function (xhr) {
                setLoadingEditFact(false);
                var msg = 'Erreur lors de la modification';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                $("#edit_fact_msg").html('<i class="zmdi zmdi-close-circle"></i> ' + msg)
                    .css('color', "#ff6b68");
                setTimeout(function () { $("#edit_fact_msg").html(""); }, 9000);
            }
        });
    }).fail(function () {
        setLoadingEditFact(false);
        $("#edit_fact_msg").html('<i class="zmdi zmdi-close-circle"></i> Erreur de communication avec le serveur')
            .css('color', "#ff6b68");
        setTimeout(function () { $("#edit_fact_msg").html(""); }, 9000);
    });
});
</script>