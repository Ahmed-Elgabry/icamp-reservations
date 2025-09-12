<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Testing Complete Verification Workflow...\n\n";

// Get the test payment
$payment = App\Models\Payment::find(61);
$bankAccount = App\Models\BankAccount::find(1);

if (!$payment || !$bankAccount) {
    echo "❌ Test payment or bank account not found\n";
    exit;
}

echo "📊 Initial State:\n";
echo "Payment ID: {$payment->id}\n";
echo "Payment Verified: " . ($payment->verified ? 'Yes' : 'No') . "\n";
echo "Payment Amount: {$payment->price}\n";
echo "Bank Balance: {$bankAccount->balance}\n\n";

// Test 1: Verify the payment (false -> true)
echo "🔄 Test 1: Verifying payment...\n";
$initialBalance = $bankAccount->balance;

try {
    DB::beginTransaction();
    
    $oldVerified = $payment->verified;
    $payment->verified = !$oldVerified; // Should become true
    $payment->save();
    
    // Fire event
    event(new App\Events\VerificationStatusChanged('payment', $payment, $payment->verified));
    
    DB::commit();
    
    $newBalance = $bankAccount->fresh()->balance;
    $change = $newBalance - $initialBalance;
    
    echo "✅ Payment verified successfully\n";
    echo "Balance change: +{$change}\n";
    echo "Expected: +{$payment->price}\n";
    echo "Test 1: " . ($change == $payment->price ? "✅ PASSED" : "❌ FAILED") . "\n\n";
    
} catch (Exception $e) {
    DB::rollBack();
    echo "❌ Error in Test 1: " . $e->getMessage() . "\n\n";
}

// Test 2: Unverify the payment (true -> false)
echo "🔄 Test 2: Unverifying payment...\n";
$initialBalance = $bankAccount->fresh()->balance;

try {
    DB::beginTransaction();
    
    $oldVerified = $payment->verified;
    $payment->verified = !$oldVerified; // Should become false
    $payment->save();
    
    // Fire event
    event(new App\Events\VerificationStatusChanged('payment', $payment, $payment->verified));
    
    DB::commit();
    
    $newBalance = $bankAccount->fresh()->balance;
    $change = $newBalance - $initialBalance;
    
    echo "✅ Payment unverified successfully\n";
    echo "Balance change: {$change}\n";
    echo "Expected: -{$payment->price}\n";
    echo "Test 2: " . ($change == -$payment->price ? "✅ PASSED" : "❌ FAILED") . "\n\n";
    
} catch (Exception $e) {
    DB::rollBack();
    echo "❌ Error in Test 2: " . $e->getMessage() . "\n\n";
}

echo "📊 Final State:\n";
$finalPayment = $payment->fresh();
$finalBalance = $bankAccount->fresh()->balance;
echo "Payment Verified: " . ($finalPayment->verified ? 'Yes' : 'No') . "\n";
echo "Final Bank Balance: {$finalBalance}\n";

echo "\n🏁 Complete verification workflow test completed!\n";
