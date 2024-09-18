
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Adoption</title>
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
            background: linear-gradient(to right, #FF4B2B, #FF416C);
            padding: 10px 20px;
            color: #ffffff;
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
            background-color: #0000;
            color: #fff;
        }

        main {
            padding: 20px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .card-content {
            padding: 15px;
        }

        .card-content h3 {
            margin: 0;
            font-size: 18px;
            color: #007bff;
        }

        .card-content p {
            margin: 10px 0;
            font-size: 16px;
        }

        .card-content button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .card-content button:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

        footer p {
            margin: 0;
        }
        

    </style>
</head>
<body>
<nav>
    <div class="logo" style="display: flex; align-items: center;">
        <img src="img/LOGO.png" alt="AdoptPaw Logo" style="height: 70px; width: 70px; margin-right: 10px;">
        <h1 style="margin: 0;">AdoptPaw</h1>
    </div>
    
    <ul class="nav-links">
        <li><a href="index.php" style="color: #ffffff; text-decoration: none; padding: 10px;">Home</a></li>

        <?php if (isset($_SESSION['username'])): ?>
            <li><span style="color: #ffffff;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
            <li><a href="logout.php" style="color: #ffffff; text-decoration: none; padding: 10px;">Logout</a></li>
        <?php else: ?>
            <li><a href="auth.php" style="color: #ffffff; text-decoration: none; padding: 10px;">Login</a></li>
            <li><a href="auth.php" style="color: #ffffff; text-decoration: none; padding: 10px;">Register</a></li>
        <?php endif; ?>
    </ul>
    
</nav>
<div class="banner" style="margin-top: 20px; text-align: center;">
    <img src="img/BANNER.png" alt="Adoption Banner" style="width: 100%; height: 600px;  object-fit: cover;">
</div>
<div class="banner" style="margin-top: 20px; text-align: center;">
    <h1>Every year we rescue more than 15,000 injured animals in West Bengal, India.</h1>
    <p>Our mission is to rescue and treat owner-less street animals of Bengal who have become ill or injured, and through their rescue inspire the community to care for the lives of all animals.</p>
</div>



    <main>
        <div class="grid-container">
            <?php
            // Database connection
            $conn = new mysqli("localhost", "root", "", "pet_adoption");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch records from database
            $sql = "SELECT name, age, breed, image_url FROM animals";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='card'>
                            <img src='{$row['image_url']}' alt='Dog Image'>
                            <div class='card-content'>
                                <h3>{$row['name']}</h3>
                                <p>Age: {$row['age']}</p>
                                <p>Breed: {$row['breed']}</p>
                                 <a href='form.php?animal_id={$row['name']}' style='
    background-color: #FF416C;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    font-weight: bold;
'>Adopt Now</a>

                            </div>
                        </div>";
                }
            } else {
                echo "<p>No animals available for adoption at the moment.</p>";
            }

            $conn->close();
            ?>
        </div>
    </main>

    <footer style="background-color: #333; color: #fff; padding: 60px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
            <!-- Logo and About Section -->
            <div style="flex: 1 1 300px; margin-bottom: 30px;">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <img src="img/LOGO.png" alt="AdoptPaw Logo" style="height: 70px; width: 70px; margin-right: 10px;">
                    <h1 style="margin: 0; font-size: 28px;">AdoptPaw</h1>
                </div>
                <p style="font-size: 16px; line-height: 1.8;">We are dedicated to connecting loving families with pets in need. Join us in our mission to give every animal a safe and loving home. Adopt, don't shop!</p>
            </div>

            <!-- Quick Links Section -->
            <div style="flex: 1 1 200px; margin-bottom: 30px;">
                <h3 style="font-size: 22px; margin-bottom: 20px;">Quick Links</h3>
                <ul style="list-style: none; padding: 0;">
                    <li><a href="index.php" style="color: #fff; text-decoration: none; margin-bottom: 10px; display: block;">Home</a></li>
                    
                    
                    <li><a href="contact.php" style="color: #fff; text-decoration: none; margin-bottom: 10px; display: block;">Contact Us</a></li>
                    <li><a href="donate.php" style="color: #fff; text-decoration: none; margin-bottom: 10px; display: block;">Donate</a></li>
                </ul>
            </div>

            <!-- Contact Information Section -->
            <div style="flex: 1 1 300px; margin-bottom: 30px;">
                <h3 style="font-size: 22px; margin-bottom: 20px;">Contact Us</h3>
                <p style="font-size: 16px; margin-bottom: 10px;">Email: <a href="mailto:info@adoptpaw.com" style="color: #FF416C;">info@adoptpaw.com</a></p>
                <p style="font-size: 16px; margin-bottom: 10px;">Phone: <a href="tel:+1234567890" style="color: #FF416C;">+123-456-7890</a></p>
                <p style="font-size: 16px;">Address: ANO 717, Astra Towers, Action Area IIC, Newtown, New Town, West Bengal 700135</p>
            </div>

            <!-- Social Media & Subscription Section -->
            <div style="flex: 1 1 300px; margin-bottom: 30px;">
                <h3 style="font-size: 22px; margin-bottom: 20px;">Stay Connected</h3>
                <p style="font-size: 16px; margin-bottom: 20px;">Follow us on social media and subscribe to our newsletter for updates on adoptable pets and events!</p>

                <!-- Social Media Links -->
                <div style="margin-bottom: 20px;">
                    <a href="#" style="color: #fff; font-size: 24px; margin-right: 15px;"><i class="fab fa-facebook"></i></a>
                    <a href="#" style="color: #fff; font-size: 24px; margin-right: 15px;"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="color: #fff; font-size: 24px; margin-right: 15px;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="color: #fff; font-size: 24px;"><i class="fab fa-youtube"></i></a>
                </div>

                <!-- Subscription Form -->
                <form action="#" method="post" style="display: flex;">
                    <input type="email" name="email" placeholder="Enter your email" style="padding: 10px; border: none; border-radius: 5px 0 0 5px; width: 70%;">
                    <input type="submit" value="Subscribe" style="background-color: #FF416C; color: #fff; padding: 10px 20px; border: none; border-radius: 0 5px 5px 0; cursor: pointer;">
                </form>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div style="border-top: 1px solid #555; padding-top: 20px; text-align: center;">
            <p style="font-size: 14px;">&copy; 2024 AdoptPaw. All rights reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>
