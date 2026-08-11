@props(['name', 'size' => 18])

@php($viewBox = $name === 'whatsapp' ? '0 0 720 720' : '0 0 24 24')

<svg {{ $attributes->merge(['class' => 'ui-icon', 'width' => $size, 'height' => $size, 'viewBox' => $viewBox, 'preserveAspectRatio' => 'xMidYMid meet', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '2', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true', 'focusable' => 'false']) }}>
    @switch($name)
        @case('eye') <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/> @break
        @case('edit') <path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/> @break
        @case('trash') <path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="m19 6-1 14H6L5 6"/><path d="M10 11v5M14 11v5"/> @break
        @case('check') <path d="m5 12 4 4L19 6"/> @break
        @case('close') <path d="M18 6 6 18M6 6l12 12"/> @break
        @case('power') <path d="M12 2v10"/><path d="M18.4 6.6a9 9 0 1 1-12.8 0"/> @break
        @case('whatsapp')
            {{-- WhatsApp Digital Glyph White RGB 2026. --}}
            <path fill="#fff" stroke="none" d="M360,0C161.18,0,0,161.18,0,360c0,65.41,17.45,126.75,47.94,179.61L0,720l187.02-44.21c51.34,28.18,110.28,44.21,172.98,44.21,198.82,0,360-161.18,360-360S558.82,0,360,0ZM360,655.52c-60.17,0-116.13-17.98-162.82-48.87l-110.49,28.14,30.99-105.61c-33.53-47.93-53.2-106.26-53.2-169.19,0-163.21,132.31-295.52,295.52-295.52s295.52,132.31,295.52,295.52-132.31,295.52-295.52,295.52Z"/>
            <path fill="#fff" stroke="none" d="M444.35,407.52l87.1,41.06c4,1.88,6.56,5.94,6.2,10.34-.94,11.46-5.54,34.43-26.13,55.02-58.12,58.12-162.49-7.64-166.74-10.18-25.67-13.79-50.06-32.24-73.19-55.36s-41.58-47.52-55.37-73.19c-2.55-4.24-68.31-108.61-10.18-166.74,20.59-20.59,43.56-25.19,55.02-26.13,4.41-.36,8.46,2.2,10.34,6.2l41.07,87.1c1.94,4.12,1.09,9.02-2.13,12.24l-30.61,30.61c-6.62,6.62-8.56,16.93-4,25.11,11.17,20.03,26.19,39.32,43.59,57.07,17.75,17.4,37.04,32.43,57.07,43.59,8.18,4.56,18.48,2.62,25.11-4l30.61-30.61c3.22-3.22,8.12-4.08,12.24-2.13Z"/>
            @break
        @case('phone-off') <path d="m3 3 18 18"/><path d="M16.5 16.5c-2.8 1.3-7.3-3.2-6-6"/><path d="M8.7 4.2 6.9 4a2 2 0 0 0-2.2 1.5l-.5 2.1c-.2.8.1 1.7.7 2.3l1.7 1.7M13.8 17.4l.3 1.7a2 2 0 0 0 2.3 1.6l2.1-.5"/> @break
        @case('chevron-left') <path d="m15 18-6-6 6-6"/> @break
        @case('chevron-right') <path d="m9 18 6-6-6-6"/> @break
        @case('search') <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/> @break
        @case('filter') <path d="M4 5h16M7 12h10M10 19h4"/> @break
        @case('calendar') <rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/> @break
        @case('clock') <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/> @break
        @case('download') <path d="M12 3v12M7 10l5 5 5-5M5 21h14"/> @break
        @case('gift') <rect x="3" y="8" width="18" height="13" rx="2"/><path d="M12 8v13M3 12h18M7.5 8C5 8 4 6.8 4 5.5S5 3 6.5 3C9 3 12 8 12 8M16.5 8C19 8 20 6.8 20 5.5S19 3 17.5 3C15 3 12 8 12 8"/> @break
        @case('plus') <path d="M12 5v14M5 12h14"/> @break
        @case('image') <rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m3 16 4.5-4.5 4 4 2.5-2.5 7 7"/> @break
        @case('user') <circle cx="12" cy="8" r="4"/><path d="M5 21v-2a7 7 0 0 1 14 0v2"/> @break
        @case('sun') <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.42 1.42M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.42-1.42M17.66 6.34l1.41-1.41"/> @break
        @case('moon') <path d="M20.7 13.1A8 8 0 1 1 10.9 3.3 6.2 6.2 0 0 0 20.7 13.1Z"/> @break
        @default <circle cx="12" cy="12" r="9"/>
    @endswitch
</svg>
