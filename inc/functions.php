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

function get_tags() 
{
    include 'connection.php';

    try {
        $tagsArray = [];
        $tags = $db->query('SELECT name FROM tags')->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tags as $tag) {
            $tagsArray[] = $tag['name'];
        }
        return $tagsArray;
    } catch (Exception $e) {
        echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
        return [];
    }
}

function add_entry($title, $date, $timeSpent, $learned, $resources = null, $tags = null)
{
    include 'connection.php';

    try {
        $db->beginTransaction();

        $sql = 'INSERT INTO entries (title, date, time_spent, learned, resources) VALUES (?, ?, ?, ?, ?)';
        $results = $db->prepare($sql);
        $results->bindValue(1, $title, PDO::PARAM_STR);
        $results->bindValue(2, $date, PDO::PARAM_STR);
        $results->bindValue(3, $timeSpent, PDO::PARAM_STR);
        $results->bindValue(4, $learned, PDO::PARAM_STR);
        $results->bindValue(5, $resources, PDO::PARAM_STR);
        $results->execute();
        $entry_id = $db->lastInsertId();

        if (isset($tags) && (in_array($tags, get_tags())) == false) {
            $sql = 'INSERT INTO tags (name) VALUES (?)';
            $results = $db->prepare($sql);
            $results->bindValue(1, $tags, PDO::PARAM_STR);
            $results->execute();
            $tags_id = $db->lastInsertId();
        } elseif (isset($tags)) {
            $sql = 'SELECT id FROM tags WHERE name = ?';
            $results = $db->prepare($sql);
            $results->bindValue(1, $tags, PDO::PARAM_STR);
            $results->execute();
            $tags_id = $results->fetch();
            $tags_id = $tags_id[0];
        }

        $sql = "INSERT INTO the_link (entry_id, tags_id) VALUES ($entry_id, $tags_id)";
        $db->query($sql);

        $db->commit();

    } catch (Exception $e) {
        echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
        $db->rollBack();
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

function edit_entry($id, $title, $date, $timeSpent, $learned, $resources = null, $tags = null)
{
    include 'connection.php';

    try {
        $db->beginTransaction();

        $sql = 'UPDATE entries SET title = ?, date = ?, time_spent = ?, learned = ?, resources = ? WHERE id = ?';
        $results = $db->prepare($sql);
        $results->bindValue(1, $title, PDO::PARAM_STR);
        $results->bindValue(2, $date, PDO::PARAM_STR);
        $results->bindValue(3, $timeSpent, PDO::PARAM_STR);
        $results->bindValue(4, $learned, PDO::PARAM_STR);
        $results->bindValue(5, $resources, PDO::PARAM_STR);
        $results->bindValue(6, $id, PDO::PARAM_INT);
        $results->execute();

        if (isset($tags) && (in_array($tags, get_tags())) == false) {
            $sql = 'INSERT INTO tags (name) VALUES (?)';
            $results = $db->prepare($sql);
            $results->bindValue(1, $tags, PDO::PARAM_STR);
            $results->execute();
            $tags_id = $db->lastInsertId();
        } elseif (isset($tags)) {
            $sql = 'SELECT id FROM tags WHERE name = ?';
            $results = $db->prepare($sql);
            $results->bindValue(1, $tags, PDO::PARAM_STR);
            $results->execute();
            $tags_id = $results->fetch();
            $tags_id = $tags_id[0];
        }

        $sql = "UPDATE the_link SET tags_id = $tags_id WHERE entry_id = $id";
        $db->query($sql);

        $db->commit();

    } catch (Exception $e) {
        echo 'ERROR!: ' . $e->getMessage() . ' 😕 <br>';
        $db->rollBack();
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

function get_specific_tag($name) 
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