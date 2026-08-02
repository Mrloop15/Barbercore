@props(['name', 'size' => 18])

<svg {{ $attributes->merge(['class' => 'ui-icon', 'width' => $size, 'height' => $size, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '2', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>
    @switch($name)
        @case('eye') <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/> @break
        @case('edit') <path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/> @break
        @case('trash') <path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="m19 6-1 14H6L5 6"/><path d="M10 11v5M14 11v5"/> @break
        @case('check') <path d="m5 12 4 4L19 6"/> @break
        @case('close') <path d="M18 6 6 18M6 6l12 12"/> @break
        @case('power') <path d="M12 2v10"/><path d="M18.4 6.6a9 9 0 1 1-12.8 0"/> @break
        @case('whatsapp') <path fill="currentColor" stroke="none" d="M12.04 2a9.84 9.84 0 0 0-8.53 14.74L2 22l5.38-1.41A9.98 9.98 0 0 0 12.04 22 10 10 0 0 0 12.04 2Zm5.84 14.13c-.25.7-1.46 1.34-2.02 1.42-.52.08-1.18.11-1.9-.11-.44-.14-1-.33-1.72-.64-3.03-1.31-5-4.36-5.15-4.56-.15-.2-1.23-1.64-1.23-3.13 0-1.49.78-2.22 1.06-2.52.27-.3.6-.37.8-.37h.58c.19.01.44-.07.69.53.25.6.85 2.08.93 2.23.07.15.12.32.02.52-.1.2-.15.32-.3.5-.15.17-.32.38-.45.5-.15.15-.3.31-.13.61.17.3.77 1.27 1.65 2.06 1.14 1.01 2.1 1.33 2.4 1.48.3.15.48.13.65-.07.18-.2.75-.87.95-1.17.2-.3.4-.25.67-.15.28.1 1.75.83 2.05.98.3.15.5.22.58.35.07.12.07.72-.18 1.42Z"/> @break
        @case('phone-off') <path d="m3 3 18 18"/><path d="M16.5 16.5c-2.8 1.3-7.3-3.2-6-6"/><path d="M8.7 4.2 6.9 4a2 2 0 0 0-2.2 1.5l-.5 2.1c-.2.8.1 1.7.7 2.3l1.7 1.7M13.8 17.4l.3 1.7a2 2 0 0 0 2.3 1.6l2.1-.5"/> @break
        @case('chevron-left') <path d="m15 18-6-6 6-6"/> @break
        @case('chevron-right') <path d="m9 18 6-6-6-6"/> @break
        @case('search') <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/> @break
        @case('filter') <path d="M4 5h16M7 12h10M10 19h4"/> @break
        @case('calendar') <rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/> @break
        @case('download') <path d="M12 3v12M7 10l5 5 5-5M5 21h14"/> @break
        @case('gift') <rect x="3" y="8" width="18" height="13" rx="2"/><path d="M12 8v13M3 12h18M7.5 8C5 8 4 6.8 4 5.5S5 3 6.5 3C9 3 12 8 12 8M16.5 8C19 8 20 6.8 20 5.5S19 3 17.5 3C15 3 12 8 12 8"/> @break
        @case('plus') <path d="M12 5v14M5 12h14"/> @break
        @case('image') <rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m3 16 4.5-4.5 4 4 2.5-2.5 7 7"/> @break
        @default <circle cx="12" cy="12" r="9"/>
    @endswitch
</svg>
