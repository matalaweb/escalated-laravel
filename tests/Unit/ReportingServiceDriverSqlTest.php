<?php

use Escalated\Laravel\Services\ReportingService;

/**
 * Verifies cross-database SQL in ReportingService's helper methods.
 *
 * Each helper now branches on driver; bug #59 happened because the
 * default branch was MySQL-specific (`TIMESTAMPDIFF(HOUR, ...)`) and
 * silently produced invalid SQL on Postgres. These tests assert the
 * exact SQL per driver so a future refactor can't regress a backend.
 */
function makeServiceWithDriver(string $driver): object
{
    return new class($driver) extends ReportingService
    {
        public function __construct(private string $injectedDriver) {}

        protected function driver(): string
        {
            return $this->injectedDriver;
        }

        public function dateExprPublic(string $column): string
        {
            return $this->dateExpression($column);
        }

        public function groupByExprPublic(string $column, ?string $groupBy): string
        {
            return $this->groupByDateExpression($column, $groupBy);
        }

        public function hoursDiffExprPublic(string $from, string $to): string
        {
            return $this->hoursDiffExpression($from, $to);
        }

        public function avgHoursDiffExprPublic(string $from, string $to): string
        {
            return $this->avgHoursDiffExpression($from, $to);
        }
    };
}

// ─── dateExpression ──────────────────────────────────────────────────────

it('emits sqlite date(col) on sqlite', function () {
    expect(makeServiceWithDriver('sqlite')->dateExprPublic('created_at'))
        ->toBe('date(created_at)');
});

it('emits col::date on postgres', function () {
    expect(makeServiceWithDriver('pgsql')->dateExprPublic('created_at'))
        ->toBe('created_at::date');
});

it('emits DATE(col) on mysql', function () {
    expect(makeServiceWithDriver('mysql')->dateExprPublic('created_at'))
        ->toBe('DATE(created_at)');
});

// ─── groupByDateExpression — week ────────────────────────────────────────

it('emits strftime week format on sqlite', function () {
    expect(makeServiceWithDriver('sqlite')->groupByExprPublic('created_at', 'week'))
        ->toBe("strftime('%Y-W%W', created_at)");
});

it('emits ISO week to_char on postgres', function () {
    expect(makeServiceWithDriver('pgsql')->groupByExprPublic('created_at', 'week'))
        ->toBe("to_char(created_at, 'IYYY-\"W\"IW')");
});

it('emits DATE_FORMAT week on mysql', function () {
    expect(makeServiceWithDriver('mysql')->groupByExprPublic('created_at', 'week'))
        ->toBe("DATE_FORMAT(created_at, '%Y-W%v')");
});

// ─── groupByDateExpression — month ───────────────────────────────────────

it('emits strftime month format on sqlite', function () {
    expect(makeServiceWithDriver('sqlite')->groupByExprPublic('created_at', 'month'))
        ->toBe("strftime('%Y-%m', created_at)");
});

it('emits to_char month on postgres', function () {
    expect(makeServiceWithDriver('pgsql')->groupByExprPublic('created_at', 'month'))
        ->toBe("to_char(created_at, 'YYYY-MM')");
});

it('emits DATE_FORMAT month on mysql', function () {
    expect(makeServiceWithDriver('mysql')->groupByExprPublic('created_at', 'month'))
        ->toBe("DATE_FORMAT(created_at, '%Y-%m')");
});

// ─── groupByDateExpression — day falls through to dateExpression ─────────

it('falls through to dateExpression for non-week non-month groupings', function () {
    expect(makeServiceWithDriver('pgsql')->groupByExprPublic('created_at', 'day'))
        ->toBe('created_at::date');
    expect(makeServiceWithDriver('sqlite')->groupByExprPublic('created_at', null))
        ->toBe('date(created_at)');
});

// ─── hoursDiffExpression ─────────────────────────────────────────────────

it('emits julianday hours on sqlite', function () {
    expect(makeServiceWithDriver('sqlite')->hoursDiffExprPublic('a', 'b'))
        ->toBe('(julianday(b) - julianday(a)) * 24');
});

it('emits EXTRACT EPOCH hours on postgres (bug #59)', function () {
    expect(makeServiceWithDriver('pgsql')->hoursDiffExprPublic('created_at', 'first_response_at'))
        ->toBe('EXTRACT(EPOCH FROM (first_response_at - created_at)) / 3600');
});

it('emits TIMESTAMPDIFF hours on mysql', function () {
    expect(makeServiceWithDriver('mysql')->hoursDiffExprPublic('a', 'b'))
        ->toBe('TIMESTAMPDIFF(HOUR, a, b)');
});

// ─── avgHoursDiffExpression wraps hoursDiff ──────────────────────────────

it('wraps hoursDiffExpression in AVG() across drivers', function () {
    foreach (['sqlite', 'pgsql', 'mysql'] as $driver) {
        $svc = makeServiceWithDriver($driver);
        expect($svc->avgHoursDiffExprPublic('a', 'b'))
            ->toBe('AVG('.$svc->hoursDiffExprPublic('a', 'b').')');
    }
});

// ─── postgres regression guard ───────────────────────────────────────────

it('never emits MySQL-specific TIMESTAMPDIFF for pgsql driver', function () {
    $svc = makeServiceWithDriver('pgsql');
    expect($svc->hoursDiffExprPublic('a', 'b'))->not->toContain('TIMESTAMPDIFF');
    expect($svc->avgHoursDiffExprPublic('a', 'b'))->not->toContain('TIMESTAMPDIFF');
});

it('never emits MySQL-specific DATE_FORMAT for pgsql driver', function () {
    $svc = makeServiceWithDriver('pgsql');
    expect($svc->groupByExprPublic('created_at', 'week'))->not->toContain('DATE_FORMAT');
    expect($svc->groupByExprPublic('created_at', 'month'))->not->toContain('DATE_FORMAT');
});
