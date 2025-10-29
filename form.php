<?php

// 1. เริ่ม session (ต้องอยู่อันแรกเสมอ)
session_start();

// 2. 🛡️ "ยาม" ตรวจสอบ
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// 3. 🧠 "ห้ามจำ" (Cache Control)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// (ถ้าหน้านี้ต้องใช้ข้อมูลผู้ใช้ ก็ดึงมาได้เลย)
// $user = $_SESSION['user_data'];
?>

<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>1. บันทึกข้อมูลแรกรับ - ระบบ Stroke Care</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            background-color: #f4f7f6;
        }

        .navbar-custom {
            background-color: #4a559d;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        /* 1. ทำให้ "หน้าแรก" (ที่เป็นลิงก์ <a>) เป็นสีเทา */
        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
        }

        /* 2. ทำให้ลิงก์ "หน้าแรก" เปลี่ยนเป็นสีน้ำเงินเมื่อชี้ */
        .breadcrumb-item a:hover {
            color: #0d6efd;
        }

        /* 3. ทำให้หน้าปัจจุบัน (active) เป็นสีเข้ม */
        .breadcrumb-item.active {
            color: #2689ebff;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-brain me-2"></i>
                ระบบส่งต่อผู้ป่วยโรคหลอดเลือดสมอง (Stroke)
            </a>
            <div class="d-flex">
                <span class="navbar-text text-white d-flex align-items-center">
                    <i class="fas fa-user-circle fa-2x me-3"></i>
                    <span>
                        <strong>ชื่อ-สกุล:</strong> <?php echo "สุขใจ (ทดสอบ) ซ่อมไว"; ?>
                    </span>
                </span>
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-lg-10 mx-auto">

                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none"><i class="fas fa-home me-1"></i> หน้าแรก</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            1. บันทึกข้อมูลแรกรับ
                        </li>
                    </ol>
                </nav>

                <h2 class="mb-4">ฟอร์มบันทึกข้อมูลแรกรับ (Admission Form)</h2>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">1. ค้นหาผู้ป่วย</h5>
                    </div>
                    <div class="card-body">
                        <label for="hn_input" class="form-label"><strong>กรอกเลข HN</strong></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="hn_input" name="hn" placeholder="กรอกเลข HN... (hn คือ เลขประจำตัวคนไข้โรงพยาบาล)">
                            <button class="btn btn-primary" type="button" id="fetch_patient_btn">
                                <i class="fas fa-search me-1"></i> ค้นหาข้อมูล
                            </button>
                        </div>
                    </div>
                </div>

                <form action="save_admission_data.php" method="POST">

                    <input type="hidden" id="hidden_hn" name="patient_hn">

                    <div class="card shadow-sm mb-4" id="patient_info_section" style="display: none;">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">2. ข้อมูลผู้ป่วย</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ชื่อ-สกุล</label>
                                    <input type="text" class="form-control" id="display_name" value="" placeholder="..." readonly>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">อายุ</label>
                                    <input type="text" class="form-control" id="display_age" value="" placeholder="..." readonly>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">เพศ</label>
                                    <input type="text" class="form-control" id="display_sex" value="" placeholder="..." readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">3. โรคประจำตัว</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="comorbid_ht" value="1" id="comorbid_ht"><label class="form-check-label" for="comorbid_ht">HT</label></div>
                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="comorbid_dm" value="1" id="comorbid_dm"><label class="form-check-label" for="comorbid_dm">DM</label></div>
                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="comorbid_dlp" value="1" id="comorbid_dlp"><label class="form-check-label" for="comorbid_dlp">DLP</label></div>
                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="comorbid_mi" value="1" id="comorbid_mi"><label class="form-check-label" for="comorbid_mi">MI</label></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="comorbid_af" value="1" id="comorbid_af"><label class="form-check-label" for="comorbid_af">AF</label></div>
                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="comorbid_smoking" value="1" id="comorbid_smoking"><label class="form-check-label" for="comorbid_smoking">SMOKING</label></div>
                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="comorbid_alcohol" value="1" id="comorbid_alcohol"><label class="form-check-label" for="comorbid_alcohol">ALCOHOL</label></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="comorbid_addictive" value="1" id="comorbid_addictive"><label class="form-check-label" for="comorbid_addictive">ADDICTIVE SUBSTANCE</label></div>
                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="old_cva" value="1" id="old_cva"><label class="form-check-label" for="old_cva">OLD CVA</label></div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="comorbid_other_check" value="1" id="comorbid_other_check">
                                        <label class="form-check-label" for="comorbid_other_check">OTHER</label>
                                    </div>
                                    <input type="text" class="form-control" name="comorbid_other_text" id="comorbid_other_text" placeholder="ระบุโรคประจำตัวอื่นๆ..." style="display: none;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">4. MRS Score (ก่อนเกิดอาการ)</h5>
                        </div>
                        <div class="card-body">
                            <label for="mrs_score" class="form-label">เลือกคะแนน MRS (0-6)</label>
                            <select class="form-select" id="mrs_score" name="mrs_score" required>
                                <option value="" selected disabled>-- กรุณาเลือก --</option>
                                <option value="0">0 - No symptoms</option>
                                <option value="1">1 - No significant disability</option>
                                <option value="2">2 - Slight disability</option>
                                <option value="3">3 - Moderate disability</option>
                                <option value="4">4 - Moderately severe disability</option>
                                <option value="5">5 - Severe disability</loption>
                                <option value="6">6 - Dead</option>
                            </select>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">5. ยาที่ได้รับ (Medication)</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="med_anti_platelet" value="1" id="med_anti_platelet">
                                <label class="form-check-label" for="med_anti_platelet">Anti-platelet (ยาต้านเกล็ดเลือด)</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="med_anti_coagulant" value="1" id="med_anti_coagulant">
                                <label class="form-check-label" for="med_anti_coagulant">Anti-coagulant (ยาต้านการแข็งตัวของเลือด)</label>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">6. ประเภทการมาของคนไข้ (Arrival Type)</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="arrival_type" id="arrival_walk_in" value="walk_in" checked>
                                <label class="form-check-label" for="arrival_walk_in">
                                    Walk in
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="arrival_type" id="arrival_er" value="er">
                                <label class="form-check-label" for="arrival_er">
                                    ER (มาห้องฉุกเฉิน)
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="arrival_type" id="arrival_refer" value="refer">
                                <label class="form-check-label" for="arrival_refer">
                                    Refer
                                </label>
                            </div>

                            <div class="mt-3" id="refer_from_field" style="display: none;">
                                <label for="refer_from_text" class="form-label">Refer จาก (ระบุโรงพยาบาล):</label>
                                <input type="text" class="form-control" name="refer_from_text" id="refer_from_text" placeholder="ระบุชื่อโรงพยาบาล...">
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save me-2"></i> บันทึกข้อมูล
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- Logic 1: สำหรับซ่อน/แสดง ช่อง "OTHER" (เหมือนเดิม) ---
            const otherCheckbox = document.getElementById('comorbid_other_check');
            const otherTextField = document.getElementById('comorbid_other_text');
            otherCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    otherTextField.style.display = 'block';
                } else {
                    otherTextField.style.display = 'none';
                    otherTextField.value = '';
                }
            });

            // --- Logic 2: สำหรับซ่อน/แสดง ช่อง "Refer" (เหมือนเดิม) ---
            const walkInRadio = document.getElementById('arrival_walk_in');
            const referRadio = document.getElementById('arrival_refer');
            const referTextField = document.getElementById('refer_from_field');
            walkInRadio.addEventListener('change', function() {
                if (this.checked) {
                    referTextField.style.display = 'none';
                    referTextField.querySelector('input').value = '';
                }
            });
            referRadio.addEventListener('change', function() {
                if (this.checked) {
                    referTextField.style.display = 'block';
                }
            });

            // --- [แก้ไข] Logic 3: ปุ่มค้นหา HN (สำหรับ Demo UI) ---
            const fetchButton = document.getElementById('fetch_patient_btn');
            const hnInput = document.getElementById('hn_input');
            const patientSection = document.getElementById('patient_info_section');

            fetchButton.addEventListener('click', function() {
                const hn = hnInput.value;
                if (hn === '') {
                    alert('กรุณากรอก HN ก่อนครับ');
                    return;
                }

                // 1. สั่งให้ "ส่วนที่ 2" แสดงผล
                patientSection.style.display = 'block';


            });

        });
    </script>

</html>