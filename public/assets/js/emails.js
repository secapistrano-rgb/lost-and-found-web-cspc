fetch(`/emails/details/${id}`, {
    credentials: 'same-origin'
})
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        if (data.status === 'success') {
            // Handle successful response
            displayEmailDetails(data.data);
        } else {
            throw new Error(data.message || 'Unknown error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error loading email details: ' + error.message);
    });