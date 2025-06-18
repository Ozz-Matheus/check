@extends('emails.layout.theme')

@section('title')
Un documento ha sido creado
@endsection

@section('content')
<p>Hola {{ $user->name }},</p>

<p>Se ha creado un nuevo documento </p>
<p>en el sistema {{ config('app.name') }} :</p>

<ul>
    <li><strong>Título:</strong> {{ $doc->title }}</li>
    <li><strong>Creado por:</strong> {{ $doc->createdBy->name ?? 'Sistema' }}</li>
    <li><strong>Fecha:</strong> {{ $doc->created_at->format('d/m/Y') }}</li>
</ul>

<p>
    <a href="{{ route('filament.dashboard.resources.docs.versions.index', ['doc' => $doc->id ]) }}"
       class="button">
        Ver Documento
    </a>
</p>
@endsection
