<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "pet_adoption");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message
$message = '';
$messageType = '';

// Handle form submission
if (isset($_POST['submit'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $breed = $_POST['breed'];

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imagePath = 'uploads/' . $imageName;

        // Create uploads directory if it does not exist
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Insert data into the database
            $sql = "INSERT INTO animals (name, age, breed, image_url) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('siss', $name, $age, $breed, $imagePath);

            if ($stmt->execute()) {
                $message = "Successfully Uploaded";
                $messageType = "success";
            } else {
                $message = "Error uploading dog details.";
                $messageType = "error";
            }

            $stmt->close();
        } else {
            $message = "Error uploading image.";
            $messageType = "error";
        }
    } else {
        $message = "No image uploaded or there was an error.";
        $messageType = "error";
    }
}

// Handle record deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Get the image path for deletion
    $sql = "SELECT image_url FROM animals WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $imagePath = $row['image_url'];

    // Delete the record
    $sql = "DELETE FROM animals WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        // Remove the image file
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $message = "Record deleted successfully.";
        $messageType = "success";
    } else {
        $message = "Error deleting record.";
        $messageType = "error";
    }

    $stmt->close();
}

// Fetch records from database
$sql = "SELECT id, name, age, breed, image_url FROM animals";
$result = $conn->query($sql);

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007bff;
            padding: 10px 20px;
            color: #fff;
        }

        .logo h1 {
            margin: 0;
            font-size: 24px;
        }

        .nav-links {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        .nav-links li {
            margin-left: 20px;
        }

        .nav-links a {
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .nav-links a:hover {
            background-color: #0056b3;
            color: #fff;
        }

        main {
            padding: 20px;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .tabs button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 5px;
            transition: background-color 0.3s ease;
        }

        .tabs button.active {
            background-color: #0056b3;
        }

        .form-container,
        .dashboard-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .form-container.active,
        .dashboard-container.active {
            display: block;
        }

        .form-container h2,
        .dashboard-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            color: #007bff;
        }

        .form-container label {
            display: block;
            margin-bottom: 5px;
            font-size: 16px;
            color: #333;
        }

        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .dashboard-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .dashboard-container table, 
        .dashboard-container th, 
        .dashboard-container td {
            border: 1px solid #ddd;
        }

        .dashboard-container th, 
        .dashboard-container td {
            padding: 10px;
            text-align: left;
        }

        .dashboard-container th {
            background-color: #007bff;
            color: #fff;
        }

        .dashboard-container img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .dashboard-container button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .dashboard-container button:hover {
            background-color: #c82333;
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: block;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        #imagePreview {
            max-width: 200px;
            height: auto;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">
            <h1>Pet Adoption Admin</h1>
        </div>
        <div>
            <ul class="nav-links">
                <div class="tabs">
                    <li>
                        <a href="index.php" id="homeTab" class="nav-link">Home</a>
                        <button id="formTab" class="active">Upload Dog Details</button>
                        <button id="dashboardTab">Dashboard</button>
                    </li>    
                </div>
            </ul>
        </div>
    </nav>
    
    <main>
        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <section id="formSection" class="form-container active">
            <h2>Upload Dog Details</h2>
            <form id="uploadForm" action="admin.php" method="post" enctype="multipart/form-data">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
                
                <label for="breed">Breed:</label>
                <input type="text" id="breed" name="breed" required>
                
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
                
                <input type="submit" name="submit" value="Upload">
            </form>

            <div class="preview-container">
                <img id="imagePreview" src="" alt="Image Preview" style="display: none;">
            </div>
        </section>

        <section id="dashboardSection" class="dashboard-container">
            <h2>Admin Dashboard</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Breed</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch records from database
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['name']}</td>
                                <td>{$row['age']}</td>
                                <td>{$row['breed']}</td>
                                <td><img src='{$row['image_url']}' alt='Dog Image'></td>
                                <td><a href='admin.php?delete={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this record?\")'><button>Delete</button></a></td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Pet Adoption Admin. All rights reserved.</p>
    </footer>

    <script>
        document.getElementById('image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };

                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        });

        // Tab functionality
        document.getElementById('formTab').addEventListener('click', function() {
            document.getElementById('formSection').classList.add('active');
            document.getElementById('dashboardSection').classList.remove('active');
            this.classList.add('active');
            document.getElementById('dashboardTab').classList.remove('active');
        });

        document.getElementById('dashboardTab').addEventListener('click', function() {
            document.getElementById('dashboardSection').classList.add('active');
            document.getElementById('formSection').classList.remove('active');
            this.classList.add('active');
            document.getElementById('formTab').classList.remove('active');
        });
    </script>
</body>
</html>
