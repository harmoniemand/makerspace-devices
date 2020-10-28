




<div class="row mt-3" style="max-width: 100%;">
    <div class="col">
        <h1 class="wp-heading-inline" style="font-size: 23px;"><?php echo __('Meine Stammdaten') ?></h1>
    </div>
</div>

<div class="row mt-3" style="max-width: 100%; margin-top: 0 !important;">
    <div class="col">
        <div class="card wp-settings" style="border-radius: 0; padding: 8px 12px;">
            <div class="card-body">

                <div style="display: flex;">
                    <h5 class="card-title"><?php echo __('Besuchende Maker Space') ?></h5>
                </div>


                <canvas id="myChart"></canvas>


            </div>
        </div>
    </div>

    <div class="col-12 col-md-3">
        <div class="card" style="padding: 0; border-radius: 0; font-size: 14px; ">
            <ul class="list-group list-group-flush">
                <li class="list-group-item" style="font-size: 14px; padding: 8px 12px;">Aktionen</li>
                <li class="list-group-item" style="font-size: 14px; padding: 8px 12px;">
                </li>
                <li class="list-group-item d-flex justify-content-end" style="background: #f5f5f5; font-size: 14px; padding: 8px 12px;"">
                        <a href="/downloads/export-visitors.csv" target="_blank" class="btn btn-primary btn-sm" style="background: #0071a1;">exportieren</a>
                </li>
            </ul>
        </div>
    </div>
</div>

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
                }
            ]
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