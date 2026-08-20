<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class EventSequenceService
{
    /**
     * Supported sequence types
     */
    public const TYPE_PASS = 'pass';
    public const TYPE_INAM = 'inam_vitran';
    public const TYPE_YUVA = 'yuva_melo';

    /**
     * Map sequence type to database column name
     */
    protected static array $columnMap = [
        'pass' => 'pass_number',
        'pass_purchase' => 'pass_number',
        'inam' => 'inam_number',
        'inam_vitran' => 'inam_number',
        'inam_vitaran' => 'inam_number',
        'yuva' => 'yuva_melo_number',
        'yuva_melo' => 'yuva_melo_number',
    ];

    /**
     * Get next sequential number for an event and sequence type.
     * Starts from 1 for each event and type. Concurrency-safe with locking and transaction.
     *
     * @param string $type
     * @param int $eventId
     * @return int
     */
    public static function getNextNumber(string $type, int $eventId): int
    {
        $normalizedType = strtolower(trim($type));
        if (!isset(self::$columnMap[$normalizedType])) {
            throw new InvalidArgumentException("Invalid sequence type [{$type}]. Supported types: pass, inam_vitran, yuva_melo.");
        }

        $column = self::$columnMap[$normalizedType];
        $maxAttempts = 5;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            $attempt++;
            try {
                return DB::transaction(function () use ($eventId, $column) {
                    // Lock the highest sequence number for this event and type
                    $maxNumber = DB::table('event_registrations')
                        ->where('event_id', $eventId)
                        ->whereNotNull($column)
                        ->lockForUpdate()
                        ->max($column);

                    return ((int)$maxNumber) + 1;
                });
            } catch (\Throwable $e) {
                if ($attempt >= $maxAttempts) {
                    Log::error("Failed to generate sequence number for event [{$eventId}] type [{$type}] after {$maxAttempts} attempts: " . $e->getMessage());
                    throw $e;
                }
                usleep(50000 * $attempt); // backoff 50ms, 100ms...
            }
        }

        return 1;
    }

    /**
     * Helper for Next Pass Number
     */
    public static function nextPassNumber(int $eventId): int
    {
        return self::getNextNumber(self::TYPE_PASS, $eventId);
    }

    /**
     * Helper for Next Inam Vitran Form Number
     */
    public static function nextInamNumber(int $eventId): int
    {
        return self::getNextNumber(self::TYPE_INAM, $eventId);
    }

    /**
     * Helper for Next Yuva Melo Form Number
     */
    public static function nextYuvaMeloNumber(int $eventId): int
    {
        return self::getNextNumber(self::TYPE_YUVA, $eventId);
    }

    /**
     * Format a reference number with left padding (e.g. 1 -> "001")
     */
    public static function format(?int $number, int $padLength = 3): string
    {
        if (is_null($number) || $number <= 0) {
            return '-';
        }
        return str_pad((string)$number, $padLength, '0', STR_PAD_LEFT);
    }
}
