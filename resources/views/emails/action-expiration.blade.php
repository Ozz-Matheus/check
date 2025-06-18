@extends('emails.layout.theme')

@section('title')
Acción próxima a vencer
@endsection

@section('content')
<p>Hola {{ $user->name }},</p>

<p>Esta próxima a vencer una Acción </p>
<p>en el sistema {{ config('app.name') }} :</p>

<ul>
    <li><strong>Título:</strong> {{ $action->title }}</li>
    <li><strong>Tipo:</strong> {{ $action->type->label }}</li>
    <li><strong>Responsable :</strong> {{ $action->responsibleBy->name ?? 'Sistema' }}</li>
    <li><strong>Vencimiento:</strong> {{ $action->deadline?->format('d/m/Y') }}</li>
</ul>

<p>
    <a href="{{ $action->url }}"
       class="button">
        Ver detalles
    </a> 
</p>
@endsection
