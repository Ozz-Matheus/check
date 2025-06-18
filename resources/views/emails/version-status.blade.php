@extends('emails.layout.theme')

@section('title')
Un documento ha cambiado de estado
@endsection

@section('content')
<p>Hola {{ $user->name }},</p>

<p>El estado de un documento ha cambiado </p>
<p>en el sistema {{ config('app.name') }} :</p>

<ul>
    <li>
        <strong>Título:</strong> {{ $version->file?->name }}
    </li>
    <li>
        <strong>Ha cambiado a :</strong> {{ ucfirst( strtolower( $status->label ) ) }}
    </li>
    <li>
        <strong>Creado por :</strong> {{ $version->createdBy?->name }}
    </li>
    @if($changeReason)
        <li><strong>Información importante sobre el estado :</strong> {{ $changeReason }}</li>
    @endif
    <li><strong>Fecha:</strong> {{ $version->created_at->format('d/m/Y') }}</li>
</ul>

<p>
    <a href="{{ route('filament.dashboard.resources.docs.versions.index', ['doc' => $version->doc->id ]) }}"
       class="button">
        Ver detalles
    </a>
</p>
@endsection
