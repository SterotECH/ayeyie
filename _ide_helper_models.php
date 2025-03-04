<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * AuditLog model representing the audit_logs table.
 *
 * @property int $log_id Unique identifier for audit log
 * @property int $user_id User who acted (staff, admin, customer)
 * @property string $action Action type, e.g., payment_processed
 * @property string $entity_type Morphable entity type (e.g., App\Models\Transaction)
 * @property int $entity_id Morphable entity ID
 * @property string|null $details Additional info
 * @property CarbonImmutable $logged_at When action occurred
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read User $user User who performed the action
 * @property-read Model|MorphTo $entity Morphable entity (e.g., Transaction, User)
 * @method static \Database\Factories\AuditLogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog query()
 */
	final class AuditLog extends \Eloquent {}
}

namespace App\Models{
/**
 * Pickup model representing the pickups table.
 *
 * @property int $pickup_id Unique identifier for each pickup
 * @property int $receipt_id Linked receipt
 * @property int $user_id Staff who processed pickup
 * @property string $pickup_status Pickup state (pending, completed)
 * @property CarbonImmutable|null $pickup_date When pickup occurred
 * @property bool $is_synced Sync status for offline mode
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Receipt $receipt The receipt associated with this pickup
 * @property-read User $user The staff member who processed the pickup
 * @method static \Database\Factories\PickupFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pickup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pickup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pickup query()
 */
	final class Pickup extends \Eloquent {}
}

namespace App\Models{
/**
 * Product model representing the products table.
 *
 * @property int $product_id Unique identifier for each product
 * @property string $name Product name, e.g., Premium Feed
 * @property string|null $description Optional product details
 * @property float $price Current price per unit
 * @property int $stock_quantity Current stock level
 * @property int $threshold_quantity Minimum stock level for alerts
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, StockAlert> $stockAlerts Stock alerts for this product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TransactionItem> $transactionItems Transaction items involving this product
 * @property-read int|null $stock_alerts_count
 * @property-read int|null $transaction_items_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 */
	final class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * Receipt model representing the receipts table.
 *
 * @property int $receipt_id Unique identifier for each receipt
 * @property int $transaction_id Linked transaction
 * @property string $receipt_code Human-readable receipt code
 * @property string $qr_code QR code for pickup verification
 * @property CarbonImmutable $issued_at When receipt was issued
 * @property bool $is_synced Sync status for offline mode
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Transaction $transaction The transaction this receipt belongs to
 * @property-read Pickup|null $pickup The pickup associated with this receipt
 * @method static \Database\Factories\ReceiptFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receipt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receipt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receipt query()
 */
	final class Receipt extends \Eloquent {}
}

namespace App\Models{
/**
 * StockAlert model representing the stock_alerts table.
 *
 * @property int $alert_id Unique identifier for stock alert
 * @property int $product_id Product nearing low stock
 * @property int $current_quantity Stock level when triggered
 * @property int $threshold Minimum stock level
 * @property string $alert_message Notification text
 * @property CarbonImmutable $triggered_at When alert was generated
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Product $product The product associated with this stock alert
 * @method static \Database\Factories\StockAlertFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert query()
 */
	final class StockAlert extends \Eloquent {}
}

namespace App\Models{
/**
 * SuspiciousActivity model representing the suspicious_activities table.
 *
 * @property int $activity_id Unique identifier for suspicious event
 * @property int $user_id Staff or customer involved
 * @property string $entity_type Morphable entity type (e.g., App\Models\User)
 * @property int $entity_id Morphable entity ID
 * @property string $description Details of suspicious action
 * @property string $severity Risk level (low, medium, high)
 * @property CarbonImmutable $detected_at When flagged
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read User $user User involved in the suspicious activity
 * @property-read Model|MorphTo $entity Morphable entity (e.g., User, Transaction)
 * @method static \Database\Factories\SuspiciousActivityFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuspiciousActivity severity(string $severity)
 */
	final class SuspiciousActivity extends \Eloquent {}
}

namespace App\Models{
/**
 * Transaction model representing the transactions table.
 *
 * @property int $transaction_id Unique identifier for each transaction
 * @property int $user_id Staff who processed the transaction
 * @property int|null $customer_user_id Customer who ordered, null for walk-ins
 * @property float $total_amount Total payment amount
 * @property string $payment_status Payment state (pending, completed, failed)
 * @property string $payment_method Method, e.g., cash, card
 * @property \Illuminate\Support\Carbon $transaction_date When the transaction occurred
 * @property bool $is_synced Sync status for offline mode
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $user Staff who processed the transaction
 * @property-read User|null $customer Customer who ordered (if registered)
 * @property-read Receipt|null $receipt Receipt associated with this transaction
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TransactionItem> $items Items in this transaction
 * @property-read int|null $items_count
 * @method static \Database\Factories\TransactionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 */
	final class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $transaction_item_id
 * @property int $transaction_id
 * @property-read Transaction $transaction
 * @method static \Database\Factories\TransactionItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionItem query()
 */
	final class TransactionItem extends \Eloquent {}
}

namespace App\Models{
/**
 * User model representing the users table for staff, admins, and customers.
 *
 * @property int $user_id Unique identifier for all users (staff, admin, customers)
 * @property string $name Full name of the user
 * @property string $phone Phone number for contact or login
 * @property string|null $email Email for login or notifications, optional for walk-ins
 * @property string|null $password Hashed password, null for unregistered walk-ins
 * @property string|null $username Username for staff/admin, optional for customers
 * @property string $role User role: staff process transactions, admins manage, customers order (staff, admin, customer)
 * @property string $language Preferred language, e.g., en for English, tw for Twi
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read Collection<int, Transaction> $transactionsAsStaff Transactions processed by this staff
 * @property-read Collection<int, Transaction> $transactionsAsCustomer Transactions ordered by this customer
 * @property-read Collection<int, Pickup> $pickups Pickups processed by this staff
 * @property-read Collection<int, SuspiciousActivity> $suspiciousActivitiesAsUser Suspicious activities where this user was involved
 * @property-read Collection<int, SuspiciousActivity> $suspiciousActivities Suspicious activities where this user is the entity
 * @property-read Collection<int, AuditLog> $auditLogs Audit logs where this user is the entity
 * @property int $id
 * @property \Carbon\CarbonImmutable|null $email_verified_at
 * @property string|null $remember_token
 * @property-read int|null $audit_logs_count
 * @property-read int|null $notifications_count
 * @property-read int|null $pickups_count
 * @property-read int|null $suspicious_activities_count
 * @property-read int|null $suspicious_activities_as_user_count
 * @property-read int|null $transactions_as_customer_count
 * @property-read int|null $transactions_as_staff_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	final class User extends \Eloquent {}
}

