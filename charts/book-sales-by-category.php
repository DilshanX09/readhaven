  <div class="col-md-6 mb-3 ">

       <div class="normal-border-gray p-4 rounded-3 h-100">
            <h3 style="font-size: 17px; line-height: 15px;">Book Sales by Category</h3>
            <p style="font-size: 13px; line-height: 15px;">
                 Shows the distribution of sold books across all categories.
            </p>
            <canvas id="bookBarChart" class="mt-4"></canvas>
       </div>

  </div>

  <?php

     $result = Database::search("
          SELECT 
              book_category.category_name, 
              SUM(invoice.invoice_qty) AS book_count
          FROM 
              invoice 
          INNER JOIN books ON books.book_id = invoice.book_id
          INNER JOIN book_category ON book_category.category_id = books.book_category_id 
          GROUP BY 
              book_category.category_name;
     ");

     $labels = [];
     $data   = [];
     $max_length = 15;

     while ($row = $result->fetch_assoc()) {

          $category_name = $row['category_name'];

          if (mb_strlen($category_name) > $max_length) {
               $category_name = mb_substr($category_name, 0, $max_length) . '...';
          }

          $labels[] = $category_name;
          $data[]   = $row['book_count'];
     }

     ?>


  <script>
       const barChart = document.getElementById('bookBarChart').getContext('2d');

       const bookBarChart = new Chart(barChart, {
            type: 'bar',
            data: {
                 labels: <?= json_encode($labels); ?>,
                 datasets: [{
                      label: 'Books Sold',
                      data: <?= json_encode($data); ?>,
                      backgroundColor: [
                           'rgb(255, 198, 185)',
                      ],
                      borderWidth: 0
                 }]
            },
            options: {
                 responsive: true,
                 scales: {
                      y: {
                           grid: {
                                display: false
                           },
                           title: {
                                display: true,
                                text: 'Number of Books Sold',
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
                                text: 'Book Categories',
                                font: {
                                     family: 'Inter 18pt'
                                }
                           }
                      }
                 }
            }
       });
  </script>