<?php

function loadEnv($path)
{
    // Debugging: Toon het pad dat we gebruiken
    echo "Looking for .env at path: " . $path . "\n";  // Dit toont het pad waar we zoeken naar .env

    if (!file_exists($path)) {
        die("Env file not found at: " . $path);  // Foutmelding met het pad naar het .env bestand
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);  // Lees het bestand regel per regel
    foreach ($lines as $line) {
        // Verwijder eventuele commentaar (regels die beginnen met #)
        if (strpos($line, '#') === 0) {
            continue;
        }

        // Scheid key en value door de "="
        list($key, $value) = explode('=', $line, 2);

        // Verwijder spaties en zet alles om naar uppercase voor consistentie
        $key = trim($key);
        $value = trim($value);

        // Sla de key-value paren op in de $_ENV variabele
        $_ENV[$key] = $value;
    }
}