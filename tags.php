<?php
require 'inc/functions.php';

$page = 'tags';

if (isset($_GET['name'])) {
    $pageTitle = ucfirst($_GET['name'])  . " Tags | My Journal";
    $tags = get_specific_tag(filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING));
}

include 'inc/header.php';
?>

<style>
    @import url('https://fonts.googleapis.com/css?family=Cousine&display=swap');
    h3 a {
        font-family: 'Cousine', monospace;
        text-decoration: none;
        color: #000;
    }
    h1 {
        padding-bottom: 3rem;
    }
</style>

<div class="entry-list single">
    <article>
    <h1>"<?php echo ucfirst($_GET['name']); ?>" Tags</h1>
    <?php
    foreach ($tags as $tag) {
        echo '<article>';
        echo '<h3><a href="detail.php?entry=' . $tag['id'] . '">' . $tag['title'] . '</a></h3>';
        echo '<time datetime="' . $tag['date'] . '">' . date_format(date_create($tag['date']), 'F j, Y') . '</time>';
        echo '</article>';
    }
    ?>
    </article>
</div>

<?php include 'inc/footer.php'; ?>