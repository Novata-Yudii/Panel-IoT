@extends('layouts.main')
@section("content")
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
<div id="container"></div>
@endsection
@push('scripts')
<script>
let chart;
async function requestData(msg) {
    let temp = (msg.temperature).toFixed(3);
    const point = [new Date().getTime(), parseFloat(temp)];
    const series = chart.series[0], shift = series.data.length > 20;
    chart.series[0].addPoint(point, true, shift);
    // setTimeout(requestData, 3000);
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

    // Konfigurasi connect to mqtt broker emqx OVER MQTTS
    const url = 'wss://x10ea311.ala.asia-southeast1.emqxsl.com:8084/mqtt'
    const options = {
        clean: true,
        connectTimeout: 4000,
        clientId: 'emqx-novatayudi',
        username: 'Novatayudi',
        password: 'Xos116ya77',
        ca:`-----BEGIN CERTIFICATE-----
MIIDrzCCApegAwIBAgIQCDvgVpBCRrGhdWrJWZHHSjANBgkqhkiG9w0BAQUFADBh
MQswCQYDVQQGEwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3
d3cuZGlnaWNlcnQuY29tMSAwHgYDVQQDExdEaWdpQ2VydCBHbG9iYWwgUm9vdCBD
QTAeFw0wNjExMTAwMDAwMDBaFw0zMTExMTAwMDAwMDBaMGExCzAJBgNVBAYTAlVT
MRUwEwYDVQQKEwxEaWdpQ2VydCBJbmMxGTAXBgNVBAsTEHd3dy5kaWdpY2VydC5j
b20xIDAeBgNVBAMTF0RpZ2lDZXJ0IEdsb2JhbCBSb290IENBMIIBIjANBgkqhkiG
9w0BAQEFAAOCAQ8AMIIBCgKCAQEA4jvhEXLeqKTTo1eqUKKPC3eQyaKl7hLOllsB
CSDMAZOnTjC3U/dDxGkAV53ijSLdhwZAAIEJzs4bg7/fzTtxRuLWZscFs3YnFo97
nh6Vfe63SKMI2tavegw5BmV/Sl0fvBf4q77uKNd0f3p4mVmFaG5cIzJLv07A6Fpt
43C/dxC//AH2hdmoRBBYMql1GNXRor5H4idq9Joz+EkIYIvUX7Q6hL+hqkpMfT7P
T19sdl6gSzeRntwi5m3OFBqOasv+zbMUZBfHWymeMr/y7vrTC0LUq7dBMtoM1O/4
gdW7jVg/tRvoSSiicNoxBN33shbyTApOB6jtSj1etX+jkMOvJwIDAQABo2MwYTAO
BgNVHQ8BAf8EBAMCAYYwDwYDVR0TAQH/BAUwAwEB/zAdBgNVHQ4EFgQUA95QNVbR
TLtm8KPiGxvDl7I90VUwHwYDVR0jBBgwFoAUA95QNVbRTLtm8KPiGxvDl7I90VUw
DQYJKoZIhvcNAQEFBQADggEBAMucN6pIExIK+t1EnE9SsPTfrgT1eXkIoyQY/Esr
hMAtudXH/vTBH1jLuG2cenTnmCmrEbXjcKChzUyImZOMkXDiqw8cvpOp/2PV5Adg
06O/nVsJ8dWO41P0jmP6P6fbtGbfYmbW0W5BjfIttep3Sp+dWOIrWcBAI+0tKIJF
PnlUkiaY4IBIqDfv8NZ5YBberOgOzW6sRBc4L0na4UU+Krk2U886UAb3LujEV0ls
YSEY1QSteDwsOoBrp+uvFRTp2InBuThs4pFsiv9kuXclVzDAGySj4dzp30d8tbQk
CAUw7C29C79Fv1C5qfPrmAESrciIxpg0X40KPMbp1ZWVbd4=
-----END CERTIFICATE-----`
        }
    const client  = mqtt.connect(url, options)
    client.on('connect', function () {
    console.log('Connected')
        client.subscribe('/temperature', function (err) {
            if (err) {
                console.log(err)
            } else {
                console.log('Subscribe in /temperature')
            }
        })
        let pub = {value : 0}
        pub = JSON.stringify(pub);
        client.publish('/lampu', pub, function(err){
            if (err) {
                console.log(err)
            } else {
                console.log('Published')
            }
        })
    })

    client.on('message', async function (topic, message) {
        if(topic == '/temperature'){
            const msg = JSON.parse(message)
            if(typeof msg == 'object'){
                await requestData(msg)
            }
        }
    })
})  
</script>
@endpush