<?php include '../includes/header.php'; ?>
<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold text-center mb-4">Input Data Film</h2>
    <form action="../api/save_data.php" method="POST" class="bg-white p-6 rounded shadow-md">
        <label for="title" class="block font-medium">Judul Film:</label>
        <input type="text" id="title" name="title" class="w-full border rounded p-2 mb-4" required>

        <label for="rating" class="block font-medium">Rating Film (1-10):</label>
        <input type="number" id="rating" name="rating" min="1" max="10" step="0.1" class="w-full border rounded p-2 mb-4" required>

        <label for="ticket_sales" class="block font-medium">Penjualan Tiket:</label>
        <input type="number" id="ticket_sales" name="ticket_sales" min="0" class="w-full border rounded p-2 mb-4" required>

        <label for="social_interactions" class="block font-medium">Interaksi Media Sosial:</label>
        <input type="number" id="social_interactions" name="social_interactions" min="0" class="w-full border rounded p-2 mb-4" required>

        <label for="release_date" class="block font-medium">Tanggal Rilis:</label>
        <input type="date" id="release_date" name="release_date" class="w-full border rounded p-2 mb-4" required>


        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan Data</button>
    </form>
    <a href="../scripts/schedule2.php" class="bg-green-500 text-white px-4 py-2 rounded mt-4 inline-block">Proses Jadwal</a>
    <a href="update.php" class="bg-green-500 text-white px-4 py-2 rounded mt-4 inline-block">Edit data</a>

</div>

<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold text-center mb-4">Jadwal Film</h2>
    <table class="table-auto w-full bg-white rounded shadow-md">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th class="px-4 py-2">Judul Film</th>
                <th class="px-4 py-2">Hari</th>
                <th class="px-4 py-2">Waktu Tayang</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include '../includes/db.php';

            $sql = "SELECT films.title, schedule.slot_time, schedule.day 
                    FROM schedule 
                    JOIN films ON schedule.film_id = films.id";
            $result = pg_query($conn, $sql);

            if (pg_num_rows($result) > 0) {
                while ($row = pg_fetch_assoc($result)) {
                    echo "<tr class='border-b'>
                            <td class='px-4 py-2'>{$row['title']}</td>
                            <td class='px-4 py-2'>{$row['day']}</td>
                            <td class='px-4 py-2'>{$row['slot_time']}</td>
                          </tr>";
                }
            } else {
                echo "<tr>
                        <td colspan='3' class='text-center py-4'>Belum ada jadwal film.</td>
                      </tr>";
            }

            pg_close($conn);
            ?>
        </tbody>
    </table>
</div>

<a href="index.php" class="bg-green-500 text-white px-4 py-2 rounded mt-4 inline-block">Ke dashboard</a>

<?php include '../includes/footer.php'; ?>
