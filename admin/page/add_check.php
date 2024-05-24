<?php   
// Устанавливаем уровень отображения ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../includes/bdd.php");
// Проверяем наличие всех необходимых полей в запросе
if (
    !isset($_POST['title']) &&
 !isset($_POST['description']) &&
    !isset($_POST['name']) &&
    !isset($_POST['price']) &&
    !isset($_FILES['image']) &&
    !isset($_POST['mape'])
) {
    header('location:add.php?message=fill everything please!');
    exit;
}

// Проверяем, не существует ли уже тур с таким же именем
$sql1 = 'SELECT tourname from tours';
$stmt1 = $pdo->prepare($sql1);
$stmt1->execute();
$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
if (in_array($_POST['name'], $result1)) {
    header('location:add.php?message=this name already exists');
    exit;
}
// Вставляем информацию о туре в базу данных
$sql2 = 'INSERT into  tours (title, description, tourname, price, map) VALUES (:title, :description, :name, :price, :map)';
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute([
    "title" => $_POST['title'],
    "description" => $_POST['description'],
    "name" => $_POST['name'],
    "price" => $_POST['price'], 
    "map" => $_POST['mape']
]);

$name = $_POST['name'];

// Обработка изображения, если оно было загружено
if (isset($_FILES["image"]) && $_FILES["image"]["error"] != 4) {
    // Проверяем тип файла
    $acceptable = ["image/jpeg", "image/gif", "image/png", "image/pdf"];
    if (!in_array($_FILES["image"]["type"], $acceptable)) {
        header('location:add.php?message=wrong file type');
        exit;
    }
    // Проверяем размер файла
    $maxSize = 1024 * 1024 * 6; //6 Mo
    if ($_FILES["image"]["size"] > $maxSize) {
        header("location:add.php?message=file bigger than 2M");
        exit;
    }
    // Создаем директорию для изображений тура, если её нет
    if (!file_exists('../../accuel/images/' . $name)) {
        mkdir('../../accuel/images/' . $name);
    }
    // Перемещаем загруженное изображение в директорию для изображений тура
    $from = $_FILES['image']['tmp_name'];
    $array = explode('.', $_FILES['image']['name']);
    $ext = end($array);
    $filename = $name . '1' . '.' . $ext;
    $to = '../../accuel/images/' . $name . '/' . $filename;
    move_uploaded_file($from, $to);

    // Обновляем информацию о туре в базе данных
    $q = "UPDATE tours SET image = :image WHERE tourname = :name";
    $stmt = $pdo->prepare($q);
    $stmt->execute([
        "name" => $name,
        "image" => isset($filename) ? $filename : "../img/default.jpg"
    ]);

    // Проверяем результат операции и перенаправляем пользователя соответственно
    if (!$stmt) {
        header("location:add.php?message=error with add.");
        exit;
    } else if ($stmt->rowCount()) {
        header("location:../admin.php?message=ok");
        exit;
    }
}
?>
