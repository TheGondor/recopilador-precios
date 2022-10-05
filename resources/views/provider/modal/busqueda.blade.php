<div class="modal" id="{{ $idModal }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Busqueda: {{ $search->search }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="w-100" id="curve_chart" style="height: 500px"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ['Dia', 'Cantidad Busquedas'],
          @foreach ($searches as $item)
              ['{{ $item->date }}', {{ $item->quantity }}],
          @endforeach
      ]);

      var options = {
        title: 'Visitas',
        curveType: 'function',
        legend: { position: 'bottom' },
        height: 500,
        vAxis: {
        viewWindow: {
                min:0
            }
        }
      };

      var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

      chart.draw(data, options);
    }
  </script>
