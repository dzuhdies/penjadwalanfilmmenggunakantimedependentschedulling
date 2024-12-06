<?php include '../includes/header.php'; ?>
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


<?php include '../includes/footer.php'; ?>
