<?php
// To read the air temp after x minutes. Define an cronjob for this for example every 5 minutes.
include '../db.php';

if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET lastemp = '1' WHERE id = '1'")) {
    if (! $insert_stmt->execute()) {
        exit();
    }
}
