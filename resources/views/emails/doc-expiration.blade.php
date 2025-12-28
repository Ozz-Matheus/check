@extends('emails.layout.theme')

@section('title')
Documento se próxima a vencer
@endsection

@section('content')
<p>Hola {{ $user->name }},</p>

<p>Esta se próxima a vencer un Documento </p>
<p>en el sistema {{ config('app.name') }} :</p>

<ul>
    <li><strong>Título:</strong> {{ $doc->title }}</li>
    <li><strong>Tipo:</strong> {{ $doc->type->label }}</li>
    <li><strong>Creador :</strong> {{ $doc->createdBy->name ?? 'Sistema' }}</li>
    <li><strong>Vencimiento:</strong> {{ $doc->central_expiration_date?->format('d/m/Y') }}</li>
    <li><strong>Última Versión:</strong> {{ $doc->latestVersion?->version }}</li>
</ul>

<p>
    <a href="{{ route('filament.dashboard.resources.docs.versions.index', ['doc' => $doc->id]) }}"
       class="button">
        Ver detalles
    </a> 
</p>
@endsection
