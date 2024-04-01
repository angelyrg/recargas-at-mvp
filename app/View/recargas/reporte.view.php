<div class="row mt-3">
    <div class="col-12 col-md-6 mx-auto mb-2">
        <div id="pieChartCanalContainer" class="card chartContainer" style="width: 100%; height: 400px; margin: auto"></div>
    </div>
    <div class="col-12 col-md-6 mx-auto mb-2">
        <div id="pieChartBancoContainer" class="card chartContainer" style="width: 100%; height: 400px; margin: auto"></div>
    </div>
</div>

<script>
    $(document).ready(function() {
        cargarDatosYRenderizarGrafico("canal_comunicacion", "pieChartCanalContainer", "Recargas por Canal de Comunicación", "Recargas");
        cargarDatosYRenderizarGrafico("banco", "pieChartBancoContainer", "Recargas por Bancos", "Recargas");
    });

    function cargarDatosYRenderizarGrafico(column_name, graphContainerId, graphTitle, serieName) {
        obtenerDatosGrafico(column_name, function(error, data) {
            if (error) {
                console.error("Error:", error);
                return;
            }
            if (data && data.data_to_graph) {
                renderizarGrafico(data.data_to_graph, graphContainerId, graphTitle, serieName);

            } else {
                console.error("No se recibieron datos para graficar.");
            }
        });
    }

    function obtenerDatosGrafico(column_name, callback) {
        const url = BASE_PATH + "/recarga/grafico";
        $.ajax({
            url: url,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                column: column_name
            }),
            success: function(data) {
                callback(null, data);
            },
            error: function(xhr, status, error) {
                callback(error);
            },
        });
    }

    function renderizarGrafico(datosParaGrafico, graphContainerId, graphTitle, serieName) {
        Highcharts.chart(graphContainerId, {
            chart: {
                type: "pie",
            },
            title: {
                text: graphTitle,
            },
            series: [{
                name: serieName,
                colorByPoint: true,
                data: datosParaGrafico,
            }],
        });
    }
</script>