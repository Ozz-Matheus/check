<x-filament::page>
    <div class="space-y-4">
        {{-- <h2 class="text-xl font-bold">{{ $file->name }}</h2> --}}
        <p><strong>MIME:</strong> {{ $file->readable_mime_type }}</p>
        <p><strong>Size:</strong> {{ $file->readable_size }}</p>

        @if ($file->isPdf())
            {{-- PDF embebido directamente --}}
            <iframe
                src="{{ $file->url() }}"
                class="w-full h-screen border rounded shadow"
                title="PDF Viewer"
            ></iframe>

        @elseif ($file->isOfficeEmbeddable())
            @php
                // Si el doc es confidencial y quieres evitar exponerlo a un tercero (Office Viewer),
                // puedes deshabilitar la vista y forzar descarga:
                $allowExternalViewer = !optional($doc)->confidential;
            @endphp

            @if ($allowExternalViewer)
                {{-- Office Online Viewer necesita URL ABSOLUTA y accesible públicamente --}}
                <iframe
                    src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($file->absoluteUrl()) }}"
                    class="w-full h-screen border rounded shadow"
                    title="Office Viewer"
                ></iframe>
            @else
                <div class="p-4 border rounded bg-yellow-50">
                    {{ __('This document is confidential. Download it to view it') }}
                </div>
            @endif

        @else
            <div class="p-4 border rounded bg-gray-50">
                {{ __('Preview not available for this file type') }}
            </div>
        @endif

        <div class="pt-2">
            <x-filament::button
                :href="$file->url()"
                tag="a"
                icon="heroicon-m-arrow-down-tray"
                :download="$file->name"
            >
                {{ __('Download') }}
            </x-filament::button>
        </div>
    </div>
</x-filament::page>
