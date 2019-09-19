<?php
require 'inc/functions.php';

$pageTitle = 'Details of Entry | My Journal';
$page = 'detail';
$title = $date = $timeSpent = $learned = $resources = $name = '';

if (isset($_GET['entry'])) {
    list($id, $title, $date, $timeSpent, $learned, $resources, $name) = get_entry(filter_input(INPUT_GET, 'entry', FILTER_SANITIZE_NUMBER_INT));
}

include 'inc/header.php';
?>

<div class="entry-list single">
    <article>
        <h1><?php echo $title; ?></h1>
        <time datetime="<?php echo $date; ?>"><?php echo date_format(date_create($date), 'F j, Y'); ?></time>
        <div class="entry">
            <h3>Time Spent: </h3>
            <p><?php echo $timeSpent; ?></p>
        </div>
        <div class="entry">
            <h3>What I Learned:</h3>
            <p><?php echo $learned; ?></p>
        </div>
        <?php
        if ($resources != null) {
            echo '<div class="entry">';
            echo '<h3>Resources to Remember:</h3>';
            echo '<ul>';
            echo "<li><a href=''>$resources</a></li>";
            echo '</ul>';
            echo '</div>';
        }
        if ($name != null) {
            $multiTagNames = explode(' ', $name);
            if (count($multiTagNames) > 1) {
                echo '<div class="entry">';
                echo '<h3>Tags:</h3>';
                foreach ($multiTagNames as $tagName) {
                    echo '<p><a href="tags.php?name=' . $tagName . '">' . $tagName . '</a></p>';
                }
                echo '</div>';
            } else {
                echo '<div class="entry">';
                echo '<h3>Tags:</h3>';
                echo '<p><a href="tags.php?name=' . $multiTagNames[0] . '">' . $multiTagNames[0] . '</a></p>';
                echo '</div>';
            }
        }
        ?>
    </article>
</div>
</div>
<div class="edit">
<p><a href="edit.php?entry=<?php echo $id; ?>">Edit Entry</a></p>

<?php include 'inc/footer.php' ?>