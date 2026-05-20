<?php
$apiKey = '1c30b32530e34ac2bf32e3ae6df159291e9ba20d';
$items = [];

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
	$search = $_GET['search'];
	$ch = curl_init();

	curl_setopt_array($ch, [
			CURLOPT_URL => "https://google.serper.dev/search",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode([
					'q' => $search,
					'gl' => 'ua',
					'hl' => 'uk'
			]),
			CURLOPT_HTTPHEADER => [
					"X-API-KEY: " . $apiKey,
					"Content-Type: application/json"
			],
			CURLOPT_SSL_VERIFYPEER => false
	]);

	$response = curl_exec($ch);
	$err = curl_error($ch);
	curl_close($ch);

	if (!$err) {
		$data = json_decode($response, true);
		if (isset($data['organic'])) {
			$items = $data['organic'];
		}
	}
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
	<meta charset="UTF-8">
	<title>Пошук Serper API</title>
	<style>
        body { font-family: sans-serif; max-width: 800px; margin: 20px auto; padding: 0 20px; }
        .result { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .result h3 { margin-bottom: 5px; }
        .result a { color: #1a0dab; text-decoration: none; }
        .result p { color: #4d5156; font-size: 14px; }
	</style>
</head>
<body>

<form action="" method="GET">
	<input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Введіть запит...">
	<button type="submit">Шукати</button>
</form>

<hr>

<?php
if (!empty($items)) {
	foreach ($items as $item) {
		echo "<div class='result'>";
		echo "<h3><a href='" . htmlspecialchars($item['link']) . "' target='_blank'>" . htmlspecialchars($item['title']) . "</a></h3>";
		echo "<p>" . htmlspecialchars($item['snippet'] ?? '') . "</p>";
		echo "</div>";
	}
} elseif (isset($_GET['search'])) {
	echo "<p>Результатів не знайдено.</p>";
}
?>

</body>
</html>