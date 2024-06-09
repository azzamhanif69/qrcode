<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);

  if ($data) {
    $name = $data['name'];
    $address = $data['address'];
    $photo = $data['photo'];

    // Simpan data ke dalam file atau database
    // ...

    echo json_encode(['status' => 'success']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
