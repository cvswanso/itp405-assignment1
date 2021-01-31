<?php 

if (!isset($_GET["playlist"]) || empty($_GET["playlist"])) {
	header("Location: playlists.php");
}

require('./vendor/autoload.php');

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$pdo = new PDO($_ENV['PDO_CONNECTION_STRING']);

$sql = "SELECT tracks.name, artists.name AS artist, albums.title AS album, composer, unit_price AS price, 
genres.name AS genre
FROM playlist_track
INNER JOIN tracks
ON tracks.id = playlist_track.track_id
INNER JOIN albums
ON tracks.album_id = albums.id
INNER JOIN artists
ON albums.artist_id = artists.id
INNER JOIN genres
ON tracks.genre_id = genres.id  
WHERE playlist_id = " . $_GET["playlist"] . ";";

$statement = $pdo->prepare($sql);
$statement->execute();
$tracks = $statement->fetchAll(PDO::FETCH_OBJ);

if ($statement->rowCount() == 0) {
    $sql = "SELECT name
    FROM playlists
    WHERE id = " . $_GET["playlist"] . ";";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $playlists = $statement->fetchAll(PDO::FETCH_OBJ);
    foreach ($playlists as $playlist) {
        echo "No tracks found for " . $playlist->name;
        die();
    }
}

?>


<table>
  <thead>
    <tr>
      <th>Track Name</th>
      <th>Album Title</th>
      <th>Artist Name</th>
      <th>Price</th>
      <th>Genre Name</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($tracks as $track) : ?>
    <tr>
      <td>
        <?php echo $track->name; ?>
      </td>
      <td>
        <?php echo $track->album; ?>
      </td>
      <td>
        <?php echo $track->composer; ?>
      </td>
      <td>
        <?php echo $track->price; ?>
      </td>
      <td>
        <?php echo $track->genre; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>