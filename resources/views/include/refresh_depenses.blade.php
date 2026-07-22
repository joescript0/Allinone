<?php

use App\Models\Contrevenants;
use App\Models\Groupes;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\User;
use App\Models\Factures;
use App\Models\Entres;
use App\Models\Societes;
use App\Models\Mesures;
use App\Models\Activites;
use App\Models\Type_frais;
use Illuminate\Support\Facades\Auth;

?>
<div class="table-responsive">
    <table class="table table-bordered mb-0" id="depensesTable">
        <thead>
            <tr>
                <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                <th style="padding-top: 5px;padding-bottom: 5px;">Type de dépense</th>
                <th style="padding-top: 5px;padding-bottom: 5px;">N ° pièce</th>
                <th style="padding-top: 5px;padding-bottom: 5px;">Montant</th>
                <th style="padding-top: 5px;padding-bottom: 5px;">Date</th>
                <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
            </tr>
        </thead>
        <tbody id="depensesTableBody">
            {{ !($i = 1) }}
            @foreach ($depenses as $data)
                <tr
                    data-libelle="{{ $data->type_depense_id ? Type_frais::find($data->type_depense_id)->nom ?? '' : $data->libelle ?? '' }}">
                    <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                    <td class="user-cell" data-user-id="{{ $data->user_id }}"
                        style="padding-top: 5px;padding-bottom: 5px;">
                        @if ($data->user_id == Auth::user()->id)
                            Vous
                        @else
                            {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                        @endif
                    </td>
                    <td class="type-cell" data-type-id="{{ $data->type_depense_id }}"
                        style="padding-top: 5px;padding-bottom: 5px;">
                        @if ($data->type_depense_id != 0 && $data->type_depense_id != null)
                            {{ Type_frais::where('id', $data->type_depense_id)->first()['nom'] ?? 'N/A' }}
                        @else
                            {{ $data->libelle ?: 'Sans type' }}
                        @endif
                    </td>
                    <td class="piece-cell" data-n-piece="{{ $data->n_piece }}"
                        style="padding-top: 5px;padding-bottom: 5px;">
                        {{ $data->n_piece ?: '-' }}
                    </td>
                    <td class="montant-cell" data-montant="{{ $data->montant }}" data-devise="{{ $data->devise }}"
                        style="padding-top: 5px;padding-bottom: 5px;">
                        <?php
                        if ($data->devise == 0) {
                            echo number_format($data->montant, 2, ',', ' ') . 'USD';
                        } else {
                            echo number_format($data->montant, 2, ',', ' ') . 'CDF';
                        }
                        ?>
                    </td>
                    <td class="date-cell"
                        data-date="{{ \Carbon\Carbon::createFromFormat('d/m/Y', $data->date_depense)->format('Y-m-d') }}"
                        style="padding-top: 5px;padding-bottom: 5px;">
                        {{ $data->date_depense }}
                    </td>
                    <td class="text-center" style="padding-top: 5px;padding-bottom: 5px;">
                        <?php if(strlen(trim($data->preuve_de_sortie)) > 0){ ?>
                        <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-file-text text-success"></i></a>
                        &nbsp;
                        <?php }else{ ?>
                        <a id="#" href="#"><i class="zmdi zmdi-close-circle text-danger"></i></a> &nbsp;
                        <?php }?>
                    </td>
                    <script>
                        $("#edit_<?= $i ?>").click(function(e) {
                            e.preventDefault();
                            var url = "<?= $data->preuve_de_sortie ?>";
                            const extension = url.split('.').pop().toLowerCase();
                            const typeMap = {
                                'png': 'image',
                                'jpg': 'image',
                                'jpeg': 'image',
                                'gif': 'image',
                                'bmp': 'image',
                                'webp': 'image',
                                'svg': 'image',
                                'txt': 'texte',
                                'pdf': 'pdf',
                                'doc': 'word',
                                'docx': 'word',
                                'odt': 'texte',
                                'xls': 'excel',
                                'xlsx': 'excel',
                                'ods': 'tableur',
                                'ppt': 'powerpoint',
                                'pptx': 'powerpoint',
                                'odp': 'présentation',
                                'zip': 'archive',
                                'rar': 'archive',
                                '7z': 'archive',
                                'mp3': 'audio',
                                'wav': 'audio',
                                'mp4': 'vidéo',
                                'avi': 'vidéo',
                                'mov': 'vidéo',
                                'html': 'page web',
                                'css': 'feuille de style',
                                'js': 'script JavaScript',
                                'json': 'données JSON',
                                'xml': 'données XML'
                            };
                            var type = typeMap[extension] || 'type inconnu';
                            if (type == "image") {
                                $("#titre_modal_fichier").html("Visualisation : " + url.split('/').pop());
                                $("#fichier_content").html('<img src="' + url +
                                    '" class="img-fluid" style="max-height:100%;width: 100%;" />');
                                $("#btn_detail_fichier").trigger("click");
                            }
                            if (type == "excel") {
                                var fileUrl = "{{ asset('') }}" + url;
                                fileUrl = fileUrl.replace(/([^:]\/)\/+/g, "$1");
                                $("#excelModalTitle").html("Visualisation : " + url.split('/').pop());
                                $("#modalExcelViewer").modal({
                                    backdrop: 'static',
                                    keyboard: false,
                                    show: true
                                });
                                var container = document.getElementById("excelViewerContainer");
                                container.innerHTML =
                                    '<div style="text-align:center; padding:50px;"><i class="zmdi zmdi-spinner zmdi-hc-spin" style="font-size: 40px;"></i><br>Chargement du fichier Excel...</div>';
                                fetch(fileUrl)
                                    .then(response => {
                                        if (!response.ok) throw new Error("HTTP " + response.status);
                                        return response.arrayBuffer();
                                    })
                                    .then(arrayBuffer => {
                                        var workbook = XLSX.read(arrayBuffer, {
                                            type: 'array'
                                        });
                                        container.innerHTML = '';
                                        new ExcelViewer("#excelViewerContainer", workbook, url.split('/').pop());
                                    })
                                    .catch(err => {
                                        container.innerHTML =
                                            `<div class="alert alert-danger" style="margin: 20px;"><i class="zmdi zmdi-alert-circle"></i> Erreur de chargement : ${err.message}<br>Vérifiez que le fichier existe et est accessible.</div>`;
                                        console.error(err);
                                    });
                                class ExcelViewer {
                                    constructor(container, workbook, title = "Classeur") {
                                        this.container = typeof container === 'string' ? document.querySelector(container) :
                                            container;
                                        if (!this.container) throw new Error("Conteneur introuvable");
                                        this.workbook = workbook;
                                        this.title = title;
                                        this.currentSheetName = null;
                                        this.buildUI();
                                        this.initSheets();
                                    }
                                    buildUI() {
                                        this.container.innerHTML =
                                            `<div class="excel-viewer" style="padding: 10px;"><h5 style="color: #1e466e; margin-bottom: 15px;display:none;"><i class="zmdi zmdi-chart"></i> ${this.title}</h5><div class="excel-sheet-selector" style="display: none; margin-bottom: 10px; background: #f1f5f9; padding: 8px 12px; border-radius: 8px;"><label style="margin-right: 10px;padding-top:8px;color:black;"><i class="zmdi zmdi-tab"></i> Feuille :</label><select class="excel-sheet-select" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #ccc;"></select></div><div class="excel-info" style="background: #e3f2fd; padding: 8px 12px; border-radius: 8px; margin-bottom: 10px; font-size: 13px;"></div><div class="excel-search-bar" style="display: flex; gap: 8px; margin-bottom: 10px;"><input type="text" class="excel-search-input" placeholder="Rechercher dans le tableau..." style="flex: 2; padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px;"><button class="excel-search-btn btn btn-info btn-sm" style="background: #17a2b8; border: none;"><i class="zmdi zmdi-search"></i> Rechercher</button><button class="excel-clear-btn btn btn-secondary btn-sm" style="background: #6c757d; border: none;"><i class="zmdi zmdi-close"></i> Effacer</button></div><div class="excel-table-wrapper" style="overflow: auto; max-height: 500px; border: 1px solid #dee2e6; border-radius: 8px; background: white;"></div><div class="excel-selected-cell" style="margin-top: 12px; padding: 10px; background: #f1f5f9; border-radius: 8px; display: flex; gap: 10px; align-items: center;"><label style="margin: 0; font-weight: bold; color: #1e466e;"><i class="zmdi zmdi-info"></i> Cellule sélectionnée :</label><input type="text" class="excel-selected-value" readonly style="flex: 1; padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; background: white; font-family: monospace;"></div></div>`;
                                        this.infoDiv = this.container.querySelector('.excel-info');
                                        this.sheetSelectorDiv = this.container.querySelector('.excel-sheet-selector');
                                        this.sheetSelect = this.container.querySelector('.excel-sheet-select');
                                        this.searchInput = this.container.querySelector('.excel-search-input');
                                        this.searchBtn = this.container.querySelector('.excel-search-btn');
                                        this.clearBtn = this.container.querySelector('.excel-clear-btn');
                                        this.tableWrapper = this.container.querySelector('.excel-table-wrapper');
                                        this.selectedCellInput = this.container.querySelector('.excel-selected-value');
                                        this.searchBtn.addEventListener('click', () => this.highlightSearch());
                                        this.clearBtn.addEventListener('click', () => this.clearHighlights());
                                        this.sheetSelect.addEventListener('change', (e) => {
                                            this.currentSheetName = e.target.value;
                                            this.renderCurrentSheet();
                                        });
                                    }
                                    initSheets() {
                                        const sheetNames = this.workbook.SheetNames;
                                        if (sheetNames.length === 0) {
                                            this.showMessage("Aucune feuille trouvée", true);
                                            return;
                                        }
                                        if (sheetNames.length > 1) {
                                            this.sheetSelectorDiv.style.display = 'flex';
                                            this.sheetSelect.innerHTML = sheetNames.map(n =>
                                                `<option value="${n}">${n}</option>`).join('');
                                        }
                                        this.currentSheetName = sheetNames[0];
                                        this.renderCurrentSheet();
                                    }
                                    renderCurrentSheet() {
                                        if (!this.workbook || !this.currentSheetName) return;
                                        const sheet = this.workbook.Sheets[this.currentSheetName];
                                        const html = XLSX.utils.sheet_to_html(sheet, {
                                            editable: false
                                        });
                                        this.tableWrapper.innerHTML = `<div style="overflow-x: auto;">${html}</div>`;
                                        const table = this.tableWrapper.querySelector('table');
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
                                                td.style.border = '1px solid #d4dce6';
                                                td.style.padding = '6px 8px';
                                                td.style.cursor = 'pointer';
                                                td.addEventListener('click', (e) => {
                                                    e.stopPropagation();
                                                    this.onCellClick(td);
                                                });
                                            });
                                            table.querySelectorAll('tr:nth-child(even) td').forEach(td => {
                                                td.style.backgroundColor = '#fafcff';
                                            });
                                        }
                                        this.selectedCellInput.value = '';
                                        this.showMessage(`📄 Feuille : ${this.currentSheetName}`);
                                    }
                                    onCellClick(cell) {
                                        if (cell.classList.contains('cell-highlight')) {
                                            cell.classList.remove('cell-highlight');
                                        } else {
                                            cell.classList.remove('cell-search');
                                            cell.classList.add('cell-highlight');
                                        }
                                        const value = cell.innerText || cell.textContent;
                                        this.selectedCellInput.value = value;
                                        this.showMessage(
                                            `📌 Contenu : "${value.substring(0, 80)}${value.length > 80 ? '…' : ''}"`);
                                    }
                                    clearHighlights() {
                                        const cells = this.tableWrapper.querySelectorAll('td, th');
                                        cells.forEach(cell => cell.classList.remove('cell-highlight', 'cell-search'));
                                        this.showMessage("✅ Entourages effacés");
                                    }
                                    highlightSearch() {
                                        const text = this.searchInput.value.trim();
                                        if (!text) {
                                            this.showMessage("⚠️ Entrez un texte à rechercher", true);
                                            return;
                                        }
                                        this.clearHighlights();
                                        const cells = this.tableWrapper.querySelectorAll('td');
                                        let found = [];
                                        cells.forEach(cell => {
                                            if (cell.innerText.toLowerCase().includes(text.toLowerCase())) {
                                                cell.classList.add('cell-search');
                                                found.push(cell);
                                            }
                                        });
                                        if (found.length === 0) {
                                            this.showMessage(`❌ Aucune cellule ne contient "${text}"`, true);
                                        } else {
                                            this.showMessage(`🔍 ${found.length} cellule(s) trouvée(s) pour "${text}".`);
                                            this.scrollToCell(found[0]);
                                        }
                                    }
                                    scrollToCell(cell) {
                                        const wrapper = this.tableWrapper;
                                        const cellRect = cell.getBoundingClientRect();
                                        const wrapperRect = wrapper.getBoundingClientRect();
                                        const scrollTop = wrapper.scrollTop + cellRect.top - wrapperRect.top - wrapperRect
                                            .height / 2 + cellRect.height / 2;
                                        wrapper.scrollTo({
                                            top: scrollTop,
                                            behavior: 'smooth'
                                        });
                                        cell.classList.add('first-found');
                                        setTimeout(() => cell.classList.remove('first-found'), 800);
                                    }
                                    showMessage(msg, isError = false) {
                                        this.infoDiv.innerHTML = msg;
                                        this.infoDiv.style.backgroundColor = isError ? '#f8d7da' : '#e3f2fd';
                                        this.infoDiv.style.color = isError ? '#721c24' : '#0c5460';
                                        if (!isError) {
                                            setTimeout(() => {
                                                if (this.infoDiv.style.backgroundColor !== '#f8d7da') {
                                                    this.infoDiv.style.backgroundColor = '#e3f2fd';
                                                    this.infoDiv.style.color = '#0c5460';
                                                }
                                            }, 3000);
                                        }
                                    }
                                }
                            }
                            if (type == "word") {
                                var all_url = "<?= asset('') ?>" + url;
                                $("#titre_modal_fichier").html("Visualisation : " + url.split('/').pop());
                                document.getElementById("fichier_content").innerHTML =
                                    '<iframe src="https://docs.google.com/viewer/viewer?url=' + encodeURIComponent(all_url) +
                                    '&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
                                $("#btn_detail_fichier").trigger("click");
                            }
                            if (type == "pdf") {
                                var all_url = "<?= asset('') ?>" + url;
                                $("#titre_modal_fichier").html("Visualisation : " + url.split('/').pop());
                                document.getElementById("fichier_content").innerHTML =
                                    '<iframe src="https://docs.google.com/viewer/viewer?url=' + encodeURIComponent(all_url) +
                                    '&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
                                $("#btn_detail_fichier").trigger("click");
                            }
                            if (type == "texte") {
                                var all_url = "<?= asset('') ?>" + url;
                                $("#titre_modal_fichier").html("Visualisation : " + url.split('/').pop());
                                document.getElementById("fichier_content").innerHTML = '<iframe src="' + all_url +
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
                    </script>
                </tr>
                {{ !$i++ }}
            @endforeach
        </tbody>
    </table>
</div>
