@extends('layouts.main')
@section("content")
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<div id="container"></div>
@endsection
@push('scripts')
<script>
let chart;
async function requestData() {
    const result = await fetch('http://127.0.0.1:8000/api/temperature');
    if (result.ok) {
        const data = await result.json();
        const temp = data.data[0].temperature;
        const time = data.data[0].created_at;
        const point = [new Date().getTime(), parseFloat(temp)];
        const series = chart.series[0], shift = series.data.length > 20;
        chart.series[0].addPoint(point, true, shift);
        setTimeout(requestData, 3000);
    }
}

window.addEventListener('load', function () {
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container',
            defaultSeriesType: 'spline',
            events: {
                load: requestData
            }
        },
        time:{
            timezone: 'Asia/Jakarta'
        },
        title: {
            text: 'Data temperature'
        },
        subtitle: {
            text: 'Sensor DHT 11'
        },
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 150,
            maxZoom: 20 * 1500
        },
        yAxis: {
            minPadding: 0.2,
            maxPadding: 0.2,
            title: {
                text: 'Celcius',
                margin: 80
            }
        },
        series: [
        {
            name: 'Suhu',
            data: []
        }
        ]
    });
});
</script>
@endpush