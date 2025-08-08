<?php
include "connection.php";

if (isset($_POST['id'])) {
  $id = intval($_POST['id']);
  $stmt = $conn->prepare("DELETE FROM entries WHERE id = ?");
  $stmt->bind_param("i", $id);
  $success = $stmt->execute();

  echo json_encode(['success' => $success]);
}
?>
