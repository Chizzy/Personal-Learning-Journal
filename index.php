<?php
require 'inc/functions.php';

$pageTitle = 'My Journal';
$page = null;

include 'inc/header.php';
?>

<div class="entry-list">
    <?php
    foreach (get_all_entries() as $entry) {
        echo '<article>';
        echo '<h2><a href="detail.php?entry=' . $entry['id'] . '">' . $entry['title'] . '</a></h2>';
        echo '<time datetime="' . $entry['date'] . '">' . date_format(date_create($entry['date']), 'F j, Y') . '</time>';
        echo '</article>';
    }
    ?>
</div>

<?php include 'inc/footer.php'?>