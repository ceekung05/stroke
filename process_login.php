<?php

    require 'connectdb.php';
// เริ่ม session ทุกครั้งที่เรียกใช้ไฟล์ PHP ที่ต้องการเก็บข้อมูล
session_start();

// ตรวจสอบว่ามีการส่งค่าแบบ POST มาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. รับค่าจากฟอร์ม
    $username = $_POST['uname'];
    $password = $_POST['psword'];
    $functionName = 'authenUserLogin'; // ค่า fnc ที่ API ต้องการ

    // 2. เตรียมข้อมูลที่จะส่งไปยัง API
    $apiUrl = 'http://61.19.25.200/api/gtw/api-gtw.php';
    $postData = [
        'uname' => $username,
        'psword' => $password,
        'fnc' => $functionName
    ];

    // 3. ใช้ cURL เพื่อยิง API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    // แปลง array เป็น query string (uname=...&psword=...&fnc=...)
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // รับผลลัพธ์เป็น string
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // Timeout
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch); // ยิง API และเก็บผลลัพธ์
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // ดูสถานะ HTTP
    curl_close($ch);

    // 4. ตรวจสอบผลลัพธ์
    if ($response === false) {
        // กรณี API ยิงไม่สำเร็จ (เช่น network error, server down)
        $_SESSION['error_message'] = "ไม่สามารถเชื่อมต่อ API ได้";
        header("Location: login.php");
        exit;
    }

    // แปลง JSON string ที่ได้มาเป็น PHP Array (true = ให้เป็น associative array)
    $responseData = json_decode($response, true);

    // 5. ตรวจสอบความถูกต้อง
    // เราจะเช็คว่ามี 'HR_CID' อยู่ในผลลัพธ์หรือไม่
    // (API ที่ดีควรส่ง error message มา, แต่ในที่นี้เราใช้ข้อมูลตัวอย่างที่คุณให้มาเป็นเกณฑ์)
    if ($httpCode == 200 && $responseData && isset($responseData['HR_CID'])) {
        // ----- ล็อกอินสำเร็จ -----
        // ▼▼▼ ส่วนที่ 5: บันทึกข้อมูลลงฐานข้อมูล ▼▼▼
        
        // เราจะใช้ '?' เป็น placeholder แทน
        $sql = "INSERT INTO tbl_user (
                    hr_fname, hr_cid, position_in_work, 
                    department_name, hr_department_sub_name, 
                    hr_department_sub_sub_name, created_by,
                    last_login
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?,NOW()
                )
                ON DUPLICATE KEY UPDATE
                    hr_fname = VALUES(hr_fname),
                    position_in_work = VALUES(position_in_work),
                    department_name = VALUES(department_name),
                    hr_department_sub_name = VALUES(hr_department_sub_name),
                    hr_department_sub_sub_name = VALUES(hr_department_sub_sub_name),
                    updated_by = ?,
                    last_login = NOW()"; 

        // ใช้ $conn (จากไฟล์ของคุณ) แทน $pdo
        $stmt = $conn->prepare($sql);

        // 's' ย่อมาจาก 'string'. เรามี ? 8 ตัว เลยต้องมี 's' 8 ตัว
        $stmt->bind_param("ssssssss", 
            $responseData['HR_FNAME'],
            $responseData['HR_CID'],
            $responseData['POSITION_IN_WORK'],
            $responseData['DEPARTMENT_NAME'],
            $responseData['HR_DEPARTMENT_SUB_NAME'],
            $responseData['HR_DEPARTMENT_SUB_SUB_NAME'],
            $username, // created_by
            $username  // updated_by
        );

        // รันคำสั่ง
        if (!$stmt->execute()) {
            // ถ้าบันทึก DB ไม่ได้
            $_SESSION['error_message'] = "บันทึกข้อมูลลงฐานข้อมูลล้มเหลว: " . $stmt->error;
            $stmt->close();
            $conn->close();
            header("Location: login.php");
            exit;
        }

        // บันทึกสำเร็จ ปิด statement
        $stmt->close();
        
        // ▲▲▲ สิ้นสุดส่วนที่ 5 ▲▲▲
        // เก็บข้อมูลผู้ใช้ไว้ใน Session
        $_SESSION['user_data'] = $responseData;
        $_SESSION['logged_in'] = true;

        // ส่งต่อไปยังหน้าแสดงผล
        header("Location: index.php");
        exit;

    } else {
        // ----- ล็อกอินไม่สำเร็จ -----
        
        // API อาจจะส่งข้อความ error มา (ถ้ามี)
        if (!empty($responseData['message'])) {
             $_SESSION['error_message'] = $responseData['message'];
        } else {
            $_SESSION['error_message'] = "Username หรือ Password ไม่ถูกต้อง";
        }
        
        header("Location: login.php");
        exit;
    }

} else {
    // ถ้าเข้าหน้านี้โดยตรง (ไม่ใช่ POST) ให้เด้งกลับไป
    header("Location: login.php");
    exit;
}
?>