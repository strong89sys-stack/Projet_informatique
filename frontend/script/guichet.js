const video = document.getElementById("camera");
const canvas = document.getElementById("snapshot");
const captureBtn = document.getElementById("capture");

// Demande accès à la caméra
navigator.mediaDevices.getUserMedia({ video: true })
  .then(stream => {
    video.srcObject = stream;
  })
  .catch(err => {
    console.error("Erreur caméra :", err);
  });

// Capture une image
captureBtn.addEventListener("click", () => {
  const ctx = canvas.getContext("2d");
  ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

  // Convertir en base64 pour envoi au serveur
  const imageData = canvas.toDataURL("image/png");
  console.log(imageData);
});