<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des prestations</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #0a192f;
            font-size: 20px;
        }
        .date-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 8px;
            border-left: 4px solid #e31b23;
            padding-left: 10px;
            color: #0a192f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background: #1e3a5f;
            color: white;
            font-weight: bold;
        }
        .text-success { color: #10b981; }
        .text-info { color: #0ea5e9; }
        .text-danger { color: #e31b23; }
        footer {
            text-align: center;
            font-size: 9px;
            margin-top: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Liste des prestations</h1>
    @foreach ($date_date as $valeur)
        <?php $dateFormatee = date('d/m/Y', strtotime($valeur)); ?>
        <div class="date-title">📅 DATE : {{ $dateFormatee }}</div>
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Matricule</th>
                    <th>Nom</th>
                    <th>Fonction</th>
                    <th>Prestation</th>
                    <th>Horaire</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach ($prestations as $data)
                    @if ($valeur == $data['date'])
                        <?php $user = \App\Models\User::find($data['user_id']); ?>
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $user->matricule ?? '' }}</td>
                            <td>{{ $user->name ?? '' }}</td>
                            <td>{{ \App\Models\Groupes::find($user->role)->nom ?? '' }}</td>
                            <td>
                                @if($data['service'] == 'journée')
                                    <span class="text-success">journée</span>
                                @elseif($data['service'] == 'nuit')
                                    <span class="text-info">nuit</span>
                                @else
                                    <span class="text-danger">repos</span>
                                @endif
                            </td>
                            <td>
                                @if($data['horaire'] == '06h00 - 18h00')
                                    <span class="text-success">06h00-18h00</span>
                                @elseif($data['horaire'] == '18h00 - 06h00')
                                    <span class="text-info">18h00-06h00</span>
                                @else
                                    <span class="text-danger">repos</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endforeach
    <footer>Généré le {{ date('d/m/Y à H:i') }}</footer>
</body>
</html>