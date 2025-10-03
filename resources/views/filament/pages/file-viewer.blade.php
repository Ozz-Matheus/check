@php
    /** @var \App\Models\File $file */
    /** @var \App\Models\Doc|null $doc */
@endphp

<x-filament::page>
    <div class="space-y-4">
        <h2 class="text-xl font-bold">{{ $file->name }}</h2>
        <p><strong>MIME:</strong> {{ $file->readable_mime_type }}</p>
        <p><strong>Size:</strong> {{ $file->readable_size }}</p>

        @if ($file->isPdf())
            {{-- PDF embebido directamente --}}
            <iframe
                src="{{ $file->url() }}"
                class="w-full h-[80vh] border rounded shadow"
                title="PDF Viewer"
            ></iframe>

        @elseif ($file->isOfficeEmbeddable())
            @php
                // Si el doc es confidencial y quieres evitar exponerlo a un tercero (Office Viewer),
                // puedes deshabilitar la vista y forzar descarga:
                $allowExternalViewer = !optional($doc)->display_restriction;
            @endphp

            @if ($allowExternalViewer)
                {{-- Office Online Viewer necesita URL ABSOLUTA y accesible públicamente --}}
                <iframe
                    src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($file->absoluteUrl()) }}"
                    class="w-full h-[80vh] border rounded shadow"
                    title="Office Viewer"
                ></iframe>
            @else
                <div class="p-4 border rounded bg-yellow-50">
                    {{ __('Este documento es confidencial. Descárgalo para verlo.') }}
                </div>
            @endif

        @else
            <div class="p-4 border rounded bg-gray-50">
                {{ __('Vista previa no disponible para este tipo de archivo.') }}
            </div>
        @endif

        <div class="pt-2">
            <a
                href="{{ $file->url() }}"
                class="px-4 py-2 bg-primary-600 text-white rounded"
                download="{{ $file->name }}"
            >
                {{ __('Descargar archivo') }}
            </a>
        </div>
    </div>
</x-filament::page>
