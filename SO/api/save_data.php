<?php
include '../includes/db.php';

$title = $_POST['title'];
$rating = $_POST['rating'];
$ticket_sales = $_POST['ticket_sales'];
$social_interactions = $_POST['social_interactions'];
$release_date = $_POST['release_date'];

// Sanitasi input untuk mencegah SQL Injection
$title = pg_escape_string($conn, $title);
$rating = floatval($rating);
$ticket_sales = intval($ticket_sales);
$social_interactions = intval($social_interactions);

$sql = "INSERT INTO films (title, rating, ticket_sales, social_interactions, release_date)
        VALUES ('$title', $rating, $ticket_sales, $social_interactions, '$release_date')";

// Menggunakan pg_query() untuk eksekusi query di PostgreSQL
$result = pg_query($conn, $sql);

if ($result) {
    header('Location: ../pages/admin.php?success=1');
} else {
    echo "Error: " . pg_last_error($conn);
}

pg_close($conn);
?>
