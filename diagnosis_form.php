<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>บันทึกข้อมูลผู้ป่วย (Admission & Diagnosis)</title> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body { background-color: #f4f7f6; }
        .navbar-custom { background-color: #4a559d; }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        .breadcrumb-item a { color: #6c757d; text-decoration: none; }
        .breadcrumb-item a:hover { color: #0d6efd; }
        .breadcrumb-item.active { color: #212529; }
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
                        <li class="breadcrumb-item">
                            <a href="index.php"><i class="fas fa-home me-1"></i> หน้าแรก</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            บันทึกข้อมูลผู้ป่วย
                        </li>
                    </ol>
                </nav>

                <h2 class="mb-4">ฟอร์มบันทึกข้อมูลผู้ป่วย (Admission, Diagnosis & Treatment)</h2>

                

                <form action="save_all_data.php" method="POST"> <input type="hidden" id="hidden_hn" name="patient_hn">
                   
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"> ข้อมูลการมาถึง (Arrival Info)</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="arrival_time" class="form-label"><strong>เวลาที่มาถึงโรงพยาบาล (Hospital Arrival Time)</strong></label>
                                <input type="datetime-local" class="form-control" name="arrival_time" id="arrival_time">
                            </div>
                            <hr class="my-4">
                            <label class="form-label"><strong>ประเภทการมาของคนไข้ (Arrival Type)</strong></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="arrival_type" id="arrival_walk_in" value="walk_in" checked>
                                <label class="form-check-label" for="arrival_walk_in"> Walk in </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="arrival_type" id="arrival_er" value="er">
                                <label class="form-check-label" for="arrival_er"> ER (มาห้องฉุกเฉิน) </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="arrival_type" id="arrival_refer" value="refer">
                                <label class="form-check-label" for="arrival_refer"> Refer </label>
                            </div>
                            <div class="mt-3 p-3 border rounded" id="refer_from_field" style="display: none; background-color: #f8f9fa;">
                                <div class="mb-3">
                                    <label for="refer_from_text" class="form-label">Refer จาก (ระบุโรงพยาบาล):</label>
                                    <input type="text" class="form-control" name="refer_from_text" id="refer_from_text" placeholder="ระบุชื่อโรงพยาบาล...">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="refer_time_out" class="form-label"><strong>Refer Time Out</strong> (รถออก)</label>
                                        <input type="datetime-local" class="form-control" name="refer_time_out" id="refer_time_out">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="refer_time_in" class="form-label"><strong>Refer Time In</strong> (รถถึง)</label>
                                        <input type="datetime-local" class="form-control" name="refer_time_in" id="refer_time_in">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="refer_duration" class="form-label"><strong><i class="fas fa-clock me-1"></i> ระยะเวลา Refer</strong></label>
                                        <input type="text" class="form-control" id="refer_duration" name="refer_duration" placeholder="กรุณากดปุ่ม 'คำนวณ'" readonly style="background-color: #e9ecef; border: none;">
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-primary w-100" id="calculate_duration_btn">
                                            <i class="fas fa-calculator me-1"></i> คำนวณ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"> การส่งตรวจ (Imaging Request)</h5>
                        </div>
                        <div class="card-body">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="imaging_type" id="img_ct_nc" value="ct_nc">
                                        <label class="form-check-label" for="img_ct_nc">CT NC (ไม่ฉีดสี)</label>
                                    </div>
                                    <div class="mt-2 ps-4" id="time_ct_nc_wrapper" style="display: none;">
                                        <label for="time_ct_nc" class="form-label small mb-1">เวลาที่ตรวจ CT NC:</label>
                                        <input type="datetime-local" class="form-control form-control-sm" name="time_ct_nc" id="time_ct_nc">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="imaging_type" id="img_cta" value="cta">
                                        <label class="form-check-label" for="img_cta">CTA (ฉีดสีดูหลอดเลือด)</label>
                                    </div>
                                    <div class="mt-2 ps-4" id="time_cta_wrapper" style="display: none;">
                                        <label for="time_cta" class="form-label small mb-1">เวลาที่ตรวจ CTA:</label>
                                        <input type="datetime-local" class="form-control form-control-sm" name="time_cta" id="time_cta">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="imaging_type" id="img_ctp" value="ctp">
                                        <label class="form-check-label" for="img_ctp">CT Perfusion</label>
                                    </div>
                                    <div class="mt-2 ps-4" id="time_ctp_wrapper" style="display: none;">
                                        <label for="time_ctp" class="form-label small mb-1">เวลาที่ตรวจ CT Perfusion:</label>
                                        <input type="datetime-local" class="form-control form-control-sm" name="time_ctp" id="time_ctp">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="imaging_type" id="img_mri" value="mri">
                                        <label class="form-check-label" for="img_mri">MRI / MRA</label>
                                    </div>
                                    <div class="mt-2 ps-4" id="time_mri_wrapper" style="display: none;">
                                        <label for="time_mri" class="form-label small mb-1">เวลาที่ตรวจ MRI / MRA:</label>
                                        <input type="datetime-local" class="form-control form-control-sm" name="time_mri" id="time_mri">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"> ผลการวินิจฉัย (Imaging Results)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="aspects_score" class="form-label">คะแนน ASPECTS (0-10)</label>
                                    <input type="number" class="form-control" id="aspects_score" name="aspects_score" min="0" max="10">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="collateral_score" class="form-label">คะแนน Collateral (0-5)</label>
                                    <input type="number" class="form-control" id="collateral_score" name="collateral_score" min="0" max="5">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label"><strong>ผลการวินิจฉัยหลัก</strong></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="diagnosis_type" id="diag_ischemic" value="ischemic">
                                        <label class="form-check-label" for="diag_ischemic">Ischemic Stroke (สมองขาดเลือด)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="diagnosis_type" id="diag_hemorrhage" value="hemorrhage">
                                        <label class="form-check-label" for="diag_hemorrhage">Hemorrhagic Stroke (สมองมีเลือดออก)</label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="location" class="form-label">ตำแหน่ง (Location)</label>
                                    <input type="text" class="form-control" id="location" name="location" placeholder="เช่น MCA M1 left...">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="comp_ivh" value="1" id="comp_ivh">
                                        <label class="form-check-label" for="comp_ivh">พบ IVH (มีเลือดออกในโพรงสมอง)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="comp_hemorrhage_cc" value="1" id="comp_hemorrhage_cc">
                                        <label class="form-check-label" for="comp_hemorrhage_cc">พบ Hemorrhage (CC)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white"><h5 class="mb-0"> การรักษาด้วยยา (Medical Treatment)</h5></div>
                        <div class="card-body">
                            <label class="form-label"><strong>การให้ยาละลายลิ่มเลือด (rT-PA / TNK)</strong></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ivt_status" id="ivt_given" value="given">
                                <label class="form-check-label" for="ivt_given">ให้ยา (Given)</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="ivt_status" id="ivt_not_given" value="not_given" checked>
                                <label class="form-check-label" for="ivt_not_given">ไม่ให้ยา (Not Given)</label>
                            </div>
                            <div class="ps-4" id="ivt_given_details" style="display: none;">
                                <label for="ivt_time_start" class="form-label small">เวลาที่เริ่มให้ยา (Start Time):</label>
                                <input type="datetime-local" class="form-control form-control-sm" name="ivt_time_start" id="ivt_time_start">
                            </div>
                            <div class="ps-4" id="ivt_not_given_details">
                                <label for="ivt_reason" class="form-label small">เหตุผลที่ไม่ให้ยา (Reason):</label>
                                <input type="text" class="form-control form-control-sm" name="ivt_reason" id="ivt_reason" placeholder="เช่น out of window time...">
                            </div>
                        </div>
                    </div>
                    
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white"><h5 class="mb-0"> การรักษาผ่านสายสวน (Endovascular Treatment - EVT)</h5></div>
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="evt_status" id="evt_done" value="done">
                                <label class="form-check-label" for="evt_done">ทำ (Performed)</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="evt_status" id="evt_not_done" value="not_done" checked>
                                <label class="form-check-label" for="evt_not_done">ไม่ได้ทำ (Not Performed)</label>
                            </div>
                            <div id="evt_details_wrapper" style="display: none;" class="p-3 border rounded" style="background-color: #f8f9fa;">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="evt_puncture_time" class="form-label"><strong>Puncture Time</strong> (เวลาเริ่มเจาะ)</label>
                                        <input type="datetime-local" class="form-control" name="evt_puncture_time" id="evt_puncture_time">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="evt_recanal_time" class="form-label"><strong>Recanalization Time</strong> (เวลาเปิดสำเร็จ)</label>
                                        <input type="datetime-local" class="form-control" name="evt_recanal_time" id="evt_recanal_time">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="evt_tici_score" class="form-label"><strong>TICI Score</strong></label>
                                        <select class="form-select" id="evt_tici_score" name="evt_tici_score">
                                            <option value="" selected disabled>-- เลือก TICI Score --</option>
                                            <option value="0">0 - No perfusion</option>
                                            <option value="1">1 - Minimal perfusion</option>
                                            <option value="2a">2a - Partial perfusion (<50%)></option>
                                            <option value="2b">2b - Partial perfusion (>=50%)</option>
                                            <option value="3">3 - Complete perfusion</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white"><h5 class="mb-0"> การผ่าตัด (Surgical Treatment)</h5></div>
                        <div class="card-body">
                            <label class="form-label"><strong>ประเภทการผ่าตัด (ถ้ามี)</strong></label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="op_craniectomy" value="1" id="op_craniectomy">
                                <label class="form-check-label" for="op_craniectomy">Decompressive Craniectomy</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="op_craniotomy" value="1" id="op_craniotomy">
                                <label class="form-check-label" for="op_craniotomy">Craniotomy</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="op_ventriculostomy" value="1" id="op_ventriculostomy">
                                <label class="form-check-label" for="op_ventriculostomy">Ventriculostomy</label>
                            </div>
                        </div>
                    </div>


                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save me-2"></i> บันทึกข้อมูลทั้งหมด
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        // [แก้ไข] ย้ายทุกอย่างมาไว้ในนี้
        document.addEventListener('DOMContentLoaded', function() {

            // --- Logic 1: ปุ่มค้นหา HN (สำหรับ Demo UI) ---
            const fetchButton = document.getElementById('fetch_patient_btn');
            const hnInput = document.getElementById('hn_input');
            const patientSection = document.getElementById('patient_info_section');

            fetchButton.addEventListener('click', function() {
                const hn = hnInput.value;
                if (hn === '') {
                    alert('กรุณากรอก HN ก่อนครับ');
                    return;
                }
                patientSection.style.display = 'block';
                // ใส่ข้อมูล Demo
                document.getElementById('display_name').value = 'สุขใจ (ทดสอบ) ซ่อมไว';
                document.getElementById('display_age').value = '58 ปี';
                document.getElementById('display_sex').value = 'ชาย';
                document.getElementById('hidden_hn').value = hn; 
            });


            // --- Logic 2: ซ่อน/แสดง ช่อง "Refer" ---
            // [แก้ไข] ย้ายเข้ามาในนี้
            const walkInRadio = document.getElementById('arrival_walk_in');
            const erRadio = document.getElementById('arrival_er'); 
            const referRadio = document.getElementById('arrival_refer');
            const referTextField = document.getElementById('refer_from_field');

            function hideReferField() {
                referTextField.style.display = 'none';
                const inputs = referTextField.querySelectorAll('input');
                inputs.forEach(function(input) { input.value = ''; });
                const durationDisplay = document.getElementById('refer_duration');
                if (durationDisplay) {
                    durationDisplay.value = '';
                    durationDisplay.classList.remove('is-invalid');
                }
            }
            function showReferField() {
                referTextField.style.display = 'block';
            }
            walkInRadio.addEventListener('change', hideReferField);
            erRadio.addEventListener('change', hideReferField);
            referRadio.addEventListener('change', showReferField);


            // --- Logic 4: คำนวณระยะเวลา Refer (ย้ายมาในนี้) ---
            // (ผมขอย่อโค้ดส่วนนี้เพื่อไม่ให้ยาว แต่โค้ด Logic 4 เดิมของคุณต้องอยู่ตรงนี้)
            const timeOutInput = document.getElementById('refer_time_out');
            const timeInInput = document.getElementById('refer_time_in');
            const durationDisplay = document.getElementById('refer_duration');
            const calculateBtn = document.getElementById('calculate_duration_btn');
            
            // (ฟังก์ชัน parseCustomDate(dateStr) ก็ต้องอยู่ตรงนี้ด้วย)
            function parseCustomDate(dateStr) {
                try {
                    const parts = dateStr.split(' '); const dateParts = parts[0].split('/'); const timeParts = parts[1].split(':'); const ampm = parts[2].toUpperCase();
                    let month = parseInt(dateParts[0], 10) - 1; let day = parseInt(dateParts[1], 10); let year = parseInt(dateParts[2], 10);
                    let hour = parseInt(timeParts[0], 10); let minute = parseInt(timeParts[1], 10);
                    if (ampm === 'PM' && hour !== 12) { hour += 12; } if (ampm === 'AM' && hour === 12) { hour = 0; }
                    return new Date(year, month, day, hour, minute);
                } catch (e) { return null; }
            }

            function calculateReferDuration() {
                const timeOutValue = timeOutInput.value; const timeInValue = timeInInput.value;
                if (timeOutValue && timeInValue) {
                    const dateOut = parseCustomDate(timeOutValue); const dateIn = parseCustomDate(timeInValue);
                    if (!dateOut || !dateIn) { durationDisplay.value = 'รูปแบบวันที่ผิดพลาด'; durationDisplay.classList.add('is-invalid'); return; }
                    let diffMs = dateIn - dateOut;
                    if (diffMs < 0) { durationDisplay.value = 'ข้อมูลผิดพลาด: เวลาถึงก่อนเวลาออก'; durationDisplay.classList.add('is-invalid'); return; }
                    durationDisplay.classList.remove('is-invalid');
                    const diffHours = Math.floor(diffMs / (1000 * 60 * 60)); diffMs -= diffHours * (1000 * 60 * 60); const diffMins = Math.floor(diffMs / (1000 * 60));
                    durationDisplay.value = `${diffHours} ชั่วโมง ${diffMins} นาที`;
                } else { durationDisplay.value = 'กรุณากรอกเวลาทั้ง 2 ช่อง'; durationDisplay.classList.add('is-invalid'); }
            }
            if(calculateBtn) calculateBtn.addEventListener('click', calculateReferDuration);


            // --- Logic 5: ซ่อน/แสดง ช่องเวลา Imaging ---
            // [แก้ไข] ย้ายเข้ามาในนี้
            const imagingRadios = document.querySelectorAll('input[name="imaging_type"]');
            const timeWrappers = {
                ct_nc: document.getElementById('time_ct_nc_wrapper'),
                cta: document.getElementById('time_cta_wrapper'),
                ctp: document.getElementById('time_ctp_wrapper'),
                mri: document.getElementById('time_mri_wrapper')
            };

            function handleImagingChange() {
                for (const key in timeWrappers) {
                    if (timeWrappers[key]) { 
                        timeWrappers[key].style.display = 'none';
                        const input = timeWrappers[key].querySelector('input[type="datetime-local"]');
                        if (input) { input.value = ''; }
                    }
                }
                let selectedValue = null;
                for (const radio of imagingRadios) {
                    if (radio.checked) { selectedValue = radio.value; break; }
                }
                if (selectedValue && timeWrappers[selectedValue]) {
                    timeWrappers[selectedValue].style.display = 'block';
                }
            }
            imagingRadios.forEach(radio => {
                radio.addEventListener('change', handleImagingChange);
            });
            

            // --- [เพิ่ม] Logic 6: ซ่อน/แสดง รายละเอียดการให้ยา IVT ---
            const ivtGivenRadio = document.getElementById('ivt_given');
            const ivtNotGivenRadio = document.getElementById('ivt_not_given');
            const ivtGivenDetails = document.getElementById('ivt_given_details');
            const ivtNotGivenDetails = document.getElementById('ivt_not_given_details');

            if(ivtGivenRadio) { // (เพิ่มการตรวจสอบว่า element มีอยู่จริง)
                ivtGivenRadio.addEventListener('change', function() {
                    if (this.checked) {
                        ivtGivenDetails.style.display = 'block';
                        ivtNotGivenDetails.style.display = 'none';
                        ivtNotGivenDetails.querySelector('input').value = ''; 
                    }
                });
            }
            if(ivtNotGivenRadio) {
                ivtNotGivenRadio.addEventListener('change', function() {
                    if (this.checked) {
                        ivtGivenDetails.style.display = 'none';
                        ivtNotGivenDetails.style.display = 'block';
                        ivtGivenDetails.querySelector('input').value = '';
                    }
                });
            }


            // --- [เพิ่ม] Logic 7: ซ่อน/แสดง รายละเอียดการทำ EVT ---
            const evtDoneRadio = document.getElementById('evt_done');
            const evtNotDoneRadio = document.getElementById('evt_not_done');
            const evtDetailsWrapper = document.getElementById('evt_details_wrapper');

            if(evtDoneRadio) {
                evtDoneRadio.addEventListener('change', function() {
                    if (this.checked) {
                        evtDetailsWrapper.style.display = 'block';
                    }
                });
            }
            if(evtNotDoneRadio) {
                evtNotDoneRadio.addEventListener('change', function() {
                    if (this.checked) {
                        evtDetailsWrapper.style.display = 'none';
                    }
                });
            }

        }); // <-- [สำคัญ] นี่คือ } ปิดของ DOMContentLoaded
    </script>

</body>
</html>