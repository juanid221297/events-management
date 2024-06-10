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
