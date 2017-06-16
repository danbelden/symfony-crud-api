<?php

namespace AppBundle\Paginator;

class LimitOffsetHelper
{
    /**
     * Helper method to calculate previous limit given current limit, offset
     * and count values
     *
     * @param int $limit
     * @param int $offset
     * @param int $count
     * @return int|null
     */
    public static function getPreviousLimit($limit, $offset, $count)
    {
        if ($limit <= 0 || $offset <= 0 || $count <= 0) {
            return null;
        }

        if ($offset >= $count) {
            return $limit >= $count ? $count : $limit;
        }

        return $offset >= $limit ? $limit : $offset;
    }

    /**
     * Helper method to calculate previous offset given current limit, offset
     * and count values
     *
     * @param int $limit
     * @param int $offset
     * @param int $count
     * @return int
     */
    public static function getPreviousOffset($limit, $offset, $count)
    {
        if ($limit <= 0 || $offset <= 0 || $count <= 0) {
            return null;
        }

        if ($offset >= $count) {
            return $limit >= $count ? 0 : $count - $limit;
        }

        $prevOffset = $offset - $limit;

        return $prevOffset <= 0 ? 0 : $prevOffset;
    }

    /**
     * Helper method to calculate next limit given current limit, offset
     * and count values
     *
     * @param int $limit
     * @param int $offset
     * @param int $count
     * @return int
     */
    public static function getNextLimit($limit, $offset, $count)
    {
        if ($limit < 0 || $limit >= $count || $offset < 0 || $offset >= $count || $count <= 0) {
            return null;
        }

        $nextMinimumOffset = ($limit + $offset);
        if ($nextMinimumOffset >= $count) {
            return null;
        }

        $nextOffset = ($nextMinimumOffset + $limit);
        if ($nextOffset <= $count) {
            return $limit;
        }

        return $count - $nextMinimumOffset;
    }

    /**
     * Helper method to calculate next offset given current limit, offset
     * and count values
     *
     * @param int $limit
     * @param int $offset
     * @param int $count
     * @return int
     */
    public static function getNextOffset($limit, $offset, $count)
    {
        if ($limit < 0 || $limit >= $count || $offset < 0 || $offset >= $count || $count <= 0) {
            return null;
        }

        $nextMinimumOffset = ($limit + $offset);
        if ($nextMinimumOffset >= $count) {
            return null;
        }

        return $nextMinimumOffset;
    }
}
