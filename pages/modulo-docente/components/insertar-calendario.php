<?php
$pdo = new PDO("mysql:host=localhost:3307;dbname=stbh", "root", "");

// Fechas de inicio y fin
$startDate = new DateTime("2025-04-01");
$endDate = new DateTime("2025-06-30");

while ($startDate <= $endDate) {
    $dayOfWeek = $startDate->format('N'); // 1 = lunes, 7 = domingo

    // Es día escolar si NO es sábado (6) ni domingo (7)
    $isSchoolDay = ($dayOfWeek < 6) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT IGNORE INTO school_calendar (date, is_school_day) VALUES (?, ?)");
    $stmt->execute([$startDate->format('Y-m-d'), $isSchoolDay]);

    $startDate->modify('+1 day');
}

echo "Calendario insertado correctamente.";
?>
