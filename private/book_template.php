<div class="card">
<h2><?= $book['title']['rendered'];?> </h2>
<div class="content">
<div><?= $book['content']['rendered']?> </div>

<?php
$links = $book['_links'];

if (array_key_exists("wp:featuredmedia", $links)) {
    $media_url = $links["wp:featuredmedia"][0]['href'];
    $media = $request->get($media_url, true);
?>
    <img class='thumbnail' src='<?= $media['media_details']['sizes']['hestia-blog']['source_url']?>' >
<?php
}?>
</div>

</div>