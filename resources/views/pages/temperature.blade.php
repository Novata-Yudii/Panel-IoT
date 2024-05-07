@extends('layouts.main')
@section("content")
<script src="https://code.highcharts.com/highcharts.js"></script>
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
        console.log(temp);
        console.log(time);
        const point = [new Date().getTime(), temp];
        const series = chart.series[0],
        shift = series.data.length > 20; // shift if the series is longer than 20
        chart.series[0].addPoint(point, true, shift);
        // call it again after one second
        setTimeout(requestData, 1000);
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
        title: {
            text: 'Data temperature'
        },
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 150,
            maxZoom: 20 * 1000
        },
        yAxis: {
            minPadding: 0.2,
            maxPadding: 0.2,
            title: {
                text: 'Celcius',
                margin: 80
            }
        },
        series: [{
            name: 'Time',
            data: []
        }]
    });
});
</script>
@endpush