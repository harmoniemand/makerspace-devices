

<canvas id="myChart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script>

var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: [<?php
            
            foreach ($dates as $date) {
                echo "'" . $date->date . "',";
            }

            ?>],
        datasets: [{
            label: 'Visitors',
            order: 1,
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: [<?php
            
            foreach ($dates as $date) {
                echo $date->count . ",";
            }

            ?>]
        },
        {
            label: 'Guests',
            order: 2,
            backgroundColor: 'rgb(100, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: [<?php
            
            foreach ($dates as $date) {
                echo $date->guests . ",";
            }

            ?>]
        }]
    },

    // Configuration options go here
    options: {
        scales: {
            xAxes: [{
                stacked: true
            }],
            yAxes: [{
                stacked: true
            }]
        }
    }
});

</script>
