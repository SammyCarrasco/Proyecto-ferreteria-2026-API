<div class="container-fluid">

<!-- ENCABEZADO -->
<div class="d-flex justify-content-between align-items-center mb-4">
<div>
<h4 class="fw-bold text-dark mb-0">
<i class="bi bi-speedometer2 text-primary me-2"></i>
Dashboard Principal
</h4>

<small class="text-muted">
Resumen general de la ferretería
</small>
</div>
</div>

<!-- KPI -->
<div class="row g-3">

<!-- VENTAS -->
<div class="col-md-4 col-lg-3">
<div class="card border-0 shadow-sm">
<div class="card-body">
<div class="d-flex justify-content-between">
<div>
<h6 class="text-muted">
Ventas
</h6>

<h3 class="fw-bold text-success" id="totalVentas">
$ 0.00
</h3>
</div>
<div class="text-success fs-1">
<i class="bi bi-cash-stack"></i>
</div>
</div>
</div>
</div>
</div>

<!-- PRODUCTOS -->
<div class="col-md-4 col-lg-3">
<div class="card border-0 shadow-sm">
<div class="card-body">

<div class="d-flex justify-content-between">

<div>
<h6 class="text-muted">
Productos

</h6>
<h3 class="fw-bold" id="totalProductos">
0
</h3>
</div>

<div class="text-primary fs-1">
<i class="bi bi-box-seam"></i>
</div>
</div>
</div>
</div>
</div>

<!-- CLIENTES -->

<div class="col-md-4 col-lg-3">
<div class="card border-0 shadow-sm">
<div class="card-body">
<div class="d-flex justify-content-between">
<div>
<h6 class="text-muted">
Clientes

</h6>
<h3 class="fw-bold" id="totalClientes">
0
</h3>
</div>
<div class="text-warning fs-1">
<i class="bi bi-people-fill"></i>
</div>
</div>

</div>

</div>

</div>

<!-- COTIZACIONES -->

<div class="col-md-4 col-lg-3">

<div class="card border-0 shadow-sm">

<div class="card-body">

<div class="d-flex justify-content-between">

<div>

<h6 class="text-muted">
Cotizaciones
</h6>
<h3 class="fw-bold" id="totalCotizaciones">
0
</h3>
</div>

<div class="text-danger fs-1">
<i class="bi bi-file-earmark-text"></i>
</div>
</div>
</div>
</div>

</div>
<!-- INVENTARIO -->

<div class="col-md-4 col-lg-3">
<div class="card border-0 shadow-sm">
<div class="card-body">
<div class="d-flex justify-content-between">
<div>
<h6 class="text-muted">

Stock Inventario
</h6>
<h3 class="fw-bold" id="stockInventario">
0
</h3>
</div>
<div class="text-info fs-1">
<i class="bi bi-archive-fill"></i>
</div>

</div>

</div>

</div>

</div>

</div>

<!-- GRAFICOS -->

<div class="row mt-4">

<div class="col-md-6">

<div class="card border-0 shadow-sm">

<div class="card-header bg-white fw-bold">

Productos por Categoría

</div>

<div class="card-body">

<canvas id="graficoCategorias"></canvas>

</div>

</div>
</div>

<div class="col-md-6">
<div class="card border-0 shadow-sm">
<div class="card-header bg-white fw-bold">
Ventas Mensuales
</div>

<div class="card-body">

<canvas id="graficoVentas"></canvas>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){
const token = localStorage.getItem('token');
let chartCategorias = null;
let chartVentas = null;

function cargarDashboard(){
$.ajax({
url:'dashboard',
type:'GET',
headers:{
'Authorization':'Bearer '+token
},

dataType:'json',

success:function(res){

console.log("Dashboard:",res);

let data=res.data;

$("#totalVentas")
.text("$ "+parseFloat(data.ventas.total_ventas).toFixed(2));

$("#totalProductos")
.text(data.productos.total_productos);

$("#totalClientes")
.text(data.clientes.total_clientes);

$("#totalCotizaciones")
.text(data.cotizaciones.total_cotizaciones);

$("#stockInventario")
.text(data.inventario.stock_total);
cargarCategorias(
data.productosCategoria || []
);
cargarVentas(
data.ventasMensuales || []
);

},

error:function(xhr){
console.log(xhr.responseText);
}
});
}
function cargarCategorias(datos){
let etiquetas=[];
let valores=[];

datos.forEach(item=>{
etiquetas.push(item.categoria);
valores.push(item.cantidad_productos);
});

if(chartCategorias){
chartCategorias.destroy();
}

chartCategorias=new Chart(
document.getElementById('graficoCategorias'),

{
type:'doughnut',
data:{
labels:etiquetas,
datasets:[{
data:valores

}]

}

}

);

}

function cargarVentas(datos){

let meses=[];
let valores=[];

datos.forEach(item=>{
meses.push(item.mes);
valores.push(item.total);

});

// si no existen ventas mensuales

if(datos.length===0){
meses.push("Sin ventas");
valores.push(0);

}

if(chartVentas){

chartVentas.destroy();
}


chartVentas=new Chart(
document.getElementById('graficoVentas'),

{

type:'bar',

data:{

labels:meses,

datasets:[{

label:'Ventas',
data:valores

}]

}


}

);

}

cargarDashboard();

})();

</script>