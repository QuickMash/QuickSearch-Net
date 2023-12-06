<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="general.css">
    <style>
        /* Your styles here */
        img {
            max-width: <?php echo isset($_GET['imgWidth']) ? (int)$_GET['imgWidth'] : 100; ?>px;
            max-height: <?php echo isset($_GET['imgHeight']) ? (int)$_GET['imgHeight'] : 100; ?>px;
        }
        hr {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<header>
    <p>QuickSearch</p>
</header>

<?php
if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $startIndex = isset($_GET['start']) ? (int)$_GET['start'] : 1;

    echo "<h2>Search Result:</h2>";
    echo "<p>You are redirected to: <a href='$query' target='_blank'>$query</a></p>";

    // Add more HTML content or processing as needed

    // Debug: Output the raw JSON response
    $apiKey = 'no6t7a7real9api8key-ad7d8yours8here8'; // Replace with your actual API key
    $cx = 'e6xam9p7le'; // Replace with your actual Custom Search Engine ID
    $url = "https://www.googleapis.com/customsearch/v1?q=" . urlencode($query) . "&key=$apiKey&cx=$cx&start=$startIndex";
    
    $response = file_get_contents($url);

    // Debug: Output error information if fetching fails
    if ($response === FALSE) {
        echo '<p>Error fetching search results. Please try again later.</p>';
        echo '<p>Error details: ' . error_get_last()['message'] . '</p>';
    } else {
        $data = json_decode($response);

        // Debug: Output the raw JSON response
        // echo '<pre>';
        // echo json_encode($data, JSON_PRETTY_PRINT);
        // echo '</pre>';

        if (isset($data->items) && !empty($data->items)) {
            echo '<h2>Search Results:</h2>';
            foreach ($data->items as $item) {
                echo "<p><a href='{$item->link}' target='_blank'>{$item->title}</a></p>";
                echo "<p>{$item->snippet}</p>";

                // Check if 'pagemap' and 'cse_thumbnail' are present
                if (isset($item->pagemap->cse_thumbnail[0]->src)) {
                    $imageSrc = $item->pagemap->cse_thumbnail[0]->src;
                    echo "<img src='$imageSrc' alt='Image'>";
                }

                echo '<hr>';
            }

            // Show More Pages
            if (isset($data->queries->nextPage) && !empty($data->queries->nextPage)) {
                $nextPageLink = "result.php?query=$query&imgWidth=" . (isset($_GET['imgWidth']) ? (int)$_GET['imgWidth'] : 30) . "&imgHeight=" . (isset($_GET['imgHeight']) ? (int)$_GET['imgHeight'] : 30) . "&start=" . $data->queries->nextPage[0]->startIndex;
                echo "<p><a href='$nextPageLink'>Show More</a></p>";
            }
        } else {
            echo '<p>No results found. Try again.</p>';
        }
    }
} else {
    echo '<p>No result URL specified.</p>';
}
?>
<footer>
    <p>QuickMash Studios | QuickSearch, A <span class="pop">QUICK</span> Search engine.</p>
    <p xmlns:cc="http://creativecommons.org/ns#" xmlns:dct="http://purl.org/dc/terms/"><a property="dct:title" rel="cc:attributionURL" href="https://github.com/QuickMash/QSNET.1">QuickSearch</a> by <a rel="cc:attributionURL dct:creator" property="cc:attributionName" href="https://youtube.com/channels/@QUICKMASH">Tanner Curr</a> is licensed under <a href="http://creativecommons.org/licenses/by-sa/4.0/?ref=chooser-v1" target="_blank" rel="license noopener noreferrer" style="display:inline-block;">CC BY-SA 4.0<img style="height:22px!important;margin-left:3px;vertical-align:text-bottom;" src="https://mirrors.creativecommons.org/presskit/icons/cc.svg?ref=chooser-v1"><img style="height:22px!important;margin-left:3px;vertical-align:text-bottom;" src="https://mirrors.creativecommons.org/presskit/icons/by.svg?ref=chooser-v1"><img style="height:22px!important;margin-left:3px;vertical-align:text-bottom;" src="https://mirrors.creativecommons.org/presskit/icons/sa.svg?ref=chooser-v1"></a></p>
</footer>
</body>
</html>
