document.getElementById("Forminscription").addEventListener("submit", async function (e) {
  e.preventDefault();

  const formData = new FormData(e.target);
  const data = Object.fromEntries(formData.entries());
  
  const birthDate = new Date(data.date_naissance);
  const today = new Date();
  const age = today.getFullYear() - birthDate.getFullYear();
  if (today < new Date(birthDate.setFullYear(birthDate.getFullYear() + age))) {
    age--;
  }
  
  if (age < 13) {
    alert("Vous devez avoir au moins 13 ans pour vous inscrire.");
    return;
  }
  try {
    const response = await fetch("../Controllers/RegisterController.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();
    if (response.ok) {
      alert("Inscription réussie !");
    } else {
      alert("Erreur : " + result.message);
    }
  } catch (error) {
    alert("Une erreur réseau s'est produite.");
  }
});