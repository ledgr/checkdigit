<?php
/**
 * This file is part of ledgr/checkdigit.
 *
 * Copyright (c) 2014 Hannes Forsgård
 *
 * ledgr/checkdigit is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ledgr/checkdigit is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ledgr/checkdigit.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace ledgr\checkdigit;

/**
 * Modulo10 calculator
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Modulo10
{
    /**
     * Verify that the last digit of nr is a valid check digit
     *
     * @param  string                    $nr
     * @return bool
     * @throws InvalidStructureException If nr is not numerical
     */
    public static function verify($nr)
    {
        if (!is_string($nr) || !ctype_digit($nr)) {
            throw new InvalidStructureException("Number must consist of characters 0-9");
        }

        $check = substr($nr, -1);
        $nr = substr($nr, 0, strlen($nr)-1);

        return $check == self::getCheckDigit($nr);
    }

    /**
     * Calculate check digit for nr
     *
     * @param  string                    $nr
     * @return string
     * @throws InvalidStructureException If nr is not numerical
     */
    public static function getCheckDigit($nr)
    {
        if (!is_string($nr) || !ctype_digit($nr)) {
            throw new InvalidStructureException("Number must consist of characters 0-9");
        }

        $n = 2;
        $sum = 0;

        for ($i=strlen($nr)-1; $i>=0; $i--) {
            $tmp = $nr[$i] * $n;
            ($tmp > 9) ? $sum += 1 + ($tmp % 10) : $sum += $tmp;
            ($n == 2) ? $n = 1 : $n = 2;
        }

        $ceil = $sum;

        while ($ceil % 10 != 0) {
            $ceil++;
        }

        $check = $ceil-$sum;

        return (string)$check;
    }
}
