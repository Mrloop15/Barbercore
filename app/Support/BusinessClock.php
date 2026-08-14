<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

final class BusinessClock
{
    public static function timezone(): string
    {
        return (string) config('app.display_timezone', 'America/Mexico_City');
    }

    public static function now(): CarbonImmutable
    {
        return CarbonImmutable::now(self::timezone());
    }

    public static function today(): CarbonImmutable
    {
        return self::now()->startOfDay();
    }

    public static function localDate(string $date): CarbonImmutable
    {
        return CarbonImmutable::parse($date, self::timezone());
    }

    /** @return array{0: CarbonImmutable, 1: CarbonImmutable} */
    public static function utcRange(string|CarbonInterface $start, string|CarbonInterface $end): array
    {
        $startLocal = $start instanceof CarbonInterface
            ? CarbonImmutable::parse($start->toDateString(), self::timezone())
            : self::localDate($start);
        $endLocal = $end instanceof CarbonInterface
            ? CarbonImmutable::parse($end->toDateString(), self::timezone())
            : self::localDate($end);

        return [
            $startLocal->startOfDay()->utc(),
            $endLocal->endOfDay()->utc(),
        ];
    }

    public static function fromUtc(CarbonInterface|string $value): CarbonImmutable
    {
        $utc = $value instanceof CarbonInterface
            ? CarbonImmutable::instance($value)->utc()
            : CarbonImmutable::parse($value, 'UTC');

        return $utc->setTimezone(self::timezone());
    }

    public static function formatUtc(CarbonInterface|string $value, string $format): string
    {
        return self::fromUtc($value)->format($format);
    }
}
