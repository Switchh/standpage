<?php
/**
 * Page d'attente générique avec compteur simple.
 * À placer à la racine du site sous le nom : index.php
 */

/**
 * Détection HTTPS, y compris derrière proxy / CDN.
 */
$isHttps =
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (!empty($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
    || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) === 'on')
    || (!empty($_SERVER['HTTP_CF_VISITOR']) && strpos($_SERVER['HTTP_CF_VISITOR'], '"scheme":"https"') !== false);

if ($isHttps) {
    $_SERVER['HTTPS'] = 'on';
}

/**
 * Redirection HTTP vers HTTPS.
 * À désactiver si ton hébergeur / Cloudflare force déjà le HTTPS.
 */
if (!$isHttps && !empty($_SERVER['HTTP_HOST']) && !empty($_SERVER['REQUEST_URI'])) {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301);
    exit;
}

/**
 * Petit compteur de vues sans base de données.
 */
$counterFile = __DIR__ . '/counter.txt';

if (!file_exists($counterFile)) {
    file_put_contents($counterFile, '0');
}

$count = 0;
$fp = fopen($counterFile, 'c+');

if ($fp) {
    flock($fp, LOCK_EX);

    $content = trim(stream_get_contents($fp));
    $count = is_numeric($content) ? (int) $content : 0;
    $count++;

    rewind($fp);
    ftruncate($fp, 0);
    fwrite($fp, (string) $count);

    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">

    <title>Site en préparation</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Ce site est actuellement en préparation.">

    <style>
        :root {
            --bg: #fff8ef;
            --card: #ffffff;
            --text: #263238;
            --muted: #667085;
            --accent: #f59e0b;
            --accent-dark: #d97706;
            --border: rgba(38, 50, 56, 0.08);
            --shadow: 0 24px 70px rgba(38, 50, 56, 0.08);
            --radius: 30px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top left, rgba(245, 158, 11, 0.16), transparent 32%),
                linear-gradient(135deg, #fffaf3 0%, var(--bg) 100%);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
        }

        .page {
            width: min(1120px, 100%);
            min-height: 560px;
            display: grid;
            grid-template-columns: 1fr 0.86fr;
            gap: clamp(24px, 4vw, 42px);
            align-items: stretch;
        }

        .content,
        .visual-card {
            min-height: 100%;
            border-radius: var(--radius);
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            backdrop-filter: blur(8px);
        }

        .content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: clamp(34px, 5vw, 56px);
        }

        .badge {
            display: inline-flex;
            width: fit-content;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(245, 158, 11, 0.13);
            color: var(--accent-dark);
            font-size: 14px;
            font-weight: 800;
        }

        .badge::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 0 5px rgba(245, 158, 11, 0.18);
        }

        h1 {
            margin: 0 0 22px;
            max-width: 640px;
            font-size: clamp(40px, 6vw, 68px);
            line-height: 0.98;
            letter-spacing: -0.055em;
            color: var(--text);
        }

        p {
            margin: 0;
            max-width: 580px;
            font-size: clamp(17px, 2vw, 20px);
            line-height: 1.7;
            color: var(--muted);
        }

        .footer {
            margin-top: 34px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            color: var(--muted);
            font-size: 14px;
        }

        .counter {
            padding: 9px 14px;
            border-radius: 999px;
            background: #fff;
            border: 1px solid var(--border);
            color: var(--text);
            font-weight: 800;
            box-shadow: 0 8px 22px rgba(38, 50, 56, 0.04);
        }

        .visual-card {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .visual-card img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: center bottom;
        }

        @media (max-width: 900px) {
            body {
                align-items: flex-start;
                padding: 28px 18px;
            }

            .page {
                min-height: auto;
                grid-template-columns: 1fr;
                gap: 22px;
            }

            .visual-card {
                order: 1;
                min-height: auto;
                aspect-ratio: 4 / 5;
                max-width: 520px;
                width: 100%;
                margin-inline: auto;
                border-radius: 24px;
            }

            .content {
                order: 2;
                min-height: auto;
                text-align: center;
                align-items: center;
                border-radius: 24px;
                padding: 34px 26px;
            }

            h1 {
                max-width: 620px;
            }

            p {
                margin-inline: auto;
            }

            .footer {
                justify-content: center;
            }
        }

        @media (max-width: 520px) {
            body {
                padding: 20px 14px;
            }

            .page {
                gap: 16px;
            }

            .visual-card {
                border-radius: 20px;
            }

            .content {
                border-radius: 20px;
                padding: 30px 22px;
            }

            h1 {
                font-size: clamp(34px, 13vw, 48px);
            }

            .badge {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    <main class="page">
        <section class="content">
            <div class="badge">Site en préparation</div>

            <h1>On prépare quelque chose par ici.</h1>

            <p>
                Ce domaine est bien réservé et la page arrive bientôt.
                En attendant, notre petit technicien garde un œil sur les branchements.
            </p>

            <div class="footer">
                <span class="counter">
                    <?= number_format($count, 0, ',', ' ') ?> vue<?= $count > 1 ? 's' : '' ?>
                </span>

                <span>Merci pour votre visite.</span>
            </div>
        </section>

        <section class="visual-card" aria-hidden="true">
            <img src="stand-fox-vert.webp" alt="">
        </section>
    </main>
</body>
</html>