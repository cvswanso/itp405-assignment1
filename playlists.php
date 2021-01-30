<?php 

require('./vendor/autoload.php');

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$pdo = new PDO($_ENV['PDO_CONNECTION_STRING']);

$sql = "SELECT *
FROM playlists;";

$statement = $pdo->prepare($sql);
$statement->execute();
$playlists = $statement->fetchAll(PDO::FETCH_OBJ);

?>

<?php foreach ($playlists as $playlist) : ?>
    <?php echo '<a href="tracks.php?playlist=' . $playlist->id . '">' . $playlist->name . '</a>'; ?>
    <br>
<?php endforeach; ?>
  