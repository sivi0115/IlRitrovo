<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Cookie disabilitati</title>
    <link href="/IlRitrovo/src/Smarty/css/styles.css" rel="stylesheet">
    <style>
        /* Classe per il testo evidenziato come i bottoni */
        .highlighted-text {
            font-weight: bold;
            color: #8b3a3a; /* stesso colore dei bottoni */
        }
    </style>
</head>
<body>
    <main>
        <section>
            <h1 style="color: #8b3a3a;">Cookies are disabled 🍪</h1>
            <p><strong style="color: #8b3a3a;">To use this web site, cookies must be enabled in your browser</strong>.</p>
            <p>If cookies are already enabled but you still see this message, <strong style="color: #8b3a3a;">simply reload this page once</strong>.</p>
            <p>If cookies are currently disabled, <strong style="color: #8b3a3a;">please enable them and reload this page twice</strong> to ensure full functionality.</p>
            <p>
            <p>Without cookies, <strong style="color: #8b3a3a;"> you won't be able to login, signup, or access other essential features of the application</strong>. </p>

            Need help?:
            <ul>
                <li><strong>Chrome:</strong> Settings → Privacy and security → Cookies and other site data → Allow all cookies.</li>
                <li><strong>Firefox:</strong> Settings → Privacy and security → Cookie and other site data → Allow all cookies.</li>
                <li><strong>Safari:</strong> Preferences → Privacy → Advanced Settings → Uncheck “Block all cookies”.</li>
                <li><strong>Edge:</strong> Settings → Cookies and site permissions → Manage and delete cookies → Enable cookies.</li>
            </ul>
            </p>
            <form method="POST" action="/IlRitrovo/public/User/showHomePage">
                <button type="submit" class="user-button">Try Again</button>
            </form>
        </section>
    </main>
</body>
</html>