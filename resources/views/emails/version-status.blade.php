@extends('emails.layout.theme')

@section('title')
    Cambio de Estado del Documento
@endsection

@section('content')
    <p>Hola <strong>{{ $user->name }}</strong>,</p>

    <p>Te informamos que el estado de un documento ha sido actualizado en {{ config('app.name') }}.</p>

    <div class="info-card">
        <ul>
            <li>
                <strong>Código de clasificación:</strong>
                <span>{{ $version->doc?->classification_code }}</span>
            </li>
            <li>
                <strong>Documento:</strong>
                <span>{{ $version->file?->name }}</span>
            </li>
            <li>
                <strong>Nuevo Estado:</strong>
                <span>
                    <span class="status-badge {{ $status->color }}">
                        {{ ucfirst(strtolower($status->label)) }}
                    </span>
                </span>
            </li>
            <li>
                <strong>Subido por:</strong>
                <span>{{ $version->createdBy?->name }}</span>
            </li>
            <li>
                <strong>Fecha:</strong>
                <span>{{ $version->created_at->format('d/m/Y H:i') }}</span>
            </li>
        </ul>
    </div>

    @if ($changeReason)
        <div class="alert">
            <strong>Información Importante</strong>
            {{ $changeReason }}
        </div>
    @endif

    <p style="margin-top: 28px;">
        Para revisar todos los detalles y gestionar este documento, haz clic en el siguiente botón:
    </p>

    <p style="text-align: center;">
        <a href="{{ route('filament.dashboard.resources.docs.versions.index', ['doc' => $version->doc->id]) }}"
            class="button">
            Ver Detalles del Documento
        </a>
    </p>

    <div class="alert-box">
        <img width="24" height="24" src="{{ asset('images/email-icons/version.png') }}" alt="Icono de Documento">
        <span>Accede a este documento cuando quieras desde tu panel de control.</span>
    </div>
@endsection
