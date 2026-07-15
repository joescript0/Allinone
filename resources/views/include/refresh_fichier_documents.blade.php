<?php

use App\Models\Contrevenants;
use App\Models\Groupes;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\Type_documents;
use App\Models\Documents;
use App\Models\Fichiers_documents;
use App\Models\Droit_fichiers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Type de document</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Description</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateurs</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{! $i = 1; }}
                @foreach ($fichier_documents as $data)
                <?php
                                            $documents = Documents::where('id', $data->documents_id)->first();
                                            $type_documents_id = $documents["type_documents_id"];
                                            $type_document = Type_documents::where('id', $type_documents_id)->first();
                                            $ut = User::where('id', $documents["user_id"])->first();
                                        ?>
                <?php if((Droit_fichiers::where(['user_id' => Auth::user()->id, 'fichier_documents_id' => $data->id, 'numero_permission' => 1])->get()->count() > 0) || ((Auth::user()->id == $documents["user_id"]) || (Auth::user()->role == 0))){ ?>

                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $type_document["nom"] }}
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        {{ $documents["description"] }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        @if (Auth::user()->id == $documents["user_id"])
                        Vous
                        @else
                        {{ $ut['name'] }}
                        @endif
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;" class="text-center">
                        <?=  $data->fichier_documents_id ?>
                        <?php if((Droit_fichiers::where(['user_id' => Auth::user()->id, 'fichier_documents_id' => $data->id, 'numero_permission' => 1])->get()->count() > 0) || ((Auth::user()->id == $documents["user_id"]) || (Auth::user()->role == 0))){ ?>
                        <a id="voir_<?= $i ?>" class="btn btn-info btn-sm" href="">
                            <i class="zmdi zmdi-eye"></i> Voir
                        </a>&nbsp;
                        <?php } ?>
                        <?php if((Droit_fichiers::where(['user_id' => Auth::user()->id, 'fichier_documents_id' => $data->id, 'numero_permission' => 2])->get()->count() > 0) || ((Auth::user()->id == $documents["user_id"]) || (Auth::user()->role == 0))){ ?>
                        <a id="telecharger_<?= $i ?>" class="btn btn-dark btn-sm" href="">
                            <i class="zmdi zmdi-download"></i> Telecharger
                        </a>&nbsp;
                        <?php } ?>
                        <?php if((Droit_fichiers::where(['user_id' => Auth::user()->id, 'fichier_documents_id' => $data->id, 'numero_permission' => 3])->get()->count() > 0) || ((Auth::user()->id ==$documents["user_id"]) || (Auth::user()->role == 0))){ ?>
                        <a style="dispaly:none;" id="edit_<?= $i ?>" class="btn btn-success btn-sm" href="">
                            <i class="zmdi zmdi-edit"></i> Modifier
                        </a>&nbsp;
                        <?php } ?>
                        <?php if((Droit_fichiers::where(['user_id' => Auth::user()->id, 'fichier_documents_id' => $data->id, 'numero_permission' => 4])->get()->count() > 0) || ((Auth::user()->id == $documents["user_id"]) || (Auth::user()->role == 0))){ ?>
                        <a id="delete_<?= $i ?>" class="btn btn-danger btn-sm" href="">
                            <i class="zmdi zmdi-delete"></i> Supprimer
                        </a>&nbsp;
                        <?php } ?>
                        <?php if(((Auth::user()->id == $documents["user_id"]) || (Auth::user()->role == 0))){ ?>
                        <a id="partager_<?= $i ?>" class="btn btn-secondary btn-sm" href="">
                            <span id="spin_t"><i class="zmdi zmdi-share"></i></span> Partager
                        </a>
                        <?php } ?>
                        <script>
                        $("#edit_<?= $i ?>").click(function(e) {
                            e.preventDefault();
                            $.get("{{ url('/refresh_edit_fichier_document_id') }}", {
                                fichier_documents_id: <?= $data->id ?>,
                            }, function(refresh_edit_type_documents) {
                                $("#bloc_1").hide();
                                $("#bloc_2").hide();
                                $("#bloc_3").show();
                                $("#bloc_3").html(refresh_edit_type_documents);
                            });
                        });
                        $("#delete_<?= $i ?>").click(function(e) {
                            e.preventDefault();
                            $("#element").html("<?= $data->nom ?>");
                            $("#data_id").html("<?= $data->id ?>");
                            $("#btn_sup").trigger("click");
                        });
                        $("#voir_<?= $i ?>").click(function(e) {
                            e.preventDefault();
                            var url = "<?= $data->lien ?>";
                            // 1. Extraire l'extension (en minuscules pour éviter la casse)
                            const extension = url.split('.').pop().toLowerCase();

                            // 2. Définir une correspondance extension -> type de document
                            const typeMap = {
                                // Images
                                'png': 'image',
                                'jpg': 'image',
                                'jpeg': 'image',
                                'gif': 'image',
                                'bmp': 'image',
                                'webp': 'image',
                                'svg': 'image',
                                // Documents texte
                                'txt': 'texte',
                                'pdf': 'pdf',
                                'doc': 'word',
                                'docx': 'word',
                                'odt': 'texte',
                                // Tableurs
                                'xls': 'excel',
                                'xlsx': 'excel',
                                'ods': 'tableur',
                                // Présentations
                                'ppt': 'powerpoint',
                                'pptx': 'powerpoint',
                                'odp': 'présentation',
                                // Archives
                                'zip': 'archive',
                                'rar': 'archive',
                                '7z': 'archive',
                                // Audio / Vidéo
                                'mp3': 'audio',
                                'wav': 'audio',
                                'mp4': 'vidéo',
                                'avi': 'vidéo',
                                'mov': 'vidéo',
                                // Autres
                                'html': 'page web',
                                'css': 'feuille de style',
                                'js': 'script JavaScript',
                                'json': 'données JSON',
                                'xml': 'données XML'
                            };

                            var type = typeMap[extension] || 'type inconnu';
                            if (type == "image") {
                                $("#titre_modal_fichier").html("Visualisation : " + url
                                    .split('/').pop());
                                $("#fichier_content").html('<img src="' + url +
                                    '" class="img-fluid" style="max-height:100%;width: 100%;" />'
                                );
                                $("#btn_detail_fichier").trigger("click");
                            }
                            if (type == "excel") {
                                // Récupérer l'URL complète du fichier (sur le même serveur)
                                var fileUrl = "{{ asset('') }}" + url;
                                // Nettoyer les doubles slashes
                                fileUrl = fileUrl.replace(/([^:]\/)\/+/g, "$1");

                                // Titre de la modale
                                $("#excelModalTitle").html("Visualisation : " + url.split(
                                        '/')
                                    .pop());

                                // Ouvrir la modale
                                $("#modalExcelViewer").modal({
                                    backdrop: 'static',
                                    keyboard: false,
                                    show: true
                                });

                                // Conteneur pour le visualiseur
                                var container = document.getElementById(
                                    "excelViewerContainer");
                                container.innerHTML =
                                    '<div style="text-align:center; padding:50px;"><i class="zmdi zmdi-spinner zmdi-hc-spin" style="font-size: 40px;"></i><br>Chargement du fichier Excel...</div>';

                                // Charger le fichier depuis le serveur (pas de proxy CORS car même serveur)
                                fetch(fileUrl)
                                    .then(response => {
                                        if (!response.ok) throw new Error("HTTP " +
                                            response.status);
                                        return response.arrayBuffer();
                                    })
                                    .then(arrayBuffer => {
                                        // Charger le workbook avec SheetJS
                                        var workbook = XLSX.read(arrayBuffer, {
                                            type: 'array'
                                        });
                                        // Nettoyer le conteneur
                                        container.innerHTML = '';
                                        // Instancier notre visualiseur Excel
                                        new ExcelViewer("#excelViewerContainer",
                                            workbook, url.split('/').pop());
                                    })
                                    .catch(err => {
                                        container.innerHTML = `
                                                                <div class="alert alert-danger" style="margin: 20px;">
                                                                    <i class="zmdi zmdi-alert-circle"></i> 
                                                                    Erreur de chargement : ${err.message}<br>
                                                                    Vérifiez que le fichier existe et est accessible.
                                                                </div>
                                                            `;
                                        console.error(err);
                                    });
                                // ========================
                                // Classe ExcelViewer - Visualiseur Excel avancé
                                // ========================
                                class ExcelViewer {
                                    constructor(container, workbook, title = "Classeur") {
                                        this.container = typeof container === 'string' ?
                                            document.querySelector(container) :
                                            container;
                                        if (!this.container) throw new Error(
                                            "Conteneur introuvable");
                                        this.workbook = workbook;
                                        this.title = title;
                                        this.currentSheetName = null;
                                        this.buildUI();
                                        this.initSheets();
                                    }

                                    buildUI() {
                                        this.container.innerHTML = `
                                                                <div class="excel-viewer" style="padding: 10px;">
                                                                    <h5 style="color: #1e466e; margin-bottom: 15px;display:none;">
                                                                        <i class="zmdi zmdi-chart"></i> ${this.title}
                                                                    </h5>
                                                                    <div class="excel-sheet-selector" style="display: none; margin-bottom: 10px; background: #f1f5f9; padding: 8px 12px; border-radius: 8px;">
                                                                        <label style="margin-right: 10px;padding-top:8px;color:black;"><i class="zmdi zmdi-tab"></i> Feuille :</label>
                                                                        <select class="excel-sheet-select" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #ccc;"></select>
                                                                    </div>
                                                                    <div class="excel-info" style="background: #e3f2fd; padding: 8px 12px; border-radius: 8px; margin-bottom: 10px; font-size: 13px;"></div>
                                                                    <div class="excel-search-bar" style="display: flex; gap: 8px; margin-bottom: 10px;">
                                                                        <input type="text" class="excel-search-input" placeholder="Rechercher dans le tableau..." style="flex: 2; padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px;">
                                                                        <button class="excel-search-btn btn btn-info btn-sm" style="background: #17a2b8; border: none;">
                                                                            <i class="zmdi zmdi-search"></i> Rechercher
                                                                        </button>
                                                                        <button class="excel-clear-btn btn btn-secondary btn-sm" style="background: #6c757d; border: none;">
                                                                            <i class="zmdi zmdi-close"></i> Effacer
                                                                        </button>
                                                                    </div>
                                                                    <div class="excel-table-wrapper" style="overflow: auto; max-height: 500px; border: 1px solid #dee2e6; border-radius: 8px; background: white;"></div>
                                                                    <div class="excel-selected-cell" style="margin-top: 12px; padding: 10px; background: #f1f5f9; border-radius: 8px; display: flex; gap: 10px; align-items: center;">
                                                                        <label style="margin: 0; font-weight: bold; color: #1e466e;"><i class="zmdi zmdi-info"></i> Cellule sélectionnée :</label>
                                                                        <input type="text" class="excel-selected-value" readonly style="flex: 1; padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; background: white; font-family: monospace;">
                                                                    </div>
                                                                </div>
                                                            `;

                                        this.infoDiv = this.container.querySelector(
                                            '.excel-info');
                                        this.sheetSelectorDiv = this.container
                                            .querySelector('.excel-sheet-selector');
                                        this.sheetSelect = this.container.querySelector(
                                            '.excel-sheet-select');
                                        this.searchInput = this.container.querySelector(
                                            '.excel-search-input');
                                        this.searchBtn = this.container.querySelector(
                                            '.excel-search-btn');
                                        this.clearBtn = this.container.querySelector(
                                            '.excel-clear-btn');
                                        this.tableWrapper = this.container
                                            .querySelector('.excel-table-wrapper');
                                        this.selectedCellInput = this.container
                                            .querySelector('.excel-selected-value');

                                        this.searchBtn.addEventListener('click', () =>
                                            this.highlightSearch());
                                        this.clearBtn.addEventListener('click', () =>
                                            this.clearHighlights());
                                        this.sheetSelect.addEventListener('change', (
                                            e) => {
                                            this.currentSheetName = e.target
                                                .value;
                                            this.renderCurrentSheet();
                                        });
                                    }

                                    initSheets() {
                                        const sheetNames = this.workbook.SheetNames;
                                        if (sheetNames.length === 0) {
                                            this.showMessage("Aucune feuille trouvée",
                                                true);
                                            return;
                                        }
                                        if (sheetNames.length > 1) {
                                            this.sheetSelectorDiv.style.display =
                                                'flex';
                                            this.sheetSelect.innerHTML = sheetNames.map(
                                                n =>
                                                `<option value="${n}">${n}</option>`
                                            ).join('');
                                        }
                                        this.currentSheetName = sheetNames[0];
                                        this.renderCurrentSheet();
                                    }

                                    renderCurrentSheet() {
                                        if (!this.workbook || !this.currentSheetName)
                                            return;
                                        const sheet = this.workbook.Sheets[this
                                            .currentSheetName];
                                        const html = XLSX.utils.sheet_to_html(sheet, {
                                            editable: false
                                        });
                                        this.tableWrapper.innerHTML =
                                            `<div style="overflow-x: auto;">${html}</div>`;
                                        const table = this.tableWrapper.querySelector(
                                            'table');
                                        if (table) {
                                            table.style.width = '100%';
                                            table.style.borderCollapse = 'collapse';
                                            table.style.fontSize = '13px';
                                            table.querySelectorAll('th').forEach(th => {
                                                th.style.background = '#2c7da0';
                                                th.style.color = 'white';
                                                th.style.padding = '8px';
                                                th.style.position = 'sticky';
                                                th.style.top = '0';
                                                th.style.zIndex = '10';
                                            });
                                            table.querySelectorAll('td').forEach(td => {
                                                td.style.border =
                                                    '1px solid #d4dce6';
                                                td.style.padding = '6px 8px';
                                                td.style.cursor = 'pointer';
                                                td.addEventListener('click', (
                                                    e) => {
                                                    e.stopPropagation();
                                                    this.onCellClick(
                                                        td);
                                                });
                                            });
                                            table.querySelectorAll(
                                                'tr:nth-child(even) td').forEach(
                                                td => {
                                                    td.style.backgroundColor =
                                                        '#fafcff';
                                                });
                                        }
                                        this.selectedCellInput.value = '';
                                        this.showMessage(
                                            `📄 Feuille : ${this.currentSheetName}`);
                                    }

                                    onCellClick(cell) {
                                        if (cell.classList.contains('cell-highlight')) {
                                            cell.classList.remove('cell-highlight');
                                        } else {
                                            cell.classList.remove('cell-search');
                                            cell.classList.add('cell-highlight');
                                        }
                                        const value = cell.innerText || cell
                                            .textContent;
                                        this.selectedCellInput.value = value;
                                        this.showMessage(
                                            `📌 Contenu : "${value.substring(0, 80)}${value.length > 80 ? '…' : ''}"`
                                        );
                                    }

                                    clearHighlights() {
                                        const cells = this.tableWrapper
                                            .querySelectorAll('td, th');
                                        cells.forEach(cell => cell.classList.remove(
                                            'cell-highlight', 'cell-search'));
                                        this.showMessage("✅ Entourages effacés");
                                    }

                                    highlightSearch() {
                                        const text = this.searchInput.value.trim();
                                        if (!text) {
                                            this.showMessage(
                                                "⚠️ Entrez un texte à rechercher",
                                                true);
                                            return;
                                        }
                                        this.clearHighlights();
                                        const cells = this.tableWrapper
                                            .querySelectorAll('td');
                                        let found = [];
                                        cells.forEach(cell => {
                                            if (cell.innerText.toLowerCase()
                                                .includes(text.toLowerCase())) {
                                                cell.classList.add(
                                                    'cell-search');
                                                found.push(cell);
                                            }
                                        });
                                        if (found.length === 0) {
                                            this.showMessage(
                                                `❌ Aucune cellule ne contient "${text}"`,
                                                true);
                                        } else {
                                            this.showMessage(
                                                `🔍 ${found.length} cellule(s) trouvée(s) pour "${text}".`
                                            );
                                            this.scrollToCell(found[0]);
                                        }
                                    }

                                    scrollToCell(cell) {
                                        const wrapper = this.tableWrapper;
                                        const cellRect = cell.getBoundingClientRect();
                                        const wrapperRect = wrapper
                                            .getBoundingClientRect();
                                        const scrollTop = wrapper.scrollTop + cellRect
                                            .top - wrapperRect.top - wrapperRect
                                            .height / 2 + cellRect.height / 2;
                                        wrapper.scrollTo({
                                            top: scrollTop,
                                            behavior: 'smooth'
                                        });
                                        cell.classList.add('first-found');
                                        setTimeout(() => cell.classList.remove(
                                            'first-found'), 800);
                                    }

                                    showMessage(msg, isError = false) {
                                        this.infoDiv.innerHTML = msg;
                                        this.infoDiv.style.backgroundColor = isError ?
                                            '#f8d7da' : '#e3f2fd';
                                        this.infoDiv.style.color = isError ? '#721c24' :
                                            '#0c5460';
                                        if (!isError) {
                                            setTimeout(() => {
                                                if (this.infoDiv.style
                                                    .backgroundColor !==
                                                    '#f8d7da') {
                                                    this.infoDiv.style
                                                        .backgroundColor =
                                                        '#e3f2fd';
                                                    this.infoDiv.style.color =
                                                        '#0c5460';
                                                }
                                            }, 3000);
                                        }
                                    }
                                }
                            }
                            if (type == "word") {
                                var all_url = "<?= asset(""); ?>" + url;
                                $("#titre_modal_fichier").html("Visualisation : " + url
                                    .split('/').pop());
                                // var all_url = "https://www.africtechapp.com/public/Introduction.docx";
                                document.getElementById("fichier_content").innerHTML =
                                    '<iframe src="https://docs.google.com/viewer/viewer?url=' +
                                    encodeURIComponent(all_url) +
                                    '&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
                                $("#btn_detail_fichier").trigger("click");
                            }
                            if (type == "pdf") {
                                var all_url = "<?= asset(""); ?>" + url;
                                $("#titre_modal_fichier").html("Visualisation : " + url
                                    .split('/').pop());
                                // var all_url = "https://www.africtechapp.com/public/FACTURE_MARS_2026_Hall_de_l_____toile.pdf";
                                document.getElementById("fichier_content").innerHTML =
                                    '<iframe src="https://docs.google.com/viewer/viewer?url=' +
                                    encodeURIComponent(all_url) +
                                    '&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
                                $("#btn_detail_fichier").trigger("click");
                            }
                            if (type == "texte") {
                                var all_url = "<?= asset(""); ?>" + url;
                                $("#titre_modal_fichier").html("Visualisation : " + url
                                    .split('/').pop());
                                // var all_url = "https://www.africtechapp.com/public/Login_mmg.txt";
                                document.getElementById("fichier_content").innerHTML =
                                    '<iframe src="' + all_url +
                                    '" style="width:100%; height:100%;" frameborder="0"></iframe>';
                                $("#btn_detail_fichier").trigger("click");
                            }
                            if (type == "powerpoint") {
                                console.log("Powerpoint");
                            }
                            if (type == "audio") {
                                console.log("audio");
                            }
                            if (type == "vidéo") {
                                console.log("vidéo");
                            }
                        });
                        $("#telecharger_<?= $i ?>").click(function(e) {
                            e.preventDefault();
                            // Récupération directe de la variable PHP (échappement pour sécurité)
                            var url = "<?= $data->lien ?>";

                            // Téléchargement via l'attribut download (même origine)
                            var link = document.createElement('a');
                            link.href = url;
                            link.download =
                                ""; // Laissez vide pour garder le nom du serveur
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        });
                        $("#partager_<?= $i ?>").click(function(e) {
                            e.preventDefault();
                            $.get("{{ url('/refresh_partager_fichier') }}", {
                                fichier_document_id: <?= $data->id ?>,
                            }, function(partager_fichier) {
                                $("#bloc_1").hide();
                                $("#bloc_2").hide();
                                $("#bloc_3").hide();
                                $("#bloc_4").show();
                                $("#bloc_4").html(partager_fichier);
                            });
                        });
                        </script>
                    </td>
                </tr>
                <?php } ?>
                {{! $i++; }}
                @endforeach
            </tbody>
        </table>
    </div>
</div>