<?php
session_start();
require_once "../includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$current_page = basename($_SERVER['PHP_SELF']);

// Výpočet celkových příjmů a výdajů
$stmt = $conn->prepare("
    SELECT 
        COALESCE(SUM(CASE WHEN typ = 'prijem' THEN castka ELSE 0 END), 0) as income,
        COALESCE(SUM(CASE WHEN typ = 'vydaj' THEN castka ELSE 0 END), 0) as expenses
    FROM transakce 
    WHERE uzivatel_id = ?
");
$stmt->execute([$user_id]);
$balance = $stmt->fetch(PDO::FETCH_ASSOC);

$total_income = $balance['income'];
$total_expenses = $balance['expenses'];
$total_balance = $total_income - $total_expenses;

$category_stmt = $conn->prepare("SELECT * FROM kategorie ORDER BY nazev");
$category_stmt->execute();
$category_list = $category_stmt->fetchAll(PDO::FETCH_ASSOC);

// Načtení posledních 10 transakcí
$transaction_stmt = $conn->prepare("
    SELECT t.*, k.nazev as cat_name, k.ikona 
    FROM transakce t
    LEFT JOIN kategorie k ON t.kategorie_id = k.id
    WHERE t.uzivatel_id = ?
    ORDER BY t.datum DESC, t.id DESC
    LIMIT 10
");
$transaction_stmt->execute([$user_id]);
$transactions_list = $transaction_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/dashboard.css">
    <link rel="stylesheet" href="./css/modal.css">
</head>

<body>
    <?php include "../includes/sidebar.php"; ?>
    <div class="app-layout">

        <main class="main-content">
            <header class="top-header">
                <button id="openSidebar" class="hamburger-btn"><i class="fas fa-bars"></i></button>
                <h1>Dashboard</h1>
                <div class="header-actions">
                    <button id="addTransactionBtn" class="btn btn-primary openTransaction">
                        <i class="fas fa-plus"></i> <span class="btn-text">Nová transakce</span>
                    </button>
                </div>
            </header>

            <div class="stats-grid">
                <div class="stat-card income">
                    <h3>Příjmy</h3>
                    <p class="amount">+ <?php echo number_format($total_income, 2, ',', ' '); ?> Kč</p>
                </div>
                <div class="stat-card expense">
                    <h3>Výdaje</h3>
                    <p class="amount">- <?php echo number_format($total_expenses, 2, ',', ' '); ?> Kč</p>
                </div>
                <div class="stat-card total">
                    <h3>Zůstatek</h3>
                    <p class="amount <?php echo $total_balance >= 0 ? 'positive' : 'negative'; ?>">
                        <?php echo number_format($total_balance, 2, ',', ' '); ?> Kč
                    </p>
                </div>
            </div>

            <div class="charts-section">
                <div class="chart-container main-chart">
                    <div class="chart-header">
                        <h3>Vývoj financí</h3>
                        <select class="chart-filter">
                            <option>Tento rok</option>
                            <option>Posledních 30 dní</option>
                        </select>
                    </div>
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-area"></i>
                        <p>Zde se brzy objeví graf vývoje vašich financí.</p>
                    </div>
                </div>

                <div class="chart-container side-chart">
                    <div class="chart-header">
                        <h3>Výdaje dle kategorií</h3>
                    </div>
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-pie"></i>
                        <p>Analýza kategorií se připravuje.</p>
                    </div>
                </div>
            </div>

            <div class="transactions-section">
                <div class="section-header">
                    <h2>Poslední transakce</h2>
                    <a href="transaction.php" class="view-all">Zobrazit vše</a>
                </div>

                <div id="transactions-output">
                    <?php if (count($transactions_list) > 0): ?>
                        <?php foreach ($transactions_list as $t): ?>
                            <div class="trans-item">
                                <div class="trans-icon-wrapper <?php echo $t['typ'] == 'prijem' ? 'income-bg' : 'expense-bg'; ?>">
                                    <i class="fas <?php echo htmlspecialchars($t['ikona'] ?? 'fa-question'); ?>"></i>
                                </div>

                                <div class="trans-info">
                                    <h4><?php echo htmlspecialchars($t['cat_name'] ?? 'Bez kategorie'); ?></h4>
                                    <span class="trans-date">
                                        <?php echo !empty($t['popis']) ? htmlspecialchars($t['popis']) : date('d.m.Y', strtotime($t['datum'])); ?>
                                    </span>
                                </div>

                                <div class="trans-amount <?php echo $t['typ'] == 'prijem' ? 'positive' : 'negative'; ?>">
                                    <?php echo $t['typ'] == 'prijem' ? '+' : '-'; ?>
                                    <?php echo number_format($t['castka'], 2, ',', ' '); ?> Kč
                                </div>

                                <div class="trans-actions">
                                    <button class="btn-icon btn-edit"
                                        data-id="<?php echo $t['id']; ?>"
                                        data-type="<?php echo $t['typ']; ?>"
                                        data-amount="<?php echo $t['castka']; ?>"
                                        data-category="<?php echo $t['kategorie_id']; ?>"
                                        data-date="<?php echo $t['datum']; ?>"
                                        data-description="<?php echo htmlspecialchars($t['popis']); ?>">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button class="btn-icon btn-delete" data-id="<?php echo $t['id']; ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-receipt"></i>
                            <p>Zatím žádné transakce.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <?php include "../includes/modal.php"; ?>
    <script src="./js/dashboard.js"></script>
    <script src="./js/profile.js"></script>
    <script src="./js/change_pass.js"></script>
</body>

</html>