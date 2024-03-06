<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<pre>Debug: Start of Script</pre>";

// Check if the 'film' query parameter is set
if (isset($_GET['film'])) {
    $movie = $_GET['film'];

    // Path to the movie directory
    $movieDir = "movies/$movie";

    // Check for the existence of required files
    if (!file_exists("$movieDir/info.txt") || !file_exists("$movieDir/overview.txt")) {
        echo "<pre>Debug: Movie data not found.</pre>";
        exit;
    }

    // Assuming files are properly formatted and exist
    $info = file("$movieDir/info.txt", FILE_IGNORE_NEW_LINES);
    $overview = file("$movieDir/overview.txt", FILE_IGNORE_NEW_LINES);
    $backgroundImage = glob("$movieDir/background.{jpg,png}", GLOB_BRACE)[0] ?? '';
	echo "<pre>Debug: Start of Script - Background Image: $backgroundImage</pre>";
    $thumbnailImage = glob("$movieDir/thumbnail.{jpg,png}", GLOB_BRACE)[0] ?? '';

    // Extract information from info.txt
    [$title, $year, $rating, $reviewCount, $trailerLink] = $info;

    // Read cast names
    $castNames = file("$movieDir/cast.txt", FILE_IGNORE_NEW_LINES);
	$castImages = [];
	foreach ($castNames as $castName) {
		// $castFileName = strtolower(str_replace(' ', '_', $castName));
		$castImage = glob("cast/{$castFileName}.{jpg,png}", GLOB_BRACE)[0] ?? '';
		$castImages[] = ['name' => $castName, 'image' => $castImage];
		
	}
	foreach ($castImages as $cast) {
    echo "<pre>Debug: Cast Image Path - " . htmlspecialchars($cast['image']) . "</pre>";
	}


	// Read watch data from CSV and their images
	$watchData = [];
	if (($handle = fopen("$movieDir/watch.csv", "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			[$brand, $url] = $data;
			$brandImage = glob("brands/{$brand}.{jpg,png}", GLOB_BRACE)[0] ?? '';
			$watchData[] = ['brand' => $brand, 'url' => $url, 'image' => $brandImage];
		}
		fclose($handle);
	}

} else {
    echo "<pre>Debug: Movie not specified.</pre>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<img src="logo.png" alt="Logo" class="logo"> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="movie.css">
	<style>
		.logo {
			z-index: 10; /* Ensure the logo is above other elements */
			height: 110px; /* Adjust the height as needed */
			width: auto; /* This will maintain the aspect ratio */
			position: absolute; /* Position it relative to the header */
			top: 10px; /* Adjust as needed */
			left: 50px; /* Adjust as needed */
			transform: translateX(0%);
		}
		.movie-details, .synopsis {
			background-color: rgba(255, 255, 255, 0.85);
			padding: 15px;
			border-radius: 10px;
			margin-bottom: 20px;
			width: 80%;
			max-width: 800px;
			color: #d1630f; /* Orange text */
		}
		.movie-details strong {
			display: inline-block; /* This makes the element respect the width */
			min-width: 180px; /* Adjust the width as needed */
			margin-right: 20px; /* This simulates the tab space */
		}

		.movie-details p {
			margin: 5px 0; /* Add some vertical space between each line */
		}


		.movie-thumbnail {
			max-width: 300px; /* Adjust the thumbnail size */
			border-radius: 10px;
			overflow: hidden;
			box-shadow: 0 4px 8px rgba(0,0,0,0.1);
		}

		.synopsis {
			justify-content: center;
			align-items: center;
			flex-direction: column; /* Stack the elements vertically */
			margin-left: auto;
			margin-right: auto;
			color: #000000; /* Orange text */
			max-width: 800px; /* or your desired width */
		}
		.back-button {
			font-size: 20px;
			padding: 15px 30px; /* More padding */
			border-radius: 25px; /* Even more rounded corners */
			background: rgba(255, 255, 255, 0.85); /* Translucent white background */
			color: #ff6347; /* Orange text */
			text-decoration: none;
			font-weight: bold;
			position: absolute;
			top: 30px; /* More space from the top */
			left: 230px; /* More space from the left */
			transition: box-shadow 0.1s ease-in-out;
			z-index: 1000; /* High z-index to bring to front */
		}

		.back-button:hover {
			box-shadow: 0 0 30px #ff6347; /* Glow effect */
			color: #ff6347; /* Keep the text orange on hover */
			text-decoration: none; /* Optional: Removes the underline on hover */
		}


	</style>

</head>
<body style="background-image: url('<?= htmlspecialchars($backgroundImage) ?>');">


	<a href="index.php" class="back-button">Back To Search</a>
    <header>
        <h1><?= htmlspecialchars($title) ?></h1>
    </header>
    <main>
        <aside class="movie-thumbnail">
            <img src="<?= htmlspecialchars($thumbnailImage) ?>" alt="<?= htmlspecialchars($title) ?> Thumbnail" class="thumbnail">
            <div class="thumbnail-box"></div>
        </aside>

		<section class="trailer">
			<iframe width="800" height="450" src="<?= htmlspecialchars($trailerLink) ?>" title="<?= htmlspecialchars($title) ?> Movie - Official Trailer" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
		</section>
		

		<section class="movie-details">

			<?php foreach ($overview as $line): ?>
				<?php
				[$key, $value] = explode(': ', $line, 2);
				if ($key !== 'SYNOPSIS') {
					echo "<p><strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($value) . "</p>";
				}
				?>
			<?php endforeach; ?>
		</section>

		<section class="synopsis">
			<?php
			foreach ($overview as $line) {
				if (str_starts_with($line, 'SYNOPSIS: ')) {
					echo "<p>" . htmlspecialchars(substr($line, 10)) . "</p>";
					break;
				}
			}
			?>
		</section>

		<section class="cast">
			<ul>
				<?php foreach ($castImages as $cast): ?>
					<li>
						<img src="<?= htmlspecialchars($cast['image']) ?>" alt="<?= htmlspecialchars($cast['name']) ?>" class="cast-img">
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
		
		<section class="watch">
			<h2>Where to Watch</h2>
			<div class="watch-icons">
				<?php foreach ($watchData as $watch): ?>
					<a href="<?= htmlspecialchars($watch['url']) ?>" target="_blank">
						<img src="<?= htmlspecialchars($watch['image']) ?>" alt="<?= htmlspecialchars($watch['brand']) ?>" class="watch-img">
					</a>
				<?php endforeach; ?>
			</div>
		</section>
    </main>
    <footer>
        <p>&copy; 2024 MoviRevs</p>
    </footer>
</body>
</html>
