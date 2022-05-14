<?php

namespace App\Entity;

use App\Repository\WordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WordRepository::class)]
class Word
{

    // PROPERTIES
    // Original string that was passed.
    #[ORM\Column(type: 'string', length: 255)]
    private $string;
    // Score.
    #[ORM\Column(type: 'integer')]
    private $score;
    // Regex used for the clean string validation.
    private static string $wordRegex = '/^[a-zA-Z0-9!&-]*$/';
    // Points system for every scenario.
    private static array $scoreSystem = [
        // Score per unique character.
        'unique' => 1,
        // Bonus for the Word being an palindrome.
        'palindrome' => 3,
        // Bonus for the Word being almost an palindrome.
        'almost-palindrome' => 2
    ];
    // Stores more precise API responses.
    private array $messages = [];
    // Stores less precise API responses.
    private array $scenarios = [];

    // GETERS AND SETERS
    public function getScore(): ?int
    {
        // Gets score.
        return $this->score;
    }
    private function setString(string $string): void
    {
        // Sets string.
        $this->string = $string;
    }
    private function getString(): ?string
    {
        // Gets string.
        return $this->string;
    }
    private function setScore(int $score): void
    {
        // Sets score.
        $this->score = $score;
    }
    private function getPoints(string $scenario): int
    {
        return self::$scoreSystem[$scenario];
    }
    protected function setScenario(string $scenario, int $points): void
    {
        // Add to the scenario array.
        $this->scenarios[$scenario] = $points;
    }
    public function getScenario(): array
    {
        return $this->scenarios;
    }
    private function setUniqueCharactersMessage(int $points): void
    {
        // Message type.
        $type = 'unique';
        // Message body.
        $message = $points . ', based on unique characters.';
        // Set message.
        $this->setMessage($type, $message);
    }

    private function setPalindromeMessage(int $points): void
    {
        // Message type.
        $type = 'palindrome';
        // Message body.
        $message = $points . ', based on the word being a palendrome.';
        // Set message.
        $this->setMessage($type, $message);
    }

    private function setAlmostAPalindromeMessage(int $points): void
    {
        // Message type.
        $type = 'almost-palindrome';
        // Message body.
        $message = $points . ', based on the word being almost a palendrome.';
        // Set message.
        $this->setMessage($type, $message);

    }

    private function setTotalScoreMessage(int $points): void
    {
        // Message type.
        $type = 'total';
        // Message body.
        $message = 'Congratulations, you\'ve scored ' . $points . ' points, based on:';
        // Set message.
        $this->setMessage($type, $message);
    }

    private function setFailedCleanStringValidation(string $character): void
    {
        // Message type.
        $type = 'failed.word.format';
        // Message body.
        $message = 'There was an error, string contains forbidden character: ' . $character;
        // Set message.
        $this->setMessage($type, $message);
    }
    protected function setMessage(string $type, string $message): void
    {
        $this->messages[$type] = $message;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    // PUBLIC STATIC METHODS
    public static function testArrayForForbiddenCharacters(array $words): array
    {
        // Set word regex variable.
        $wordRegex = self::$wordRegex;
        // Determines if the validation passed.
        $resultTest = [
            'wordsThatPassed' => [],
            'wordsThatFailed' => []
        ];
        // Itterate through all words.
        foreach($words as $word)
        {
            // Removes whitespaces and converts string to lowercase.
            $cleanString = trim(strtolower($word));
            // Performs regex check on the clean string.
            $passed = preg_match($wordRegex, $cleanString);
            // Clean string validation didn't pass.
            if(! $passed)
            {
                // Word contains forbidden characters.
                $resultTest['wordsThatFailed'][]=$word;
            }
            else{
                // Word doesn't contain forbidden characters.
                $resultTest['wordsThatPassed'][]=$word;
            }
        }
        // Return result.
        return $resultTest;
    }

    // PUBLIC METHODS
    public function __construct(string $string)
    {
        // Run the set up method.
        $this->setUpWord($string);
    }
    public function checkAlmostAPalindrome(string $string): bool
    {
        // Removes whitespaces and converts string to lowercase.
        $cleanString = $this->makeCleanString($string);
        // Reverse the string.
        $reverseString = strrev($cleanString);
        // Itterate through the string.
        for($i = 0; $i < strlen($cleanString); $i++)
        {
            // Characters between the two strings don't match, at least one of the two causes a problem.
            if($cleanString[$i] !== $reverseString[$i])
            {
                // Remove problematic character from the first string.
                $shortenedString = substr_replace($cleanString, '', $i, 1);                
                // Word is now a palindrome.
                if($this->checkPalindrome($shortenedString))
                {
                    // Word is almost a palindrome.
                    return true;
                }
                // Word is not a palindrome yet.
                else
                {
                    // Remove problematic character from the reversed string.
                    $shortenedString = substr_replace($reverseString, '', -$i, 1);
                    // Word is now a palindrome.
                    if($this->checkPalindrome($shortenedString))
                    {
                        // Word is almost a palindrome.
                        return true;
                    }
                }
                // Word is not a almost a palindrome.
                return false;
            }
        }
    }

    // PRTOECTED METHODS
    protected function setUpWord(string $string): void
    {
        // Run all of the set string methods.
        $this->setString($string);
        // Sets score.
        $this->setScore(0);
        // String passes the validation.
        if($this->validateWord($string))
        {
            // Run the calculate score method.
            $this->calculateScore($string);
        }

    }
    protected function calculateScore($string): void
    {
        // Removes whitespaces and converts string to lowercase.
        $cleanString = $this->makeCleanString($string);
        // Score a Word based on unique characters.
        $this->scoreWordBasedOnUniqueCharacters($string);
        // Word is a palindrome.
        if($this->checkPalindrome($cleanString))
        {
            // Increase score for being a palindrome.
            $this->increaseScore($this->getPoints('palindrome'));
            // Add scenario.
            $this->setScenario('palindrome', $this->getPoints('palindrome'));
            // Add message.
            $this->setPalindromeMessage($this->getPoints('palindrome'));
        }
        // Word is not a palindrome.
        else
        {
            // Word is almost a palindrome.
            if($this->checkAlmostAPalindrome($string))
            {
                // Increase score for being almost a palindrome.
                $this->increaseScore($this->getPoints('almost-palindrome'));
                // Add scenario.
                $this->setScenario('almost-palindrome', $this->getPoints('almost-palindrome'));
                // Add message.
                $this->setAlmostAPalindromeMessage($this->getPoints('almost-palindrome'));
            }
        }
        // Add total score message.
        $this->setTotalScoreMessage($this->score);
    }
    protected function validateWord(string $string): bool
    {
        // Removes whitespaces and converts string to lowercase.
        $cleanString = $this->makeCleanString($string);
        // Performs regex check on the clean string.
        $passed = preg_match(self::$wordRegex, $cleanString);
        // Clean string validation didn't pass.
        if(! $passed)
        {
            // Add a scenario.
            $this->setScenario('failed.word.format', 0);
            // Get forbidden characters.
            $forbiddenCharacters = preg_replace('/[a-zA-Z0-9!&-]/', '', $cleanString);
            // Add message.
            $this->setFailedCleanStringValidation($forbiddenCharacters);
            // Clean string validation didn't pass.
            return false;
        }
        // Clean string validation passed.
        return true;
    }

    // PRIVATE METHODS.
    private function makeCleanString(string $string): string
    {
        return trim(strtolower($string));
    }
    private function increaseScore(int $newPoints)
    {
        // Add new points to the exsisting score.
        $this->score+=$newPoints;
    }
    private function scoreWordBasedOnUniqueCharacters(string $string): void
    {
        // Removes whitespaces and converts string to lowercase.
        $cleanString = $this->makeCleanString($string);
        // Make an array out of the existing string.
        $stringArray = str_split($cleanString);
        // Filter array in order to get rid of duplicate members, then count the remaning members.
        $uniqueCharacters = count(array_unique($stringArray));
        // Score to be added, certain number of points for each character.
        $newPoints = $uniqueCharacters * $this->getPoints('unique');
        // Increase the score.
        $this->increaseScore($newPoints);
        // Add scenario.
        $this->setScenario('unique', $newPoints);
        // Add message.
        $this->setUniqueCharactersMessage($newPoints);
    }
    private function checkPalindrome(string $string): bool
    {
        // Reverse the string.
        $reverseString = strrev($string);
        // String matches the reversed string.
        if($reverseString === $string)
        {
            // Word is an palindrome.
            return true;
        }
        // Arrays don't match.
        // Word isn't a palindrome.
        return false;
    }
}
