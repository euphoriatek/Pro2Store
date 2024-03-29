<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Faker\Provider\pt_BR;

/**
 * Calculates one MOD 11 check digit based on customary Brazilian algorithms.
 * @link http://en.wikipedia.org/wiki/Check_digit
 * @link http://pt.wikipedia.org/wiki/CNPJ#Algoritmo_de_Valida.C3.A7.C3.A3o
 * @link http://en.wikipedia.org/wiki/Cadastro_de_Pessoas_F%C3%ADsicas#Validation
 *
 * @param string|integer $numbers Numbers on which generate the check digit
 * @return integer
 */
function check_digit($numbers)
{
    $length = strlen($numbers);
    $second_algorithm = $length >= 12;
    $verifier = 0;

    for ($i = 1; $i <= $length; $i++) {
        if (!$second_algorithm) {
            $multiplier = $i+1;
        } else {
            $multiplier = ($i >= 9)? $i-7 : $i+1;
        }
        $verifier += $numbers[$length-$i] * $multiplier;
    }

    $verifier = 11 - ($verifier % 11);
    if ($verifier >= 10) {
        $verifier = 0;
    }

    return $verifier;
}
