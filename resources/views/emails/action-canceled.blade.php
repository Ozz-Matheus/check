@extends('emails.layout.theme')

@section('title')
Una Acción se ha Cancelado
@endsection

@section('content')
<p>Hola {{ $user->name }},</p>

<p>Se ha cancelado una acción en el sistema {{ config('app.name') }} :</p>

<ul>
    <li><strong>Título:</strong> {{ $action->title }}</li>
    <li><strong>Tipo:</strong> {{ $action->type->label }}</li>
    <li><strong>Responsable :</strong> {{ $action->responsibleBy->name ?? 'Sistema' }}</li>
    <li><strong>Motivo de cancelación : </strong> {{ $action->reason_for_cancellation }}</li>
    <li><strong>Fecha:</strong> {{ $action->created_at->format('d/m/Y') }}</li>
</ul>

<p>
    <a href="{{ $action->url }}"
       class="button">
        Ver detalles
    </a> 
</p>
@endsection
