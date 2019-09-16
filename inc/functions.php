<?php

// application functions

function get_entries() 
{
    include 'connection.php';

    try {
        return $db->query('SELECT  id, title, date FROM entries');
    } catch (Exception $e) {
        echo 'ERROR!: ' . $e->getMessage() . ' 😕 </ br>';
        return [];
    }
}