<?php

namespace App\Utils;

use Random\Randomizer;
use App\Entity\Data\PasswordRequirementData;

final class GeneratorPassword
{
    public static function generate(
        int $length,
        bool $uppercaseLetters = false,
        bool $digits = false,
        bool $specialCharacters = false
    ): string {
        $randomizer = new Randomizer();

        // Alphabets
        $lowercaseLettersAlphabet = range('a', 'z');
        $uppercaseLettersAlphabet = range('A', 'Z');
        $digitsAlphabet = range(0, 9);
        $specialCharactersAlphabet = str_split('!"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~');

        // Password alphabet defaults to all lowercase letters alphabet
        $passwordAlphabet = $lowercaseLettersAlphabet;

        // Start by adding a lowercase letter
        $password = self::randomItemFromAlphabet($lowercaseLettersAlphabet, $randomizer);

        // We make sure that the final password contains at least
        // one {uppercase letter and/or digit and/or special character}
        // based on user's requested constraints.
        // We also grow at the same time the password alphabet with
        // the alphabet of the requested constraint.

        if ($uppercaseLetters) {
            $passwordAlphabet = array_merge($passwordAlphabet, $uppercaseLettersAlphabet);
            $password .= self::randomItemFromAlphabet($uppercaseLettersAlphabet, $randomizer);
        }

        if ($digits) {
            $passwordAlphabet = array_merge($passwordAlphabet, $digitsAlphabet);
            $password .= self::randomItemFromAlphabet($digitsAlphabet, $randomizer);
        }

        if ($specialCharacters) {
            $passwordAlphabet = array_merge($passwordAlphabet, $specialCharactersAlphabet);
            $password .= self::randomItemFromAlphabet($specialCharactersAlphabet, $randomizer);
        }

        $numberOfCharactersRemaining = $length - mb_strlen($password);

        for ($i = 0; $i < $numberOfCharactersRemaining; ++$i) {
            $password .= self::randomItemFromAlphabet($passwordAlphabet, $randomizer);
        }

        // We do a shuffle at the end to make the order
        // of the final password characters unpredictable
        return $randomizer->shuffleBytes($password);
    }

    public static function fromPasswordRequirementData(PasswordRequirementData $passwordRequirementData): string
    {
        return self::generate(
            $passwordRequirementData->getLength(),
            $passwordRequirementData->getUppercaseLetters(),
            $passwordRequirementData->getDigits(),
            $passwordRequirementData->getSpecialCharacters()
        );
    }

    private static function randomItemFromAlphabet(array $alphabet, Randomizer $randomizer): string
    {
        return $alphabet[$randomizer->getInt(0, count($alphabet) - 1)];
    }
}
