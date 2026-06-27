<div class="col-md-3 mb-3">

     <div class="normal-border-gray p-4 rounded-3 h-100">
          <h5 style="font-size: 17px; line-height: 15px;">Overall Rating Distribution</h5>
          <p style="font-size: 13px; line-height: 15px;">
               This chart shows the snapshot of customer satisfaction
          </p>
          <canvas id="myDoughnutChart" class="mt-4"></canvas>
     </div>

</div>

<?php

$ratingCounts = [0, 0, 0, 0, 0];

$result = Database::search("SELECT rating FROM feedback");

while ($row = $result->fetch_assoc()) {

     $rating = intval($row['rating']);

     if ($rating >= 1 && $rating <= 5) {
          $ratingCounts[$rating - 1]++;
     }
}

?>

<script>
     const ctx = document.getElementById('myDoughnutChart').getContext('2d');

     const myDoughnutChart = new Chart(ctx, {
          type: 'doughnut',
          data: {
               labels: ['1 Stars', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
               datasets: [{
                    label: 'Votes',
                    data: <?= json_encode($ratingCounts); ?>, // values
                    backgroundColor: [
                         'rgba(255, 99, 132, 0.7)',
                         'rgba(54, 162, 235, 0.7)',
                         'rgba(255, 206, 86, 0.7)',
                         'rgba(75, 192, 192, 0.7)',
                         'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                         'rgba(255, 99, 132, 1)',
                         'rgba(54, 162, 235, 1)',
                         'rgba(255, 206, 86, 1)',
                         'rgba(75, 192, 192, 0.7)',
                         'rgba(153, 102, 255, 0.7)'
                    ],
                    borderWidth: 1
               }]
          },
          options: {
               responsive: true,
               plugins: {
                    legend: {
                         position: 'bottom',
                         labels: {
                              font: {
                                   family: "Inter 18pt"
                              }
                         }
                    }
               }
          }
     });
</script>