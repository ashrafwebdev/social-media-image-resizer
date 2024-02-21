//   script.js
  // JavaScript to select or deselect all checkboxes
   document.getElementById("select-all").addEventListener("change", function() {
    var checkboxes = document.getElementsByClassName("size-checkbox");
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = this.checked;
    }
});

document.getElementById('uploadForm').addEventListener('submit', function(event) {
    var fileInput = document.getElementById('image');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

    if (!allowedExtensions.exec(filePath)) {
        alert('Invalid file type. Please upload an image with .jpg, .jpeg, .png, or .gif extension.');
        event.preventDefault(); // Prevent form submission
    }
});