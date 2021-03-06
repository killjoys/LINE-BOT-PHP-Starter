<?php


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

			$type = checkQuestion($text);
			echo $type;

			if($type == 'translate'){
				$vocab = preg_replace("/[^a-zA-Z ]+/", "", $text);
				$answer = translate($vocab);
			}
			else if($type == 'calculate'){

				$answer = eval('return '.$text.';');

			}
			else{
				$answer = "I don't understand what you said";
			}
			echo $answer;



			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $answer
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


function checkQuestion($text){

	if (strpos($text, 'แปลว่า') !== false) {
	    return 'translate';
	}
	else if (preg_match('/[0-9]+/', $text))
	{
	    return 'calculate';
	}
	else{
		return 'null';
	}

}


function translate($text)
{     
	$source="en";
	$target="th";
	$api_key = 'AIzaSyCSRoZPuTGPX2VIX7CnCaOPn6ar2Kif6u0';


	$url = 'https://www.googleapis.com/language/translate/v2?key=' . $api_key . '&q=' . rawurlencode($text);
	$url .= '&target='.$target;
	$url .= '&source='.$source;
	 
	$response = file_get_contents($url);
	$obj =json_decode($response,true);
	if($obj != null)
	{
	    if(isset($obj['error']))
	    {
	        $result  = 'Error';
	    }
	    else
	    {
	        $result = $obj['data']['translations'][0]['translatedText'];
	    }
	}
	else
	    $result = $url;

	return $result;
}