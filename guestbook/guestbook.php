<?php
session_start();

function renderComments() {
	$filename = 'comments.csv';

	if (file_exists($filename) && filesize($filename) > 0) {
		$fileStream = fopen($filename, "r");

		echo '<div class="list-group">';
		while (!feof($fileStream)) {
			$jsonString = fgets($fileStream);
			$comment = json_decode($jsonString, true);

			if (empty($comment)) break;

			echo '<div class="list-group-item list-group-item-action flex-column align-items-start mb-2 shadow-sm">';
			echo '  <div class="d-flex w-100 justify-content-between">';
			echo '    <h5 class="mb-1 text-primary"><i class="fa fa-user"></i> ' . htmlspecialchars($comment['name']) . '</h5>';
			echo '    <small class="text-muted">' . htmlspecialchars($comment['email']) . '</small>';
			echo '  </div>';
			echo '  <p class="mb-1 mt-2 text-secondary">' . nl2br(htmlspecialchars($comment['text'])) . '</p>';
			echo '</div>';
		}
		echo '</div>';

		fclose($fileStream);
	} else {
		echo '<div class="alert alert-info">Відгуків ще немає. Будьте першим!</div>';
	}
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	if (isset($_POST['email'], $_POST['name'], $_POST['text'])) {

		$email = trim($_POST['email']);
		$name = trim($_POST['name']);
		$text = trim($_POST['text']);

		if (!empty($email) && !empty($name) && !empty($text)) {

			$newComment = [
					'email' => $email,
					'name'  => $name,
					'text'  => $text
			];

			$jsonString = json_encode($newComment);

			$filename = 'comments.csv';
			$fileStream = fopen($filename, 'a');

			if ($fileStream) {
				fwrite($fileStream, $jsonString . "\n");
				fclose($fileStream);
			}

			header('Location: guestbook.php');
			exit;
		}
	}
}
?>

<!DOCTYPE html>
<html>

<?php require_once 'sectionHead.php' ?>

<body>

<div class="container">

    <!-- navbar menu -->
    <?php require_once 'sectionNavbar.php' ?>
    <br>

    <!-- guestbook section -->
    <div class="card card-primary">
        <div class="card-header bg-primary text-light">
            GuestBook form
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-sm-6">
	                <form action="guestbook.php" method="POST" class="p-2">
		                <div class="mb-3">
			                <label for="email" class="form-label">Email адреса:</label>
			                <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" required>
		                </div>
		                <div class="mb-3">
			                <label for="name" class="form-label">Ваше ім'я:</label>
			                <input type="text" name="name" id="name" class="form-control" placeholder="Іван Іванов" required>
		                </div>
		                <div class="mb-3">
			                <label for="text" class="form-label">Текст відгуку:</label>
			                <textarea name="text" id="text" rows="4" class="form-control" placeholder="Залиште свій коментар тут..." required></textarea>
		                </div>
		                <div class="d-grid gap-2">
			                <button type="submit" class="btn btn-primary">
				                <i class="fa fa-paper-plane"></i> Надіслати відгук
			                </button>
		                </div>
	                </form>
                </div>
            </div>

        </div>
    </div>

    <br>

    <div class="card card-primary">
        <div class="card-header bg-body-secondary text-dark">
            Сomments
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
	                <?php
	                renderComments();
	                ?>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
