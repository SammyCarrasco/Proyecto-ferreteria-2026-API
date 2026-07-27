/**
 * Módulo: Catálogo de productos (Caso de uso 10)
 * Se ejecuta apenas se inyecta form_catalogo.php dentro del menú.
 * Usa ruta relativa 'productos', igual que login.php usa 'auth'
 * -> ambas pasan por el mismo router de public/index.php.
 */
(function () {
    $.ajax({
        url: 'productos',
        method: 'GET',
        dataType: 'json',
        success: function (resp) {
            $('#catalogo-status').addClass('d-none');

            if (!resp.data || resp.data.length === 0) {
                $('#catalogo-container').html('<p class="text-muted">No hay productos registrados en el catálogo.</p>');
                return;
            }

            let html = '';
            const PLACEHOLDER = 'data:image/svg+xml;utf8,' + encodeURIComponent(
                '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="180"><rect width="100%" height="100%" fill="#e9ecef"/><text x="50%" y="50%" font-family="Arial" font-size="16" fill="#6c757d" text-anchor="middle" dominant-baseline="middle">Sin imagen</text></svg>'
            );
            resp.data.forEach(function (p) {
                let imgSrc = p.imagen
                    ? 'uploads/productos/' + p.imagen
                    : PLACEHOLDER;

                html += `
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <img src="${imgSrc}" class="card-img-top" alt="${p.nombre || ''}"
                                 style="height:200px; width:100%; object-fit:contain; background:#f8f9fa; padding:10px;"
                                 onerror="this.onerror=null; this.src='${PLACEHOLDER}';">
                            <div class="card-body">
                                <h5 class="card-title">${p.nombre || '(sin nombre)'}</h5>
                                <p class="card-text mb-1">L. ${p.precio ?? '-'}</p>
                                <p class="card-text"><small class="text-muted">${p.unidad_medida || '-'}</small></p>
                            </div>
                        </div>
                    </div>`;
            });
            $('#catalogo-container').html(html);
        },
        error: function (xhr) {
            $('#catalogo-status')
                .removeClass('alert-secondary')
                .addClass('alert-danger')
                .text('No se pudo cargar el catálogo (' + xhr.status + ').');
        }
    });
})();