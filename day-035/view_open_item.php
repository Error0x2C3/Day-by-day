<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un article</title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/open_item.css">
</head>
<style>
    /* --- 1. CONFIGURATION DE BASE (Couleurs & Reset) --- */
    :root {
        --bg-body: #0f111a;       /* Fond de page très sombre */
        --bg-card: #1e2130;       /* Fond des blocs */
        --text-main: #ffffff;
        --text-muted: #a0a0a0;
        --accent-green: #10b981;  /* Vert pour les prix/boutons */
        --border-color: #2d3748;
    }

    body {
        background-color: var(--bg-body);
        color: var(--text-main);
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 20px;
    }

    /* --- 2. STRUCTURE PRINCIPALE (La Grille) --- */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        /* La magie opère ici : 2/3 à gauche, 1/3 à droite */
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    /* Responsive : 1 seule colonne sur mobile */
    @media (max-width: 768px) {
        .container { grid-template-columns: 1fr; }
    }

    /* --- 3. STYLE DES CARTES (Les blocs gris) --- */
    .card {
        background-color: var(--bg-card);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px; /* Espace sous chaque carte */
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    }

    /* --- 4. DETAILS SPECIFIQUES (Gauche) --- */
    .product-image {
        width: 100%;
        background-color: #fff; /* Fond blanc pour l'image du piano */
        border-radius: 8px 8px 0 0;
        padding: 20px;
        box-sizing: border-box;
        display: flex;
        justify-content: center;
    }

    .product-image img {
        max-width: 100%;
        height: auto;
    }

    .product-info {
        /* Partie texte sous l'image */
        padding: 20px;
        background-color: var(--bg-card);
        border-radius: 0 0 8px 8px;
        margin-bottom: 24px;
    }

    .title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .badge {
        background-color: #4b5563;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        text-transform: uppercase;
    }

    /* Galerie miniatures */
    .gallery-grid {
        display: flex;
        gap: 10px;
    }
    .thumb {
        width: 100px;
        height: 60px;
        background: #fff;
        border: 2px solid #007bff; /* Bordure bleue active */
        border-radius: 4px;
    }

    /* Historique des enchères */
    .history-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
    }
    .history-row:last-child { border-bottom: none; }
    .price-green { color: var(--accent-green); font-weight: bold; }

    /* --- 5. DETAILS SPECIFIQUES (Droite - Sidebar) --- */
    .price-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .input-group {
        display: flex;
        background: #0f111a;
        border: 1px solid var(--border-color);
        padding: 10px;
        border-radius: 6px;
        margin: 15px 0;
    }

    .btn {
        width: 100%;
        padding: 12px;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        margin-bottom: 10px;
        border: none;
    }

    .btn-primary { background-color: var(--accent-green); color: white; }
    .btn-outline { background-color: transparent; border: 1px solid #4a5568; color: white; }

    .seller-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .avatar {
        width: 40px; height: 40px;
        background-color: #6b7280;
        border-radius: 50%;
    }

</style>
</head>
<body>

<div class="container">

    <main>

        <article>
            <div class="product-image">
                <img src="https://via.placeholder.com/600x300?text=Piano+Yamaha" alt="Yamaha P-45">
            </div>

            <div class="product-info">
                <div class="title-row">
                    <h1>Piano numérique Yamaha P-45</h1>
                    <span class="badge">Auction</span>
                </div>
                <p style="color: var(--text-muted);">Touches lestées, pédale incluse.</p>

                <div style="margin-top: 15px; font-size: 0.9rem; color: var(--text-muted);">
                    <div>Start: <span style="color: #fff">10/11/2025 04:16:04</span></div>
                    <div>Ends: <span style="color: #fff">17/11/2025 04:16:04</span></div>
                </div>
            </div>
        </article>

        <section>
            <h3 style="color: var(--text-muted); font-size: 1rem;">Additional Images</h3>
            <div class="gallery-grid">
                <div class="thumb"></div> <div class="thumb" style="border-color: transparent;"></div> </div>
        </section>

        <section class="card" style="margin-top: 24px;">
            <div class="title-row" style="margin-bottom: 15px;">
                <h3>Bid History</h3>
                <span class="badge" style="background: white; color: black;">3 entries</span>
            </div>

            <div class="history-list">
                <div class="history-row">
                    <div>
                        <strong>Boris</strong><br>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">11/11/2025 21:55:03</span>
                    </div>
                    <div class="price-green">€ 229,00</div>
                </div>

                <div class="history-row">
                    <div>
                        <strong>Marc</strong><br>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">11/11/2025 10:25:18</span>
                    </div>
                    <div class="price-green">€ 214,00</div>
                </div>

                <div class="history-row">
                    <div>
                        <strong>Marc</strong><br>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">10/11/2025 20:36:50</span>
                    </div>
                    <div class="price-green">€ 198,00</div>
                </div>
            </div>
        </section>

    </main>

    <aside>

        <div class="card">
            <h3 style="margin-top: 0; color: var(--text-muted);">Pricing</h3>

            <div class="price-line">
                <span>Current Bid</span>
                <span class="price-green">€ 229,00</span>
            </div>
            <div class="price-line">
                <span>Buy Now</span>
                <strong>€ 385,00</strong>
            </div>

            <div class="input-group">
                <span style="color: var(--text-muted); padding-right: 10px;">€</span>
                <input type="number" value="230" style="background: transparent; border: none; color: white; width: 100%;">
            </div>

            <button class="btn btn-primary">Place Bid</button>
            <button class="btn btn-outline">Buy Now at € 385,00</button>
        </div>

        <div class="card">
            <h3 style="margin-top: 0; color: var(--text-muted); font-size: 1rem;">Seller Information</h3>
            <div class="seller-row">
                <div class="avatar"></div>
                <div>
                    <div style="font-weight: bold;">Xavier</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Member</div>
                </div>
            </div>
        </div>

    </aside>

</div>

</body>
</html>
