<form id="form_edit" action="#" method="post">
    @csrf
    <div class="row">
        <div class="col-12">
            <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">INVITATION</h4>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr style="display: none;">
                    <th>Nom</th>
                    <th>Nom</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-info text-info"></i> Numero de l'invitation</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $invitations->numero_invitation ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-info text-info"></i> Objet</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $invitations->libelle ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-calendar text-info"></i> Date invitation</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $invitations->date_invitation ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-alarm text-info"></i> Heure invitation</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $invitations->heure_invitation ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-calendar text-info"></i> Date document</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $invitations->date_document ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-info text-info"></i> Verbalisateur</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        @foreach ($verbalisateurs as $data)
                        @if ($data->id == $invitations->verbalisateur_id)
                        {{ $data->nom }}
                        @endif
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-edit text-info"></i> Signer par</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $invitations->signer_par ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-library text-info"></i> Statut</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $invitations->statut ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr>
</form>
