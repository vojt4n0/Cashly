<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/modal.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Cashly</title>
</head>

<body>

    <main>
        <?php include_once "../includes/header.php"; ?>
        <section class="hero">
            <div class="container hero-inner">
                <div class="hero-text">
                    <h1>Sledujte své finance efektivně</h1>
                    <p>Importujte své transakce, nastavte rozpočty a získejte přehledné grafy – vše na jednom místě.</p>
                    <div class="hero-buttons">
                        <a href="#" class="btn btn-primary openRegister">Začít zdarma</a>
                        <a href="#" class="btn btn-outline openLogin">Přihlásit se</a>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="img-placeholder">Screenshot aplikace</div>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <h2>Funkce aplikace</h2>
                <div class="features-grid">
                    <div class="feature-item">
                        <h3>Sledování transakcí</h3>
                        <p>Přidávejte, tříděte a vyhodnocujte všechny své výdaje i příjmy.</p>
                    </div>
                    <div class="feature-item">
                        <h3>Analýza výdajů</h3>
                        <p>Grafy a přehledy vám ukáží vaše finanční návyky a trendy.</p>
                    </div>
                    <div class="feature-item">
                        <h3>Měsíční rozpočty</h3>
                        <p>Nastavte limit pro jednotlivé kategorie a zůstaňte v rozpočtu.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="process">
            <div class="container">
                <h2>Jak to funguje</h2>
                <div class="process-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h4>Připojte nebo přidejte transakce</h4>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <h4>Sledujte výdaje a analyzujte</h4>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <h4>Nastavte rozpočet a cíle</h4>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta">
            <div class="container">
                <h2>Připraveni mít své finance pod kontrolou?</h2>
                <a href="#" class="btn btn-primary openRegister">Vytvořit účet</a>
            </div>
        </section>
        <div class="background-decor">

            <div class="shape shape-orange shape-large shape1"></div>
            <div class="shape shape-yellow shape-medium shape2"></div>
            <div class="shape shape-soft shape-large shape3"></div>
            <div class="shape shape-red shape-small shape4"></div>

        </div>


    </main>

    <?php include_once "../includes/footer.php"; ?>
</body>

<?php include "../includes/modal.php"; ?>
<script src="./js/register.js"></script>
<script src="./js/login.js"></script>

</html>