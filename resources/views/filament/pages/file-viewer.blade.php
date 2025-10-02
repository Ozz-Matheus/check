<x-filament::page>
    <div class="space-y-4">
        <h2 class="text-xl font-bold">{{ $file->name }}</h2>
        <p><strong>MIME:</strong> {{ $file->readable_mime_type }}</p>
        <p><strong>Size:</strong> {{ $file->readable_size }}</p>
        <br>
        @if(Str::contains($file->mime_type, 'pdf'))
            <iframe src="{{ $file->url() }}"
                    class="w-full h-[800px] border rounded shadow"
                    style="height: 100vh;">
            </iframe>
        <br>
        @else
            <div class="w-full h-[800px] border rounded shadow">
                <iframe
                    src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode( config('app.url') . $file->url() ) }}"
                    class="w-full h-full rounded"
                    frameborder="0">
                </iframe>
            </div>
        @endif
        <br>
        <a
            href="{{ $file->url() }}"
            class="px-4 py-2 bg-primary-600 text-white rounded"
            download="{{ $file->name }}">
            {{ __('Download File') }}
        </a>
    </div>
</x-filament::page>
