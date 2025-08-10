<?php

declare(strict_types=1);

namespace App\Enums;

enum AuditAction: string
{
    // Authentication Actions
    case USER_LOGIN = 'user_login';
    case USER_LOGOUT = 'user_logout';
    case USER_LOGIN_FAILED = 'user_login_failed';
    case USER_PASSWORD_CHANGED = 'user_password_changed';
    case USER_PASSWORD_RESET = 'user_password_reset';
    
    // User Management Actions
    case USER_CREATED = 'user_created';
    case USER_UPDATED = 'user_updated';
    case USER_DELETED = 'user_deleted';
    case USER_ROLE_CHANGED = 'user_role_changed';
    case USER_ACTIVATED = 'user_activated';
    case USER_DEACTIVATED = 'user_deactivated';
    
    // Product Management Actions
    case PRODUCT_CREATED = 'product_created';
    case PRODUCT_UPDATED = 'product_updated';
    case PRODUCT_DELETED = 'product_deleted';
    case PRODUCT_PRICE_CHANGED = 'product_price_changed';
    case PRODUCT_STOCK_UPDATED = 'product_stock_updated';
    case PRODUCT_VIEWED = 'product_viewed';
    
    // Transaction Actions
    case TRANSACTION_CREATED = 'transaction_created';
    case TRANSACTION_UPDATED = 'transaction_updated';
    case TRANSACTION_CANCELLED = 'transaction_cancelled';
    case TRANSACTION_REFUNDED = 'transaction_refunded';
    case PAYMENT_PROCESSED = 'payment_processed';
    case PAYMENT_FAILED = 'payment_failed';
    
    // Inventory Actions
    case STOCK_ALERT_CREATED = 'stock_alert_created';
    case STOCK_THRESHOLD_UPDATED = 'stock_threshold_updated';
    case INVENTORY_ADJUSTMENT = 'inventory_adjustment';
    case INVENTORY_COUNT = 'inventory_count';
    
    // Security Actions
    case SUSPICIOUS_ACTIVITY_DETECTED = 'suspicious_activity_detected';
    case FRAUD_ALERT_TRIGGERED = 'fraud_alert_triggered';
    case SECURITY_VIOLATION = 'security_violation';
    case ACCESS_DENIED = 'access_denied';
    
    // System Actions
    case SYSTEM_BACKUP = 'system_backup';
    case SYSTEM_MAINTENANCE = 'system_maintenance';
    case DATABASE_MIGRATION = 'database_migration';
    case SYSTEM_ERROR = 'system_error';
    
    // Customer Actions
    case CUSTOMER_REGISTERED = 'customer_registered';
    case CUSTOMER_PROFILE_UPDATED = 'customer_profile_updated';
    case CUSTOMER_ORDER_PLACED = 'customer_order_placed';
    case PAYMENT_CONFIRMATION_REQUESTED = 'payment_confirmation_requested';
    case DELIVERY_REQUESTED = 'delivery_requested';
    
    // Pickup Actions
    case PICKUP_CREATED = 'pickup_created';
    case PICKUP_UPDATED = 'pickup_updated';
    case PICKUP_COMPLETED = 'pickup_completed';
    case PICKUP_CANCELLED = 'pickup_cancelled';

    /**
     * Get all available actions
     */
    public static function all(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    /**
     * Get human readable label
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::USER_LOGIN => 'User Login',
            self::USER_LOGOUT => 'User Logout',
            self::USER_LOGIN_FAILED => 'Login Failed',
            self::USER_PASSWORD_CHANGED => 'Password Changed',
            self::USER_PASSWORD_RESET => 'Password Reset',
            
            self::USER_CREATED => 'User Created',
            self::USER_UPDATED => 'User Updated',
            self::USER_DELETED => 'User Deleted',
            self::USER_ROLE_CHANGED => 'User Role Changed',
            self::USER_ACTIVATED => 'User Activated',
            self::USER_DEACTIVATED => 'User Deactivated',
            
            self::PRODUCT_CREATED => 'Product Created',
            self::PRODUCT_UPDATED => 'Product Updated',
            self::PRODUCT_DELETED => 'Product Deleted',
            self::PRODUCT_PRICE_CHANGED => 'Product Price Changed',
            self::PRODUCT_STOCK_UPDATED => 'Product Stock Updated',
            self::PRODUCT_VIEWED => 'Product Viewed',
            
            self::TRANSACTION_CREATED => 'Transaction Created',
            self::TRANSACTION_UPDATED => 'Transaction Updated',
            self::TRANSACTION_CANCELLED => 'Transaction Cancelled',
            self::TRANSACTION_REFUNDED => 'Transaction Refunded',
            self::PAYMENT_PROCESSED => 'Payment Processed',
            self::PAYMENT_FAILED => 'Payment Failed',
            
            self::STOCK_ALERT_CREATED => 'Stock Alert Created',
            self::STOCK_THRESHOLD_UPDATED => 'Stock Threshold Updated',
            self::INVENTORY_ADJUSTMENT => 'Inventory Adjustment',
            self::INVENTORY_COUNT => 'Inventory Count',
            
            self::SUSPICIOUS_ACTIVITY_DETECTED => 'Suspicious Activity Detected',
            self::FRAUD_ALERT_TRIGGERED => 'Fraud Alert Triggered',
            self::SECURITY_VIOLATION => 'Security Violation',
            self::ACCESS_DENIED => 'Access Denied',
            
            self::SYSTEM_BACKUP => 'System Backup',
            self::SYSTEM_MAINTENANCE => 'System Maintenance',
            self::DATABASE_MIGRATION => 'Database Migration',
            self::SYSTEM_ERROR => 'System Error',
            
            self::CUSTOMER_REGISTERED => 'Customer Registered',
            self::CUSTOMER_PROFILE_UPDATED => 'Customer Profile Updated',
            self::CUSTOMER_ORDER_PLACED => 'Customer Order Placed',
            self::PAYMENT_CONFIRMATION_REQUESTED => 'Payment Confirmation Requested',
            self::DELIVERY_REQUESTED => 'Delivery Requested',
            
            self::PICKUP_CREATED => 'Pickup Created',
            self::PICKUP_UPDATED => 'Pickup Updated',
            self::PICKUP_COMPLETED => 'Pickup Completed',
            self::PICKUP_CANCELLED => 'Pickup Cancelled',
        };
    }

    /**
     * Get the category of the action
     */
    public function getCategory(): string
    {
        return match ($this) {
            self::USER_LOGIN, self::USER_LOGOUT, self::USER_LOGIN_FAILED, 
            self::USER_PASSWORD_CHANGED, self::USER_PASSWORD_RESET => 'Authentication',
            
            self::USER_CREATED, self::USER_UPDATED, self::USER_DELETED, 
            self::USER_ROLE_CHANGED, self::USER_ACTIVATED, self::USER_DEACTIVATED => 'User Management',
            
            self::PRODUCT_CREATED, self::PRODUCT_UPDATED, self::PRODUCT_DELETED, 
            self::PRODUCT_PRICE_CHANGED, self::PRODUCT_STOCK_UPDATED, self::PRODUCT_VIEWED => 'Product Management',
            
            self::TRANSACTION_CREATED, self::TRANSACTION_UPDATED, self::TRANSACTION_CANCELLED,
            self::TRANSACTION_REFUNDED, self::PAYMENT_PROCESSED, self::PAYMENT_FAILED => 'Transactions',
            
            self::STOCK_ALERT_CREATED, self::STOCK_THRESHOLD_UPDATED, 
            self::INVENTORY_ADJUSTMENT, self::INVENTORY_COUNT => 'Inventory',
            
            self::SUSPICIOUS_ACTIVITY_DETECTED, self::FRAUD_ALERT_TRIGGERED,
            self::SECURITY_VIOLATION, self::ACCESS_DENIED => 'Security',
            
            self::SYSTEM_BACKUP, self::SYSTEM_MAINTENANCE, 
            self::DATABASE_MIGRATION, self::SYSTEM_ERROR => 'System',
            
            self::CUSTOMER_REGISTERED, self::CUSTOMER_PROFILE_UPDATED, 
            self::CUSTOMER_ORDER_PLACED, self::PAYMENT_CONFIRMATION_REQUESTED,
            self::DELIVERY_REQUESTED => 'Customer',
            
            self::PICKUP_CREATED, self::PICKUP_UPDATED, 
            self::PICKUP_COMPLETED, self::PICKUP_CANCELLED => 'Pickup',
        };
    }

    /**
     * Get actions by category
     */
    public static function getByCategory(string $category): array
    {
        return array_filter(self::cases(), fn($case) => $case->getCategory() === $category);
    }
}