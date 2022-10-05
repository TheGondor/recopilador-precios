@extends('layouts.lubeck')
@section('title')
    <title>Bienvenido</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

          // Create the data table.
          var data = new google.visualization.DataTable();
          data.addColumn('string', 'Topping');
          data.addColumn('number', 'Slices');
          data.addRows([
            ['Stock: {{ $stock }}', {{ $stock }}],
            ['Sin Stock: {{ $sin_stock }}', {{ $sin_stock }}],
            ['Stock fuera dispersion: {{ $stock_dispersion }}', {{ $stock_dispersion }}],
            ['Sin stock fuera dispersion: {{ $sin_stock_dispersion }}', {{ $sin_stock_dispersion }}]
          ]);

          // Set chart options
          var options = {'title':'Grafico productos Lubeck',
                        'pieHole' : 0.4,
                         'height':500};

          // Instantiate and draw our chart, passing in some options.
          var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
          chart.draw(data, options);
        }
      </script>
@endsection
@section('content')

<div class="container m-auto bg-white p-5 rounded" style="opacity: 0.9">
    <h2>Productos {{$provider->name}}</h2>
    <div>
        <div id="chart_div"></div>
    </div>
    <a href='/provider/{{ $provider->id}}/ferreteria' target="_blank" class='btn btn-sm btn-outline-success m-1 px-3' title='Descargar'><i class="fas fa-file-excel"></i> Descargar Excel</a>
</div>
@endsection
