/*links.forEach(link => {
    // Set the active class based on the current URL
    if (link.href === window.location.href) {
        link.classList.add('active');
    }

    link.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default link behavior

        // Remove active class from all links
        links.forEach(l => l.classList.remove('active'));

        // Add active class to the clicked link
        this.classList.add('active');
        
        // Fetch and display content as before
        const url = this.getAttribute('href');
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                document.getElementById('content').innerHTML = data;
            })
            .catch(error => {
                console.error('There has been a problem with your fetch operation:', error);
                document.getElementById('content').innerHTML = `<p>Error loading content. Please try again later.</p>`;
            });
    });
});
*/