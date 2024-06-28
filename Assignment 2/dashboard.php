<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Include database connection class
require_once 'dbconnect.php';

// Create instance of dbconnect class
$db = new dbconnect();
$services = $db->getServices(); // Fetch services from database
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <title>Dashboard</title>
</head>
<body>
    <div class="top-bar">
        <div class="top-bar-title">
            <h3>My Clinic</h3>
        </div>
        <div class="top-bar-logout">
            <form method="POST" action="logout.php">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>
    </div>

    <div class="content">
        <h3>Welcome to your dashboard, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h3>
        
        <div id="services-container"></div>

        <div id="service-modal" style="display:none;">
            <div class="modal-content">
                <span id="close-modal">&times;</span>
                <h2 id="modal-service-name"></h2>
                <p id="modal-service-description"></p>
                <p id="modal-service-price"></p>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; <?php echo date('Y'); ?> My Clinic. All rights reserved.</p>
    </footer>

    <div id="modal-overlay" style="display:none;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const services = <?php echo json_encode($services); ?>;
            const container = document.getElementById('services-container');
            
            services.forEach(service => {
                const card = document.createElement('div');
                card.className = 'service-card';
                let imageSrc;
                
                // Set image source based on service name
                switch (service.service_name) {
                    case 'General Checkup':
                        imageSrc = '<?php echo base64_encode(file_get_contents('generalcheckup.jpg')); ?>';
                        break;
                    case 'Dental Cleaning':
                        imageSrc = '<?php echo base64_encode(file_get_contents('dentalcleaning.jpg')); ?>';
                        break;
                    case 'Vision Test':
                        imageSrc = '<?php echo base64_encode(file_get_contents('visiontest.jpg')); ?>';
                        break;
                    default:
                        imageSrc = '';
                }

                card.innerHTML = `
                    <img src="data:image/jpeg;base64,${imageSrc}" alt="${service.service_name}" class="service-image">
                    <h4>${service.service_name}</h4>
                    <button class="detail-button">Details</button>
                `;
                card.addEventListener('click', () => {
                    document.getElementById('modal-service-name').innerText = service.service_name;
                    document.getElementById('modal-service-description').innerText = service.service_description;
                    document.getElementById('modal-service-price').innerText = 'Price: $' + service.service_price;
                    document.getElementById('service-modal').style.display = 'flex';
                    document.getElementById('modal-overlay').style.display = 'block';
                });
                container.appendChild(card);
            });

            document.getElementById('close-modal').addEventListener('click', () => {
                document.getElementById('service-modal').style.display = 'none';
                document.getElementById('modal-overlay').style.display = 'none';
            });

            // Event listener for detail buttons
            const detailButtons = document.querySelectorAll('.detail-button');
            detailButtons.forEach((button, index) => {
                button.addEventListener('click', () => {
                    // Display modal with service details based on index
                    document.getElementById('modal-service-name').innerText = services[index].service_name;
                    document.getElementById('modal-service-description').innerText = services[index].service_description;
                    document.getElementById('modal-service-price').innerText = 'Price: $' + services[index].service_price;
                    document.getElementById('service-modal').style.display = 'flex';
                    document.getElementById('modal-overlay').style.display = 'block';
                });
            });
        });
    </script>
</body>
</html>
