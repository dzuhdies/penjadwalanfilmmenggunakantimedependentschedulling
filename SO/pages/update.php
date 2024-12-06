<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold text-center mb-4">Update Data Film</h2>
    <table class="table-auto w-full bg-white rounded shadow-md">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th class="px-4 py-2">Judul Film</th>
                <th class="px-4 py-2">Rating</th>
                <th class="px-4 py-2">Penjualan Tiket</th>
                <th class="px-4 py-2">Interaksi Sosial</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include '../includes/db.php';
            $result = pg_query($conn, "SELECT * FROM films");
            
            if (!$result) {
                echo "<tr><td colspan='5' class='text-center py-4'>Error fetching data: " . pg_last_error($conn) . "</td></tr>";
            } else {
                while ($film = pg_fetch_assoc($result)) {
                    echo "<tr>
                            <td class='px-4 py-2'>{$film['title']}</td>
                            <td class='px-4 py-2'>{$film['rating']}</td>
                            <td class='px-4 py-2'>{$film['ticket_sales']}</td>
                            <td class='px-4 py-2'>{$film['social_interactions']}</td>
                            <td class='px-4 py-2'>
                                <a href='edit.php?id={$film['id']}' class='bg-yellow-500 text-white px-2 py-1 rounded'>Edit</a>
                            </td>
                        </tr>";
                }
            }

            pg_close($conn);
            ?>
        </tbody>
    </table>
    <a href="admin.php" class="bg-green-500 text-white px-4 py-2 rounded mt-4 inline-block">Kembali ke admin</a>
</div>
