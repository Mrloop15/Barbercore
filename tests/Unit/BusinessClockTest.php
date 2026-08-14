<?php

namespace Tests\Unit;

use App\Models\VentaProducto;
use App\Support\BusinessClock;
use Tests\TestCase;

class BusinessClockTest extends TestCase
{
    public function test_utc_instant_is_displayed_in_guadalajara_time(): void
    {
        config()->set('app.display_timezone', 'America/Mexico_City');

        $this->assertSame(
            '13/08/2026 20:30',
            BusinessClock::formatUtc('2026-08-14 02:30:00', 'd/m/Y H:i'),
        );
    }

    public function test_local_day_is_converted_to_the_correct_utc_query_range(): void
    {
        config()->set('app.display_timezone', 'America/Mexico_City');

        [$start, $end] = BusinessClock::utcRange('2026-08-13', '2026-08-13');

        $this->assertSame('2026-08-13 06:00:00.000000', $start->format('Y-m-d H:i:s.u'));
        $this->assertSame('2026-08-14 05:59:59.999999', $end->format('Y-m-d H:i:s.u'));
        $this->assertSame('UTC', $start->timezoneName);
        $this->assertSame('UTC', $end->timezoneName);
    }

    public function test_api_datetime_cast_keeps_sale_timestamp_as_a_utc_instant(): void
    {
        $sale = new VentaProducto;
        $sale->setRawAttributes(['fecha_venta' => '2026-08-14 02:30:00']);

        $this->assertSame('2026-08-14T02:30:00.000000Z', $sale->toArray()['fecha_venta']);
    }
}
