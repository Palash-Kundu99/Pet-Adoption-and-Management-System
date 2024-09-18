<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
    $servername = "localhost";  // Update with your server details
    $username = "root";         // Update with your database username
    $password = "";             // Update with your database password
    $dbname = "pet_adoption";  // Update with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Collect and sanitize form data
    $fullName = htmlspecialchars($_POST['fullName']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $city = htmlspecialchars($_POST['city']);
    $residence = htmlspecialchars($_POST['residence']);
    $yard = htmlspecialchars($_POST['yard']);
    $children = htmlspecialchars($_POST['children']);
    $dayLocation = htmlspecialchars($_POST['dayLocation']);
    $aloneHours = htmlspecialchars($_POST['aloneHours']);
    $owned = htmlspecialchars($_POST['owned']);
    $aware = htmlspecialchars($_POST['aware']);
    $signature = htmlspecialchars($_POST['signature']);
    $date = htmlspecialchars($_POST['date']);
    $agree = isset($_POST['agree']) ? 'Yes' : 'No';

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO adoption_applications (fullName, email, phone, city, residence, yard, children, dayLocation, aloneHours, owned, aware, signature, date, agree) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssss", $fullName, $email, $phone, $city, $residence, $yard, $children, $dayLocation, $aloneHours, $owned, $aware, $signature, $date, $agree);

    // Execute the statement
    if ($stmt->execute()) {
        $message = "Form submitted successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    $message = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Adoption Form</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #8fc4b7;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            background-color: #fff;
            border-radius: .3rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 700px;
            width: 100%;
            margin: auto;
        }
        .card img {
            width: 100%;
            border-top-left-radius: .3rem;
            border-top-right-radius: .3rem;
        }
        .card-body {
            padding: 20px;
        }
        .card-body h3 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #495057;
            text-transform: uppercase;
        }
        .form-outline {
            margin-bottom: 15px;
        }
        .form-outline label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .form-outline input, 
        .form-outline select {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        .form-check-label {
            margin-left: 10px;
        }
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .btn {
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            background-color: #ff416c;
        }
        .btn-reset {
            background-color: #6c757d;
        }
        .form-section {
            margin-bottom: 20px;
        }
        @media (min-width: 1025px) {
            .h-custom {
                height: 100vh !important;
            }
        }
    </style>
</head>
<body>
    <section class="h-100 h-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-8 col-xl-6">
                    <div class="card rounded-3">
                        <img src="img/FORM.jpg"
                            alt="Sample photo">
                        <div class="card-body p-4 p-md-5">
                            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 px-md-2">Pet Adoption Form</h3>
                            <form id="adoptionForm" action="form.php" method="post" onsubmit="showSuccessMessage(event)">
                                <!-- Personal Information -->
                                <div class="form-section">
                                    <h4>Personal Information</h4>
                                    <div class="form-outline mb-4">
                                        <label for="fullName">Full Name</label>
                                        <input type="text" id="fullName" name="fullName" required>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label for="email">Email Address</label>
                                        <input type="email" id="email" name="email" required>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label for="phone">Phone Number</label>
                                        <input type="tel" id="phone" name="phone" required>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label for="city">City</label>
                                        <input type="text" id="city" name="city" required>
                                    </div>
                                </div>
                                <!-- Household Information -->
                                <div class="form-section">
                                    <h4>Household Information</h4>
                                    <div class="form-outline mb-4">
                                        <label for="residence">Type of Residence</label>
                                        <select id="residence" name="residence">
                                            <option value="Apartment">Apartment</option>
                                            <option value="House">House</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label>Do you have a yard or terrace?</label>
                                        <div class="form-check">
                                            <input type="radio" id="yardYes" name="yard" value="Yes" required>
                                            <label for="yardYes" class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" id="yardNo" name="yard" value="No">
                                            <label for="yardNo" class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label>Are there children in the household?</label>
                                        <div class="form-check">
                                            <input type="radio" id="childrenYes" name="children" value="Yes" required>
                                            <label for="childrenYes" class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" id="childrenNo" name="children" value="No">
                                            <label for="childrenNo" class="form-check-label">No</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pet Care Information -->
                                <div class="form-section">
                                    <h4>Pet Care Information</h4>
                                    <div class="form-outline mb-4">
                                        <label for="dayLocation">Where will the pet be kept during the day?</label>
                                        <input type="text" id="dayLocation" name="dayLocation" required>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label for="aloneHours">How many hours a day will the pet be alone?</label>
                                        <input type="text" id="aloneHours" name="aloneHours" required>
                                    </div>
                                </div>
                                <!-- Experience -->
                                <div class="form-section">
                                    <h4>Experience</h4>
                                    <div class="form-outline mb-4">
                                        <label>Have you owned a pet before?</label>
                                        <div class="form-check">
                                            <input type="radio" id="ownedYes" name="owned" value="Yes" required>
                                            <label for="ownedYes" class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" id="ownedNo" name="owned" value="No">
                                            <label for="ownedNo" class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label>Are you aware of the costs associated with pet care?</label>
                                        <div class="form-check">
                                            <input type="radio" id="awareYes" name="aware" value="Yes" required>
                                            <label for="awareYes" class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" id="awareNo" name="aware" value="No">
                                            <label for="awareNo" class="form-check-label">No</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Adoption Agreement -->
                                <div class="form-section">
                                    <h4>Adoption Agreement</h4>
                                    <div class="form-outline mb-4">
                                        <label for="signature">Signature</label>
                                        <input type="text" id="signature" name="signature" required>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label for="date">Date</label>
                                        <input type="date" id="date" name="date" required>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <input type="checkbox" id="agree" name="agree" required>
                                        <label for="agree">I agree to the terms and conditions</label>
                                    </div>
                                </div>
                                <button type="reset" class="btn btn-reset">Reset all</button>
                                <button type="submit" class="btn">Submit form</button>
                                <a href="index.php" class="btn">Back to Home</a>
                            </form>
                            <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function showSuccessMessage(event) {
            event.preventDefault(); // Prevent form from submitting the traditional way

            // Collect form data
            const formData = new FormData(document.getElementById('adoptionForm'));

            // Send form data via fetch
            fetch('form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                alert('Thank you for your submission. We will connect with you soon!');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
</body>
</html>
