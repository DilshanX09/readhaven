<div class="carousel-wrapper">

     <div class="carousel-container">
          <div class="carousel-track">
               <div class="carousel-row" id="row1"></div>
               <div class="carousel-row" id="row1-duplicate"></div>
          </div>
     </div>

     <div class="carousel-container">
          <div class="carousel-track reverse">
               <div class="carousel-row" id="row2"></div>
               <div class="carousel-row" id="row2-duplicate"></div>
          </div>
     </div>

     <div class="carousel-container">
          <div class="carousel-track">
               <div class="carousel-row" id="row3"></div>
               <div class="carousel-row" id="row3-duplicate"></div>
          </div>
     </div>
</div>

<?php



$books = Database::search("SELECT * FROM books INNER JOIN book_img ON books.book_id = book_img.book_id INNER JOIN author_name ON books.author_name_id = author_name.id WHERE books.active_active_id = '1' ORDER BY books.datetime_added DESC LIMIT 50");
$booksArray = [];

while ($book_data = $books->fetch_assoc()) {

     $maxLength = 50;
     $title = $book_data['title'];
     $shortenedTitle = (strlen($title) > $maxLength) ? substr($title, 0, $maxLength) . '...' : $title;

     $author = isset($book_data['author_name']) ? $book_data['author_name'] : 'Unknown Author';
     $maxAuthorLength = 50;
     $shortenedAuthor = (strlen($author) > $maxAuthorLength) ? substr($author, 0, $maxAuthorLength) . '...' : $author;

     $stringPattern = new StringExploder();
     $stringPattern->explode($book_data['title'], " ");
     $stringPattern->implode("-");
     $formattedName = $stringPattern->getSplitedText();

     $booksArray[] = [
          'id' => $book_data['book_id'],
          'title' => $shortenedTitle,
          'fullTitle' => $title,
          'author' => $shortenedAuthor,
          'image' => $book_data['img_path'],
          'formattedName' => $formattedName
     ];
}

$booksJSON = json_encode($booksArray);

?>

<script>
     const books = <?= $booksJSON; ?>;

     const displayBooks = books.length > 0 ? books : [];

     function createBookCard(book) {

          const card = document.createElement('div');
          card.className = 'book-card';

          if (book.image && book.image !== '') {
               card.innerHTML = `
                <img src="${book.image}" alt="${book.title}" class="book-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <svg class="book-cover" style="display:none;" viewBox="0 0 200 280" xmlns="http://www.w3.org/2000/svg">
                    <rect width="200" height="280" fill="${book.color || '#4a90e2'}"/>
                    <text x="100" y="140" text-anchor="middle" fill="white" font-size="16" font-weight="bold" font-family="Arial">
                        ${book.title}
                    </text>
                    <text x="100" y="170" text-anchor="middle" fill="rgba(255,255,255,0.8)" font-size="12" font-family="Arial">
                        ${book.author}
                    </text>
                </svg>
                <div class="book-overlay">
                    <div class="book-title">${book.fullTitle || book.title}</div>
                    <div class="book-author">${book.author}</div>
                </div>
            `;
          }

          if (book.id) {
               card.style.cursor = 'pointer';
               card.addEventListener('click', function() {
                    window.location.href = `book.php?bn=${book.formattedName}&id=${book.id}`;
               });
          }

          return card;
     }

     function populateRow(rowId, startIndex) {
          const row = document.getElementById(rowId);
          const duplicateRow = document.getElementById(rowId + '-duplicate');

          const booksPerRow = 10;

          for (let i = 0; i < booksPerRow; i++) {
               const bookIndex = (startIndex + i) % displayBooks.length;
               row.appendChild(createBookCard(displayBooks[bookIndex]));
               duplicateRow.appendChild(createBookCard(displayBooks[bookIndex]));
          }
     }

     populateRow('row1', 0);
     populateRow('row2', Math.floor(displayBooks.length / 3));
     populateRow('row3', Math.floor(displayBooks.length * 2 / 3));
</script>