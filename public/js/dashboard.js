document.addEventListener("DOMContentLoaded", () => {
  //Sidebar
  const sidebar = document.getElementById("sidebar");
  const openSidebarBtn = document.getElementById("openSidebar");
  const closeSidebarBtn = document.getElementById("closeSidebar");

  //Přidání/editace
  const modal = document.getElementById("transactionModal");
  const openBtn = document.getElementById("addTransactionBtn");
  const closeBtn = document.getElementById("closeTransactionModal");
  const saveBtn = document.getElementById("saveTransBtn");
  const form = document.getElementById("transactionForm");

  //Mazání
  const deleteModal = document.getElementById("deleteModal");
  const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
  const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
  const closeDeleteModal = document.getElementById("closeDeleteModal");

  let isSubmitting = false;
  let transactionIdToDelete = null;

  //Sidebar
  openSidebarBtn.addEventListener("click", () =>
    sidebar.classList.add("active"),
  );
  closeSidebarBtn.addEventListener("click", () =>
    sidebar.classList.remove("active"),
  );

  window.addEventListener("click", (e) => {
    if (
      window.innerWidth <= 900 &&
      !sidebar.contains(e.target) &&
      e.target !== openSidebarBtn &&
      !openSidebarBtn.contains(e.target) &&
      sidebar.classList.contains("active")
    ) {
      sidebar.classList.remove("active");
    }
    if (e.target === modal) modal.style.display = "none";
    if (e.target === deleteModal) closeDelete();
  });

  //Reset formuláře
  const resetForm = () => {
    form.reset();
    document.getElementById("trans-id").value = "";
    document.getElementById("trans-date").valueAsDate = new Date();
    document.getElementById("err-transaction").textContent = "";

    saveBtn.disabled = false;
    saveBtn.textContent = "Uložit";
    isSubmitting = false;
  };

  openBtn.addEventListener("click", (e) => {
    e.preventDefault();
    resetForm();
    if (modal) modal.style.display = "flex";
  });

  closeBtn.addEventListener("click", () => {
    if (modal) modal.style.display = "none";
  });

  // Editace
  document.querySelectorAll(".btn-edit").forEach((button) => {
    button.addEventListener("click", (e) => {
      e.preventDefault();
      const btn = e.currentTarget;

      const id = btn.dataset.id;
      const type = btn.dataset.type;
      const amount = btn.dataset.amount;
      const category = btn.dataset.category;
      const date = btn.dataset.date;
      const description = btn.dataset.description;

      document.getElementById("trans-id").value = id;
      document.getElementById("trans-amount").value = amount;
      document.getElementById("trans-category").value = category;
      document.getElementById("trans-date").value = date;
      document.getElementById("trans-desc").value = description;

      const radios = document.getElementsByName("type");
      for (const r of radios) {
        if (r.value === type) r.checked = true;
      }

      saveBtn.textContent = "Aktualizovat";
      modal.style.display = "flex";
    });
  });

  //Přidání nebo Úprava
  saveBtn.addEventListener("click", async (e) => {
    e.preventDefault();
    if (isSubmitting) return;

    const typeInputs = document.getElementsByName("type");
    const amount = document.getElementById("trans-amount").value;
    const category = document.getElementById("trans-category").value;
    const date = document.getElementById("trans-date").value;
    const desc = document.getElementById("trans-desc").value;
    const transId = document.getElementById("trans-id").value;

    let selectedType = "vydaj";
    for (const radio of typeInputs) {
      if (radio.checked) {
        selectedType = radio.value;
        break;
      }
    }

    if (!amount || amount <= 0) {
      document.getElementById("err-transaction").textContent =
        "Zadejte platnou částku.";
      return;
    }

    isSubmitting = true;
    saveBtn.disabled = true;
    saveBtn.textContent = "Ukládám...";

    const data = {
      type: selectedType,
      amount: amount,
      category: category,
      date: date,
      description: desc,
    };

    let url = "./add_transaction.php";
    if (transId) {
      url = "./edit_transaction.php";
      data.id = transId;
    }

    try {
      const res = await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });

      const json = await res.json();

      if (json.success) {
        window.showToast(
          transId ? "Transakce aktualizována!" : "Transakce přidána!",
          "success",
        );
      } else {
        document.getElementById("err-transaction").textContent = json.message;
        window.showToast(json.message, "error");
        isSubmitting = false;
        saveBtn.disabled = false;
        saveBtn.textContent = transId ? "Aktualizovat" : "Uložit";
      }
    } catch (error) {
      console.error("Chyba:", error);
      const errMsg = "Chyba komunikace se serverem.";
      document.getElementById("err-transaction").textContent = errMsg;
      window.showToast(errMsg, "error");
      isSubmitting = false;
      saveBtn.disabled = false;
      saveBtn.textContent = transId ? "Aktualizovat" : "Uložit";
    }
  });

  //Mazání transakce
  const closeDelete = () => {
    if (deleteModal) deleteModal.style.display = "none";
    transactionIdToDelete = null;
  };

  if (closeDeleteModal) closeDeleteModal.addEventListener("click", closeDelete);
  if (cancelDeleteBtn) cancelDeleteBtn.addEventListener("click", closeDelete);

  document.querySelectorAll(".btn-delete").forEach((button) => {
    button.addEventListener("click", (e) => {
      e.preventDefault();
      const btn = e.currentTarget;
      transactionIdToDelete = btn.dataset.id;

      if (deleteModal) deleteModal.style.display = "flex";
    });
  });

  // Potvrzení smazání
  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener("click", async () => {
      if (!transactionIdToDelete) return;

      const originalText = confirmDeleteBtn.textContent;
      confirmDeleteBtn.textContent = "Mažu...";
      confirmDeleteBtn.disabled = true;

      try {
        const res = await fetch("./delete_transaction.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id: transactionIdToDelete }),
        });

        const json = await res.json();

        if (json.success) {
          window.showToast("Transakce smazána!", "success");
          closeDelete();

          setTimeout(() => {
            window.location.reload();
          }, 1000);
        } else {
          window.showToast("Chyba: " + json.message, "error");
          confirmDeleteBtn.textContent = originalText;
          confirmDeleteBtn.disabled = false;
        }
      } catch (error) {
        console.error("Chyba:", error);
        window.showToast("Chyba komunikace se serverem.", "error");
        confirmDeleteBtn.textContent = originalText;
        confirmDeleteBtn.disabled = false;
      }
    });
  }
});

// Toast
window.showToast = (message, type = "success") => {
  let container = document.getElementById("toast-container");

  if (!container) {
    container = document.createElement("div");
    container.id = "toast-container";
    document.body.appendChild(container);
  }

  const toast = document.createElement("div");
  toast.className = `toast ${type}`;

  let icon = '<i class="fas fa-check-circle"></i>';
  if (type === "error") {
    icon = '<i class="fas fa-exclamation-circle"></i>';
  }

  toast.innerHTML = `${icon} <span>${message}</span>`;
  container.appendChild(toast);

  setTimeout(() => {
    toast.classList.add("hide");
    toast.addEventListener("animationend", () => toast.remove());
  }, 3000);
};
