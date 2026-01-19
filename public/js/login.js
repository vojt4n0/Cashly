document.addEventListener("DOMContentLoaded", () => {
  const loginModal = document.getElementById("loginModal");
  const openLoginButtons = document.querySelectorAll(".openLogin");
  const closeLoginBtn = document.getElementById("closeLoginModal");
  const loginForm = document.getElementById("loginForm");
  const loginSubmitBtn = document.getElementById("logBtn");

  let isSubmitting = false;

  //Vyčištění formuláře
  const resetForm = () => {
    if (loginForm) loginForm.reset();
    document
      .querySelectorAll(".error-msg")
      .forEach((el) => (el.textContent = ""));

    loginSubmitBtn.disabled = false;
    loginSubmitBtn.textContent = "Přihlásit se";
    isSubmitting = false;
  };

  //Otevírání modálního okna
  openLoginButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      resetForm();
      if (loginModal) loginModal.style.display = "flex";
    });
  });

  closeLoginBtn.addEventListener("click", () => {
    if (loginModal) loginModal.style.display = "none";
  });

  loginSubmitBtn.addEventListener("click", async (e) => {
    e.preventDefault();

    if (isSubmitting) return;

    isSubmitting = true;
    loginSubmitBtn.disabled = true;
    loginSubmitBtn.textContent = "Ověřuji...";

    document
      .querySelectorAll(".error-msg")
      .forEach((el) => (el.textContent = ""));

    const data = {
      email: document.getElementById("log-email").value,
      password: document.getElementById("log-password").value,
    };

    try {
      const res = await fetch("./login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });

      const json = await res.json();

      if (!json.success) {
        const errorElement = document.getElementById("err-log-" + json.field);
        if (errorElement) errorElement.textContent = json.message;

        isSubmitting = false;
        loginSubmitBtn.disabled = false;
        loginSubmitBtn.textContent = "Přihlásit se";
      } else {
        loginSubmitBtn.textContent = "Vítejte...";
        window.location.href = "./dashboard.php";
      }
    } catch (error) {
      console.error("Chyba:", error);
      isSubmitting = false;
      loginSubmitBtn.disabled = false;
      loginSubmitBtn.textContent = "Přihlásit se";
    }
  });
});
