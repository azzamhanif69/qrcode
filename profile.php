<?php
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $filePath = "data/$id.json";

  if (file_exists($filePath)) {
    $data = json_decode(file_get_contents($filePath), true);
  } else {
    echo "Data not found.";
    exit;
  }
} else {
  echo "No ID specified.";
  exit;
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Profile</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
  <div class="container mt-5">
    <div class="card">
      <div class="card-header bg-primary text-white text-center">
        <img src="<?php echo htmlspecialchars($data['photoUrl']); ?>" alt="Photo" class="profile-photo rounded-circle">
        <h2><?php echo htmlspecialchars($data['name']); ?></h2>
        <p><?php echo htmlspecialchars($data['contact']); ?></p>
      </div>
      <div class="card-body">
        <p><strong>Alamat:</strong> <?php echo htmlspecialchars($data['address']); ?></p>
        <p><strong>Riwayat Kesehatan:</strong> <?php echo nl2br(htmlspecialchars($data['health'])); ?></p>
        <p><strong>Alergi:</strong> <?php echo nl2br(htmlspecialchars($data['allergy'])); ?></p>
        <div id="map" style="height: 300px;"></div>
      </div>
    </div>
  </div>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    function initMap() {
      var map = L.map('map').setView([-6.200000, 106.816666], 13); // Default center on Jakarta, Indonesia

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent("<?php echo htmlspecialchars($data['address']); ?>")}`)
        .then(response => response.json())
        .then(data => {
          if (data && data.length > 0) {
            var latlng = [data[0].lat, data[0].lon];
            map.setView(latlng, 13);
            L.marker(latlng).addTo(map)
              .bindPopup('<?php echo htmlspecialchars($data['address']); ?>')
              .openPopup();
          } else {
            alert('Geocode was not successful.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Geocode was not successful.');
        });
    }

    document.addEventListener('DOMContentLoaded', initMap);
  </script>
</body>

</html>