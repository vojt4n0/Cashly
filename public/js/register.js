document.addEventListener("DOMContentLoaded", () => {
  const registerModal = document.getElementById("registerModal");
  const openRegisterButtons = document.querySelectorAll(".openRegister");
  const closeRegisterBtn = document.getElementById("closeRegisterModal");
  const registerForm = document.getElementById("registerForm");
  const registerSubmitBtn = document.getElementById("regBtn");

  let isSubmitting = false;

  const resetForm = () => {
    if (registerForm) registerForm.reset();
    document
      .querySelectorAll(".error-msg")
      .forEach((el) => (el.textContent = ""));

    registerSubmitBtn.disabled = false;
    registerSubmitBtn.textContent = "Registrovat";
    isSubmitting = false;
  };

  openRegisterButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      resetForm();
      if (registerModal) registerModal.style.display = "flex";
    });
  });

  closeRegisterBtn.addEventListener("click", () => {
    if (registerModal) registerModal.style.display = "none";
  });

  registerSubmitBtn.addEventListener("click", async (e) => {
    e.preventDefault();

    if (isSubmitting) return;

    isSubmitting = true;
    registerSubmitBtn.disabled = true;
    registerSubmitBtn.textContent = "Odesílám...";

    document
      .querySelectorAll(".error-msg")
      .forEach((el) => (el.textContent = ""));

    const data = {
      username: document.getElementById("reg-username").value,
      email: document.getElementById("reg-email").value,
      password: document.getElementById("reg-password").value,
    };

    try {
      const res = await fetch("./registration.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });

      const json = await res.json();

      if (!json.success) {
        const errorElement = document.getElementById("err-" + json.field);
        if (errorElement) errorElement.textContent = json.message;

        isSubmitting = false;
        registerSubmitBtn.disabled = false;
        registerSubmitBtn.textContent = "Registrovat";
      } else {
        registerSubmitBtn.textContent = "Hotovo...";
        window.location.href = "./dashboard.php";
      }
    } catch (error) {
      console.error("Chyba:", error);
      isSubmitting = false;
      registerSubmitBtn.disabled = false;
      registerSubmitBtn.textContent = "Registrovat";
    }
  });
});
