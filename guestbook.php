<?php
// Database connection
$servername = "ftpupload.net";
$username   = "if0_40427825";   // replace with your MySQL username
$password   = "4Zxv0qGEarxw";   // replace with your MySQL password
$dbname     = "guestbook_app";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Securely insert form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = trim($_POST['name']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO guestbook_entries (name, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $message);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Guest Book</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>Guest Book</h1>
    <nav>
      <a href="index.html">Home</a> |
      <a href="guestbook.php">Guestbook</a>
    </nav>
  </header>

  <!-- Form -->
  <form method="post" class="guest-form">
    <label>Name:</label>
    <input type="text" name="name" required><br>
    
    <label>Message:</label><br>
    <textarea name="message" required></textarea><br>
    
    <input type="submit" value="Post Message">
  </form>

  <!-- Display Messages -->
  <h2>Messages</h2>
  <?php
  $result = $conn->query("SELECT * FROM guestbook_entries ORDER BY submitted_at DESC");
  while($row = $result->fetch_assoc()):
  ?>
    <div class="entry">
      <p><strong><?= htmlspecialchars($row['name']) ?></strong> wrote:</p>
      <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>
      <small>Posted on <?= $row['submitted_at'] ?></small>
      <hr>
    </div>
  <?php endwhile; ?>

  <footer>
    <p><strong>Niuskary</strong> – Public Website <span class="timezone">GMT</span></p>
  </footer>
</body>
</html>
