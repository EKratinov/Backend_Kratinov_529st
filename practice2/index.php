<?php
$data = [
    "Запит 1",
    "Запит 2",
    "Запит 3",
    "Запит 4",
];

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if ($query !== '') {
    foreach ($data as $item) {
        if (mb_stripos($item, $query) !== false) {
            $results[] = $item;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Пошук інформації</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .result-item { padding: 5px 0; border-bottom: 1px solid #eee; }
    </style>
</head>
<body>

<h2>Сервіс пошуку</h2>

<form action="index.php" method="GET">
    <input type="text" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Що шукаємо?">
    <button type="submit">Знайти</button>
</form>

<hr>

<?php if ($query !== ''): ?>
    <h3>Результати пошуку за запитом: "<?= htmlspecialchars($query) ?>"</h3>

    <?php if (!empty($results)): ?>
        <?php foreach ($results as $res): ?>
            <div class="result-item"><?= $res ?></div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>На жаль, нічого не знайдено.</p>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>