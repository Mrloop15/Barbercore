(function () {
    if (window.BarberDialog) return;

    const styles = document.createElement('style');
    styles.textContent = `
        .bc-dialog-layer{position:fixed;inset:0;z-index:20000;display:grid;place-items:center;padding:18px;opacity:0;visibility:hidden;transition:opacity .2s ease,visibility .2s ease}
        .bc-dialog-layer.open{opacity:1;visibility:visible}
        .bc-dialog-backdrop{position:absolute;inset:0;background:rgba(18,18,17,.62);backdrop-filter:blur(5px)}
        .bc-dialog-card{position:relative;width:min(430px,100%);overflow:hidden;border:1px solid var(--borde,#E5E0D6);border-radius:22px;background:var(--blanco,#fff);box-shadow:0 28px 75px rgba(0,0,0,.28);transform:translateY(12px) scale(.97);transition:transform .24s cubic-bezier(.22,.8,.24,1),background-color .38s ease,border-color .38s ease}
        .bc-dialog-layer.open .bc-dialog-card{transform:none}
        .bc-dialog-accent{height:4px;background:var(--dorado,#C9A227)}
        .bc-dialog-layer[data-tone="danger"] .bc-dialog-accent{background:var(--rojo,#C62828)}
        .bc-dialog-layer[data-tone="success"] .bc-dialog-accent{background:var(--verde,#2E7D32)}
        .bc-dialog-content{padding:25px 26px 20px;text-align:center}
        .bc-dialog-icon{width:54px;height:54px;display:grid;place-items:center;margin:0 auto 15px;border-radius:17px;background:rgba(201,162,39,.12);color:var(--dorado,#C9A227)}
        .bc-dialog-layer[data-tone="danger"] .bc-dialog-icon{background:rgba(198,40,40,.1);color:var(--rojo,#C62828)}
        .bc-dialog-layer[data-tone="success"] .bc-dialog-icon{background:rgba(46,125,50,.1);color:var(--verde,#2E7D32)}
        .bc-dialog-icon svg{width:25px;height:25px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
        .bc-dialog-title{margin:0;color:var(--texto,#1C1C1C);font:800 20px/1.25 'Manrope','DM Sans',sans-serif;letter-spacing:-.4px}
        .bc-dialog-message{margin:9px auto 0;max-width:340px;color:var(--gris,#6f6b64);font:500 13px/1.55 'DM Sans',sans-serif}
        .bc-dialog-actions{display:flex;justify-content:center;gap:9px;padding:0 26px 25px}
        .bc-dialog-button{min-width:118px;min-height:42px;padding:10px 16px;border:0;border-radius:11px;font:800 13px/1 'DM Sans',sans-serif;cursor:pointer;transition:transform .16s ease,box-shadow .16s ease,background .16s ease}
        .bc-dialog-button:hover{transform:translateY(-1px)}
        .bc-dialog-cancel{border:1px solid var(--borde,#E5E0D6);background:var(--crema,#F8F5EE);color:var(--texto,#1C1C1C)}
        .bc-dialog-confirm{background:var(--dorado,#C9A227);color:var(--texto-sobre-acento,#fff);box-shadow:0 7px 16px rgba(201,162,39,.22)}
        .bc-dialog-layer[data-tone="danger"] .bc-dialog-confirm{background:var(--rojo,#C62828);box-shadow:0 7px 16px rgba(198,40,40,.2)}
        .bc-dialog-layer[data-tone="success"] .bc-dialog-confirm{background:var(--verde,#2E7D32);box-shadow:0 7px 16px rgba(46,125,50,.2)}
        body.bc-dialog-open{overflow:hidden}
        @media(max-width:480px){.bc-dialog-content{padding:22px 20px 18px}.bc-dialog-actions{padding:0 20px 22px;flex-direction:column-reverse}.bc-dialog-button{width:100%}}
        @media(prefers-reduced-motion:reduce){.bc-dialog-layer,.bc-dialog-card,.bc-dialog-button{transition:none}}
    `;
    document.head.appendChild(styles);

    const layer = document.createElement('div');
    layer.className = 'bc-dialog-layer';
    layer.setAttribute('aria-hidden', 'true');
    layer.innerHTML = `
        <div class="bc-dialog-backdrop" data-dialog-cancel></div>
        <section class="bc-dialog-card" role="alertdialog" aria-modal="true" aria-labelledby="bcDialogTitle" aria-describedby="bcDialogMessage">
            <div class="bc-dialog-accent"></div>
            <div class="bc-dialog-content">
                <div class="bc-dialog-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 8v5M12 17h.01"/><circle cx="12" cy="12" r="9"/></svg></div>
                <h2 class="bc-dialog-title" id="bcDialogTitle"></h2>
                <p class="bc-dialog-message" id="bcDialogMessage"></p>
            </div>
            <div class="bc-dialog-actions">
                <button type="button" class="bc-dialog-button bc-dialog-cancel" data-dialog-cancel>Volver</button>
                <button type="button" class="bc-dialog-button bc-dialog-confirm" data-dialog-confirm>Confirmar</button>
            </div>
        </section>`;
    document.body.appendChild(layer);

    const title = layer.querySelector('.bc-dialog-title');
    const message = layer.querySelector('.bc-dialog-message');
    const cancelButton = layer.querySelector('.bc-dialog-cancel');
    const confirmButton = layer.querySelector('.bc-dialog-confirm');
    let resolveCurrent = null;
    let previousFocus = null;

    function close(result) {
        if (!resolveCurrent) return;
        const resolve = resolveCurrent;
        resolveCurrent = null;
        layer.classList.remove('open');
        layer.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('bc-dialog-open');
        window.setTimeout(() => previousFocus?.focus?.(), 0);
        resolve(result);
    }

    function confirmDialog(options) {
        const config = typeof options === 'string' ? { message: options } : (options || {});
        if (resolveCurrent) close(false);
        previousFocus = document.activeElement;
        title.textContent = config.title || '¿Confirmar acción?';
        message.textContent = config.message || 'Esta acción modificará la información del sistema.';
        cancelButton.textContent = config.cancelText || 'Volver';
        confirmButton.textContent = config.confirmText || 'Confirmar';
        layer.dataset.tone = config.tone || 'default';
        layer.classList.add('open');
        layer.setAttribute('aria-hidden', 'false');
        document.body.classList.add('bc-dialog-open');
        window.setTimeout(() => confirmButton.focus(), 30);
        return new Promise(resolve => { resolveCurrent = resolve; });
    }

    layer.querySelectorAll('[data-dialog-cancel]').forEach(element => element.addEventListener('click', () => close(false)));
    confirmButton.addEventListener('click', () => close(true));
    document.addEventListener('keydown', event => {
        if (!layer.classList.contains('open')) return;
        if (event.key === 'Escape') close(false);
        if (event.key === 'Tab') {
            const target = event.shiftKey ? confirmButton : cancelButton;
            if (!layer.contains(document.activeElement) || (event.shiftKey && document.activeElement === cancelButton) || (!event.shiftKey && document.activeElement === confirmButton)) {
                event.preventDefault();
                target.focus();
            }
        }
    });

    window.BarberDialog = { confirm: confirmDialog, close };

    document.addEventListener('submit', function (event) {
        const form = event.target.closest('form[data-confirm]');
        if (!form || form.dataset.confirmed === 'true') {
            if (form) delete form.dataset.confirmed;
            return;
        }

        event.preventDefault();
        const submitter = event.submitter;
        confirmDialog({
            title: form.dataset.confirmTitle,
            message: form.dataset.confirm,
            confirmText: form.dataset.confirmText,
            cancelText: form.dataset.confirmCancelText || form.dataset.cancelText,
            tone: form.dataset.confirmTone,
        }).then(confirmed => {
            if (!confirmed) return;
            form.dataset.confirmed = 'true';
            if (typeof form.requestSubmit === 'function') form.requestSubmit(submitter || undefined);
            else form.submit();
        });
    });
})();
