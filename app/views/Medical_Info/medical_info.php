<div class="container medical_info_container">
    <h1>Medical Info</h1>
    <p id="welcome">Welcome to </p>
</div>

<div class="container medical_info_container">
    <div id="basic_info">
        <h2>Basic Information</h2>
        <p id="full_name">Full Name: </p>
        <p id="age">Age: </p>
        <p id="date_of_birth">Date of Birth: </p>
        <button>Edit</button>
    </div>
</div>

<script>
    const fetchBasic = async () => {
        try {
            const response = await fetch('/getBasic', {
                method: 'GET',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            renderBasic(data);
        } catch (error) {
            console.error('Error fetching info:', error);
        }
    };

    const renderBasic = (basic_info) => {
        console.log("aaaaa");
        document.getElementById('full_name').textContent += basic_info[0].name;
        document.getElementById('age').textContent += basic_info[0].age;
        document.getElementById('date_of_birth').textContent += basic_info[0].birth_date;
        document.getElementById('welcome').textContent += basic_info[0].name;
        document.getElementById('welcome').textContent += "'s Medical Information Page. This page is intended to provide important medical details for ";
        document.getElementById('welcome').textContent += basic_info[0].name;
        document.getElementById('welcome').textContent += " to ensure their safety and well-being in various situations. ";
    };

    fetchBasic();
</script>

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
    <p>Include any additional medical information that may be relevant to the child's health and well-being. This could include past illnesses, surgeries, or other medical events that may impact their care.</p>
    <button>Edit</button>
</div>