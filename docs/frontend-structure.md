# Organización visual de BarberCore

## Hojas principales

- `public/css/barbercore-admin.css`: estilos compartidos por todas las vistas que heredan de `layouts.app`.
- `public/css/barbercore-login.css`: estilos exclusivos del inicio de sesión.
- `public/css/barbercore-landing.css`: estilos exclusivos de la landing pública.
- `public/css/modules/`: estilos que pertenecen únicamente a un módulo.

## Estilos por módulo

Las vistas que heredan de `layouts.app` deben registrar su hoja mediante el stack `styles`:

```blade
@push('styles')
<link rel="stylesheet" href="{{ asset('css/modules/modulo.css') }}">
@endpush
```

El layout carga estos archivos después de `barbercore-admin.css`, por lo que un módulo puede ajustar componentes globales sin usar estilos inline.

## Componentes Blade

Los patrones visuales repetidos deben vivir en `resources/views/components`. Por ejemplo, `x-section-heading` representa títulos y subtítulos internos de tarjetas.

## Reglas de mantenimiento

1. No agregar bloques `<style>` a vistas activas.
2. No usar `style="..."` para valores estáticos; crear una clase reutilizable.
3. Mantener inline únicamente variables calculadas con datos, como posiciones del Gantt o porcentajes de gráficas.
4. Colocar estilos compartidos en `barbercore-admin.css` y estilos exclusivos en `public/css/modules`.
5. Reutilizar un componente Blade cuando una misma estructura aparezca en más de una vista.
6. Conservar los estilos del PDF embebidos, porque el generador necesita recibirlos dentro del documento renderizado.
