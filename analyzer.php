<?php

function normalize_numbers($input) {
    // Remove non-digit characters and split input by various separators
    $input = preg_replace("/[^\d,\s]/", "", $input);
    $numbers = preg_split("/[\s,\n]+/", $input);
    $cleaned = [];

    foreach ($numbers as $n) {
        $n = trim($n);
        if (strlen($n) >= 10 && strlen($n) <= 13) {
            // Normalize to format like 09123456789
            if (substr($n, 0, 2) === "98") {
                $n = "0" . substr($n, 2);
            } elseif (substr($n, 0, 3) === "+98") {
                $n = "0" . substr($n, 3);
            } elseif (substr($n, 0, 1) !== "0") {
                $n = "0" . $n;
            }
            if (preg_match("/^09\d{9}$/", $n)) {
                $cleaned[] = $n;
            }
        }
    }

    return $cleaned;
}

function detect_operator($number) {
    $prefix = substr($number, 0, 4);
    $hamrah_aval = ["0910", "0911", "0912", "0913", "0914", "0915", "0916", "0917", "0918", "0919", "0990", "0991", "0992"];
    $irancell    = ["0930", "0933", "0935", "0936", "0937", "0938", "0939", "0901", "0902", "0903", "0905", "0941"];
    $rightel     = ["0920", "0921", "0922"];

    if (in_array($prefix, $hamrah_aval)) return "Hamrah Aval";
    if (in_array($prefix, $irancell))    return "Irancell";
    if (in_array($prefix, $rightel))     return "Rightel";
    return "Other";
}

// Handle form input
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["numbers"])) {
    $raw_input = $_POST["numbers"];
    $normalized = normalize_numbers($raw_input);
    $unique_numbers = array_unique($normalized);

    // Stats
    $total = count($normalized);
    $unique = count($unique_numbers);
    $duplicates = $total - $unique;

    $operators = [
        "Hamrah Aval" => 0,
        "Irancell" => 0,
        "Rightel" => 0,
        "Other" => 0
    ];

    foreach ($unique_numbers as $num) {
        $op = detect_operator($num);
        $operators[$op]++;
    }

    // Show results
    echo "<h1>Analysis Result</h1>";
    echo "<p><strong>Total numbers entered:</strong> $total</p>";
    echo "<p><strong>Unique numbers:</strong> $unique</p>";
    echo "<p><strong>Duplicates removed:</strong> $duplicates</p>";

    echo "<h2>Operator Breakdown:</h2><ul>";
    foreach ($operators as $op => $count) {
        $percent = $unique > 0 ? round(($count / $unique) * 100, 2) : 0;
        echo "<li><strong>$op:</strong> $count ($percent%)</li>";
    }
    echo "</ul>";

    echo "<br><a href='index.php'>Analyze Another List</a>";
} else {
    header("Location: index.php");
    exit;
}
?>
