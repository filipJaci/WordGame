<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;

class WordResourceTest extends WebTestCase
{
    private function validateResponseFormat(object $client, string $scenario): void
    {
        // Get response data.
        $responseData = json_decode($client->getResponse()->getContent());
        // There is the messages key.
        $this->assertObjectHasAttribute('messages', $responseData);
        // Returned scenario is the expected one.
        $this->assertObjectHasAttribute($scenario, $responseData->messages);
    }

    private function runTheWordControllerRequest(string $word, int $statusCode, array $scenarios): void
    {
        // Setup client, used for HTTP requests.
        $client = static::createClient();
        // Make a HTTP request to validate word example.
        $client->request(
            'POST',
            'api/word',
            [
                'word' => $word
            ]
        );
        // Check status code.
        $this->assertResponseStatusCodeSame($statusCode);
        // For each scenario.
        foreach($scenarios as $scenario)
        {
            // Validate response format.
            $this->validateResponseFormat($client, $scenario);
        }
    }
    
    /** @test */
    public function a_word_has_to_be_of_type_string_and_not_an_array(): void
    {
        $this->runTheWordControllerRequest(json_encode(['word']), 400, ['failed.attribute']);
    }

    /** @test */
    public function a_word_has_to_be_of_type_string_and_not_a_numeral(): void
    {
        $this->runTheWordControllerRequest(123, 400, ['failed.attribute']);
    }

    /** @test */
    public function a_word_has_to_be_of_type_string_and_not_a_boolean(): void
    {
        $this->runTheWordControllerRequest(true, 400, ['failed.attribute']);
    }

    /** @test */
    public function a_word_that_is_not_a_valid_word_doesnt_pass_the_API_validation(): void
    {
        $this->runTheWordControllerRequest('example123', 400, ['failed.api.validation']);
    }

    /** @test */
    public function a_word_can_be_validated_through_the_request(): void
    {
        $this->runTheWordControllerRequest('reviver', 200, ['unique', 'palindrome']);
    }

}
