@extends('layouts.app')

@section('title', 'Preguntas frecuentes | BarberCore')
@section('page-title', 'Preguntas frecuentes')

@section('content')

@php
    $preguntasFormulario = old('preguntas');
    if ($preguntasFormulario === null) {
        $preguntasFormulario = $preguntasFrecuentes->map(fn ($faq) => [
            'id_pregunta' => $faq->id_pregunta,
            'pregunta' => $faq->pregunta,
            'respuesta' => $faq->respuesta,
            'activo' => $faq->activo,
        ])->all();
    }
@endphp

<style>
    .faq-admin { max-width: 980px; margin: 0 auto; }
    .faq-admin-head { display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 18px; }
    .faq-admin-head h3 { margin: 0 0 5px; font-size: 21px; }
    .faq-admin-head p { margin: 0; color: var(--gris); font-size: 13px; line-height: 1.55; }
    .faq-list { display: grid; gap: 10px; }
    .faq-editor { overflow: hidden; border: 1px solid var(--borde); border-radius: 14px; background: var(--blanco); }
    .faq-editor summary { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 16px 18px; cursor: pointer; list-style: none; }
    .faq-editor summary::-webkit-details-marker { display: none; }
    .faq-editor summary::after { content: "Editar"; color: var(--dorado); font-size: 11px; font-weight: 800; text-transform: uppercase; }
    .faq-editor[open] summary { border-bottom: 1px solid var(--borde); background: var(--fondo); }
    .faq-editor[open] summary::after { content: "Cerrar"; }
    .faq-summary-main { min-width: 0; display: flex; align-items: center; gap: 12px; }
    .faq-number { width: 30px; height: 30px; display: grid; place-items: center; flex: 0 0 auto; border-radius: 9px; background: rgba(201,162,39,.13); color: var(--dorado); font-size: 11px; font-weight: 900; }
    .faq-summary-main strong { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 14px; }
    .faq-visibility { flex: 0 0 auto; padding: 5px 8px; border-radius: 999px; background: rgba(39,174,96,.1); color: var(--verde); font-size: 9px; font-weight: 900; text-transform: uppercase; }
    .faq-visibility.is-hidden { background: rgba(107,107,107,.1); color: var(--gris); }
    .faq-editor-body { padding: 18px; }
    .faq-fields { display: grid; grid-template-columns: 1fr 1.35fr; gap: 18px; }
    .faq-fields textarea { min-height: 105px; }
    .faq-options { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-top: 14px; }
    .faq-active { display: inline-flex; align-items: center; gap: 8px; margin: 0; font-size: 12px; }
    .faq-active input { width: 17px; height: 17px; accent-color: var(--dorado); }
    .faq-empty { padding: 34px; border: 1px dashed var(--borde); border-radius: 14px; color: var(--gris); text-align: center; }
    .faq-actions { display: flex; align-items: center; justify-content: space-between; gap: 14px; margin-top: 18px; padding-top: 18px; border-top: 1px solid var(--borde); }
    .faq-actions-main { display: flex; gap: 10px; }
    .btn-outline-danger { border: 1px solid rgba(192,57,43,.28); background: transparent; color: var(--rojo); }

    @media (max-width: 700px) {
        .faq-admin-head, .faq-options, .faq-actions { align-items: stretch; flex-direction: column; }
        .faq-fields { grid-template-columns: 1fr; }
        .faq-actions-main { display: grid; }
        .faq-editor summary { padding: 14px; }
        .faq-visibility { display: none; }
    }
</style>

<div class="faq-admin">
    @if ($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif

    <div class="faq-admin-head">
        <div><h3>Contenido de ayuda para clientes</h3><p>Las preguntas permanecen compactas. Abre solamente la que quieras editar.</p></div>
        <a class="btn btn-secondary" href="{{ route('configuracion.index', ['seccion' => 'preguntas']) }}">Volver a configuración</a>
    </div>

    <div class="content-card">
        <form method="POST" action="{{ route('configuracion.preguntas') }}" id="faq-form">
            @csrf
            @method('PUT')
            <div class="faq-list" id="faq-list">
                @foreach($preguntasFormulario as $indice => $faq)
                    <details class="faq-editor" data-faq-row @if($loop->first && $errors->any()) open @endif>
                        <summary>
                            <span class="faq-summary-main"><span class="faq-number" data-faq-number>{{ $indice + 1 }}</span><strong data-faq-summary>{{ $faq['pregunta'] ?? 'Nueva pregunta' }}</strong></span>
                            <span class="faq-visibility {{ !($faq['activo'] ?? false) ? 'is-hidden' : '' }}" data-faq-visibility>{{ ($faq['activo'] ?? false) ? 'Visible' : 'Oculta' }}</span>
                        </summary>
                        <div class="faq-editor-body">
                            <input type="hidden" name="preguntas[{{ $indice }}][id_pregunta]" data-faq-field="id_pregunta" value="{{ $faq['id_pregunta'] ?? '' }}">
                            <div class="faq-fields">
                                <div><label>Pregunta</label><input type="text" name="preguntas[{{ $indice }}][pregunta]" data-faq-field="pregunta" value="{{ $faq['pregunta'] ?? '' }}" maxlength="255" required></div>
                                <div><label>Respuesta</label><textarea name="preguntas[{{ $indice }}][respuesta]" data-faq-field="respuesta" maxlength="2000" required>{{ $faq['respuesta'] ?? '' }}</textarea></div>
                            </div>
                            <div class="faq-options">
                                <label class="faq-active"><input type="checkbox" name="preguntas[{{ $indice }}][activo]" data-faq-field="activo" value="1" @checked((bool) ($faq['activo'] ?? false))> Mostrar en la landing</label>
                                <button type="button" class="btn btn-outline-danger" data-remove-faq>Eliminar pregunta</button>
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>

            <div class="faq-empty" id="faq-empty" @if(count($preguntasFormulario)) hidden @endif>No hay preguntas frecuentes registradas.</div>
            <div class="faq-actions">
                <button type="button" class="btn btn-secondary" id="add-faq">Agregar pregunta</button>
                <div class="faq-actions-main"><a class="btn btn-secondary" href="{{ route('configuracion.index') }}">Cancelar</a><button type="submit" class="btn btn-primary">Guardar cambios</button></div>
            </div>
        </form>
    </div>
</div>

<template id="faq-template">
    <details class="faq-editor" data-faq-row open>
        <summary><span class="faq-summary-main"><span class="faq-number" data-faq-number></span><strong data-faq-summary>Nueva pregunta</strong></span><span class="faq-visibility" data-faq-visibility>Visible</span></summary>
        <div class="faq-editor-body">
            <input type="hidden" data-faq-field="id_pregunta" value="">
            <div class="faq-fields"><div><label>Pregunta</label><input type="text" data-faq-field="pregunta" maxlength="255" required></div><div><label>Respuesta</label><textarea data-faq-field="respuesta" maxlength="2000" required></textarea></div></div>
            <div class="faq-options"><label class="faq-active"><input type="checkbox" data-faq-field="activo" value="1" checked> Mostrar en la landing</label><button type="button" class="btn btn-outline-danger" data-remove-faq>Eliminar pregunta</button></div>
        </div>
    </details>
</template>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const list = document.getElementById('faq-list');
        const empty = document.getElementById('faq-empty');
        const add = document.getElementById('add-faq');
        const template = document.getElementById('faq-template');

        const updateRow = (row) => {
            const question = row.querySelector('[data-faq-field="pregunta"]');
            const active = row.querySelector('[data-faq-field="activo"]');
            const visibility = row.querySelector('[data-faq-visibility]');
            row.querySelector('[data-faq-summary]').textContent = question.value.trim() || 'Nueva pregunta';
            visibility.textContent = active.checked ? 'Visible' : 'Oculta';
            visibility.classList.toggle('is-hidden', !active.checked);
        };

        const reindex = () => {
            const rows = [...list.querySelectorAll('[data-faq-row]')];
            rows.forEach((row, index) => {
                row.querySelector('[data-faq-number]').textContent = index + 1;
                row.querySelectorAll('[data-faq-field]').forEach((field) => field.name = `preguntas[${index}][${field.dataset.faqField}]`);
                updateRow(row);
            });
            empty.hidden = rows.length > 0;
            add.disabled = rows.length >= 20;
        };

        list.addEventListener('input', (event) => {
            const row = event.target.closest('[data-faq-row]');
            if (row) updateRow(row);
        });
        list.addEventListener('change', (event) => {
            const row = event.target.closest('[data-faq-row]');
            if (row) updateRow(row);
        });
        list.addEventListener('click', async (event) => {
            const button = event.target.closest('[data-remove-faq]');
            if (!button) return;
            const confirmed = await window.BarberDialog.confirm({
                title: 'Eliminar pregunta',
                message: 'La pregunta se quitará del listado. El cambio se aplicará cuando guardes la configuración.',
                confirmText: 'Quitar pregunta',
                tone: 'danger',
            });
            if (!confirmed) return;
            button.closest('[data-faq-row]').remove();
            reindex();
        });
        add.addEventListener('click', () => {
            if (list.querySelectorAll('[data-faq-row]').length >= 20) return;
            list.appendChild(template.content.cloneNode(true));
            reindex();
            list.lastElementChild.querySelector('[data-faq-field="pregunta"]').focus();
        });

        reindex();
    });
</script>

@endsection
