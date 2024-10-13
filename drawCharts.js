setInterval(function() {
    main();
}, 3000);

async function main() {
    const vitalSigns = await makeDbRequest();
    populateTable(vitalSigns);
    drawCharts(vitalSigns);
}

async function makeDbRequest() {
    const url = 'read_db.php';

    try {
        const response = await fetch(url, {
            method: 'POST',
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const responseData = await response.json();
        // console.log('Success:', responseData);
        return responseData;
    } catch (error) {
        console.error('Error:', error);
    }
}

function populateTable(data) {
    const rows = data.rows;
    const tableBody = document.getElementById('vital-signs').querySelector('tbody');
    tableBody.innerHTML = '';
    rows.forEach(row => {
        let tableRowMarkup =
            `<tr id="tr-id-2" class="tr-class-2">
                <td id="td-id-2" class="td-class-2">
                    ${row.id}
                </td>
                <td>
                    ${row.sensor_names}
                </td>
                <td>
                    ${row.temperature_value}
                </td>
                <td>
                    ${row.heart_rate_value}
                </td>
            </tr>`;
        tableBody.innerHTML += tableRowMarkup;
    });
}

function drawCharts(data) {
    const value1 = JSON.parse(data.temperature);
    const value2 = JSON.parse(data.heart_rate);
    const reading_time = JSON.parse(data.reading_time);
    let chartT = new Highcharts.Chart({
        chart: { renderTo: 'chart-temperature' },
        title: { text: 'Temperatura' },
        series: [{
            showInLegend: false,
            data: value1
        }],
        plotOptions: {
            line: {
                animation: false,
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
        chart: { renderTo: 'chart-heart-rate' },
        title: { text: 'Ritmo Cardiaco' },
        series: [{
            showInLegend: false,
            data: value2
        }],
        plotOptions: {
            line: {
                animation: false,
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
}