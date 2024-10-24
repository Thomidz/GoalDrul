<?php

$api_key = '00855a3a8bef4ce1fab54f4eeba3c07f';
$fixtures_url = 'https://v3.football.api-sports.io/fixtures';

$league_id = 78; // Premier League ID
$params = [
    'league' => $league_id,
    'next' => 50 // Retrieve all upcoming matches
];

include "service/database.php";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $fixtures_url . '?' . http_build_query($params));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['x-apisports-key: ' . $api_key]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if ($response === false) {
    echo 'Error: ' . curl_error($ch);
} else {
    $data = json_decode($response, true);

    if (!empty($data['response'])) {
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Ligue1 Schedule</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="css/leaguestyle.css">
        </head>
        <body>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="assets/gd.png" class="img-fluid" alt="Logo Goaldrul"> 
                GOALDRUL 
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user"></i> Profile</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="register.php">Register</a></li>
                            <li><a class="dropdown-item" href="login.php">Login</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-star"></i> Favorite Team</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-calendar-alt"></i> Upcoming Matches</a>
                    </li>
                   
                </ul>
            </div>
        </div>
    </nav>

        <div class="container mt-4">
            <h1>Upcoming Ligue1 Matches</h1>

            <?php
            // Track the current day to group matches
            $current_day = '';

            foreach ($data['response'] as $match) {
                $home_team = $match['teams']['home']['name'];
                $away_team = $match['teams']['away']['name'];
                $home_score = $match['goals']['home'];
                $away_score = $match['goals']['away'];
                $home_logo = $match['teams']['home']['logo']; // Home team logo
                $away_logo = $match['teams']['away']['logo']; // Away team logo
                $match_status = $match['fixture']['status']['long'];
                $match_date = $match['fixture']['date'];
                $formatted_date = date('d M Y, H:i', strtotime($match_date));
                $match_day = date('l, d M Y', strtotime($match_date)); // Group by this day
                $home_team_id = $match['teams']['home']['id'];
                $away_team_id = $match['teams']['away']['id'];

                // Check if we are still on the same day, if not print the new day heading
                if ($current_day !== $match_day) {
                    $current_day = $match_day;

                    // Print a separator line for different days
                    echo "<hr style='border-top: 2px solid #000;'>";
                    echo "<h4 class='mt-4 mb-4 text-center'>$current_day</h4>"; // New day heading centered
                }

                // Output match details with logos and aligned score
                echo "<div class='row mb-3 match-info align-items-center'>"; // Centered match info

                // Home team logo and name
                echo "    <div class='col-md-5 d-flex justify-content-end align-items-center'>";
                echo "        <img src='$home_logo' alt='$home_team Logo' class='img-fluid' style='width: 30px; height: 30px; margin-right: 10px;'>";
                echo "        <a href='team_info.php?team_id=$home_team_id'>$home_team</a>";
                echo "    </div>";

                // Score in the center, separated by a dash
                echo "    <div class='col-md-2 text-center'>";
                echo "        <h3>$home_score - $away_score</h3>"; // Score displayed in the center
                echo "    </div>";

                // Away team logo and name
                echo "    <div class='col-md-5 d-flex justify-content-start align-items-center'>";
                echo "        <a href='team_info.php?team_id=$away_team_id'>$away_team</a>";
                echo "        <img src='$away_logo' alt='$away_team Logo' class='img-fluid' style='width: 30px; height: 30px; margin-left: 10px;'>";
                echo "    </div>";

                echo "</div>";

                // Match details (status, date)
                echo "<div class='match-details text-center'>";
                echo "    <p>Status: $match_status</p>"; // Status below score
                echo "    <p>Date: $formatted_date</p>"; // Date below status
                echo "</div>";
            }
            ?>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>

        <?php
    } else {
        echo "There are no upcoming matches.";
    }
}

curl_close($ch);
?>
