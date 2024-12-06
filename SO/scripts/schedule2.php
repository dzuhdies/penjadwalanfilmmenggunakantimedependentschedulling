<?php
include '../includes/db.php';

pg_query($conn, "TRUNCATE TABLE schedule");

$sql = "SELECT * FROM films";
$result = pg_query($conn, $sql);

if (pg_num_rows($result) > 0) {
    $films = [];
    $total_popularity = 0;
    while ($row = pg_fetch_assoc($result)) {
        $days_since_release = (time() - strtotime($row['release_date'])) / (60 * 60 * 24);
        $popularity = (0.5 * $row['rating']) + 
                      (0.3 * $row['ticket_sales']) + 
                      (0.2 * $row['social_interactions']);
        $time_factor = ($days_since_release <= 30) ? 1.2 : 1 / (1 + exp($days_since_release - 30));

        $final_score = $popularity * $time_factor;
        $total_popularity += $final_score;

        $films[] = [
            'id' => $row['id'], 
            'title' => $row['title'], 
            'final_score' => $final_score,
            'ticket_sales' => $row['ticket_sales'],
            'social_interactions' => $row['social_interactions']
        ];
    }

    $valid_films = array_filter($films, function($film) {
        return $film['ticket_sales'] >= 50 || $film['social_interactions'] >= 50;
    });

    if (empty($valid_films)) {
        header('Location: ../pages/admin.php?error=no_valid_films');
        exit;
    }

    $total_popularity = array_sum(array_column($valid_films, 'final_score'));
    $average_popularity = $total_popularity / count($valid_films);

    usort($valid_films, function($a, $b) {
        return $b['final_score'] <=> $a['final_score'];
    });

    $prime_days = ['Jumat', 'Sabtu', 'Minggu'];
    $prime_slots = ['18:00 - 21:00', '16:00 - 18:00'];
    $regular_days = ['Senin', 'Selasa', 'Rabu', 'Kamis'];
    $regular_slots = ['08:00 - 12:00', '12:00 - 16:00'];

    $existing_schedule = [];

    foreach ($valid_films as $index => $film) {
        if ($film['final_score'] > $average_popularity) {
            $days = $prime_days;
            $slots = $prime_slots;
        } else {
            $days = array_merge($regular_days, $prime_days);
            $slots = array_merge($regular_slots, $prime_slots);
        }

        do {
            $day = $days[$index % count($days)];
            $slot_time = $slots[$index % count($slots)];
            $conflict = false;

            foreach ($existing_schedule as $schedule) {
                if ($schedule['day'] === $day && $schedule['slot_time'] === $slot_time) {
                    // Bandingkan skor akhir
                    if ($film['final_score'] < $schedule['final_score']) {
                        $conflict = true;
                        break;
                    } else {
                        // Hapus jadwal lama karena film ini lebih bagus
                        $delete_query = "DELETE FROM schedule WHERE film_id = {$schedule['film_id']}";
                        pg_query($conn, $delete_query);
                        $existing_schedule = array_filter($existing_schedule, function($s) use ($schedule) {
                            return $s['film_id'] !== $schedule['film_id'];
                        });
                    }
                }
            }

            if ($conflict) {
                $index++;
            } else {
                $existing_schedule[] = [
                    'film_id' => $film['id'], 
                    'day' => $day, 
                    'slot_time' => $slot_time, 
                    'final_score' => $film['final_score']
                ];
                break;
            }
        } while (true);
        $insert_query = "INSERT INTO schedule (film_id, slot_time, day) 
                         VALUES ({$film['id']}, '$slot_time', '$day')";
        $insert_result = pg_query($conn, $insert_query);

        if (!$insert_result) {
            echo "Error inserting schedule: " . pg_last_error($conn);
        }
    }

    header('Location: ../pages/admin.php?success=1');
} else {
    header('Location: ../pages/admin.php?error=no_data');
}
pg_close($conn);
?>
