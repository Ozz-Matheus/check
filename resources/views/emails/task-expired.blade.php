@extends('emails.layout.theme')

@section('title')
Tarea Vencida
@endsection

@section('content')
<p>Hola {{ $user->name }},</p>

<p>La Tarea ha alcanzado su fecha de vencimiento.</p>
<p>en el sistema {{ config('app.name') }} :</p>

<ul>
    <li><strong>Título:</strong> {{ $task->title }}</li>
    <li><strong>Detalles:</strong> {{ $task->detail }}</li>
    <li><strong>Responsable :</strong> {{ $task->responsibleBy->name ?? 'Sistema' }}</li>
    <li><strong>Vencimiento:</strong> {{ $task->limit_date?->format('d/m/Y') }}</li>
</ul>

<p>
    <a href="{{ route('filament.dashboard.resources.actions.view', ['record' => $task->action->id ]) }}"
       class="button">
        Ver detalles
    </a>
</p>
@endsection
