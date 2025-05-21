<?php
function loginAdmin($username, $password) {
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
    if ($password) {
      return $admin;
    }
  }

  return false;
}
?>
