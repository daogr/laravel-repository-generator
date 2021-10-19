<?php

    namespace Otodev\Utils;

    /**
     * Class RepositoryUtil
     * @package Otodev\Utils
     */
    class RepositoryUtil {

        /**
         * Returns the structure for paginated list.
         *
         * @param $items
         * @param $count
         * @param $total
         *
         * @return array
         */
        public static function paginate($items, $count, $total) {
            return compact('items', 'count', 'total');
        }

    }
