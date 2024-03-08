<?php
if (isset($_GET['film']) && !empty(trim($_GET['film']))) {
    $movie = strtolower(trim($_GET['film']));
    $movieDir = "movies/" . $movie;

    if (is_dir($movieDir) && file_exists($movieDir . "/info.txt")) {
        header("Location: movie.php?film=" . urlencode($movie));
        exit;
    } else {
        $notFound = true;
    }
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <img src="logo.png" alt="Logo" class="logo"> <!-- Make sure to replace path/to/your/logo.png with the actual path to your logo file -->
	
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoviRev</title>
    <link rel="stylesheet" href="movie.css">
    <style>
		.logo {
			height: 110px; /* Adjust the height as needed */
			width: auto; /* This will maintain the aspect ratio */
			position: absolute; /* Position it relative to the header */
			top: 10px; /* Adjust as needed */
			left: 50px; /* Adjust as needed */
			transform: translateX(0%);
		}

        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden; /* Prevents scrolling */
            font-family: Arial, sans-serif;
            background: transparent;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('background.png'); /* Adjust with your actual background image path */
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            z-index: -2;
        }

        header, footer {
            backdrop-filter: blur(5px);
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 20px;
            text-align: center;
            position: fixed;
            left: 0;
            right: 0;
            z-index: -1;
        }

        header {
            top: 0;
        }

        footer {
            bottom: 0;
        }

        main {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100% - 40px);
            padding-top: 40px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 1;
        }

        .search-input {
            font-size: 25px;
            padding: 15px 20px;
            margin-bottom: 20px; /* Space between input and button */
            width: 600%; /* Increase as per requirement */
            max-width: 900px; /* Adjust based on the desired width */
            border-radius: 30px;
            border: 0px solid #ff6347;
        }

        .search-button {
            font-size: 25px;
            padding: 10px 25px;
            border-radius: 30px;
            background-color: #ff6347;
            color: white;
            border: none;
            cursor: pointer;
        }

        .search-input:focus, .search-button:focus {
            outline: none;
            box-shadow: 0 0 200px rgba(255, 99, 71, 1);
        }
		.not-found {
            margin-top: 20px;
            color: #ff6347;
            font-size: 22px;
        }
    </style>
</head>
<body>
    <div class="background"></div>
    <header>
        <h1>MoviRevs</h1>
    </header>
    <main>
        <div class="search-container">
            <form action="index.php" method="get">
                <input type="text" name="film" placeholder="Search Movies..." class="search-input">
                <button type="submit" class="search-button">Search</button>
            </form>
            <?php
            if (isset($_GET['film']) && !empty(trim($_GET['film']))) {
                $movie = trim($_GET['film']);
                $movieDir = "movies/" . $movie;

                if (!is_dir($movieDir) || !file_exists($movieDir . "/info.txt")) {
                    echo "<div class='not-found'>Movie '{$movie}' was not found.</div>";
                }
            }
            ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 MoviRev</p>
    </footer>
</body>
</html>
