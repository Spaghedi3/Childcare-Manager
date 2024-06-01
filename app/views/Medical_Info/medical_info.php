<?php
if (!isset($_COOKIE['child_id'])) {
    header('Location: /select');
    exit();
}
?>

<div class="container medical_info_container">
    <h1>Medical Info</h1>
    <p>Welcome to [Child's Name]'s Medical Information Page. This page is intended to provide important medical details for [Child's Name] to ensure their safety and well-being in various situations.</p>
</div>

<div class="container medical_info_container">
    <div id="basic_info">
        <h2>Basic Information</h2>
        <p>Full Name: [Child's Name]</p>
        <p>Age: [Child's age]</p>
        <p>Date of Birth: [Child's Date of Birth]</p>
        <p>Parents Names: [Child's Parents Names]</p>
        <button>Edit</button>
    </div>
</div>

<div class="container medical_info_container" id="emergency_contact">
    <h2>Emergency Contact Information</h2>
    <p>Provide contact details for parents or guardians, including phone numbers and email addresses. Also, include the child's primary care physician's contact information.</p>
    <button>Edit</button>
</div>

<div class="container medical_info_container" id="medical_conditions">
    <h2>Medical Conditions</h2>
    <p>Outline the child's medical history, including any chronic conditions, allergies, surgeries, hospitalizations, or ongoing treatments. Be concise but thorough.</p>
    <button>Edit</button>
</div>

<div class="container medical_info_container" id="medications">
    <h2>Medication Information</h2>
    <p>List any medications the child is currently taking, including dosage and frequency. Mention any specific instructions or precautions related to these medications.</p>
    <button>Edit</button>
</div>

<div class="container medical_info_container" id="allergies">
    <h2>Allergies</h2>
    <p>Clearly list any allergies the child has, including the type of allergen and the reaction it causes. This section is crucial for ensuring the child's safety, especially in emergency situations.</p>
    <button>Edit</button>
</div>

<div class="container medical_info_container" id="immunization_record">
    <h2>Immunization Record</h2>
    <p>Include a record of the child's immunizations, including dates and types of vaccines received. This information is important for healthcare providers and schools.</p>
    <button>Edit</button>
</div>

<div class="container medical_info_container" id="insurance">
    <h2>Insurance Information</h2>
    <p>Provide details about the child's health insurance coverage, including the name of the insurance company, policy number, and any relevant contact information. This information is essential for medical emergencies and routine care.</p>
    <button>Edit</button>
</div>

<div class="container medical_info_container">
    <h2>Medical History</h2>
    <p>Include any additional medical information that may be relevant to the child's health and well-being. This
        could include past illnesses, surgeries, or other medical events that may impact their care.</p>
    <button>Edit</button>
</div>