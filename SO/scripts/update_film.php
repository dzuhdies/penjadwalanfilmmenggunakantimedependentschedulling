<?php
include '../includes/db.php';

$id = $_POST['id'];
$rating = $_POST['rating'];
$ticket_sales = $_POST['ticket_sales'];
$social_interactions = $_POST['social_interactions'];

// Query untuk memperbarui data film
$update_query = "UPDATE films 
                 SET rating=$rating, ticket_sales=$ticket_sales, social_interactions=$social_interactions 
                 WHERE id=$id";

$result = pg_query($conn, $update_query);

if ($result) {
    header('Location: ../pages/update.php?success=1');
} else {
    echo "Error updating film: " . pg_last_error($conn);
}

// Tutup koneksi
pg_close($conn);
?>
