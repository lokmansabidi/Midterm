document.addEventListener('DOMContentLoaded', function() {
    fetch('services.php')
        .then(response => response.json())
        .then(services => {
            const container = document.getElementById('services-container');
            services.forEach(service => {
                const card = document.createElement('div');
                card.className = 'service-card';
                card.innerHTML = `
                    <img src="images/${service.service_image}" alt="${service.service_name}" class="service-image">
                    <h4>${service.service_name}</h4>
                    <button class="detail-button">Details</button>
                `;
                card.addEventListener('click', () => {
                    document.getElementById('modal-service-name').innerText = service.service_name;
                    document.getElementById('modal-service-description').innerText = service.service_description;
                    document.getElementById('modal-service-price').innerText = 'Price: $' + service.service_price;
                    document.getElementById('service-modal').style.display = 'flex';
                });
                container.appendChild(card);
            });
        });

    document.getElementById('close-modal').addEventListener('click', () => {
        document.getElementById('service-modal').style.display = 'none';
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
        });
    });
});
