<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#C9A227">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="BarberCore">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/branding/icon_192_Barbercore.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/branding/icon_192_Barbercore.png') }}">

    <meta charset="UTF-8">
    <title>@yield('title', 'BarberCore')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        (() => {
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            let theme = systemTheme;

            try {
                const savedTheme = localStorage.getItem('barbercore-theme');
                if (savedTheme === 'dark' || savedTheme === 'light') theme = savedTheme;
            } catch (error) {
                // The system preference remains active when storage is unavailable.
            }

            document.documentElement.dataset.theme = theme;
            document.querySelector('meta[name="theme-color"]').content = theme === 'dark' ? '#171815' : '#C9A227';
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/barbercore-admin.css') }}">
    @stack('styles')
</head>
<body>

<div id="mobileOverlay" class="mobile-overlay" aria-hidden="true"></div>
<div class="idle-timer" id="idleTimer" role="status" aria-live="polite"><span>Sesión por inactividad</span><strong id="idleTimerValue">10:00</strong></div>
<svg width="0" height="0" class="svg-symbol-sprite" aria-hidden="true"><defs>
    <symbol id="i-dashboard" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24"><circle cx="9" cy="8" r="4"/><path d="M3 21v-2a6 6 0 0 1 12 0v2M16 4.5a4 4 0 0 1 0 7.5M17 15a6 6 0 0 1 4 5.7"/></symbol>
    <symbol id="i-user-off" viewBox="0 0 24 24"><circle cx="10" cy="8" r="4"/><path d="M3.5 21v-2a6.5 6.5 0 0 1 11-4.7M17 17l4 4M21 17l-4 4"/></symbol>
    <symbol id="i-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5 21v-2a7 7 0 0 1 14 0v2"/></symbol>
    <symbol id="i-calendar" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
    <symbol id="i-scissors" viewBox="0 0 24 24"><circle cx="6" cy="7" r="3"/><circle cx="6" cy="17" r="3"/><path d="m8.5 8.5 11 7M8.5 15.5l11-7"/></symbol>
    <symbol id="i-box" viewBox="0 0 24 24"><path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="M3 8v9l9 5 9-5V8M12 13v9"/></symbol>
    <symbol id="i-cart" viewBox="0 0 24 24"><path d="M3 3h2l2.4 11.5a2 2 0 0 0 2 1.5h7.8a2 2 0 0 0 2-1.6L21 7H6"/><circle cx="10" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></symbol>
    <symbol id="i-gift" viewBox="0 0 24 24"><rect x="3" y="8" width="18" height="13" rx="2"/><path d="M12 8v13M3 12h18M7 8C4 8 4 3 7 3s5 5 5 5M17 8c3 0 3-5 0-5s-5 5-5 5"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24"><path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/></symbol>
    <symbol id="i-settings" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19 12a7 7 0 0 0-.1-1l2-1.5-2-3.4-2.5 1a8 8 0 0 0-1.8-1L14.2 3h-4.4l-.4 3.1a8 8 0 0 0-1.8 1l-2.5-1-2 3.4L5.1 11a7 7 0 0 0 0 2l-2 1.5 2 3.4 2.5-1a8 8 0 0 0 1.8 1l.4 3.1h4.4l.4-3.1a8 8 0 0 0 1.8-1l2.5 1 2-3.4-2-1.5a7 7 0 0 0 .1-1Z"/></symbol>
    <symbol id="i-logout" viewBox="0 0 24 24"><path d="M10 17l5-5-5-5M15 12H3M15 4h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4"/></symbol>
</defs></svg>

<div class="app">
    <aside class="sidebar" id="sidebar" aria-label="Menú principal">
        <div class="sidebar-header">
            <div class="brand">
                <img src="{{ asset('images/branding/icon_192_Barbercore.png') }}"
                     alt="BarberCore"
                     class="brand-logo"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="brand-icon is-initially-hidden">✂</div>

                <div class="brand-text">
                    <strong>BarberCore</strong>
                    <span>Panel administrativo</span>
                    <div id="connectionStatus" class="connection-status checking" role="status" aria-live="polite" title="Comprobando conexión con BarberCore">
                        <i class="connection-status-dot" aria-hidden="true"></i>
                        <span id="connectionStatusLabel">Comprobando</span>
                    </div>
                </div>
            </div>

            <button
                type="button"
                class="mobile-close-btn"
                id="closeSidebar"
                aria-label="Cerrar menú"
                aria-controls="sidebar"
            >✕</button>
            <button type="button" class="sidebar-collapse-btn" id="collapseSidebar" aria-label="Colapsar menú" aria-expanded="true" title="Colapsar menú"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg></button>
        </div>

        <nav class="menu">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard"><span class="menu-icon"><svg><use href="#i-dashboard"/></svg></span><span class="menu-text">Dashboard</span></a>
            <div class="menu-group">
                <span class="menu-label">Principal</span>
                <button type="button" class="menu-group-toggle" aria-expanded="false" aria-label="Mostrar opciones de Principal" title="Principal"><span class="menu-icon"><svg><use href="#i-users"/></svg></span></button>
                <div class="menu-group-items" data-label="Principal">
                    <a href="{{ route('clientes.index') }}" class="{{ request()->routeIs('clientes.index') ? 'active' : '' }}" title="Clientes"><span class="menu-icon"><svg><use href="#i-users"/></svg></span><span class="menu-text">Clientes</span></a>
                    <a href="{{ route('clientes.inactivos') }}" class="{{ request()->routeIs('clientes.inactivos') ? 'active' : '' }}" title="Clientes inactivos"><span class="menu-icon"><svg><use href="#i-user-off"/></svg></span><span class="menu-text">Clientes inactivos</span></a>
                    @if(auth()->check() && auth()->user()->rol === 'admin')
                        <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}" title="Usuarios"><span class="menu-icon"><svg><use href="#i-user"/></svg></span><span class="menu-text">Usuarios</span></a>
                    @endif
                </div>
            </div>
            <div class="menu-group">
                <span class="menu-label">Operación</span>
                <button type="button" class="menu-group-toggle" aria-expanded="false" aria-label="Mostrar opciones de Operación" title="Operación"><span class="menu-icon"><svg><use href="#i-calendar"/></svg></span></button>
                <div class="menu-group-items" data-label="Operación">
                    <a href="{{ route('citas.index') }}" class="{{ request()->routeIs('citas.*') ? 'active' : '' }}" title="Citas"><span class="menu-icon"><svg><use href="#i-calendar"/></svg></span><span class="menu-text">Citas</span></a>
                    <a href="{{ route('agenda.index') }}" class="{{ request()->routeIs('agenda.*') ? 'active' : '' }}" title="Agenda"><span class="menu-icon"><svg><use href="#i-clock"/></svg></span><span class="menu-text">Agenda</span></a>
                    @if(auth()->user()->rol === 'admin')
                    <a href="{{ route('servicios.index') }}" class="{{ request()->routeIs('servicios.*') ? 'active' : '' }}" title="Servicios"><span class="menu-icon"><svg><use href="#i-scissors"/></svg></span><span class="menu-text">Servicios</span></a>
                    <a href="{{ route('productos.index') }}" class="{{ request()->routeIs('productos.*') ? 'active' : '' }}" title="Productos"><span class="menu-icon"><svg><use href="#i-box"/></svg></span><span class="menu-text">Productos</span></a>
                    <a href="{{ route('ventas-productos.index') }}" class="{{ request()->routeIs('ventas-productos.*') ? 'active' : '' }}" title="Ventas"><span class="menu-icon"><svg><use href="#i-cart"/></svg></span><span class="menu-text">Ventas</span></a>
                    <a href="{{ route('recompensas.index') }}" class="{{ request()->routeIs('recompensas.*') ? 'active' : '' }}" title="Recompensas"><span class="menu-icon"><svg><use href="#i-gift"/></svg></span><span class="menu-text">Recompensas</span></a>
                    @endif
                </div>
            </div>
            @if(auth()->user()->rol === 'admin')
            <div class="menu-group">
                <span class="menu-label">Análisis</span>
                <button type="button" class="menu-group-toggle" aria-expanded="false" aria-label="Mostrar opciones de Análisis" title="Análisis"><span class="menu-icon"><svg><use href="#i-chart"/></svg></span></button>
                <div class="menu-group-items" data-label="Análisis">
                    <a href="{{ route('estadisticas.index') }}" class="{{ request()->routeIs('estadisticas.*') ? 'active' : '' }}" title="Reportes"><span class="menu-icon"><svg><use href="#i-chart"/></svg></span><span class="menu-text">Reportes</span></a>
                    <a href="{{ route('configuracion.index') }}" class="{{ request()->routeIs('configuracion.*') ? 'active' : '' }}" title="Configuración"><span class="menu-icon"><svg><use href="#i-settings"/></svg></span><span class="menu-text">Configuración</span></a>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button type="submit" class="logout-button">
                    <span class="menu-icon"><svg><use href="#i-logout"/></svg></span><span class="menu-text">Cerrar sesión</span>
                </button>
            </form>
        </nav>
    </aside>

    <main class="main">
        <div class="topbar">
            <div class="topbar-left">
                <button
                    type="button"
                    class="mobile-menu-btn"
                    id="openSidebar"
                    aria-label="Abrir menú"
                    aria-controls="sidebar"
                    aria-expanded="false"
                >☰</button>
                <div>
                    <div class="page-kicker">Panel administrativo</div>
                    <h2>@yield('page-title', 'Panel BarberCore')</h2>
                </div>
            </div>

            <div class="topbar-actions">
                <button id="themeToggle" class="theme-toggle" type="button" aria-label="Cambiar tema" aria-pressed="false" title="Cambiar tema">
                    <span class="theme-toggle-halo" aria-hidden="true"></span>
                    <x-icon name="sun" class="theme-toggle-icon theme-toggle-sun" />
                    <x-icon name="moon" class="theme-toggle-icon theme-toggle-moon" />
                </button>
                <button id="installPwaBtn" class="pwa-install-btn is-initially-hidden" type="button">
                    <x-icon name="download" size="16" />
                    <span>Instalar BarberCore</span>
                </button>
                <a href="{{ route('agenda.index') }}" class="topbar-info" title="Abrir mi agenda">
                    <span class="topbar-barber-avatar" aria-hidden="true">{{ $inicialesBarbero ?: 'BC' }}</span>
                    <span class="topbar-barber-content">
                        <span class="topbar-barber-heading">
                            <strong>{{ auth()->user()->nombre ?? auth()->user()->name ?? 'Usuario' }}</strong>
                            <span class="topbar-role-chip">{{ ucfirst(auth()->user()->rol ?? 'barbero') }}</span>
                        </span>
                        @if ($proximaCitaBarbero && $proximaCitaInicio)
                            <span class="topbar-next-appointment">
                                <x-icon name="clock" size="13" />
                                <span>Próxima cita</span>
                                <strong class="js-next-appointment-countdown" data-start="{{ $proximaCitaInicio->toIso8601String() }}">Calculando…</strong>
                            </span>
                            <span class="topbar-appointment-detail">
                                {{ $proximaCitaBarbero->cliente->nombre ?? 'Cliente' }} {{ $proximaCitaBarbero->cliente->apellido ?? '' }} · {{ $proximaCitaBarbero->servicio->nombre ?? 'Servicio' }} · {{ $proximaCitaInicio->format('d/m H:i') }}
                            </span>
                        @else
                            <span class="topbar-next-appointment"><x-icon name="calendar" size="13" /><strong>Sin citas próximas</strong></span>
                            <span class="topbar-appointment-detail">Tu agenda pendiente está libre.</span>
                        @endif
                    </span>
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<nav class="quick-context-menu" id="quickContextMenu" aria-label="Accesos rápidos" aria-hidden="true">
    <div class="quick-context-heading"><span>Accesos rápidos</span><span>BarberCore</span></div>
    <div class="quick-context-list">
        <a class="quick-context-item" href="{{ route('citas.create') }}">
            <span class="quick-context-icon"><svg><use href="#i-calendar"/></svg></span>
            <span class="quick-context-copy"><strong>Nueva cita</strong><small>Registrar una cita rápidamente</small></span>
        </a>
        <a class="quick-context-item" href="{{ route('agenda.index', ['vista' => 'dia', 'fecha' => now()->toDateString()]) }}">
            <span class="quick-context-icon"><svg><use href="#i-clock"/></svg></span>
            <span class="quick-context-copy"><strong>Agenda de hoy</strong><small>Consultar horarios y disponibilidad</small></span>
        </a>
        @if(auth()->user()->rol === 'admin')
        <a class="quick-context-item" href="{{ route('ventas-productos.create') }}">
            <span class="quick-context-icon"><svg><use href="#i-cart"/></svg></span>
            <span class="quick-context-copy"><strong>Registrar venta</strong><small>Crear una venta de productos</small></span>
        </a>
        @endif
        <a class="quick-context-item" href="{{ route('clientes.create') }}">
            <span class="quick-context-icon"><svg><use href="#i-users"/></svg></span>
            <span class="quick-context-copy"><strong>Nuevo cliente</strong><small>Agregar un cliente al sistema</small></span>
        </a>
    </div>
</nav>
<div class="barber-tooltip" id="barberTooltip" role="tooltip" aria-hidden="true"></div>

<script>
    (function () {
        const instances = new Map();
        let opened = null;
        let sequence = 0;

        function close(instance = opened, restoreFocus = false) {
            if (!instance) return;
            instance.wrapper.classList.remove('open');
            instance.menu.classList.remove('open');
            instance.trigger.setAttribute('aria-expanded', 'false');
            if (restoreFocus) instance.trigger.focus();
            if (opened === instance) opened = null;
        }

        function position(instance) {
            const rect = instance.trigger.getBoundingClientRect();
            const margin = 10;
            const gap = 6;
            const availableBelow = window.innerHeight - rect.bottom - margin;
            const menuHeight = Math.min(instance.menu.scrollHeight, 280);
            const dropUp = availableBelow < Math.min(menuHeight, 180) && rect.top > availableBelow;
            const width = Math.max(rect.width, 170);
            const left = Math.min(Math.max(margin, rect.left), window.innerWidth - width - margin);
            const top = dropUp
                ? Math.max(margin, rect.top - menuHeight - gap)
                : Math.min(window.innerHeight - menuHeight - margin, rect.bottom + gap);

            instance.menu.classList.toggle('drop-up', dropUp);
            instance.menu.style.width = `${width}px`;
            instance.menu.style.left = `${left}px`;
            instance.menu.style.top = `${top}px`;
        }

        function sync(instance, rebuild = false) {
            const { select, trigger, menu } = instance;
            if (rebuild) {
                menu.replaceChildren();
                Array.from(select.options).forEach(function (option, index) {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'bc-select-option';
                    item.setAttribute('role', 'option');
                    item.dataset.index = String(index);
                    item.textContent = option.textContent.trim();
                    item.disabled = option.disabled;
                    item.addEventListener('click', function () {
                        select.selectedIndex = index;
                        select.dispatchEvent(new Event('change', { bubbles: true }));
                        close(instance, true);
                    });
                    menu.appendChild(item);
                });
            }

            const selected = select.options[select.selectedIndex];
            trigger.querySelector('.bc-select-value').textContent = selected?.textContent.trim() || 'Selecciona una opción';
            trigger.disabled = select.disabled;
            menu.querySelectorAll('.bc-select-option').forEach(function (item) {
                const isSelected = Number(item.dataset.index) === select.selectedIndex;
                item.classList.toggle('selected', isSelected);
                item.setAttribute('aria-selected', String(isSelected));
            });
        }

        function open(instance, focusSelected = false) {
            if (instance.select.disabled) return;
            if (opened && opened !== instance) close(opened);
            opened = instance;
            instance.wrapper.classList.add('open');
            instance.menu.classList.add('open');
            instance.trigger.setAttribute('aria-expanded', 'true');
            position(instance);
            if (focusSelected) {
                (instance.menu.querySelector('.selected:not(:disabled)') || instance.menu.querySelector('.bc-select-option:not(:disabled)'))?.focus();
            }
        }

        function enhance(select) {
            if (!(select instanceof HTMLSelectElement) || select.multiple || instances.has(select)) return;

            const wrapper = document.createElement('div');
            wrapper.className = 'bc-select';
            const trigger = document.createElement('button');
            const menu = document.createElement('div');
            const menuId = `bc-select-menu-${++sequence}`;
            trigger.type = 'button';
            trigger.className = 'bc-select-trigger';
            trigger.setAttribute('aria-haspopup', 'listbox');
            trigger.setAttribute('aria-expanded', 'false');
            trigger.setAttribute('aria-controls', menuId);
            trigger.innerHTML = '<span class="bc-select-value"></span><span class="bc-select-chevron" aria-hidden="true"><svg viewBox="0 0 20 20"><path d="m5 7 5 5 5-5"/></svg></span>';
            menu.id = menuId;
            menu.className = 'bc-select-menu';
            menu.setAttribute('role', 'listbox');

            select.parentNode.insertBefore(wrapper, select);
            wrapper.appendChild(select);
            wrapper.appendChild(trigger);
            document.body.appendChild(menu);
            select.classList.add('bc-select-native');

            const instance = { select, wrapper, trigger, menu };
            instances.set(select, instance);
            sync(instance, true);

            if (select.id) {
                document.querySelectorAll('label[for]').forEach(function (label) {
                    if (label.htmlFor !== select.id) return;
                    label.addEventListener('click', function (event) {
                        if (event.target === label) {
                            event.preventDefault();
                            trigger.focus();
                        }
                    });
                });
            }

            trigger.addEventListener('click', () => opened === instance ? close(instance) : open(instance));
            select.addEventListener('focus', () => trigger.focus());
            trigger.addEventListener('keydown', function (event) {
                if (['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(event.key)) {
                    event.preventDefault();
                    open(instance, true);
                }
            });
            menu.addEventListener('keydown', function (event) {
                const options = Array.from(menu.querySelectorAll('.bc-select-option:not(:disabled)'));
                const current = options.indexOf(document.activeElement);
                if (event.key === 'Escape' || event.key === 'Tab') return close(instance, event.key === 'Escape');
                if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                    event.preventDefault();
                    const direction = event.key === 'ArrowDown' ? 1 : -1;
                    options[(current + direction + options.length) % options.length]?.focus();
                }
                if (event.key === 'Home' || event.key === 'End') {
                    event.preventDefault();
                    options[event.key === 'Home' ? 0 : options.length - 1]?.focus();
                }
            });
            select.addEventListener('change', () => sync(instance));
            select.addEventListener('invalid', function (event) {
                event.preventDefault();
                trigger.focus();
                open(instance, true);
            });
            select.form?.addEventListener('reset', () => window.setTimeout(() => sync(instance), 0));

            new MutationObserver(() => sync(instance, true)).observe(select, { childList: true, subtree: true, attributes: true, attributeFilter: ['disabled', 'selected'] });
        }

        document.querySelectorAll('select').forEach(enhance);
        new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                mutation.addedNodes.forEach(function (node) {
                    if (!(node instanceof Element)) return;
                    if (node.matches('select')) enhance(node);
                    node.querySelectorAll('select').forEach(enhance);
                });
            });
        }).observe(document.body, { childList: true, subtree: true });
        document.addEventListener('pointerdown', function (event) {
            if (opened && !opened.wrapper.contains(event.target) && !opened.menu.contains(event.target)) close(opened);
        });
        window.addEventListener('resize', () => opened && position(opened));
        window.addEventListener('scroll', () => close(opened), true);
    })();

    (function () {
        const tooltip = document.getElementById('barberTooltip');
        if (!tooltip || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

        let activeTarget = null;
        let showTimer = null;

        function enhance(element) {
            if (!(element instanceof Element) || !element.hasAttribute('title')) return;
            const text = element.getAttribute('title')?.trim();
            if (text) {
                element.dataset.tooltip = text;
                if (!element.hasAttribute('aria-label') && !element.textContent.trim()) {
                    element.setAttribute('aria-label', text);
                }
            }
            element.removeAttribute('title');
        }

        function enhanceTree(root) {
            if (!(root instanceof Element || root instanceof Document)) return;
            if (root instanceof Element) enhance(root);
            root.querySelectorAll('[title]').forEach(enhance);
        }

        function hideTooltip() {
            window.clearTimeout(showTimer);
            activeTarget = null;
            tooltip.classList.remove('visible');
            tooltip.setAttribute('aria-hidden', 'true');
        }

        function positionTooltip(target) {
            const targetRect = target.getBoundingClientRect();
            const tooltipRect = tooltip.getBoundingClientRect();
            const margin = 9;
            const gap = 9;
            let placement = 'top';
            let left = targetRect.left + (targetRect.width - tooltipRect.width) / 2;
            let top = targetRect.top - tooltipRect.height - gap;

            if (target.closest('.sidebar') && document.body.classList.contains('sidebar-collapsed')) {
                placement = 'right';
                left = targetRect.right + gap;
                top = targetRect.top + (targetRect.height - tooltipRect.height) / 2;
            } else if (top < margin) {
                placement = 'bottom';
                top = targetRect.bottom + gap;
            }

            left = Math.min(Math.max(margin, left), window.innerWidth - tooltipRect.width - margin);
            top = Math.min(Math.max(margin, top), window.innerHeight - tooltipRect.height - margin);
            tooltip.dataset.placement = placement;
            tooltip.style.left = `${left}px`;
            tooltip.style.top = `${top}px`;
        }

        function scheduleTooltip(target, immediate = false) {
            const text = target?.dataset.tooltip?.trim();
            if (!text) return;
            window.clearTimeout(showTimer);
            activeTarget = target;
            showTimer = window.setTimeout(function () {
                if (activeTarget !== target || !target.isConnected) return;
                tooltip.textContent = text;
                tooltip.classList.add('visible');
                tooltip.setAttribute('aria-hidden', 'false');
                positionTooltip(target);
            }, immediate ? 0 : 260);
        }

        enhanceTree(document);

        document.addEventListener('pointerover', function (event) {
            const target = event.target.closest?.('[data-tooltip]');
            if (target && !target.contains(event.relatedTarget)) scheduleTooltip(target);
        });
        document.addEventListener('pointerout', function (event) {
            const target = event.target.closest?.('[data-tooltip]');
            if (target && !target.contains(event.relatedTarget)) hideTooltip();
        });
        document.addEventListener('focusin', function (event) {
            const target = event.target.closest?.('[data-tooltip]');
            if (target) scheduleTooltip(target, true);
        });
        document.addEventListener('focusout', hideTooltip);
        document.addEventListener('contextmenu', hideTooltip);
        window.addEventListener('scroll', hideTooltip, true);
        window.addEventListener('resize', hideTooltip);

        new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.type === 'attributes') enhance(mutation.target);
                mutation.addedNodes.forEach((node) => enhanceTree(node));
            });
        }).observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['title'] });
    })();

    (function () {
        const menu = document.getElementById('quickContextMenu');
        if (!menu || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

        const links = Array.from(menu.querySelectorAll('a'));

        function closeMenu() {
            menu.classList.remove('open');
            menu.setAttribute('aria-hidden', 'true');
        }

        function openMenu(clientX, clientY) {
            menu.style.left = '0px';
            menu.style.top = '0px';
            menu.classList.add('open');
            menu.setAttribute('aria-hidden', 'false');

            const margin = 12;
            const width = menu.offsetWidth;
            const height = menu.offsetHeight;
            const left = Math.min(Math.max(margin, clientX), window.innerWidth - width - margin);
            const top = Math.min(Math.max(margin, clientY), window.innerHeight - height - margin);

            menu.style.left = `${left}px`;
            menu.style.top = `${top}px`;
        }

        document.addEventListener('contextmenu', function (event) {
            event.preventDefault();
            openMenu(event.clientX, event.clientY);
        });

        document.addEventListener('pointerdown', function (event) {
            if (!menu.contains(event.target)) closeMenu();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeMenu();
                return;
            }

            if (event.shiftKey && event.key === 'F10') {
                event.preventDefault();
                openMenu(window.innerWidth / 2, window.innerHeight / 2);
                links[0]?.focus();
            }
        });

        window.addEventListener('resize', closeMenu);
        window.addEventListener('scroll', closeMenu, true);
    })();

    (function () {
        const TIMEOUT_MS = 10 * 60 * 1000;
        const SHOW_AFTER_MS = 5000;
        const HEARTBEAT_INTERVAL_MS = 30000;
        const ACTIVITY_THROTTLE_MS = 1000;
        const STORAGE_KEY = 'barbercore-last-activity';
        const timer = document.getElementById('idleTimer');
        const timerValue = document.getElementById('idleTimerValue');
        const logoutForm = document.getElementById('logoutForm');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        let lastActivity = Date.now();
        let lastHandledActivity = 0;
        let lastHeartbeat = 0;
        let loggingOut = false;

        function storeActivity(timestamp) {
            lastActivity = timestamp;
            try { localStorage.setItem(STORAGE_KEY, String(timestamp)); } catch (error) {}
        }

        function heartbeat() {
            const now = Date.now();
            if ((now - lastHeartbeat) < HEARTBEAT_INTERVAL_MS) return;
            lastHeartbeat = now;

            fetch(@json(route('session.activity')), {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(function (response) {
                if (response.status === 401 || response.status === 419) {
                    window.location.assign(@json(route('login')));
                }
            }).catch(function () {
                // El siguiente request protegido también validará la expiración.
            });
        }

        function registerActivity() {
            const now = Date.now();
            if ((now - lastHandledActivity) < ACTIVITY_THROTTLE_MS) return;
            lastHandledActivity = now;
            storeActivity(now);
            timer?.classList.remove('visible');
            heartbeat();
        }

        function finishSession() {
            if (loggingOut) return;
            loggingOut = true;
            timer?.classList.add('visible');
            if (timerValue) timerValue.textContent = '00:00';
            try { localStorage.removeItem(STORAGE_KEY); } catch (error) {}

            if (logoutForm) {
                logoutForm.requestSubmit();
            } else {
                window.location.assign(@json(route('login')));
            }
        }

        function updateTimer() {
            const idleTime = Date.now() - lastActivity;
            const remaining = Math.max(0, TIMEOUT_MS - idleTime);

            if (remaining <= 0) {
                finishSession();
                return;
            }

            if (idleTime >= SHOW_AFTER_MS) timer?.classList.add('visible');
            else timer?.classList.remove('visible');

            const totalSeconds = Math.ceil(remaining / 1000);
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            if (timerValue) timerValue.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        }

        ['pointerdown', 'pointermove', 'keydown', 'scroll', 'touchstart'].forEach(function (eventName) {
            window.addEventListener(eventName, registerActivity, { passive: true });
        });

        window.addEventListener('storage', function (event) {
            if (event.key !== STORAGE_KEY || !event.newValue) return;
            const sharedActivity = Number(event.newValue);
            if (Number.isFinite(sharedActivity) && sharedActivity > lastActivity) {
                lastActivity = sharedActivity;
                timer?.classList.remove('visible');
            }
        });

        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) registerActivity();
        });

        storeActivity(Date.now());
        heartbeat();
        updateTimer();
        window.setInterval(updateTimer, 1000);
    })();
</script>

<script>
    (function () {
        const MOBILE_BREAKPOINT = 900;
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        const collapseBtn = document.getElementById('collapseSidebar');
        const menuLinks = sidebar ? sidebar.querySelectorAll('.menu a') : [];
        const menuGroups = sidebar ? sidebar.querySelectorAll('.menu-group') : [];
        const groupToggles = sidebar ? sidebar.querySelectorAll('.menu-group-toggle') : [];
        const menuSequenceItems = sidebar ? sidebar.querySelectorAll('.menu > a, .menu-group > .menu-label, .menu-group-items > a, .menu > form') : [];
        const collapsedIconItems = sidebar ? sidebar.querySelectorAll('.menu > a, .menu-group-toggle, .menu > form') : [];
        const brandLines = sidebar ? sidebar.querySelectorAll('.brand-text strong, .brand-text > span') : [];
        let previousFocus = null;
        let sidebarAnimationBusy = false;

        menuSequenceItems.forEach(function (item, index) {
            item.style.setProperty('--item-index', index);
        });

        collapsedIconItems.forEach(function (item, index) {
            item.style.setProperty('--collapsed-icon-index', index);
        });

        brandLines.forEach(function (line, lineIndex) {
            const text = line.textContent;
            line.textContent = '';
            line.classList.add('brand-type-line', lineIndex === 0 ? 'brand-type-primary' : 'brand-type-secondary');

            Array.from(text).forEach(function (character, characterIndex) {
                const span = document.createElement('span');
                span.className = 'brand-character';
                span.style.setProperty('--char-index', characterIndex);
                span.textContent = character === ' ' ? '\u00a0' : character;
                span.setAttribute('aria-hidden', 'true');
                line.appendChild(span);
            });

            line.setAttribute('aria-label', text);
        });

        function isMobileView() {
            return window.innerWidth <= MOBILE_BREAKPOINT;
        }

        function updateAccessibility(isOpen) {
            if (openBtn) {
                openBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }

            if (overlay) {
                overlay.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
            }

            if (sidebar) {
                sidebar.setAttribute('aria-hidden', isMobileView() && !isOpen ? 'true' : 'false');
            }
        }

        function openSidebar() {
            if (!isMobileView() || !sidebar || !overlay) {
                return;
            }

            previousFocus = document.activeElement;
            sidebar.classList.add('open');
            overlay.classList.add('show');
            document.body.classList.add('sidebar-open');
            updateAccessibility(true);

            window.requestAnimationFrame(function () {
                if (closeBtn) {
                    closeBtn.focus();
                }
            });
        }

        function closeSidebar(restoreFocus = true) {
            if (!sidebar || !overlay) {
                return;
            }

            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.classList.remove('sidebar-open');
            updateAccessibility(false);

            if (restoreFocus && previousFocus && typeof previousFocus.focus === 'function') {
                previousFocus.focus();
            }

            previousFocus = null;
        }

        if (openBtn) {
            openBtn.addEventListener('click', openSidebar);
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                closeSidebar();
            });
        }

        function setCollapsed(collapsed) {
            document.body.classList.toggle('sidebar-collapsed', collapsed);
            if (!collapsed) {
                menuGroups.forEach(function (group) { group.classList.remove('open'); });
                groupToggles.forEach(function (toggle) { toggle.setAttribute('aria-expanded', 'false'); });
            }
            if (collapseBtn) {
                collapseBtn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                collapseBtn.setAttribute('aria-label', collapsed ? 'Expandir menú' : 'Colapsar menú');
                collapseBtn.setAttribute('title', collapsed ? 'Expandir menú' : 'Colapsar menú');
            }
        }

        try {
            setCollapsed(localStorage.getItem('barbercore-sidebar-collapsed') === 'true');
        } catch (error) {
            setCollapsed(false);
        }

        if (collapseBtn) {
            collapseBtn.addEventListener('click', function () {
                if (sidebarAnimationBusy || isMobileView()) return;
                sidebarAnimationBusy = true;
                const isCollapsed = document.body.classList.contains('sidebar-collapsed');

                if (isCollapsed) {
                    document.body.classList.add('sidebar-expanding');
                    setCollapsed(false);
                    window.setTimeout(function () {
                        document.body.classList.remove('sidebar-expanding');
                        sidebarAnimationBusy = false;
                    }, 2700);
                } else {
                    document.body.classList.add('sidebar-collapsing');
                    window.setTimeout(function () {
                        setCollapsed(true);
                        document.body.classList.remove('sidebar-collapsing');
                        document.body.classList.add('sidebar-icons-revealing');
                        window.setTimeout(function () {
                            document.body.classList.remove('sidebar-icons-revealing');
                            sidebarAnimationBusy = false;
                        }, 1250);
                    }, 160);
                }

                try {
                    localStorage.setItem('barbercore-sidebar-collapsed', String(!isCollapsed));
                } catch (error) {}
            });
        }

        groupToggles.forEach(function (toggle) {
            toggle.addEventListener('click', function (event) {
                event.stopPropagation();
                const group = toggle.closest('.menu-group');
                const willOpen = group && !group.classList.contains('open');
                menuGroups.forEach(function (item) { item.classList.remove('open'); });
                groupToggles.forEach(function (item) { item.setAttribute('aria-expanded', 'false'); });
                if (group && willOpen) {
                    group.classList.add('open');
                    toggle.setAttribute('aria-expanded', 'true');
                }
            });
        });

        document.addEventListener('click', function (event) {
            if (sidebar && !sidebar.contains(event.target)) {
                menuGroups.forEach(function (group) { group.classList.remove('open'); });
                groupToggles.forEach(function (toggle) { toggle.setAttribute('aria-expanded', 'false'); });
            }
        });

        if (overlay) {
            overlay.addEventListener('click', function () {
                closeSidebar();
            });
        }

        menuLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                if (isMobileView()) {
                    closeSidebar(false);
                }
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && sidebar && sidebar.classList.contains('open')) {
                closeSidebar();
            }
        });

        window.addEventListener('resize', function () {
            if (!isMobileView()) {
                closeSidebar(false);
                updateAccessibility(false);
            } else if (sidebar && !sidebar.classList.contains('open')) {
                updateAccessibility(false);
            }
        });

        updateAccessibility(sidebar ? sidebar.classList.contains('open') : false);
    })();
</script>

<script>
    (function () {
        const months = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
        const weekdays = ['L','M','M','J','V','S','D'];
        const pad = number => String(number).padStart(2, '0');
        const toValue = date => date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());
        const fromValue = value => value ? new Date(value + 'T12:00:00') : new Date();
        const formatLabel = value => value ? fromValue(value).toLocaleDateString('es-MX', { day: 'numeric', month: 'long', year: 'numeric' }) : 'Seleccionar fecha';

        document.querySelectorAll('input[type="date"]').forEach(function (input) {
            if (input.dataset.customDateReady || input.readOnly || input.disabled) return;
            input.dataset.customDateReady = 'true';
            input.classList.add('native-date-enhanced');

            const picker = document.createElement('div');
            picker.className = 'custom-date-picker';
            picker.innerHTML = `
                <button type="button" class="custom-date-trigger" aria-expanded="false">
                    <span></span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>
                </button>
                <div class="custom-calendar" role="dialog" aria-label="Seleccionar fecha">
                    <div class="calendar-head"><button type="button" class="calendar-nav prev" aria-label="Mes anterior">‹</button><strong></strong><button type="button" class="calendar-nav next" aria-label="Mes siguiente">›</button></div>
                    <div class="calendar-week">${weekdays.map(day => `<span>${day}</span>`).join('')}</div>
                    <div class="calendar-days"></div>
                    <div class="calendar-manual">
                        <label>Escribir una fecha</label>
                        <div class="calendar-manual-row">
                            <input type="text" class="calendar-manual-input" inputmode="numeric" maxlength="10" placeholder="dd/mm/aaaa" aria-label="Escribir fecha en formato día, mes y año">
                            <button type="button" class="calendar-manual-apply" aria-label="Aplicar fecha escrita">✓</button>
                        </div>
                        <span class="calendar-manual-error" role="status" aria-live="polite"></span>
                    </div>
                    <div class="calendar-footer"><button type="button" class="calendar-today">Ir a hoy</button><span>Selecciona un día</span></div>
                </div>`;
            input.insertAdjacentElement('afterend', picker);

            const trigger = picker.querySelector('.custom-date-trigger');
            const triggerLabel = trigger.querySelector('span');
            const calendar = picker.querySelector('.custom-calendar');
            const title = picker.querySelector('.calendar-head strong');
            const days = picker.querySelector('.calendar-days');
            const manualInput = picker.querySelector('.calendar-manual-input');
            const manualApply = picker.querySelector('.calendar-manual-apply');
            const manualError = picker.querySelector('.calendar-manual-error');
            const restrictWeekdays = input.hasAttribute('data-open-weekdays');
            const openWeekdays = (input.dataset.openWeekdays || '').split(',').filter(Boolean).map(Number);
            let cursor = fromValue(input.value);

            function toManualValue(value) {
                if (!value) return '';
                const date = fromValue(value);
                return pad(date.getDate()) + '/' + pad(date.getMonth() + 1) + '/' + date.getFullYear();
            }

            function parseManualValue(value) {
                const match = value.trim().match(/^(\d{1,2})[\/.-](\d{1,2})[\/.-](\d{4})$/);
                if (!match) return null;
                const day = Number(match[1]);
                const month = Number(match[2]);
                const year = Number(match[3]);
                const date = new Date(year, month - 1, day, 12);
                if (date.getFullYear() !== year || date.getMonth() !== month - 1 || date.getDate() !== day) return null;
                return date;
            }

            function dateAvailabilityError(date) {
                const value = toValue(date);
                if (input.min && value < input.min) return 'La fecha es anterior al límite permitido.';
                if (input.max && value > input.max) return 'La fecha supera el límite permitido.';
                const weekday = (date.getDay() + 6) % 7;
                if (restrictWeekdays && !openWeekdays.includes(weekday)) return 'Ese día no está disponible.';
                return '';
            }

            function applyManualDate() {
                const date = parseManualValue(manualInput.value);
                if (!date) {
                    manualError.textContent = 'Escribe una fecha válida: dd/mm/aaaa.';
                    manualInput.focus();
                    return;
                }
                const availabilityError = dateAvailabilityError(date);
                if (availabilityError) {
                    manualError.textContent = availabilityError;
                    manualInput.focus();
                    return;
                }

                input.value = toValue(date);
                input.dispatchEvent(new Event('change', { bubbles: true }));
                cursor = date;
                manualError.textContent = '';
                closeCalendar();
                render();
                trigger.focus();
            }

            function closeCalendar() {
                calendar.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            }

            function positionCalendar() {
                if (window.matchMedia('(max-width: 420px)').matches) {
                    calendar.style.left = '12px';
                    calendar.style.right = '12px';
                    calendar.style.top = '50%';
                    return;
                }

                calendar.style.right = 'auto';
                const triggerRect = trigger.getBoundingClientRect();
                const calendarRect = calendar.getBoundingClientRect();
                const margin = 12;
                const gap = 8;
                const availableBelow = window.innerHeight - triggerRect.bottom - margin;
                const availableAbove = triggerRect.top - margin;
                const openUp = calendarRect.height > availableBelow && availableAbove > availableBelow;
                const left = Math.min(
                    Math.max(margin, triggerRect.left),
                    window.innerWidth - calendarRect.width - margin
                );
                const top = openUp
                    ? Math.max(margin, triggerRect.top - calendarRect.height - gap)
                    : Math.min(window.innerHeight - calendarRect.height - margin, triggerRect.bottom + gap);

                calendar.style.left = `${left}px`;
                calendar.style.top = `${Math.max(margin, top)}px`;
            }

            function render() {
                triggerLabel.textContent = formatLabel(input.value);
                if (document.activeElement !== manualInput) manualInput.value = toManualValue(input.value);
                title.textContent = months[cursor.getMonth()] + ' ' + cursor.getFullYear();
                days.innerHTML = '';
                const first = new Date(cursor.getFullYear(), cursor.getMonth(), 1);
                const mondayOffset = (first.getDay() + 6) % 7;
                const gridStart = new Date(cursor.getFullYear(), cursor.getMonth(), 1 - mondayOffset);
                const today = toValue(new Date());

                for (let index = 0; index < 42; index++) {
                    const date = new Date(gridStart);
                    date.setDate(gridStart.getDate() + index);
                    const value = toValue(date);
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'calendar-day';
                    button.textContent = date.getDate();
                    if (date.getMonth() !== cursor.getMonth()) button.classList.add('outside');
                    if (value === today) button.classList.add('today');
                    if (value === input.value) button.classList.add('selected');
                    if ((input.min && value < input.min) || (input.max && value > input.max)) button.disabled = true;
                    const weekday = (date.getDay() + 6) % 7;
                    if (restrictWeekdays && !openWeekdays.includes(weekday)) button.disabled = true;
                    button.addEventListener('click', function () {
                        input.value = value;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                        cursor = date;
                        closeCalendar();
                        render();
                    });
                    days.appendChild(button);
                }
            }

            trigger.addEventListener('click', function () {
                document.querySelectorAll('.custom-calendar.open').forEach(open => {
                    if (open !== calendar) {
                        open.classList.remove('open');
                        open.closest('.custom-date-picker')?.querySelector('.custom-date-trigger')?.setAttribute('aria-expanded', 'false');
                    }
                });
                calendar.classList.toggle('open');
                trigger.setAttribute('aria-expanded', calendar.classList.contains('open') ? 'true' : 'false');
                if (calendar.classList.contains('open')) positionCalendar();
            });
            picker.querySelector('.prev').addEventListener('click', () => { cursor = new Date(cursor.getFullYear(), cursor.getMonth() - 1, 1); render(); });
            picker.querySelector('.next').addEventListener('click', () => { cursor = new Date(cursor.getFullYear(), cursor.getMonth() + 1, 1); render(); });
            picker.querySelector('.calendar-today').addEventListener('click', () => {
                const today = new Date();
                const value = toValue(today);
                const weekday = (today.getDay() + 6) % 7;
                const isOpen = !restrictWeekdays || openWeekdays.includes(weekday);
                if ((!input.min || value >= input.min) && (!input.max || value <= input.max) && isOpen) {
                    input.value = value;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
                cursor = today;
                render();
            });
            manualInput.addEventListener('input', function () {
                manualError.textContent = '';
                const digits = manualInput.value.replace(/\D/g, '').slice(0, 8);
                manualInput.value = [digits.slice(0, 2), digits.slice(2, 4), digits.slice(4, 8)].filter(Boolean).join('/');
            });
            manualInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    applyManualDate();
                }
            });
            manualApply.addEventListener('click', applyManualDate);
            input.addEventListener('change', function () { cursor = fromValue(input.value); render(); });
            render();
        });

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.custom-date-picker')) document.querySelectorAll('.custom-calendar.open').forEach(calendar => {
                calendar.classList.remove('open');
                calendar.closest('.custom-date-picker')?.querySelector('.custom-date-trigger')?.setAttribute('aria-expanded', 'false');
            });
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') document.querySelectorAll('.custom-calendar.open').forEach(calendar => {
                calendar.classList.remove('open');
                calendar.closest('.custom-date-picker')?.querySelector('.custom-date-trigger')?.setAttribute('aria-expanded', 'false');
            });
        });
        function closeOpenCalendars() {
            document.querySelectorAll('.custom-calendar.open').forEach(calendar => {
                calendar.classList.remove('open');
                calendar.closest('.custom-date-picker')?.querySelector('.custom-date-trigger')?.setAttribute('aria-expanded', 'false');
            });
        }
        window.addEventListener('resize', closeOpenCalendars);
        window.addEventListener('scroll', function (event) {
            if (event.target instanceof Element && event.target.closest('.custom-calendar')) return;
            closeOpenCalendars();
        }, true);
    })();
</script>

<script>
    (function () {
        const pad = number => String(number).padStart(2, '0');
        const toMinutes = value => { const parts = (value || '00:00').split(':'); return (parseInt(parts[0], 10) * 60) + parseInt(parts[1], 10); };
        const toValue = minutes => pad(Math.floor(minutes / 60)) + ':' + pad(minutes % 60);
        const formatTime = value => {
            if (!value) return 'Seleccionar hora';
            const date = new Date('2000-01-01T' + value);
            return date.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });
        };

        document.querySelectorAll('input[type="time"]').forEach(function (input) {
            if (input.dataset.customTimeReady) return;
            input.dataset.customTimeReady = 'true';
            input.classList.add('native-time-enhanced');

            const picker = document.createElement('div');
            picker.className = 'custom-time-picker';
            picker.innerHTML = `
                <button type="button" class="custom-time-trigger" aria-expanded="false">
                    <span></span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                </button>
                <div class="custom-time-panel" role="dialog" aria-label="Seleccionar hora">
                    <div class="time-panel-head"><strong>Selecciona un horario</strong><span>Intervalos de 15 min</span></div>
                    <div class="time-period morning"><div class="time-period-label">Mañana</div><div class="time-slots"></div></div>
                    <div class="time-period afternoon"><div class="time-period-label">Tarde</div><div class="time-slots"></div></div>
                </div>`;
            input.insertAdjacentElement('afterend', picker);

            const trigger = picker.querySelector('.custom-time-trigger');
            const label = trigger.querySelector('span');
            const panel = picker.querySelector('.custom-time-panel');
            const morning = picker.querySelector('.morning .time-slots');
            const afternoon = picker.querySelector('.afternoon .time-slots');
            const afternoonPeriod = picker.querySelector('.afternoon');

            function render() {
                label.textContent = formatTime(input.value);
                morning.innerHTML = '';
                afternoon.innerHTML = '';
                const unavailable = input.dataset.unavailable === 'true';
                trigger.disabled = unavailable;

                if (unavailable) {
                    label.textContent = input.dataset.unavailableLabel || 'Sin horarios disponibles';
                    const message = input.dataset.unavailableMessage || 'El negocio no atiende este día.';
                    const empty = document.createElement('span');
                    empty.className = 'custom-time-empty';
                    empty.textContent = message;
                    morning.appendChild(empty);
                    afternoonPeriod.style.display = 'none';
                    panel.classList.remove('open');
                    trigger.setAttribute('aria-expanded', 'false');
                    return;
                }

                afternoonPeriod.style.display = '';
                const step = Math.max(5, parseInt(input.step || '900', 10) / 60);
                const start = input.min ? toMinutes(input.min) : 8 * 60;
                const end = input.max ? toMinutes(input.max) : 20 * 60;
                const restrictAvailableTimes = input.hasAttribute('data-available-times');
                const availableTimes = (input.dataset.availableTimes || '').split(',').filter(Boolean);
                const now = new Date();
                const nearestNow = Math.round((now.getHours() * 60 + now.getMinutes()) / step) * step;

                for (let minutes = start; minutes <= end; minutes += step) {
                    const value = toValue(minutes);
                    if (restrictAvailableTimes && !availableTimes.includes(value)) continue;
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'time-slot';
                    button.textContent = formatTime(value);
                    if (value === input.value.slice(0, 5)) button.classList.add('selected');
                    if (minutes === nearestNow) button.classList.add('now');
                    button.addEventListener('click', function () {
                        input.value = value;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                        panel.classList.remove('open');
                        trigger.setAttribute('aria-expanded', 'false');
                        render();
                    });
                    (minutes < 12 * 60 ? morning : afternoon).appendChild(button);
                }

                if (!morning.children.length && !afternoon.children.length) {
                    const empty = document.createElement('span');
                    empty.className = 'custom-time-empty';
                    empty.textContent = input.dataset.unavailableMessage || 'No hay horarios disponibles.';
                    morning.appendChild(empty);
                    afternoonPeriod.style.display = 'none';
                }
            }

            trigger.addEventListener('click', function () {
                document.querySelectorAll('.custom-time-panel.open').forEach(open => { if (open !== panel) open.classList.remove('open'); });
                panel.classList.toggle('open');
                trigger.setAttribute('aria-expanded', panel.classList.contains('open') ? 'true' : 'false');
            });
            input.addEventListener('change', render);
            render();
        });

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.custom-time-picker')) document.querySelectorAll('.custom-time-panel.open').forEach(panel => panel.classList.remove('open'));
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') document.querySelectorAll('.custom-time-panel.open').forEach(panel => panel.classList.remove('open'));
        });
    })();
</script>

<script>
    (function () {
        const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]:not([data-skip-auto-preview]), input[type="file"][accept*="jpg"]:not([data-skip-auto-preview]), input[type="file"][accept*="png"]:not([data-skip-auto-preview])');

        imageInputs.forEach(function (input) {
            const preview = document.createElement('div');
            preview.className = 'image-selection-preview';
            preview.setAttribute('aria-live', 'polite');
            preview.innerHTML = '<img alt="Vista previa de la imagen seleccionada"><div><strong></strong><span></span></div>';
            input.insertAdjacentElement('afterend', preview);

            input.addEventListener('change', function () {
                const file = input.files && input.files[0];
                if (!file) {
                    preview.classList.remove('show');
                    return;
                }

                const image = preview.querySelector('img');
                const name = preview.querySelector('strong');
                const meta = preview.querySelector('span');
                image.src = URL.createObjectURL(file);
                image.onload = function () { URL.revokeObjectURL(image.src); };
                name.textContent = file.name;
                meta.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB · imagen lista para guardar';
                preview.classList.add('show');
            });
        });
    })();
</script>

<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/sw.js')
                .then(function () {
                    console.log('Service Worker registrado correctamente.');
                })
                .catch(function (error) {
                    console.log('Error al registrar el Service Worker:', error);
                });
        });
    }
</script>

<script>
    (function () {
        const counters = document.querySelectorAll('.js-next-appointment-countdown');
        if (!counters.length) return;

        function updateCounter(counter) {
            const start = new Date(counter.dataset.start);
            const difference = start.getTime() - Date.now();

            if (!Number.isFinite(start.getTime()) || difference <= 60000) {
                counter.textContent = 'por comenzar';
                return;
            }

            const totalMinutes = Math.ceil(difference / 60000);
            if (totalMinutes < 60) {
                counter.textContent = `en ${totalMinutes} min`;
                return;
            }

            const hours = Math.floor(totalMinutes / 60);
            const minutes = totalMinutes % 60;
            if (hours < 24) {
                counter.textContent = `en ${hours} h${minutes ? ` ${minutes} min` : ''}`;
                return;
            }

            const days = Math.floor(hours / 24);
            const remainingHours = hours % 24;
            counter.textContent = `en ${days} ${days === 1 ? 'día' : 'días'}${remainingHours ? ` ${remainingHours} h` : ''}`;
        }

        const updateAll = () => counters.forEach(updateCounter);
        updateAll();
        window.setInterval(updateAll, 30000);
    })();
</script>

<script src="/js/pwa-install.js"></script>
<script src="/js/pwa-status.js"></script>
<script src="/js/barber-dialog.js"></script>
<script src="/js/theme-toggle.js"></script>

</body>
</html>
