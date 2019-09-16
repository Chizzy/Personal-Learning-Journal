<?php

require 'inc/functions.php';

include 'inc/header.php';

?>

<div class="entry-list">
    <?php
    
    foreach (get_entries() as $entry) {
        echo '<article>';
        echo '<h2><a href="detail.html">' . $entry['title'] . '</a></h2>';
        echo '<time datetime="' . $entry['date'] . '">January 31, 2016</time>';
        echo '</article>';
    }

    ?>
</div>

<?php include 'inc/footer.php'?>