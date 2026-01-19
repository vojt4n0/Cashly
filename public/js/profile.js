document.addEventListener("DOMContentLoaded", () => {
  const profileModal = document.getElementById("profileModal");
  const openProfileBtn = document.getElementById("openProfileBtn");
  const closeProfileBtn = document.getElementById("closeProfileModal");
  const profileEmailEl = document.getElementById("profile-email");
  const profileDateEl = document.getElementById("profile-date");
  const profileCountEl = document.getElementById("profile-count");

  let isDataLoaded = false;

  openProfileBtn.addEventListener("click", async () => {
    if (profileModal) profileModal.style.display = "flex";

    if (isDataLoaded) return;

    try {
      const res = await fetch("./profile.php");
      const data = await res.json();

      if (data.success) {
        if (profileEmailEl) profileEmailEl.textContent = data.email;

        if (profileDateEl) profileDateEl.textContent = data.date;

        if (profileCountEl && data.transaction_count !== undefined) {
          profileCountEl.textContent = data.transaction_count;
        }

        isDataLoaded = true;
      } else {
        console.error("Chyba dat:", data.message);
        if (profileEmailEl) {
          profileEmailEl.textContent = "Chyba načítání";
        }
      }
    } catch (error) {
      console.error("Chyba sítě:", error);
      if (profileEmailEl) {
        profileEmailEl.textContent = "Chyba spojení";
      }
    }
  });

  closeProfileBtn.addEventListener("click", () => {
    if (profileModal) profileModal.style.display = "none";
  });

  window.addEventListener("click", (e) => {
    if (profileModal && e.target === profileModal) {
      profileModal.style.display = "none";
    }
  });
});
