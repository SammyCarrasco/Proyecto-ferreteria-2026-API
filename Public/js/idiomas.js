/**
 * Aplica el idioma guardado (localStorage 'idioma', 'es' por defecto)
 * a cualquier elemento marcado con data-i18n="clave".
 * Uso: <span data-i18n="menu_clientes">Clientes</span>
 */
(function () {
    const idioma = localStorage.getItem('idioma') || 'es';

    $.ajax({
        url: 'traducciones',
        method: 'GET',
        dataType: 'json',
        success: function (resp) {
            let dict = {};
            (resp.data || []).forEach(function (fila) {
                dict[fila.clave] = fila[idioma] || fila.es;
            });

            $('[data-i18n]').each(function () {
                let clave = $(this).data('i18n');
                if (dict[clave]) {
                    $(this).text(dict[clave]);
                }
            });
        },
        error: function () {
            console.warn('No se pudieron cargar las traducciones, se deja el texto por defecto.');
        }
    });
})();