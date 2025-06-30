<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Error</title>
        <link href="../css/styles.css" rel="stylesheet">
        <script>
            function goBackProperly() {
                if (document.referrer) {
                    window.location.href = document.referrer; // reload da server
                } else {
                    window.history.back(); // fallback se referrer non disponibile
                }
            }
        </script>
    </head>
    <body>
        <div class="error-container">
            <h1>An error has occurred: </h1>
            <p>{$errorMessage|escape}</p>
            <p><a href="#" onclick="goBackProperly(); return false;">Back</a></p>
        </div>
    </body>
</html>