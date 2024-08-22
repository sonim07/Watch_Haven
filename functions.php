<?php
// Function to get user details
function getUserDetails($conn, $username) {
    $sql = "SELECT * FROM users WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
    }
    return null;
}

// Function to update user profile
function updateUserProfile($conn, $username, $name, $email, $phone) {
    $sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $name, $email, $phone, $username);
        return $stmt->execute();
    }
    return false;
}

// Function to validate current password
function validatePassword($conn, $username, $password) {
    $sql = "SELECT password FROM users WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return password_verify($password, $row['password']);
            }
        }
    }
    return false;
}

// Function to update password
function updatePassword($conn, $username, $new_password) {
    $sql = "UPDATE users SET password = ? WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt->bind_param("ss", $hashed_password, $username);
        return $stmt->execute();
    }
    return false;
}

// Function to update profile image
function updateProfileImage($conn, $username, $imagePath) {
    $sql = "UPDATE users SET profile_image = ? WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $imagePath, $username);
        return $stmt->execute();
    }
    return false;
}
?>
