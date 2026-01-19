<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">Cashly</div>
        <button id="closeSidebar" class="close-sidebar-btn"><i class="fas fa-times"></i></button>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li class="<?php echo ($current_page == 'dashboard.php' || $current_page == 'index.php') ? 'active' : ''; ?>">
                <a href="dashboard.php"><i class="fas fa-columns"></i>Přehled</a>
            </li>

            <li class="<?php echo ($current_page == 'transaction.php') ? 'active' : ''; ?>">
                <a href="transaction.php"><i class="fas fa-list"></i>Transakce</a>
            </li>

            <li>
                <a href="#"><i class="fas fa-chart-pie"></i>Rozpočty</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-chart-line"></i>Reporty</a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar" id="openProfileBtn">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-details">
                <span class="user-name"><?php echo htmlspecialchars($username); ?></span>
                <a href="logout.php" class="logout-link">Odhlásit se</a>
            </div>
        </div>
    </div>
</aside>