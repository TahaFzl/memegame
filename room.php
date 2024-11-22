<?php
// فایل JSON برای ذخیره اطلاعات روم‌ها
$roomsFile = 'rooms.json';

// خواندن اطلاعات از فایل JSON
function readRoomsData() {
    global $roomsFile;
    $data = file_get_contents($roomsFile);
    return json_decode($data, true);
}

// دریافت کد روم از URL
$roomCode = $_GET['roomCode'] ?? null;

// خواندن داده‌های موجود در فایل JSON
$roomsData = readRoomsData();

// پیدا کردن روم با کد وارد شده
$room = null;
foreach ($roomsData['rooms'] as $r) {
    if ($r['room_code'] == $roomCode) {
        $room = $r;
        break;
    }
}

if (!$room) {
    echo "روم پیدا نشد.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>روم شماره <?php echo $room['room_code']; ?></title>
</head>
<body>
    <h1>روم: <?php echo $room['room_code']; ?></h1>
    <p>سازنده روم: <?php echo $room['creator_name']; ?></p>

    <h2>اعضای روم:</h2>
    <ul>
        <?php foreach ($room['members'] as $member): ?>
            <li><?php echo $member; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>