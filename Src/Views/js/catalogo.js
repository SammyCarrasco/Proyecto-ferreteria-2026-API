$(document).ready(function () {
    $.ajax({
        url: API_BASE_URL + "?route=productos", // ajusta según cómo definan la base en funciones.js
        method: "GET",
        headers: { /* si el catálogo requiere token, agrégalo aquí */ },
        success: function (resp) {
            let html = "";
            resp.data.forEach(p => {
                html += `
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <img src="${p.imagen}" class="card-img-top" alt="${p.nombre}">
                            <div class="card-body">
                                <h5 class="card-title">${p.nombre}</h5>
                                <p class="card-text">L. ${p.precio}</p>
                                <p class="card-text"><small class="text-muted">${p.unidad_medida}</small></p>
                            </div>
                        </div>
                    </div>`;
            });
            $("#catalogo-container").html(html);
        },
        error: function (err) { console.error(err); }
    });
});