<?php

declare(strict_types=1);

namespace App\Enums;

enum SuspiciousActivitySeverity: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    /**
     * Get all available severity levels
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
            self::LOW => 'text-success',
            self::MEDIUM => 'text-warning',
            self::HIGH => 'text-error',
        };
    }

    /**
     * Get the background color class for badges
     */
    public function getBadgeClass(): string
    {
        return match ($this) {
            self::LOW => 'bg-success/10 text-success',
            self::MEDIUM => 'bg-warning/10 text-warning',
            self::HIGH => 'bg-error/10 text-error',
        };
    }

    /**
     * Get the icon for the severity level
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::LOW => 'information-circle',
            self::MEDIUM => 'exclamation-circle',
            self::HIGH => 'exclamation-triangle',
        };
    }

    /**
     * Get human readable label
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::LOW => 'Low Risk',
            self::MEDIUM => 'Medium Risk',
            self::HIGH => 'High Risk',
        };
    }
}