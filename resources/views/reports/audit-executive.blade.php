<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @charset "UTF-8";
        body { font-family: sans-serif; font-size: 12px; color: #000; }
        h1, h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; }
        .titulo { font-size: 16px; font-weight: bold; margin-top: 30px; }
    </style>
</head>
<body>

    <h1>INFORME EJECUTIVO DE AUDITORÍA</h1>

    <table>
        <tr>
            <td><strong>PROCESO:</strong></td>
            <td>{{ $audit->process->title }}</td>
            <td><strong>SUBPROCESO:</strong></td>
            <td>{{ $audit->subprocess->title }}</td>
        </tr>
        <tr>
            <td><strong>FECHA DE DILIGENCIAMIENTO:</strong></td>
            <td>{{ now()->format('d/m/Y') }}</td>
            <td><strong>LÍDER DEL PROCESO:</strong></td>
            <td>{{ $audit->subprocess->leader->name }}</td>
        </tr>
    </table>

    <div class="titulo">Detalle de auditoría</div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Audit Date</th>
                <th>Status</th>
                <th>Priority</th>
            </tr>
        </thead>
        <tbody>
            <tr class="audit">
                <td>{{ $audit->classification_code }}</td>
                <td>{{ $audit->title }}</td>
                <td>{{ \Carbon\Carbon::parse($audit->audit_date)->format('d/m/Y') }}</td>
                <td>{{ $audit->status->label }}</td>
                <td>{{ $audit->priority->title }}</td>
            </tr>
        </tbody>
    </table>


</body>
</html>