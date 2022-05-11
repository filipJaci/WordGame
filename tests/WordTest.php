<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Word;

class WordTest extends TestCase
{

    /** @test */
    public function a_5_letter_word_with_5_unique_characters_scores_5_points(): void
    {
        // Set a 5 letter Word with unique characters.
        $string = 'words';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->getScore();
        // Score is 5.
        $this->assertEquals(5, $score);
        // Get scenarios.
        $scenarios = $word->getScenarios();
        // There is 1 scenario that got triggered.
        $this->assertCount(1, $scenarios);
        // Scenario unique got triggered.
        $this->assertTrue(array_key_exists('unique', $scenarios));
    }

    /** @test */
    public function a_5_letter_word_with_4_unique_characters_scores_4_points(): void
    {
        // Set a 5 letter Word with 4 unique characters.
        $string = 'hello';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->getScore();
        // Score is 4.
        $this->assertEquals(4, $score);
        // Get scenarios.
        $scenarios = $word->getScenarios();
        // There is 1 scenario that got triggered.
        $this->assertCount(1, $scenarios);
        // Scenario unique got triggered.
        $this->assertTrue(array_key_exists('unique', $scenarios));
    }

    /** @test */
    public function word_racecar_is_scored_as_a_palindrome()
    {
        // Set a 7 letter Word that's a palindrome.
        $string = 'racecar';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->getScore();
        // Score is 7, 4 for the unique characters and 3 for being a palindrome.
        $this->assertEquals(7, $score);
        // Get scenarios.
        $scenarios = $word->getScenarios();
        // There are 2 scenarios that got triggered.
        $this->assertCount(2, $scenarios);
        // Scenario unique got triggered.
        $this->assertTrue(array_key_exists('unique', $scenarios));
        // Scenario palindrome got triggered.
        $this->assertTrue(array_key_exists('palindrome', $scenarios));
        // Scenario unique scored 4 points.
        $this->assertEquals(4, $scenarios['unique']);
        // Scenario palindrome scored 3 points.
        $this->assertEquals(3, $scenarios['palindrome']);
    }

    /** @test */
    public function word_engage_is_scored_as_alomost_a_palindrome()
    {
        // Set a 6 letter Word that's almost a palindrome.
        $string = 'engage';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->getScore();
        // Score is 6, 4 for the unique characters and 2 for being almost a palindrome.
        $this->assertEquals(6, $score);
        // Get scenarios.
        $scenarios = $word->getScenarios();
        // There are 2 scenarios that got triggered.
        $this->assertCount(2, $scenarios);
        // Scenario unique got triggered.
        $this->assertTrue(array_key_exists('unique', $scenarios));
        // Scenario almost-palindrome got triggered.
        $this->assertTrue(array_key_exists('almost-palindrome', $scenarios));
        // Scenario unique scored 4 points.
        $this->assertEquals(4, $scenarios['unique']);
        // Scenario almost-palindrome scored 3 points.
        $this->assertEquals(2, $scenarios['almost-palindrome']);
    }

    /** @test */
    public function word_validation_is_not_case_sensitive()
    {
        // Set a 6 letter Word with mixed cases that's almost a palindrome.
        $string = 'EnGaGe';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->getScore();
        // Word got validated properly even though it's in mixed case.
        // Score is 6, 4 for the unique characters and 2 for being almost a palindrome.
        $this->assertEquals(6, $score);
        // Get scenarios.
        $scenarios = $word->getScenarios();
        // There are 2 scenarios that got triggered.
        $this->assertCount(2, $scenarios);
        // Scenario unique got triggered.
        $this->assertTrue(array_key_exists('unique', $scenarios));
        // Scenario almost-palindrome got triggered.
        $this->assertTrue(array_key_exists('almost-palindrome', $scenarios));
        // Scenario unique scored 4 points.
        $this->assertEquals(4, $scenarios['unique']);
        // Scenario almost-palindrome scored 3 points.
        $this->assertEquals(2, $scenarios['almost-palindrome']);
    }

    /** @test */
    public function word_validation_works_with_whitespaces()
    {
        // Set a 6 letter Word with whitespaces that's almost a palindrome.
        $string = 'engage  ';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->getScore();
        // Word got validated properly even though it had whitespaces.
        // Score is 6, 4 for the unique characters and 2 for being almost a palindrome.
        $this->assertEquals(6, $score);
        // Get scenarios.
        $scenarios = $word->getScenarios();
        // There are 2 scenarios that got triggered.
        $this->assertCount(2, $scenarios);
        // Scenario unique got triggered.
        $this->assertTrue(array_key_exists('unique', $scenarios));
        // Scenario almost-palindrome got triggered.
        $this->assertTrue(array_key_exists('almost-palindrome', $scenarios));
        // Scenario unique scored 4 points.
        $this->assertEquals(4, $scenarios['unique']);
        // Scenario almost-palindrome scored 3 points.
        $this->assertEquals(2, $scenarios['almost-palindrome']);
    }

    /** @test */
    public function words_can_contain_the_ampersand_symbol()
    {
        // Set a Word with an ampersand.
        $string = 'AT&T';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->getScore();
        // Word got validated properly even though it had whitespaces.
        // Score is 5, 3 for the unique characters and 2 for being almost a palindrome.
        $this->assertEquals(5, $score);
        // Get scenarios.
        $scenarios = $word->getScenarios();
        // There are 2 scenarios that got triggered.
        $this->assertCount(2, $scenarios);
        // Scenario unique got triggered.
        $this->assertTrue(array_key_exists('unique', $scenarios));
        // Scenario almost-palindrome got triggered.
        $this->assertTrue(array_key_exists('almost-palindrome', $scenarios));
        // Scenario unique scored 4 points.
        $this->assertEquals(3, $scenarios['unique']);
        // Scenario almost-palindrome scored 3 points.
        $this->assertEquals(2, $scenarios['almost-palindrome']);
    }

    /** @test */
    public function words_can_contain_numbers()
    {
        // Set a Word with an number.
        $string = '1st';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->getScore();
        // Word got validated properly even though it had whitespaces.
        // Score is 3, for the 3 unique characters.
        $this->assertEquals(3, $score);
        // Get scenarios.
        $scenarios = $word->getScenarios();
        // There was 1 scenario that got triggered.
        $this->assertCount(1, $scenarios);
        // Scenario unique got triggered.
        $this->assertTrue(array_key_exists('unique', $scenarios));
    }

    /** @test */
    public function words_can_contain_dashes()
    {
        // Set a Word with a dash.
        $string = 'hee-hee';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->getScore();
        // Word got validated properly even though it had whitespaces.
        // Score is 3, for the 3 unique characters.
        $this->assertEquals(3, $score);
        // Get scenarios.
        $scenarios = $word->getScenarios();
        // There was 1 scenario that got triggered.
        $this->assertCount(1, $scenarios);
        // Scenario unique got triggered.
        $this->assertTrue(array_key_exists('unique', $scenarios));
    }

    /** @test */
    public function words_can_contain_exclamations()
    {
        // Set a Word with a dash.
        $string = 'hee-hee!';
        // Create a new Word object.
        $word = new Word($string);
        // Run score method.
        $score = $word->getScore();
        // Word got validated properly even though it had whitespaces.
        // Score is 4, for the 4 unique characters.
        $this->assertEquals(4, $score);
        // Get scenarios.
        $scenarios = $word->getScenarios();
        // There was 1 scenario that got triggered.
        $this->assertCount(1, $scenarios);
        // Scenario unique got triggered.
        $this->assertTrue(array_key_exists('unique', $scenarios));
    }

    /** @test */
    public function words_can_not_contain_question_marks()
    {
        // Set array of Words with forbidden symbols.
        $word = 'Hello?';
        // Create a new Word object.
        $word = new Word($word);
        // Run score method.
        $score = $word->getScore();
        // Score is 0, since validation didn't pass.
        $this->assertEquals(0, $score);
        // Get scenarios.
        $scenarios = $word->getScenarios();
        // There was 1 scenario that got triggered.
        $this->assertCount(1, $scenarios);
        // Scenario failed.string got triggered.
        $this->assertTrue(array_key_exists('failed.string', $scenarios));
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

    /** @test */
    public function word_object_can_return_total_uniqe_and_almost_a_palendrome_messages()
    {
        // Set a 6 letter Word that's almost a palindrome.
        $string = 'engage';
        // Create a new Word object.
        $word = new Word($string);
        // Get messages.
        $messages = $word->getMessages();
        // There are 3 messages.
        $this->assertCount(3, $messages);
        // There is the message about total score.
        $this->assertEquals('Congratulations, you\'ve scored 6 points, based on:', $messages['total']);
        // There is the message about score based on unique characters.
        $this->assertEquals('4, based on unique characters.', $messages['unique']);
        // There is the message about score based on the word being almost a palendrome.
        $this->assertEquals('2, based on the word being almost a palendrome.', $messages['almost-palindrome']);
    }

    /** @test */
    public function there_is_a_message_about_being_a_palendrome()
    {
        // Set a 7 letter Word that's a palindrome.
        $string = 'racecar';
        // Create a new Word object.
        $word = new Word($string);
        // Get messages.
        $messages = $word->getMessages();
        // There are 3 messages.
        $this->assertCount(3, $messages);
        // There is the message about score based on the word being a palendrome.
        $this->assertEquals('3, based on the word being a palendrome.', $messages['palindrome']);
    }

    /** @test */
    public function there_is_a_message_about_failed_clean_string_validation()
    {
        // Set array of Words with forbidden symbols.
        $word = 'Hello?';
        // Create a new Word object.
        $word = new Word($word);
        // Get messages.
        $messages = $word->getMessages();
        // There is 1 message.
        $this->assertCount(1, $messages);
        // There is the message about score based on the word being a palendrome.
        $this->assertEquals('There was an error, string contains forbidden character: ?', $messages['failed.string']);
    }
}
