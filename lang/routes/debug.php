<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Route to remove all direct customer permissions from ALL users
Route::get('/cleanup-customer-permissions', function() {
    try {
        // Get count before deletion
        $affectedRows = DB::table('model_has_permissions')
            ->where('model_type', 'App\\Models\\User')
            ->whereIn('permission_id', function($query) {
                $query->select('id')
                      ->from('permissions')
                      ->where('name', 'like', 'customers.%');
            })
            ->count();
        
        // Delete direct customer permissions for ALL users
        DB::table('model_has_permissions')
            ->where('model_type', 'App\\Models\\User')
            ->whereIn('permission_id', function($query) {
                $query->select('id')
                      ->from('permissions')
                      ->where('name', 'like', 'customers.%');
            })
            ->delete();
        
        return response()->json([
            'message' => 'Direct customer permissions removed from all users',
            'affected_rows' => $affectedRows
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to remove permissions',
            'details' => $e->getMessage()
        ], 500);
    }
});
