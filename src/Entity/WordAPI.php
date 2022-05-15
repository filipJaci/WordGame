<?php

namespace App\Entity;

use Symfony\Component\HttpClient\HttpClient;

use App\Repository\WordAPIRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Word;

#[ORM\Entity(repositoryClass: WordAPIRepository::class)]
class WordAPI extends Word
{
    // API route.
    private static string $apiPath = 'https://dictionary-by-api-ninjas.p.rapidapi.com/v1/dictionary';
    // API headers.
    private static array $headers = [
        'X-RapidAPI-Host' => 'dictionary-by-api-ninjas.p.rapidapi.com',
        'X-RapidAPI-Key' => '0173308186msh5e37a204925e440p10e6f1jsnd4b82778e4e7'
    ];
    // Word that needs to be evaluated.
    private string $word;
    // Used for making http requests.
    private object $client;
    // Used when generating responses.
    private int $httpStatus = 200;

    private function setClient()
    {
        // Setup http client object.
        $this->client = HttpClient::create();
    }

    public function __construct($attribute)
    {
        // Validate that attribute provided in the request.
        // Validation passed.
        if($this->attributeTest($attribute))
        {
            // Attribute passes the Word validation.
            if($this->validateWord($attribute))
            {
                // Setup http client object.
                $this->setClient();
                // Attribute passes the API test.
                if($this->validateThroughAPI($attribute))
                {
                    // Run the calculate score method.
                    $this->calculateScore($attribute);
                }
            }
        }
    }

    public static function testConnection(): bool
    {
        // Make a API request to validate word example.
        $response = self::makeAPIRequest('example');
        dd(json_decode($response->getContent()));
        // API responded with status 200.
        if($response->getStatusCode() === 200)
        {
            // Connection is established.
            return true;
        }
        // Connection is not established.
        return false;
    }

    private function attributeTest($attribute): bool
    {
        // Attribute is not a string.
        if(! $this->validateAttributeType($attribute))
        {
            // Set HTTP status code to 400 - Bad request.
            $this->setHttpStatus(400);
            // Set invalid attribute message.
            $this->setInvalidAttributeFormatMessage();
            // Validation failed.
            return false;
        }
        // Attribute is a string.
        // Validation passed.
        return true;
    }

    private function validateAttributeType($attribute): bool
    {
        // Attribute is an object or array.
        if(json_decode($attribute))
        {
            // Validation failed.
            return false;
        }
        // Tests attribute for being a string.
        return is_string($attribute);
    }

    private function setInvalidAttributeFormatMessage(): void
    {
        // Set appropriate message.
        $this->setMessage('failed.attribute', 'Invalid attribute, please send a string instead', 0);
    }
    private function setHttpStatus(int $httpStatus): void
    {
        // Return http status.
        $this->httpStatus = $httpStatus;    
    }

    public function getHttpStatus(): int
    {
        // Return http status.
        return $this->httpStatus;    
    }

    private function validateThroughAPI(string $word): bool
    {
        // Make the API request.
        $response = $this->makeAPIRequest($word);
        // API responded with status 200.
        if($response->getStatusCode() === 200)
        {
            // Get content of the API response and convert it to an array.
            $contentObject = json_decode($response->getContent());
            // There is property valid in the response.
            if(property_exists($contentObject, 'valid'))
            {
                // Word didn't pass API validaton.
                if(! $contentObject->valid)
                {
                    // Set appropriate responses.
                    $this->setMessage('failed.api.validation', 'Word isn\'t a proper English word.', 0);
                    $this->setHttpStatus(400);
                    // Validation failed.
                    return false;
                };
                // Validation passed.
                return true;
            }
            else
            {
                // Set appropriate responses.
                $this->setMessage('failed.api.error', 'There was an server error, please try again later.', 0);
                $this->setHttpStatus(400);
            }
        }
        // Validation failed, there was an error.
        return false;

    }

    private static function makeAPIRequest(string $word): object
    {
        // Setup http client object.
        $client = HttpClient::create();
        // Make a HTTP request to validate word example.
        return $client->request(
            'GET',
            self::$apiPath,
            [
                'query' => [
                    'word' => $word
                ],
                'headers' => self::$headers
            ]         
        );
    }
}
