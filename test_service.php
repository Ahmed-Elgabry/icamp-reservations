<?php

// Quick test to check if the service class can be loaded
require_once __DIR__ . '/vendor/autoload.php';

try {
    $service = new \App\Services\GeneralPaymentSummaryService();
    echo "SUCCESS: GeneralPaymentSummaryService class loaded successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
