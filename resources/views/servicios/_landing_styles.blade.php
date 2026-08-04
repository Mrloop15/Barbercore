
@once
@push('styles')
<link rel="stylesheet" href="{{ asset('css/modules/service-form.css') }}">
@endpush
@endonce

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.querySelector('[data-service-image-input]');
        const preview = document.querySelector('[data-service-image-preview]');
        const empty = document.querySelector('[data-service-image-empty]');

        if (!input || !preview) return;

        let previewUrl;
        input.addEventListener('change', () => {
            const file = input.files?.[0];
            if (!file) return;

            if (previewUrl) URL.revokeObjectURL(previewUrl);
            previewUrl = URL.createObjectURL(file);
            preview.src = previewUrl;
            preview.alt = `Vista previa de ${file.name}`;
            preview.hidden = false;
            if (empty) empty.hidden = true;
        });
    });
</script>
