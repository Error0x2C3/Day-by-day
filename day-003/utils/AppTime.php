<?php

//on suppose que la session est déjà démarrée

class AppTime {
    public static function get_offset(): int {
        return $_SESSION['time_offset'] ?? 0;
    }

    public static function set_offset(int $offset): void {
        $_SESSION['time_offset'] = $offset;
    }

    private static function to_seconds(int $amount, string $unit): int {
        switch($unit) {
            case 'hour':
                return $amount * 3600;
            case 'day':
                return $amount * 86400;
            case 'week':
                return $amount * 604800;
            default:
                return 0;
        }
    }

    public static function add_period(int $amount, string $unit): void {
        $seconds = self::to_seconds($amount, $unit);
        self::set_offset(self::get_offset() + $seconds);
    }

    public static function reset_offset(): void {
        self::set_offset(0);
    }

    public static function get_current_datetime(): string {
        $dt = new DateTime();
        $dt->modify("+" . self::get_offset() . " seconds");
        return $dt->format("Y-m-d H:i:s");
    }
}