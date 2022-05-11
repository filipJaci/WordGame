<?php

namespace App\Entity;

use App\Repository\WordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WordRepository::class)]
class Word
{

    // Original string that was passed.
    #[ORM\Column(type: 'string', length: 255)]
    private $string;

    // String used for further validation.
    private string $cleanString;

    private string $wordRegex = '/^[a-zA-Z0-9!&-]*$/';

    // Score.
    #[ORM\Column(type: 'integer')]
    private $score;

    public function __construct(string $string = '')
    {
        // String was set on initialization.
        if($string)
        {
            // Run all of the set string methods.
            $this->setString($string);
            // Sets clean string.
            $this->setCleanString($string);
            // Sets score.
            $this->setScore(0);
        }
    }

    private function getString(): ?string
    {
        return $this->string;
    }

    private function setString(string $string): void
    {
        // Sets string.
        $this->string = $string;
    }

    private function getScore(): ?int
    {
        return $this->score;
    }

    private function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    private function setCleanString($string): void
    {
        // Removes whitespaces and converts string to lowercase.
        $this->cleanString = trim(strtolower($string));
    }

    private function increaseScore($newPoints)
    {
        // Add new points to the exsisting score.
        $this->score+=$newPoints;
    }

    public function calculateScore()
    {
        // Word doesn't contain any of the forbidden symbols.
        if($this->validateCleanString($this->cleanString))
        {
            // Word passes as an actual word.
            if($this->validateWord())
            {
                // Score a Word based on unique characters.
                $this->scoreWordBasedOnUniqueCharacters();
                // Word is a palindrome.
                if($this->checkPalindrome($this->cleanString))
                {
                    // Increase score by 3.
                    $this->increaseScore(3);
                }
                // Word is not a palindrome.
                else
                {
                    // Word is almost a palindrome.
                    if($this->checkAlmostAPalindrome())
                    {
                        // Increase score by 2.
                        $this->increaseScore(2);
                    }
                }
                // return score.
                return $this->getScore();
            }
        }

    }

    private function validateWord(): bool
    {
        // Validate Word through API.
        $validationResult = true;
        // API validation passed.
        if($validationResult)
        {
            // Validation passed.
            return true;

        }
        // Validation failed.
        return false;
    }

    private function validateCleanString($string): bool
    {
        // Performs regex check on the string.
        return preg_match($this->wordRegex, $string);
    }

    private function scoreWordBasedOnUniqueCharacters(): void
    {
        // Make an array out of the existing string.
        $stringArray = str_split($this->cleanString);
        // Filter array in order to get rid of duplicate members.
        $uniqueArrayKeys = array_unique($stringArray);
        // Return count of the remaining array members.
        $this->increaseScore(count($uniqueArrayKeys));
    }

    private function checkPalindrome($string): bool
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

    public function checkAlmostAPalindrome()
    {
        // Reverse the string.
        $reverseString = strrev($this->cleanString);
        // Itterate through the string.
        for($i = 0; $i < strlen($this->cleanString); $i++)
        {
            // Characters between the two strings don't match, at least one of the two causes a problem.
            if($this->cleanString[$i] !== $reverseString[$i])
            {
                // Remove problematic character from the first string.
                $shortenedString = substr_replace($this->cleanString, '', $i, 1);                
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

    public function testForForbiddenWords(array $words): bool
    {
        // Itterate through all words.
        foreach($words as $word)
        {
            // Word is valid.
            if($this->validateCleanString($word))
            {
                // Test failed.
                return false;
            }
        }
        // All Words are invalid, test passed.
        return true;
    }
}
