# GERAS SDK

SDK for integrating GERAS auth into applications


## Usage

Install via Composer: `composer require itkfm/geras-sdk`

```php
// Server URL
$gerasServerApiUrl = 'https://geras.test/api';
// Application ID (as registered on the server)
$gerasApplicationID = 1234;
// Secret API key (Base64 encoded) of the Application
$mySecretKey = '…';

// Configure API key
$apiKey = new ApiKey($gerasServerApiUrl, $gerasApplicationID, $mySecretKey);

// Choose your API Client implementation
// `HttpApiClient` (built upon Guzzle) is provided out of the box
// You can also implement your own one, see `ApiClientInterface`
$transportLayer = new HttpApiClient($apiKey, new GuzzleHttp\Client());

// Setup dependencies
$messagePacker = new MessagePacker(new JsonMapper());

// Create client
$geras = new GerasClient($transportLayer, $messagePacker);
```
