<?php
// فایل JSON برای ذخیره اطلاعات روم‌ها
$roomsFile = 'rooms.json';

// خواندن اطلاعات از فایل JSON
function readRoomsData() {
    global $roomsFile;
    $data = file_get_contents($roomsFile);
    return json_decode($data, true);
}

// ذخیره اطلاعات به فایل JSON
function saveRoomsData($data) {
    global $roomsFile;
    file_put_contents($roomsFile, json_encode($data, JSON_PRETTY_PRINT));
}

// بررسی ارسال فرم ساخت روم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createRoom'])) {
    $userName = $_POST['userName'];
    
    // تولید کد روم به صورت تصادفی
    $roomCode = rand(1000, 9999);

    // خواندن داده‌های موجود در فایل JSON
    $roomsData = readRoomsData();

    // اضافه کردن روم جدید
    $newRoom = [
        'room_code' => $roomCode,
        'creator_name' => $userName,
        'members' => [$userName]
    ];
    $roomsData['rooms'][] = $newRoom;

    // ذخیره داده‌ها در فایل JSON
    saveRoomsData($roomsData);

    // هدایت به صفحه روم با کد روم
    header("Location: room.php?roomCode=$roomCode");
    exit;
}

// بررسی ارسال فرم ورود به روم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['joinRoom'])) {
    $roomCode = $_POST['roomCode'];
    $userName = $_POST['userName'];

    // خواندن داده‌های موجود در فایل JSON
    $roomsData = readRoomsData();

    // پیدا کردن روم با کد وارد شده
    $roomFound = false;
    foreach ($roomsData['rooms'] as &$room) {
        if ($room['room_code'] == $roomCode) {
            $roomFound = true;
            // اضافه کردن نام کاربر به اعضای روم
            if (!in_array($userName, $room['members'])) {
                $room['members'][] = $userName;
            }
            break;
        }
    }

    if ($roomFound) {
        // ذخیره داده‌ها در فایل JSON
        saveRoomsData($roomsData);
        // هدایت به صفحه روم با کد روم
        header("Location: room.php?roomCode=$roomCode");
        exit;
    } else {
        echo "روم پیدا نشد.";
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ساخت و ورود به روم</title>
</head>
<body>
    <h1>ساخت روم جدید</h1>
    <form method="POST">
        <label for="userName">نام شما:</label>
        <input type="text" id="userName" name="userName" required>
        <button type="submit" name="createRoom">ساخت روم</button>
    </form>

    <h1>ورود به روم</h1>
    <form method="POST">
        <label for="roomCode">کد روم:</label>
        <input type="text" id="roomCode" name="roomCode" required>
        <label for="userName">نام شما:</label>
        <input type="text" id="userName" name="userName" required>
        <button type="submit" name="joinRoom">ورود به روم</button>
    </form>
</body>
</html>
