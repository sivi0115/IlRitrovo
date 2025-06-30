<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Error</title>
        <link href="../css/styles.css" rel="stylesheet">
    </head>
    <body>
        <div class="error-container">
            <h1>An error has occoured: </h1>
            <p>{$errorMessage|escape}</p>
            <p><a href="javascript:history.back()">Back</a></p>
        </div>
    </body>
</html>