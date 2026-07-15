<?php

use App\Models\Ressources;
use App\Models\Droit_fichiers;
?>
<h4 style="color:rgba(0, 0, 0, 0.6);display:none;"><i style="font-size: 40px;" class="zmdi zmdi-settings text-info"></i> Droits
    <select style="border-color: transparent;padding-top: 0px;padding-bottom: 0px;font-size: 17px;color:rgba(0, 0, 0, 0.6);" name="groupe_select" id="groupe_select">
        @foreach ($utilisateurs as $data)
        @if ($data->id == $groupe_id)
        <option selected value="{{ $data->id }}"> {{ strtolower($data->nom) }}</option>
        @else
        <option value="{{ $data->id }}"> {{ strtolower($data->nom) }}</option>
        @endif
        @endforeach
    </select>
</h4>
<div style="margin-bottom: 100px;" id="content_groupe" class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateurs</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Email</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;"> <i class="zmdi zmdi-eye text-info"></i> Voir</a></th>
                        <th style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-download text-dark"></i> Telecharger</a></th>
                        <th style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-edit text-success"></i> Modifier</a></th>
                        <th style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-delete text-danger"></i> Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    {{! $i = 1; }}
                    @foreach ($utilisateurs as $data)
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->name }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->email }}</td>
                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                            @if (Droit_fichiers::where(['user_id' => $data->id, 'fichier_documents_id' => $fichier_document_id, 'numero_permission' => 1])->get()->count() > 0)
                                <a id="voir__<?= $i ?>" href="#"><i class="zmdi zmdi-check-square"></i></a>
                            @else
                                <a id="voir__<?= $i ?>" href="#"><i class="zmdi zmdi-square-o"></i></a>
                            @endif
                        </td>
                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                            @if (Droit_fichiers::where(['user_id' => $data->id, 'fichier_documents_id' => $fichier_document_id, 'numero_permission' => 2])->get()->count() > 0)
                                <a id="telecharger__<?= $i ?>" href="#"><i class="zmdi zmdi-check-square"></i></a>
                            @else
                                <a id="telecharger__<?= $i ?>" href="#"><i class="zmdi zmdi-square-o"></i></a>
                            @endif
                        </td>
                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                            @if (Droit_fichiers::where(['user_id' => $data->id, 'fichier_documents_id' => $fichier_document_id, 'numero_permission' => 3])->get()->count() > 0)
                                <a id="modifier__<?= $i ?>" href="#"><i class="zmdi zmdi-check-square"></i></a>
                            @else
                                <a id="modifier__<?= $i ?>" href="#"><i class="zmdi zmdi-square-o"></i></a>
                            @endif
                        </td>
                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                            @if (Droit_fichiers::where(['user_id' => $data->id, 'fichier_documents_id' => $fichier_document_id, 'numero_permission' => 4])->get()->count() > 0)
                                <a id="supprimer__<?= $i ?>" href="#"><i class="zmdi zmdi-check-square"></i></a>
                            @else
                                <a id="supprimer__<?= $i ?>" href="#"><i class="zmdi zmdi-square-o"></i></a>
                            @endif
                        </td>
                    </tr>
                    <script>
                        $("#voir__<?= $i ?>").click(function(e) {
                            e.preventDefault();
                            $.get("{{ url('/permission_fichier') }}", {
                                user_id: "<?= $data->id ?>",
                                fichier_document_id: "<?= $fichier_document_id ?>",
                                numero_permission: 1,
                            }, function(etat) 
                            {
                                if (etat == 1) {
                                    $("#voir__<?= $i ?>").html('<i class="zmdi zmdi-check-square"></i></a>');
                                } else {
                                    $("#voir__<?= $i ?>").html('<i class="zmdi zmdi-square-o"></i></a>');
                                }
                            });
                        });
                        $("#telecharger__<?= $i ?>").click(function(e) {
                            e.preventDefault();
                            $.get("{{ url('/permission_fichier') }}", {
                                user_id: "<?= $data->id ?>",
                                fichier_document_id: "<?= $fichier_document_id ?>",
                                numero_permission: 2,
                            }, function(etat) 
                            {
                                if (etat == 1) {
                                    $("#telecharger__<?= $i ?>").html('<i class="zmdi zmdi-check-square"></i></a>');
                                } else {
                                    $("#telecharger__<?= $i ?>").html('<i class="zmdi zmdi-square-o"></i></a>');
                                }
                            });
                        });
                        $("#modifier__<?= $i ?>").click(function(e) {
                            e.preventDefault();
                            $.get("{{ url('/permission_fichier') }}", {
                                user_id: "<?= $data->id ?>",
                                fichier_document_id: "<?= $fichier_document_id ?>",
                                numero_permission: 3,
                            }, function(etat) 
                            {
                                if (etat == 1) {
                                    $("#modifier__<?= $i ?>").html('<i class="zmdi zmdi-check-square"></i></a>');
                                } else {
                                    $("#modifier__<?= $i ?>").html('<i class="zmdi zmdi-square-o"></i></a>');
                                }
                            });
                        });
                        $("#supprimer__<?= $i ?>").click(function(e) {
                            e.preventDefault();
                            $.get("{{ url('/permission_fichier') }}", {
                                user_id: "<?= $data->id ?>",
                                fichier_document_id: "<?= $fichier_document_id ?>",
                                numero_permission: 4,
                            }, function(etat) 
                            {
                                if (etat == 1) {
                                    $("#supprimer__<?= $i ?>").html('<i class="zmdi zmdi-check-square"></i></a>');
                                } else {
                                    $("#supprimer__<?= $i ?>").html('<i class="zmdi zmdi-square-o"></i></a>');
                                }
                            });
                        });
                    </script>
                    {{! $i++; }}
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

