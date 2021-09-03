<!doctype html>
<link rel="stylesheet" href="style.css">
<?php

include __DIR__ . '/../private/Request.php';

$request = new Request();

$books = $request->get("wp/v2/book");
?>
<main>
<?php

include __DIR__ . '/../private/book_submit_template.php';
if (is_array($books)) {

    foreach ($books as $book) {
        include __DIR__ . '/../private/book_template.php';
    }
}
?>
</main>

<script src="request.js"></script>
<script>
    document.getElementById("book_sumbit").addEventListener('submit', function(event) {
        event.preventDefault();
        request.post(this, function(response, form) {
            form.querySelector('#book_title').value = '';
            form.querySelector('#book_content').value = '';
        });
    });
</script>




