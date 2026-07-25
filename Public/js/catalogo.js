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
            resp.data.forEach(function (p) {
                let imgSrc = p.imagen
                    ? 'uploads/productos/' + p.imagen
                    : '';

                html += `
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <img src="${imgSrc}" class="card-img-top" alt="${p.nombre || ''}"
                                 style="height:200px; width:100%; object-fit:contain; background:#f8f9fa; padding:10px;"
                                 onerror="this.src='https://via.placeholder.com/300x180?text=Sin+imagen'">
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