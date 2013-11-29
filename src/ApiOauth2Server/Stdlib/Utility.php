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
     * Calculate the time to die countdown timer for the oauth token
     * @param  int $endTime The end time to live
     * @return string
     */
    public static function calculateTTD($endTime, $returnInt = true)
    {
        if (!$endTime instanceof \DateTime) {
            $datetime = new \DateTime();
            if (is_int($endTime)) {
                $datetime->setTimestamp($endTime);
            }
            $endTime = $datetime;
        }

        $nowTime = self::createTime()->getTimestamp();
        $endTime = $endTime->getTimestamp();

        if ($endTime > $nowTime) {
            $countdown = $endTime - $nowTime;
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
