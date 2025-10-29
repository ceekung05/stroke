<?php
session_start();

// ลบตัวแปร session ทั้งหมด
session_unset();

// 1. ลบข้อมูลทั้งหมดใน session
$_SESSION = array();

// 2. (ทางเลือก) สั่งลบคุกกี้ที่ฝั่งเบราว์เซอร์ทันที
// โดยการตั้งวันหมดอายุให้เป็นอดีต (เช่น 1 ชั่วโมงที่แล้ว)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
// ทำลาย session
session_destroy();

// กลับไปยังหน้า login
header("Location: login.php");
exit;
?>