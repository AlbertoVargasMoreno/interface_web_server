<?php
// require 'read_db.php';
// $result = read_vital_signs_history();
// $temperature = $result['temperature'];
// $heart_rate = $result['heart_rate'];
// $reading_time = $result['reading_time'];
// $rows = $result['rows'];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.css">
  </head>
<body>
    <div>
        <form>
            <input type="hidden"
             id="temperature-array" 
             name="custId" 
             value=<?= $temperature; ?>
             >
            <input type="hidden"
             id="heart-rate-array"
             name="custId" 
             value=<?= $heart_rate; ?>
             >
            <input type="hidden"
             id="reading-time-array" 
             name="custId" 
             value=<?= urlencode($reading_time); ?>
             >
        </form>
    </div>
    <div class="container">
        <table
        data-toggle="table"
        data-show-columns="true"
        data-show-export="true"
        data-height="460"
        data-pagination="true">
            <thead>
                <tr class="tr-class-1">
                    <th data-field="id" data-valign="middle">Id</th>
                    <th data-field="sensor-name" data-custom-attribute="star">Sensor</th>
                    <th data-field="temperature" data-custom-attribute="forks">Temperatura</th>
                    <th data-field="heart-rate">Ritmo Cardiaco</th>
                </tr>
            </thead>
            <tbody>
                <tr id="tr-id-1" class="tr-class-1" data-title="bootstrap table" data-object='{"key": "value"}'>
                    <td id="td-id-1" class="td-class-1" data-title="bootstrap table">
                        <?= $rows[0]['id']; ?>
                    </td>
                    <td data-value="526">
                        <?= $rows[0]['sensor_names']; ?>
                    </td>
                    <td data-text="122">
                        <?= $rows[0]['temperature_value']; ?>
                    </td>
                    <td data-i18n="Description">
                        <?= $rows[0]['heart_rate_value']; ?>
                    </td>
                </tr>
                <?php foreach ($rows as $index => $result_row): ?>
                <tr id="tr-id-2" class="tr-class-2">
                    <td id="td-id-2" class="td-class-2">
                        <?= $result_row['id']; ?>
                    </td>
                    <td>
                        <?= $result_row['sensor_names']; ?>
                    </td>
                    <td>
                        <?= $result_row['temperature_value']; ?>
                    </td>
                    <td>
                        <?= $result_row['heart_rate_value']; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="chart-temperature" class="container"></div>
    <div id="chart-heart-rate" class="container"></div>
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.22.1/dist/extensions/export/bootstrap-table-export.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="drawCharts.js"></script>
</body>
</html>