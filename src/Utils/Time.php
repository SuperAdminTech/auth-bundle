<?php


namespace SuperAdmin\Utils;

use DateTimeImmutable;

/**
 * Class Time
 * @package SuperAdmin\Utils
 */
class Time {

    public const PERIOD_HOUR = 'hour';
    public const PERIOD_DAY = 'day';
    public const PERIOD_MONTH = 'month';
    public const PERIOD_YEAR = 'year';

    /**
     * @param $period
     * @return DateTimeImmutable
     */
    public static function getBeginningOfCurrent($period): DateTimeImmutable {
        $next = self::getBeginningOfNext($period);
        return self::substractPeriod($next, $period);
    }

    /**
     * @param $period
     * @return DateTimeImmutable
     */
    public static function getBeginningOfPrevious($period): DateTimeImmutable {
        $current = self::getBeginningOfCurrent($period);
        return self::substractPeriod($current, $period);
    }

    /**
     * @param $period
     * @return DateTimeImmutable
     */
    public static function getBeginningOfNext($period): DateTimeImmutable {
        switch ($period){
            case self::PERIOD_HOUR:
                $time = new DateTimeImmutable();
                $hour = $time->modify('+1 hour')->format('H');
                return $time->setTime($hour, 0);
            case self::PERIOD_DAY:
                $time = new DateTimeImmutable();
                return $time->modify('+1 day')->setTime(0, 0);
            case self::PERIOD_MONTH:
                return new DateTimeImmutable('first day of next month 00:00:00');
            case self::PERIOD_YEAR:
                return new DateTimeImmutable('1 january next year 00:00:00');
            default:
                throw new \LogicException("Invalid period $period");
        }
    }

    /**
     * @param DateTimeImmutable $time
     * @param $period
     * @return DateTimeImmutable
     */
    public static function substractPeriod(DateTimeImmutable $time, $period): DateTimeImmutable {
        switch ($period){
            case self::PERIOD_HOUR:
                return $time->modify('-1 hour');
            case self::PERIOD_DAY:
                return $time->modify('-1 day');
            case self::PERIOD_MONTH:
                return $time->modify('first day of previous month 00:00:00');
            case self::PERIOD_YEAR:
                $year = $time->format('Y');
                return $time->setDate($year - 1, 1, 1);
            default:
                throw new \LogicException("Invalid period $period");
        }
    }


}