document
  .getElementById("fileInput")
  .addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById(
          "imagePreview"
        ).style.backgroundImage = `url("${e.target.result}")`;
        document.getElementById("imagePreview").style.backgroundSize = "cover";
        document.getElementById("imagePreview").style.backgroundPosition =
          "center";
      };
      reader.readAsDataURL(file);
    }
  });
