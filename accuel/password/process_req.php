<?php 
$newPassword = $_POST['new_password'];
// Проверка токена и времени его создания в базе данных
$token = $_GET['token'];
// Подключение к базе данных (предположим, что у вас уже есть подключение)
include('../includes/bdd.php');
// Подготовка запроса к базе данных
$stmt = $pdo->prepare("SELECT reset_token_created_at FROM users WHERE reset_token = :token");

// Передача значения токена в запрос
$stmt->bindParam(':token', $token, PDO::PARAM_STR);

// Выполнение запроса
$stmt->execute();

// Получение результатов запроса
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверка токена и времени его создания
if ($userData) {
    $resetTokenCreatedAt = strtotime($userData['reset_token_created_at']);
    $currentTime = time();
    $tokenExpirationTime = 1 * 60 * 60; // Например, 1 час (в секундах)

    if (($currentTime - $resetTokenCreatedAt) < $tokenExpirationTime) {
        // Токен действителен и не устарел
        $tokenIsValid = true;
    }
}
if ($tokenIsValid)  {
    // Хэшируйте новый пароль перед сохранением в базе
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Обновление пароля в базе данных
    $stmt = $pdo->prepare("UPDATE users SET password = :hashedPassword, reset_token = NULL, reset_token_created_at = NULL WHERE reset_token = :token");

    // Передача значений в параметры запроса
    $stmt->bindParam(':hashedPassword', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    
    // Выполнение запроса
    $stmt->execute();
    
    // Проверка на успешное выполнение запроса
    if ($stmt->rowCount() > 0) {
        // Запрос выполнен успешно
        echo 'Пароль успешно сброшен. Теперь вы можете войти с новым паролем.';
    } else {
        // Запрос не изменил ни одну запись, что может произойти, если токен не был найден
        echo 'Не удалось сбросить пароль. Возможно, токен недействителен.';
    }
}
    ?>
