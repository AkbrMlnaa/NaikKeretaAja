<?php
function loginAdmin($username, $password) {
  global $conn;
  $result = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");
  if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
    if ($user['password'] === $password) {
      return $user;
    }
  }
  return false;
}
?>
