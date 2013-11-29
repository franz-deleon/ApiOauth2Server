<?php
namespace ApiOauth2Server\Stdlib;

class Utility
{
    /**
     * We calcualte the expiration time because DateTime("@") uses a UTC timezone
     * @param int $offset
     * @return \DateTime
     */
    public static function createTime($offset = 0)
    {
        $time = new \DateTime();
        $time->setTimestamp(time() + $offset);
        return $time;
    }

    /**
     * Calculate and generate a countdown timer for the oauth token
     * @param int $endTime
     * @return string
     */
    public static function calculateExpirationCountdown($endTime, $returnInt = true)
    {
        $now = self::createTime();

        if (!$endTime instanceof \DateTime) {
            $datetime = new \DateTime();
            if (is_int($endTime)) {
                $datetime->setTimestamp($endTime);
            }
            $endTime = $datetime;
        }

        $endTime = $endTime->getTimestamp();
        $now     = $now->getTimestamp();

        if ($endTime > $now) {
            $countdown = $endTime - $now;
        } else {
            $countdown = 0;
        }

        if (true == $returnInt) {
            return $countdown;
        }

        $countMins = floor($countdown / 60);
        $countSecs = $countdown - ($countMins * 60);

        return "{$countMins} minutes {$countSecs} seconds";
    }
}
