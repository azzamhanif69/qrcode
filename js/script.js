document
  .getElementById("codeForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    const name = document.getElementById("name").value;
    const contact = document.getElementById("contact").value;
    const address = document.getElementById("address").value;
    const health = document.getElementById("health").value;
    const allergy = document.getElementById("allergy").value;
    const photo = document.getElementById("photo").files[0];
    const formData = new FormData();
    formData.append("photo", photo);
    formData.append("name", name);
    formData.append("contact", contact);
    formData.append("address", address);
    formData.append("health", health);
    formData.append("allergy", allergy);

    fetch("upload.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          const qrData = `${location.origin}/profile.php?id=${data.id}`;

          const output = document.getElementById("output");
          output.innerHTML = "";

          const qrCode = new QRCodeStyling({
            width: 300,
            height: 300,
            data: qrData,
            dotsOptions: {
              color: "#000",
              type: "rounded",
            },
            cornersSquareOptions: {
              type: "extra-rounded",
            },
            cornersDotOptions: {
              type: "dot",
            },
          });

          const qrCodeContainer = document.createElement("div");
          qrCodeContainer.id = "qrcode";
          output.appendChild(qrCodeContainer);

          qrCode.append(qrCodeContainer);

          qrCode.getRawData("png").then(function (buffer) {
            const blob = new Blob([buffer], { type: "image/png" });
            const url = URL.createObjectURL(blob);
            const downloadLink = document.createElement("a");
            downloadLink.href = url;
            downloadLink.download = "qrcode.png";
            downloadLink.textContent = "Download QR Code";
            downloadLink.className = "btn btn-primary mt-3";
            output.appendChild(downloadLink);
          });
        } else {
          alert("Upload failed");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });
