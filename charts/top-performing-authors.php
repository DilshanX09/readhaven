<div class="col-md-3 mb-3">

     <div class="normal-border-gray p-4 rounded-3 h-100">
          <h5 style="font-size: 17px; line-height: 15px;">Top 10 Performing Authors</h5>
          <p style="font-size: 13px; line-height: 15px;">
               This chart shows the total sales of books by that author.
          </p>
          <canvas id="polarAreaChart" class="mt-4"></canvas>
     </div>

</div>

<?php


$result = Database::search("
     SELECT a.author_name AS author, COUNT(i.invoice_id) AS total_sales
     FROM books b
     INNER JOIN author_name a ON b.author_name_id = a.id
     INNER JOIN invoice i ON i.book_id = b.book_id
     GROUP BY a.id
     ORDER BY total_sales DESC
     LIMIT 10
     ");

$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
     $labels[] = $row['author'];
     $data[] = (int)$row['total_sales'];
}

?>


<script>
     const polar = document.getElementById('polarAreaChart').getContext('2d');

     const polarAreaChart = new Chart(polar, {
          type: 'polarArea',
          data: {
               // labels: <?= json_encode($labels); ?>,
               datasets: [{
                    label: 'Top 5 Authors by Sales',
                    data: <?= json_encode($data); ?>,
                    backgroundColor: [
                         'rgba(255, 99, 132, 0.7)',
                         'rgba(54, 162, 235, 0.7)',
                         'rgba(255, 206, 86, 0.7)',
                         'rgba(75, 192, 192, 0.7)',
                         'rgba(153, 102, 255, 0.7)',
                         'rgba(255, 159, 64, 0.7)',
                         'rgba(199, 199, 199, 0.7)',
                         'rgba(83, 102, 255, 0.7)',
                         'rgba(255, 102, 255, 0.7)',
                         'rgba(102, 255, 204, 0.7)'
                    ],
                    borderColor: '#fff',
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
                                   family: 'Inter 18pt',
                                   size: 12
                              }
                         }
                    }
               }
          }
     });
</script>