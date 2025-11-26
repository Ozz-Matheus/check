@extends('emails.layout.theme')

@section('title')
    Nuevo Documento Creado
@endsection

@section('content')
    <p>Hola <strong>{{ $user->name }}</strong>,</p>

    <p>Te informamos que se ha creado un nuevo documento en {{ config('app.name') }}.</p>

    <div class="info-card">
        <ul>
            <li>
                <strong>Título:</strong>
                <span>{{ $doc->title }}</span>
            </li>
            <li>
                <strong>Creado por:</strong>
                <span>{{ $doc->createdBy->name ?? 'Sistema' }}</span>
            </li>
            <li>
                <strong>Fecha:</strong>
                <span>{{ $doc->created_at->format('d/m/Y H:i') }}</span>
            </li>
        </ul>
    </div>

    <p style="margin-top: 28px;">
        Para revisar el documento y gestionar sus versiones, haz clic en el siguiente botón:
    </p>

    <p style="text-align: center;">
        <a href="{{ route('filament.dashboard.resources.docs.versions.index', ['doc' => $doc->id]) }}" class="button">
            Ver Documento
        </a>
    </p>

    <div class="alert-box">
        <img width="24" height="24" src="{{ asset('images/email-icons/doc.png?01') }}" alt="Icono de Documento">
        <span>Este registro ya está disponible y listo para comenzar a gestionar.</span>
    </div>
@endsection
