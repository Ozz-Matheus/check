@extends('emails.layout.theme')

@section('title')
Una tarea se ha completado
@endsection

@section('content')
<p>Hola {{ $user->name }},</p>

<p>Se ha completado una acción </p>
<p>en el sistema {{ config('app.name') }} :</p>

<ul>
    <li><strong>Título:</strong> {{ $task->title }}</li>
    <li><strong>Detalles:</strong> {{ $task->detail }}</li>
    <li><strong>Responsable :</strong> {{ $task->responsibleBy->name ?? 'Sistema' }}</li>
    <li><strong>Fecha:</strong> {{ $task->created_at->format('d/m/Y') }}</li>
</ul>

<p>
    <a href="{{ $task->action->url }}"
       class="button">
        Ver más
    </a> 
</p>
@endsection
