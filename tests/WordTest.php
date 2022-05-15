<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Word;

class WordTest extends TestCase
{
    private function validationScenario(string $word, array $scenarios): void
    {
        // Create a new Word object.
        $word = new Word($word);
        // Get messages.
        $messages = $word->getMessages();
        // Number of triggered scenarios.
        $numberOfScenarios = count($scenarios);
        // There are 2 scenarios that got triggered, unique and total.
        $this->assertCount($numberOfScenarios, $messages);
        // For each scenario.
        foreach($scenarios as $scenario => $points)
        {
            // Scenario exsists among messages.
            $this->assertTrue(array_key_exists($scenario, $messages));
            // There are 5 points scored based on unique characters.
            $this->assertEquals($points, $messages[$scenario]['points']);
        }
    }

    /** @test */
    public function a_5_letter_word_with_5_unique_characters_scores_5_points(): void
    {
        // Set a 5 letter Word with 5 unique characters.
        $word = 'words';
        // Scenarios that should get triggered.
        $scenarios = [
            'unique' => 5,
            'total' => 5
        ];
        // Validate the scenario.
        $this->validationScenario($word, $scenarios);
    }

    /** @test */
    public function a_5_letter_word_with_4_unique_characters_scores_4_points(): void
    {
        // Set a 5 letter Word with 4 unique characters.
        $word = 'hello';
        // Scenarios that should get triggered.
        $scenarios = [
            'unique' => 4,
            'total' => 4
        ];
        // Validate the scenario.
        $this->validationScenario($word, $scenarios);
    }

    /** @test */
    public function word_racecar_is_scored_as_a_palindrome()
    {
        // Set a 7 letter Word that's a palindrome.
        $word = 'racecar';
        // Scenarios that should get triggered.
        $scenarios = [
            'unique' => 4,
            'palindrome' => 3,
            'total' => 7
        ];
        // Validate the scenario.
        $this->validationScenario($word, $scenarios);
    }

    /** @test */
    public function word_engage_is_scored_as_alomost_a_palindrome()
    {
        // Set a 6 letter Word that's almost a palindrome.
        $word = 'engage';
        // Scenarios that should get triggered.
        $scenarios = [
            'unique' => 4,
            'almost-palindrome' => 2,
            'total' => 6
        ];
        // Validate the scenario.
        $this->validationScenario($word, $scenarios);
    }

    /** @test */
    public function word_validation_is_not_case_sensitive()
    {
        // Set a 6 letter Word with mixed cases that's almost a palindrome.
        $word = 'EnGaGe';
        // Scenarios that should get triggered.
        $scenarios = [
            'unique' => 4,
            'almost-palindrome' => 2,
            'total' => 6
        ];
        // Validate the scenario.
        $this->validationScenario($word, $scenarios);
    }

    /** @test */
    public function word_validation_works_with_whitespaces()
    {
        // Set a 6 letter Word with whitespaces that's almost a palindrome.
        $word = 'engage  ';
        // Scenarios that should get triggered.
        $scenarios = [
            'unique' => 4,
            'almost-palindrome' => 2,
            'total' => 6
        ];
        // Validate the scenario.
        $this->validationScenario($word, $scenarios);
    }

    /** @test */
    public function words_can_contain_the_ampersand_symbol()
    {
        // Set a Word with an ampersand.
        $word = 'AT&T';
        // Scenarios that should get triggered.
        $scenarios = [
            'unique' => 3,
            'almost-palindrome' => 2,
            'total' => 5
        ];
        // Validate the scenario.
        $this->validationScenario($word, $scenarios);
    }

    /** @test */
    public function words_can_contain_numbers()
    {
        // Set a Word with an number.
        $word = '1st';
        // Scenarios that should get triggered.
        $scenarios = [
            'unique' => 3,
            'total' => 3
        ];
        // Validate the scenario.
        $this->validationScenario($word, $scenarios);
    }

    /** @test */
    public function words_can_contain_dashes()
    {
        // Set a Word with a dash.
        $word = 'hee-hee';
        // Scenarios that should get triggered.
        $scenarios = [
            'unique' => 3,
            'total' => 3
        ];
        // Validate the scenario.
        $this->validationScenario($word, $scenarios);
    }

    /** @test */
    public function words_can_contain_exclamations()
    {
        // Set a Word with a dash.
        $word = 'hee-hee!';
        // Scenarios that should get triggered.
        $scenarios = [
            'unique' => 4,
            'total' => 4
        ];
        // Validate the scenario.
        $this->validationScenario($word, $scenarios);
    }

    /** @test */
    public function words_can_not_contain_forbidden_symbols()
    {
        // Set array of Words with forbidden symbols and one valid word.
        $array = ['a?', 'hello=', '%world', 'exclamation', '/122nd', '\\ninja'];
        // Total number of items in the array.
        $arrayLength = count($array);
        // Test array for forbidden characters.
        $validationResult = Word::testArrayForForbiddenCharacters($array);
        // Get number of Words that failed.
        $numberOfWordsThatFailed = count($validationResult['wordsThatFailed']);
        // All of the Words failed validation, except for one word.
        $this->assertEquals($arrayLength - 1, $numberOfWordsThatFailed);
    }

    /** @test */
    public function word_object_can_return_total_uniqe_and_almost_a_palindrome_messages()
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
        $this->assertEquals('Congratulations, you\'ve scored 6 points, based on:', $messages['total']['message']);
        // There is the message about score based on unique characters.
        $this->assertEquals('4, based on unique characters.', $messages['unique']['message']);
        // There is the message about score based on the word being almost a palindrome.
        $this->assertEquals('2, based on the word being almost a palindrome.', $messages['almost-palindrome']['message']);
    }

    /** @test */
    public function there_is_a_message_about_being_a_palindrome()
    {
        // Set a 7 letter Word that's a palindrome.
        $string = 'racecar';
        // Create a new Word object.
        $word = new Word($string);
        // Get messages.
        $messages = $word->getMessages();
        // There are 3 messages.
        $this->assertCount(3, $messages);
        // There is the message about score based on the word being a palindrome.
        $this->assertEquals('3, based on the word being a palindrome.', $messages['palindrome']['message']);
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
        // There is the message about score based on the word being a palindrome.
        $this->assertEquals('There was an error, string contains forbidden character: ?', $messages['failed.word.format']['message']);
    }
}
