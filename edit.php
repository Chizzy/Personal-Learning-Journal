<?php
require 'inc/functions.php';

$pageTitle = 'Edit Entry | My Journal';
$page = 'edit';
$title = $date = $timeSpent = $learned = $resources = '';

if (isset($_GET['entry'])) {
    list($id, $title, $date, $timeSpent, $learned, $resources) = get_entry(filter_input(INPUT_GET, 'entry', FILTER_SANITIZE_NUMBER_INT));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
    $date = trim(filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING));
    $timeSpent = trim(filter_input(INPUT_POST, 'timeSpent', FILTER_SANITIZE_STRING));
    $learned = trim(filter_input(INPUT_POST, 'whatILearned', FILTER_SANITIZE_STRING));
    $resources = trim(filter_input(INPUT_POST, 'resourcesToRemember', FILTER_SANITIZE_STRING));

    $timeMatch = explode(' ', $timeSpent);

    if (empty($title) || empty($date) || empty($timeSpent) || empty($learned)) {
        $error_message = 'Please fill in the required fields: Title, Date, Time Spent, What I Learned';
    } elseif (count($timeMatch) != 2 
                    || is_numeric($timeMatch[0]) == false 
                    || $timeMatch[1] != ('hr(s)' || 'min(s)')) {
        $error_message = 'Invalid format for time spent';
    } else {
        if (edit_entry($id, $title, $date, $timeSpent, $learned, $resources)) {
            header("location: detail.php?entry=$id");
            exit;
        } else {
            $error_message = 'Could not update entry';
        }
    }
}

include 'inc/header.php';
?>

<style>
    .message {
        background-color: #ed5a5a;
        border-radius: 4px;
        padding: 1.5rem;
        color: #fff;
        text-align: left;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        -ms-border-radius: 4px;
        -o-border-radius: 4px;
    }
    .required {
        color: #ed5a5a; 
    }
</style>

<div class="edit-entry">
    <h2>Edit Entry</h2>
    <?php
    if (isset($error_message)) {
        echo '<p class="message">' . $error_message . '</p>';
    }
    ?>
    <form method="POST" action="edit.php">
        <label for="title">Title<span class="required">*</span></label>
        <input id="title" type="text" name="title" value="<?php echo htmlspecialchars($title); ?>"><br>
        <label for="date">Date<span class="required">*</span></label>
        <input id="date" type="date" name="date" value="<?php echo htmlspecialchars($date); ?>"><br>
        <label for="time-spent">Time Spent<span class="required">*</span></label>
        <input id="time-spent" type="text" name="timeSpent" value="<?php echo htmlspecialchars($timeSpent); ?>"><br>
        <label for="what-i-learned">What I Learned<span class="required">*</span></label>
        <textarea id="what-i-learned" rows="5" name="whatILearned"><?php echo htmlspecialchars($learned); ?></textarea>
        <label for="resources-to-remember">Resources to Remember</label>
        <textarea id="resources-to-remember" rows="5" name="resourcesToRemember"><?php echo htmlspecialchars($resources); ?></textarea>
        <?php
        if (!empty($id)) {
            echo '<input type="hidden" name="id" value="' . $id . '">';
        }
        ?>
        <input type="submit" value="Update Entry" class="button">
        <a href="detail.php?entry=<?php echo $id; ?>" class="button button-secondary">Cancel</a>
    </form>
</div>

<?php include 'inc/footer.php' ?>