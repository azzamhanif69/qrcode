<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    $dataDir = 'data/';

    // Buat direktori jika belum ada
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }
    if (!is_dir($dataDir)) {
      mkdir($dataDir, 0777, true);
    }

    $fileTmpPath = $_FILES['photo']['tmp_name'];
    $fileName = basename($_FILES['photo']['name']);
    $destPath = $uploadDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
      $name = $_POST['name'];
      $contact = $_POST['contact'];
      $address = $_POST['address'];
      $health = $_POST['health'];
      $allergy = $_POST['allergy'];

      $id = uniqid();
      $data = [
        'name' => $name,
        'contact' => $contact,
        'address' => $address,
        'health' => $health,
        'allergy' => $allergy,
        'photoUrl' => $destPath
      ];

      file_put_contents("$dataDir/$id.json", json_encode($data));

      $response = [
        'status' => 'success',
        'id' => $id
      ];
      echo json_encode($response);
    } else {
      $response = [
        'status' => 'error',
        'message' => 'Failed to move uploaded file.'
      ];
      echo json_encode($response);
    }
  } else {
    $response = [
      'status' => 'error',
      'message' => 'No file uploaded or upload error.'
    ];
    echo json_encode($response);
  }
} else {
  $response = [
    'status' => 'error',
    'message' => 'Invalid request method.'
  ];
  echo json_encode($response);
}
