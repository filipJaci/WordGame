<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Word;

class WordTest extends TestCase
{
    // public function a_word_can_be_validated_test(){}

    /** @test */
    public function a_5_letter_word_with_5_unique_characters_scores_5_points(): void
    {
        // Set a 5 letter Word with unique characters.
        $string = 'words';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->calculateScore();
        // Score is 5.
        $this->assertEquals(5, $score);
    }

    /** @test */
    public function a_5_letter_word_with_4_unique_characters_scores_4_points(): void
    {
        // Set a 5 letter Word with 4 unique characters.
        $string = 'hello';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->calculateScore();
        // Score is 4.
        $this->assertEquals(4, $score);
    }

    /** @test */
    public function word_racecar_is_scored_as_a_palindrome()
    {
        // Set a 7 letter Word that's a palindrome.
        $string = 'racecar';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->calculateScore();
        // Score is 7, 4 for the unique characters and 3 for being a palindrome.
        $this->assertEquals(7, $score);
    }

    /** @test */
    public function word_engage_is_scored_as_alomost_a_palindrome()
    {
        // Set a 6 letter Word that's almost a palindrome.
        $string = 'engage';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->calculateScore();
        // Score is 6, 4 for the unique characters and 2 for being almost a palindrome.
        $this->assertEquals(6, $score);
    }

    /** @test */
    public function word_validation_is_not_case_sensitive()
    {
        // Set a 6 letter Word with mixed cases that's almost a palindrome.
        $string = 'EnGaGe';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->calculateScore();
        // Word got validated properly even though it's in mixed case.
        // Score is 6, 4 for the unique characters and 2 for being almost a palindrome.
        $this->assertEquals(6, $score);
    }

    /** @test */
    public function word_validation_works_with_whitespaces()
    {
        // Set a 6 letter Word with whitespaces that's almost a palindrome.
        $string = 'engage  ';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->calculateScore();
        // Word got validated properly even though it had whitespaces.
        // Score is 6, 4 for the unique characters and 2 for being almost a palindrome.
        $this->assertEquals(6, $score);
    }

    /** @test */
    public function words_can_contain_the_ampersand_symbol()
    {
        // Set a Word with an ampersand.
        $string = 'AT&T';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->calculateScore();
        // Word got validated properly even though it had whitespaces.
        // Score is 5, 3 for the unique characters and 2 for being almost a palindrome.
        $this->assertEquals(5, $score);
    }

    /** @test */
    public function words_can_contain_numbers()
    {
        // Set a Word with an number.
        $string = '1st';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->calculateScore();
        // Word got validated properly even though it had whitespaces.
        // Score is 3, for the 3 unique characters.
        $this->assertEquals(3, $score);
    }

    /** @test */
    public function words_can_contain_dashes()
    {
        // Set a Word with a dash.
        $string = 'hee-hee';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->calculateScore();
        // Word got validated properly even though it had whitespaces.
        // Score is 3, for the 3 unique characters.
        $this->assertEquals(3, $score);
    }

    /** @test */
    public function words_can_contain_exclamations()
    {
        // Set a Word with a dash.
        $string = 'hee-hee!';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->calculateScore();
        // Word got validated properly even though it had whitespaces.
        // Score is 4, for the 4 unique characters.
        $this->assertEquals(4, $score);
    }

    /** @test */
    public function words_can_not_contain_question_marks()
    {
        // Set array of Words with forbidden symbols.
        $word = 'Hello?';
        // Create a new Word object.
        $word = new Word($word);
        // Run score method.
        $score = $word->calculateScore();
        // Score is 0, since validation didn't pass.
        $this->assertEquals(0, $score);
    }

    /** @test */
    public function words_can_not_contain_forbidden_symbols()
    {
        // Set array of Words with forbidden symbols.
        $array = ['a?', 'hello=', '%world', '/122nd', '\\ninja'];
        // Create a new Word object.
        $word = new Word();
        // Test array for forbidden words.
        $validate = $word->testForForbiddenWords($array);
        // None of the Words passed.
        $this->assertTrue($validate);
    }
}
