<?php

declare(strict_types=1);

namespace App\Enums;

enum StockAlertLevel: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case CRITICAL = 'critical';
    case OUT_OF_STOCK = 'out_of_stock';

    /**
     * Get all available alert levels
     */
    public static function all(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    /**
     * Get the color class for UI display
     */
    public function getColorClass(): string
    {
        return match ($this) {
            self::LOW => 'text-warning',
            self::MEDIUM => 'text-warning',
            self::CRITICAL => 'text-error',
            self::OUT_OF_STOCK => 'text-error',
        };
    }

    /**
     * Get the background color class for badges
     */
    public function getBadgeClass(): string
    {
        return match ($this) {
            self::LOW => 'bg-warning/10 text-warning',
            self::MEDIUM => 'bg-warning/10 text-warning',
            self::CRITICAL => 'bg-error/10 text-error',
            self::OUT_OF_STOCK => 'bg-error/10 text-error',
        };
    }

    /**
     * Get the icon for the alert level
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::LOW => 'exclamation-circle',
            self::MEDIUM => 'exclamation-triangle',
            self::CRITICAL => 'exclamation-triangle',
            self::OUT_OF_STOCK => 'x-circle',
        };
    }

    /**
     * Get human readable label
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::LOW => 'Low Stock',
            self::MEDIUM => 'Medium Stock Alert',
            self::CRITICAL => 'Critical Stock Alert',
            self::OUT_OF_STOCK => 'Out of Stock',
        };
    }

    /**
     * Determine alert level based on stock percentage
     */
    public static function fromStockPercentage(int $currentStock, int $threshold): self
    {
        if ($currentStock <= 0) {
            return self::OUT_OF_STOCK;
        }
        
        $percentage = ($currentStock / $threshold) * 100;
        
        return match (true) {
            $percentage <= 25 => self::CRITICAL,
            $percentage <= 50 => self::MEDIUM,
            default => self::LOW,
        };
    }

    /**
     * Get priority level for sorting (higher = more urgent)
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::OUT_OF_STOCK => 4,
            self::CRITICAL => 3,
            self::MEDIUM => 2,
            self::LOW => 1,
        };
    }
}