<?php
define('SITE_NAME', 'E-Rapor Universitas Bakwan');
define('BASE_PATH', __DIR__ . '/..');
define('DATA_PATH', BASE_PATH . '/data');
define('SESSION_TIMEOUT', 1800); // 30 menit

// Bobot nilai
define('BOBOT_TUGAS', 0.3);
define('BOBOT_UTS', 0.3);
define('BOBOT_UAS', 0.4);

// Grade ranges
define('GRADE_RANGES', [
    'A' => 85,
    'B' => 75,
    'C' => 65,
    'D' => 50,
    'E' => 0
]);
