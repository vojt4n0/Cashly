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

$category_stmt = $conn->prepare("SELECT * FROM kategorie ORDER BY nazev");
$category_stmt->execute();
$category_list = $category_stmt->fetchAll(PDO::FETCH_ASSOC);

//Filtrování

$sql = "SELECT t.*, k.nazev as cat_name, k.ikona 
        FROM transakce t
        LEFT JOIN kategorie k ON t.kategorie_id = k.id
        WHERE t.uzivatel_id = ?";

$params = [$user_id];

$period = $_GET['period'] ?? 'all';

if ($period == 'current') {
    $sql .= " AND YEAR(t.datum) = YEAR(CURRENT_DATE()) AND MONTH(t.datum) = MONTH(CURRENT_DATE())";
} elseif ($period == 'last') {
    $sql .= " AND t.datum >= DATE_SUB(DATE_FORMAT(NOW() ,'%Y-%m-01'), INTERVAL 1 MONTH) 
              AND t.datum < DATE_FORMAT(NOW() ,'%Y-%m-01')";
}

if (!empty($_GET['category'])) {
    $sql .= " AND t.kategorie_id = ?";
    $params[] = $_GET['category'];
}

$sql .= " ORDER BY t.datum DESC, t.id DESC";

$trans_stmt = $conn->prepare($sql);
$trans_stmt->execute($params);
$transactions_list = $trans_stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <h1>Transakce</h1>
                <div class="header-actions">
                    <button id="addTransactionBtn" class="btn btn-primary openTransaction">
                        <i class="fas fa-plus"></i> <span class="btn-text">Nová transakce</span>
                    </button>
                </div>
            </header>

            <div class="transactions-section">
                <div class="section-header">
                    <h2>Vaše transakce</h2>

                    <form method="GET" action="transaction.php" class="filter-bar">

                        <div class="filter-group">
                            <select name="period" class="filter-select" onchange="this.form.submit()">
                                <option value="current" <?php echo (isset($_GET['period']) && $_GET['period'] == 'current') ? 'selected' : ''; ?>>Tento měsíc</option>
                                <option value="last" <?php echo (isset($_GET['period']) && $_GET['period'] == 'last') ? 'selected' : ''; ?>>Minulý měsíc</option>
                                <option value="all" <?php echo (!isset($_GET['period']) || $_GET['period'] == 'all') ? 'selected' : ''; ?>>Všechno</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <select name="category" class="filter-select" onchange="this.form.submit()">
                                <option value="">Všechny kategorie</option>
                                <?php foreach ($category_list as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"
                                        <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['nazev']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php if (!empty($_GET['category']) || (isset($_GET['period']) && $_GET['period'] != 'all')): ?>
                            <a href="transaction.php" class="filter-reset" title="Zrušit filtry"><i class="fas fa-times"></i></a>
                        <?php endif; ?>
                    </form>
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