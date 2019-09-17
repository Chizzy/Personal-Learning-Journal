<?php

// application functions

function get_all_entries() 
{
    include 'connection.php';

    try {
        return $db->query('SELECT  id, title, date FROM entries ORDER BY date DESC');
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
        $results->bindValue(4, $learned, PDO::PARAM_LOB);
        $results->bindValue(5, $resources, PDO::PARAM_LOB);
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

    $sql = 'SELECT id, title, date, time_spent, learned, resources FROM entries WHERE id = ?';

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
        $results->bindValue(4, $learned, PDO::PARAM_LOB);
        $results->bindValue(5, $resources, PDO::PARAM_LOB);
        $results->bindValue(6, $id, PDO::PARAM_INT);
        $results->execute();
    } catch (Exception $e) {
        echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
        return false;
    }
    return true;
}