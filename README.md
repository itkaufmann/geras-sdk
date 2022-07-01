# GERAS SDK

SDK for integrating GERAS auth into applications


## Usage

Install via Composer: `composer require itkfm/geras-sdk`

```php
// Server URL
$gerasServerApiUrl = 'https://geras.test/api';
// Application ID (as registered on the server)
$gerasApplicationID = 1234;

// Configure app keys
$mySecretKey = '';
$gerasServerPublicKey = '';
$signatureHelper = new SignatureHelper($mySecretKey, $gerasServerPublicKey);

// Choose your API Client implementation
// `HttpApiClient` (built upon Guzzle) is provided out of the box
// You can also implement your own one, see `ApiClientInterface`
$transportLayer = new HttpApiClient($gerasServerApiUrl, $gerasApplicationID);

// Setup dependencies
$jsonMapper = new JsonMapper();
$messagePacker = new MessagePacker($signatureHelper, $jsonMapper);

// Create client
$geras = new GerasClient($transportLayer, $messagePacker);
```
