@extends('emails.layout.theme')

@section('title')
Una Acción ha sido Creada
@endsection

@section('content')
<p>Hola {{ $user->name }},</p>

<p>Se ha creado un nueva acción </p>
<p>en el sistema {{ config('app.name') }} :</p>

<ul>
    <li><strong>Título:</strong> {{ $action->title }}</li>
    <li><strong>Tipo:</strong> {{ $action->type->label }}</li>
    <li><strong>Responsable :</strong> {{ $action->responsibleBy->name ?? 'Sistema' }}</li>
    <li><strong>Fecha:</strong> {{ $action->created_at->format('d/m/Y') }}</li>
</ul>

<p>
    <a href="{{ $action->url }}"
       class="button">
        Ver Acción
    </a> 
</p>
@endsection
