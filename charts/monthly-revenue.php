<?php $year = date('Y'); ?>

<div class="normal-border-gray p-4 rounded-3">

     <h4 style="font-size: 17px; line-height: 15px;">Monthly Revenue Growth ( <?= $year ?> )</h4>
     <p style="font-size: 13px; line-height: 15px;">
          Tracks revenue month by month for the year.
     </p>
     <canvas id="monthlyRevenueChart"></canvas>

</div>

<?php

$labels = [
     'January',
     'February',
     'March',
     'April',
     'May',
     'June',
     'July',
     'August',
     'September',
     'October',
     'November',
     'December'
];

$data = array_fill(0, 12, 0);

$result = Database::search("
    SELECT MONTH(date) AS month, SUM(total) AS revenue
    FROM invoice
    WHERE YEAR(date) = '$year'
    GROUP BY MONTH(date)
");

while ($row = $result->fetch_assoc()) {
     $monthIndex = intval($row['month']) - 1;
     $data[$monthIndex] = (float)$row['revenue'];
}

?>

<script>
     const line = document.getElementById('monthlyRevenueChart').getContext('2d');
     const monthlyRevenueChart = new Chart(line, {
          type: 'line',
          data: {
               labels: <?= json_encode($labels); ?>,
               datasets: [{
                    label: 'Revenue (LKR)',
                    data: <?= json_encode($data); ?>,
                    borderColor: 'rgb(255, 82, 82)',
                    backgroundColor: 'rgb(255, 198, 185)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgb(255, 82, 82)',
                    pointRadius: 5
               }]
          },
          options: {
               responsive: true,
               gridLines: false,
               scales: {
                    y: {
                         grid: {
                              display: false
                         },
                         title: {
                              display: true,
                              text: 'Revenue in LKR',
                              font: {
                                   family: 'Inter 18pt'
                              }
                         }
                    },
                    x: {
                         grid: {
                              display: false
                         },
                         title: {
                              display: true,
                              text: 'Month',
                              font: {
                                   family: 'Inter 18pt'
                              }
                         }
                    }
               }
          }
     });
</script>