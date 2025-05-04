<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart SMS Number Analyzer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Smart SMS Number Analyzer</h1>
        <p>Paste or enter mobile numbers below. The analyzer will remove duplicates and categorize by operator.</p>

        <form action="analyzer.php" method="post">
            <textarea name="numbers" rows="10" placeholder="Enter one number per line, or separate by commas or spaces..." required></textarea>
            <button type="submit">Analyze</button>
        </form>
    </div>
</body>
</html>
