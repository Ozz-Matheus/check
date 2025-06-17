@extends('emails.layout.theme')

@section('title')
Nuevo Registro Creado
@endsection

@section('content')
<p>Hola {{ $user->name }},</p>

<p>Se ha creado un nuevo registro en el sistema {{ config('app.name') }} :</p>

<ul>
    <li><strong>Título:</strong> {{ $doc->title }}</li>
    <li><strong>Creado por:</strong> {{ $doc->createdBy->name ?? 'Sistema' }}</li>
    <li><strong>Fecha:</strong> {{ $doc->created_at->format('d/m/Y') }}</li>
</ul>

<p>
    <a href="{{ route('filament.dashboard.resources.docs.versions.index', ['doc' => $doc->id ]) }}"
       class="button">
        Ver Registro
    </a>
</p>
@endsection
