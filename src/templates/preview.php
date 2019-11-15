<div style="border: 1px solid blue">
    <img height="100" width="100" src="<?= $tag['og_image'] ? $tag['og_image'] : $tag['image'] ?>"/>
    <h1><?= $tag['og_title'] ? $tag['og_title'] : $tag['title'] ?></h1>
    <p><?= $tag['og_description'] ? $tag['og_description'] : $tag['description'] ?></p>
    <a href="<?= $tag['og_url'] ?>">Visit page</a>
</div>
