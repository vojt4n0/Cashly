document.addEventListener("DOMContentLoaded", () => {
  const passModal = document.getElementById("changePasswordModal");
  const openPassBtn = document.getElementById("openChangePassBtn");
  const closePassBtn = document.getElementById("closeChangePassModal");
  const savePassBtn = document.getElementById("savePassBtn");
  const passForm = document.getElementById("changePasswordForm");

  //Otevření modálního okna
  openPassBtn.addEventListener("click", () => {
    if (passModal) passModal.style.display = "flex";

    if (passForm) passForm.reset();
    document.getElementById("err-change-pass").textContent = "";
  });

  //Zavření okna
  closePassBtn.addEventListener("click", () => {
    if (passModal) passModal.style.display = "none";
  });

  //Zavření okna při kliknutí mimo něj
  window.addEventListener("click", (e) => {
    if (passModal && e.target === passModal) {
      passModal.style.display = "none";
    }
  });

  //Odeslání formuláře
  savePassBtn.addEventListener("click", async () => {
    const oldPass = document.getElementById("old-pass").value;
    const newPass = document.getElementById("new-pass").value;
    const confirmPass = document.getElementById("confirm-pass").value;
    const errEl = document.getElementById("err-change-pass");

    if (!oldPass || !newPass || !confirmPass) {
      errEl.textContent = "Vyplňte všechna pole.";
      return;
    }
    if (newPass !== confirmPass) {
      errEl.textContent = "Nová hesla se neshodují.";
      return;
    }

    savePassBtn.disabled = true;
    savePassBtn.textContent = "Ukládám...";

    try {
      const res = await fetch("./change_pass.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          old_password: oldPass,
          new_password: newPass,
          confirm_password: confirmPass,
        }),
      });

      const json = await res.json();

      if (json.success) {
        window.showToast("Heslo bylo změněno!");
        passModal.style.display = "none";
        savePassBtn.disabled = false;
        savePassBtn.textContent = "Uložit nové heslo";
      } else {
        errEl.textContent = json.message;
        savePassBtn.disabled = false;
        savePassBtn.textContent = "Uložit nové heslo";
      }
    } catch (error) {
      console.error(error);
      errEl.textContent = "Chyba spojení.";
      savePassBtn.disabled = false;
      savePassBtn.textContent = "Uložit nové heslo";
    }
  });
});
