<?php
require 'inc/functions.php';

$pageTitle = 'My Journal';
$page = null;

if (isset($_POST['delete'])) {
    if (delete_entry(filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_NUMBER_INT))) {
        header('location: index.php?msg=Entry+Deleted');
        exit;
    } else {
        header('location: index.php?msg=Unable+to+Delete+Entry');
        exit;
    }
}
if (isset($_GET['msg'])) {
    $error_message = trim(filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_STRING));
}

include 'inc/header.php';
?>

<div class="entry-list">
    <?php
    if (isset($error_message)) {
        echo "<p class='message'>$error_message</p>";
    }

    foreach (get_all_entries() as $entry) {
        echo '<article>';
        echo '<h2><a href="detail.php?entry=' . $entry['id'] . '">' . $entry['title'] . '</a></h2>';
        echo '<time datetime="' . $entry['date'] . '">' . date_format(date_create($entry['date']), 'F j, Y') . '</time>';
        echo '<form method="POST" action="index.php" onsubmit="return confirm("Are you sure you want to delete this entry?"); ">';
        echo '<input type="hidden" name="delete" value="' . $entry['id'] . '">';
        echo '<input type="submit" class="button--delete" value="Delete">';
        echo '</form>';
        echo '</article>';
    }
    ?>
</div>

<?php include 'inc/footer.php'?>