<?php

// application functions

function get_all_entries() 
{
    include 'connection.php';

    $sql = "SELECT  entries.id, entries.title, entries.date, GROUP_CONCAT(tags.name, ' ') AS name FROM entries
        LEFT OUTER JOIN the_link ON entries.id = the_link.entry_id 
        LEFT OUTER JOIN tags ON the_link.tags_id = tags.id
        GROUP BY entries.id ORDER BY date DESC";

    try {
        return $db->query($sql);
    } catch (Exception $e) {
        echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
        return [];
    }
}

function add_entry($title, $date, $timeSpent, $learned, $resources = null)
{
    include 'connection.php';

    $sql = 'INSERT INTO entries (title, date, time_spent, learned, resources) VALUES (?, ?, ?, ?, ?)';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $title, PDO::PARAM_STR);
        $results->bindValue(2, $date, PDO::PARAM_STR);
        $results->bindValue(3, $timeSpent, PDO::PARAM_STR);
        $results->bindValue(4, $learned, PDO::PARAM_STR);
        $results->bindValue(5, $resources, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
        return false;
    }
    return true;
}

function get_entry($id)
{
    include 'connection.php';

    $sql = "SELECT entries.id, entries.title, entries.date, entries.time_spent, entries.learned, entries.resources, GROUP_CONCAT(tags.name, ' ') AS name
        FROM entries 
        LEFT OUTER JOIN the_link ON entries.id = the_link.entry_id 
        LEFT OUTER JOIN tags ON the_link.tags_id = tags.id
        WHERE entries.id = ?
        GROUP BY entries.id ORDER BY date DESC";

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $id, PDO::PARAM_INT);
        $results->execute();
    } catch (Exception $e) {
        echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
        return false;
    }
    return $results->fetch();
}

function edit_entry($id, $title, $date, $timeSpent, $learned, $resources = null)
{
    include 'connection.php';

    $sql = 'UPDATE entries SET title = ?, date = ?, time_spent = ?, learned = ?, resources = ? WHERE id = ?';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $title, PDO::PARAM_STR);
        $results->bindValue(2, $date, PDO::PARAM_STR);
        $results->bindValue(3, $timeSpent, PDO::PARAM_STR);
        $results->bindValue(4, $learned, PDO::PARAM_STR);
        $results->bindValue(5, $resources, PDO::PARAM_STR);
        $results->bindValue(6, $id, PDO::PARAM_INT);
        $results->execute();
    } catch (Exception $e) {
        echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
        return false;
    }
    return true;
}

function delete_entry($id) {
    include 'connection.php';

    $sql = 'DELETE FROM entries WHERE id = ?';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $id, PDO::PARAM_INT);
        $results->execute();
    } catch (Exception $e) {
        echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
        return false;
    }
    return true;
}

function get_tags($name) 
{
    include 'connection.php';

    $sql = 'SELECT entries.id, entries.title, entries.date, tags.name
        FROM entries 
        LEFT OUTER JOIN the_link ON entries.id = the_link.entry_id 
        LEFT OUTER JOIN tags ON the_link.tags_id = tags.id
        WHERE tags.name = ?
        ORDER BY date DESC';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $name, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
        return false;
    }
    return $results->fetchAll(PDO::FETCH_ASSOC);
}