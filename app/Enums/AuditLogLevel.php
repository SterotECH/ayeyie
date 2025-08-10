<?php

declare(strict_types=1);

namespace App\Enums;

enum AuditLogLevel: string
{
    case INFO = 'info';
    case WARNING = 'warning';
    case ERROR = 'error';
    case CRITICAL = 'critical';

    /**
     * Get all available log levels
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
            self::INFO => 'text-success',
            self::WARNING => 'text-warning',
            self::ERROR => 'text-warning',
            self::CRITICAL => 'text-error',
        };
    }

    /**
     * Get the background color class for badges
     */
    public function getBadgeClass(): string
    {
        return match ($this) {
            self::INFO => 'bg-success/10 text-success',
            self::WARNING => 'bg-warning/10 text-warning',
            self::ERROR => 'bg-warning/10 text-warning',
            self::CRITICAL => 'bg-error/10 text-error',
        };
    }

    /**
     * Get the icon for the log level
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::INFO => 'information-circle',
            self::WARNING => 'exclamation-circle',
            self::ERROR => 'x-circle',
            self::CRITICAL => 'exclamation-triangle',
        };
    }
}