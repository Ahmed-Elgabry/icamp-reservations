<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Debugging Warehouse Item...\n";

$item = App\Models\OrderItem::with('stock')->first();
if ($item) {
    echo "Warehouse Item Debug:\n";
    echo "ID: {$item->id}\n";
    echo "Account ID: " . ($item->account_id ?? 'NULL') . "\n";
    echo "Total Price: " . ($item->total_price ?? 'NULL') . "\n";
    echo "Quantity: {$item->quantity}\n";
    echo "Has Stock: " . ($item->stock ? 'Yes' : 'No') . "\n";
    
    if ($item->stock) {
        echo "Stock ID: {$item->stock->id}\n";
        echo "Stock Quantity: {$item->stock->quantity}\n";
    }
    
    // Test the listener conditions
    echo "\nListener Conditions:\n";
    echo "Has account_id: " . ($item->account_id ? 'Yes' : 'No') . "\n";
    echo "Has total_price > 0: " . (($item->total_price ?? 0) > 0 ? 'Yes' : 'No') . "\n";
    
    if (!$item->account_id) {
        echo "❌ Issue: No account_id - listener will return early\n";
    }
    if (!($item->total_price ?? 0) > 0) {
        echo "❌ Issue: No total_price - listener will return early\n";
    }
    
} else {
    echo "❌ No warehouse items found\n";
}
