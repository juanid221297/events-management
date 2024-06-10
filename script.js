document.getElementById('userType').addEventListener('change', function () {
    const userType = this.value;
    const videoUpload = document.getElementById('videoUpload');

    if (userType === 'featured') {
        videoUpload.style.display = 'block';
    } else {
        videoUpload.style.display = 'none';
    }
});

document.getElementById('eventForm').addEventListener('submit', function (e) {
    const userType = document.getElementById('userType').value;
    const pictureFiles = document.getElementById('eventPictures').files;
    const videoFile = document.getElementById('eventVideo').files[0];
    const maxVideoSize = 100 * 1024 * 1024; // 100MB in bytes

    if (userType === 'free' && pictureFiles.length > 10) {
        alert('Free users can upload a maximum of 10 pictures.');
        e.preventDefault();
    } else if (userType === 'featured' && pictureFiles.length > 100) {
        alert('Featured users can upload a maximum of 100 pictures.');
        e.preventDefault();
    }

    if (userType === 'featured' && videoFile && videoFile.size > maxVideoSize) {
        alert('Video file exceeds the maximum allowed size of 100MB.');
        e.preventDefault();
    }
});


// script.js

const cityLocations = {
    "Karachi": ["Clifton", "Gulshan", "Defence", "Saddar"],
    "Lahore": ["Gulberg", "Johar Town", "DHA", "Model Town"],
    "Islamabad": ["F-8", "F-10", "Blue Area", "G-11"]
    // Add more cities and locations as needed
};

document.getElementById('city').addEventListener('change', function () {
    const selectedCity = this.value;
    const locationDropdown = document.getElementById('location');
    locationDropdown.innerHTML = '<option value="">Select Location</option>';
    if (selectedCity !== '') {
        const locations = cityLocations[selectedCity];
        locations.forEach(location => {
            const option = document.createElement('option');
            option.value = location;
            option.textContent = location;
            locationDropdown.appendChild(option);
        });
        locationDropdown.disabled = false;
    } else {
        locationDropdown.disabled = true;
    }
});



// JavaScript
document.getElementById('userType').addEventListener('change', function () {
    const selectedUserType = this.value;
    const paymentMessage = document.getElementById('paymentMessage');
    if (selectedUserType === 'featured') {
        paymentMessage.style.display = 'block';
    } else {
        paymentMessage.style.display = 'none';
    }
});
