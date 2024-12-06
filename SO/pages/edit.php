<?php
include '../includes/db.php';

$id = $_GET['id'];

$result = pg_query($conn, "SELECT * FROM films WHERE id=$id");
if (!$result) {
    die("Error fetching film data: " . pg_last_error($conn));
}

$film = pg_fetch_assoc($result);
?>

<form action="../scripts/update_film.php" method="POST" class="bg-white p-6 rounded shadow-md">
    <input type="hidden" name="id" value="<?php echo $film['id']; ?>">
    
    <label for="rating" class="block font-medium">Rating:</label>
    <input type="number" id="rating" name="rating" value="<?php echo $film['rating']; ?>" class="w-full border rounded p-2 mb-4" required>

    <label for="ticket_sales" class="block font-medium">Penjualan Tiket:</label>
    <input type="number" id="ticket_sales" name="ticket_sales" value="<?php echo $film['ticket_sales']; ?>" class="w-full border rounded p-2 mb-4" required>

    <label for="social_interactions" class="block font-medium">Interaksi Media Sosial:</label>
    <input type="number" id="social_interactions" name="social_interactions" value="<?php echo $film['social_interactions']; ?>" class="w-full border rounded p-2 mb-4" required>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
</form>
