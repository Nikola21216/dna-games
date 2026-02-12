<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $checkoutData = json_decode($data, true);

    if (is_array($checkoutData) && isset($checkoutData['totalAmount']) && isset($checkoutData['gameNames'])) {
        $_SESSION['checkoutData'] = $checkoutData;
        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false]);
?>