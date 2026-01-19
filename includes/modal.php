<div id="transactionModal" class="modal">
    <div class="modal-content">
        <span id="closeTransactionModal" class="close">&times;</span>
        <h2>Přidat transakci</h2>
        <form id="transactionForm">
            <div class="radio-group">
                <label><input type="radio" name="type" value="vydaj" checked> Výdaj</label>
                <label><input type="radio" name="type" value="prijem"> Příjem</label>
            </div>
            <input type="number" id="trans-amount" step="0.01" placeholder="Částka (Kč)" required>
            <select id="trans-category">
                <option value="" disabled selected>Vyberte kategorii</option>
                <?php foreach ($category_list as $cat): ?> <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nazev']); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="date" id="trans-date" value="<?php echo date('Y-m-d'); ?>" required>
            <input type="text" id="trans-desc" placeholder="Popis (volitelné)">
            <input type="hidden" id="trans-id" value="">
            <small class="error-msg" id="err-transaction"></small>
            <button type="button" id="saveTransBtn" class="btn btn-primary">Uložit</button>
        </form>
    </div>
</div>
<div id="profileModal" class="modal">
    <div class="modal-content profile-content">
        <span id="closeProfileModal" class="close">&times;</span>

        <div class="profile-avatar-large">
            <i class="fas fa-user"></i>
        </div>

        <h2 class="profile-name"><?php echo htmlspecialchars($username); ?></h2>
        <p id="profile-email" class="profile-email">Načítám...</p>

        <div class="profile-details-box">
            <p>
                <i class="fas fa-calendar-alt icon-orange"></i>
                Členem od: <strong id="profile-date">...</strong>
            </p>
            <p>
                <i class="fas fa-list-ol icon-orange"></i>
                Transakcí: <strong id="profile-count">...</strong>
            </p>
        </div>

        <a href="./logout.php" class="btn btn-primary btn-logout-modal">
            <i class="fas fa-sign-out-alt"></i> Odhlásit se
        </a>
        <br><br>
        <button id="openChangePassBtn" class="btn btn-primary btn-change-pass-modal">
            <i class="fas fa-key"></i> Změnit heslo
        </button>
    </div>
</div>

<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <span id="closeChangePassModal" class="close">&times;</span>
        <h2>Změna hesla</h2>

        <form id="changePasswordForm">
            <input type="password" id="old-pass" placeholder="Současné heslo" required>
            <input type="password" id="new-pass" placeholder="Nové heslo" required>
            <input type="password" id="confirm-pass" placeholder="Potvrzení hesla" required>

            <small class="error-msg" id="err-change-pass"></small>

            <button type="button" id="savePassBtn" class="btn btn-primary btn-save-pass">
                Uložit nové heslo
            </button>
        </form>
    </div>
</div>

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span id="closeDeleteModal" class="close">&times;</span>
        <h2>Smazat transakci?</h2>
        <p>Opravdu chcete smazat tuto transakci? Tato akce je nevratná.</p>

        <div class="modal-buttons">
            <button id="cancelDeleteBtn" class="btn btn-secondary">Zrušit</button>
            <button id="confirmDeleteBtn" class="btn btn-danger">Smazat</button>
        </div>
    </div>
</div>

<div id="registerModal" class="modal">
    <div class="modal-content">
        <span id="closeRegisterModal" class="close">&times;</span>

        <h2>Vytvořit účet</h2>

        <form id="registerForm">

            <input id="reg-username" type="text" name="username" placeholder="Uživatelské jméno">
            <small class="error-msg" id="err-username"></small>

            <input id="reg-email" type="email" name="email" placeholder="E-mail">
            <small class="error-msg" id="err-email"></small>

            <input id="reg-password" type="password" name="password" placeholder="Heslo">
            <small class="error-msg" id="err-password"></small>

            <button type="button" id="regBtn" class="btn btn-primary">Registrovat</button>
        </form>

    </div>
</div>

<div id="loginModal" class="modal">
    <div class="modal-content">
        <span id="closeLoginModal" class="close">&times;</span>
        <h2>Přihlásit se</h2>

        <form id="loginForm">
            <input id="log-email" type="email" name="email" placeholder="E-mail">
            <small class="error-msg" id="err-log-email"></small>

            <input id="log-password" type="password" name="password" placeholder="Heslo">
            <small class="error-msg" id="err-log-password"></small>

            <button type="button" id="logBtn" class="btn btn-primary">Přihlásit se</button>
        </form>
    </div>
</div>

<div id="toast-container"></div>