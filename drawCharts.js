const tmp = document.getElementById('temperature-array');
const value1 = JSON.parse(tmp.value);
const bpm = document.getElementById('heart-rate-array');
const value2 = JSON.parse(bpm.value);
const stmp = document.getElementById('reading-time-array');
const reading_time = JSON.parse(decodeURIComponent(stmp.value));

let chartT = new Highcharts.Chart({
chart:{ renderTo : 'chart-temperature' },
title: { text: 'Temperatura' },
series: [{
    showInLegend: false,
    data: value1
}],
plotOptions: {
    line: { animation: false,
    dataLabels: { enabled: true }
    },
    series: { color: '#059e8a' }
},
xAxis: { 
    type: 'datetime',
    categories: reading_time
},
yAxis: {
    title: { text: 'Temperatura (Celsius)' }
    //title: { text: 'Temperature (Fahrenheit)' }
},
credits: { enabled: false }
});

let chartHeart = new Highcharts.Chart({
chart:{ renderTo : 'chart-heart-rate' },
title: { text: 'Ritmo Cardiaco' },
series: [{
    showInLegend: false,
    data: value2
}],
plotOptions: {
    line: { animation: false,
    dataLabels: { enabled: true }
    },
    series: { color: '#059e8a' }
},
xAxis: { 
    type: 'datetime',
    categories: reading_time
},
yAxis: {
    title: { text: 'PPM(BPM)' }
    //title: { text: 'Temperature (Fahrenheit)' }
},
credits: { enabled: false }
});