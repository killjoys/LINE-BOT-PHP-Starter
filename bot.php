<?php


# Includes the autoloader for libraries installed with composer
require __DIR__ . '/google-api-php-client-2.0.3/vendor/autoload.php';

# Imports the Google Cloud client library
use Google\Cloud\Translate\TranslateClient;

# Your Translate API key
$apiKey = 'AIzaSyCAy2e4lpFNnEnHByK0v2UUo7q9wl7GTzk';

# Instantiates a client
$translate = new TranslateClient([
    'key' => $apiKey
]);

# The text to translate
$text = 'Hello, world!';
# The target language
$target = 'ru';

# Translates some text into Russian
$translation = $translate->translate($text, [
    'target' => $target
]);

echo 'Text: ' . $text . '
Translation: ' . $translation['text'];



$access_token = 'AYiADvPjYOy2x6IIf8u0uwvlQiG3lsURLeO6mAMXB9mmwjVeZgyVPfD0j/Dt3onHYCXs9dzCflr6yOhCxTy3J6aPuNi6b+cXBK0Y2y5YTJM1H6pFdpUNM1Ut+JpmfpHLImA4hTGj6gsYThKa4JbYtQdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => "WTF"
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
echo "OK";