 <?php
// Bot Token For ENV
$apiToken = $_ENV['API_TOKEN'];

// Bot Token If you don't Have ENV Remove // From $apitoken 

//$apiToken = "Bot Token Here";

    //Data From Webhook
    $content = file_get_contents("php://input");
    $update = json_decode($content, true);
    $chat_id = $update["message"]["chat"]["id"];
    $message = $update["message"]["text"];
    $message_id = $update["message"]["message_id"];
    $id = $update["message"]["from"]["id"];
    $username = $update["message"]["from"]["username"];
    $firstname = $update["message"]["from"]["first_name"];
    $chatname = $_ENV['CHAT']; 

  if($message == "/status"){
  send_MDmessage($chat_id,$message_id,"***BOT STATUS = OPERATIONAL ✅\n Check Each Gates Status Using /gates***");
}






    if (preg_match('/^\/findanime($|\s)/', $message) || preg_match('/^\/fa($|\s)/', $message)) {
    $startPosFa = strpos($message, '/fa ');
    $startPosFindanime = strpos($message, '/findanime ');
    $startPos = $startPosFindanime === 0 ? strlen('/findanime ') : ($startPosFa === 0 ? strlen('/fa ') : false);

    // Extract the additional text
    $additionalText1 = $startPos !== false ? trim(substr($message, $startPos)) : '';


    if (empty($additionalText1)) {

        sendMessage($chat_id, "***Invalid‼️\nWhat Anime Are You Searching For?\nUse /findanime <Anime Name> or /fa <Anime Name>***");
        exit; 
    }

      $additionalText = preg_replace('/\s+/', ' ', $additionalText1);


      $processedText = str_replace(' ', '-', $additionalText);




     $messageid1 =  sendMessage($chat_id,"***Finding Anime's ⭕️***");

      editMessage($chat_id, $messageid1, "***Finding Anime'ss ⭕️⭕️***");


      $url = "https://uniqueapi.online/anime/search.php?anime=$processedText";


      
      $ch = curl_init();


      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($ch);



      curl_close($ch);


      
      $data = json_decode($response, true);

      editMessage($chat_id, $messageid1, "***Finding Anime'sss ⭕️⭕️⭕️***");

        if ($data) {
            $result = '';

            foreach ($data as $index => $anime) {
                $id = $anime['id'];
                $title = $anime['title'];
                $releaseDate = $anime['releaseDate'];
                $subOrDub = $anime['subOrDub'];
                $animeUrl = $anime['url'];


                $displayIndex = $index + 1;

              
                $result .= "🔹 ***$displayIndex***. ***$title***\n   └ 🆔 ***ID:*** `$id`\n   └ 📅 ***Release Date:*** `$releaseDate`\n   └ 🎧 ***Type:*** `$subOrDub`\n\n";
              
            }

            editMessage($chat_id, $messageid1, "$result \n_Find Each Anime Info Using /animeinfo <id of anime>_");
          exit;
        } else {
            echo "Invalid JSON response or decoding error.";
                      editMessage($chat_id, $messageid1, "***‼️No Anime Found***\n_Try Using Japanese Name Of The Anime Or Proper Name_");
          exit;
          
        }
}


      if (preg_match('/^\/animeinfo($|\s)/', $message) || preg_match('/^\/ai($|\s)/', $message)) {
      $command = preg_match('/^\/animeinfo/', $message) ? '/animeinfo ' : '/ai ';
      $startPos = strlen($command);
      $additionalText2 = trim(substr($message, $startPos));



      if (empty($additionalText2)) {
          sendMessage($chat_id, "***Invalid!\nWhich Anime You Are Finding Info?\nUse /animeinfo <Anime ID> or /ai <Anime ID>***");
          exit;
      } else {

      
        $messageid1 = sendMessage($chat_id, "***Finding Anime's info ⭕️***");


        $url = "https://uniqueapi.online/anime/info.php?id=$additionalText2";


      
        $ch = curl_init();


        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  
        $response = curl_exec($ch);

       
        curl_close($ch);

        editMessage($chat_id, $messageid1, "***Finding Anime's info ⭕️⭕️***");

        $responseData = json_decode($response, true);


        $status = $responseData['results']['status'];
        $type = $responseData['results']['type'];
        $genre = $responseData['results']['genre'];
        $name = $responseData['results']['name'];
        $released = $responseData['results']['released'];
        $episodesCount = count($responseData['results']['episodes']);
        $imageUrl = $responseData['results']['image'];
        $otherName = "";
        if (isset($responseData['results']['other_name']) && !empty($responseData['results']['other_name'])) {
            $otherName = $responseData['results']['other_name'];
        } else {
            $otherName = "N/A"; // Default value if 'other_name' is empty
        }
      
        $message = "<b>✨ $name</b>\n";
        $message .= "<b>✨ Other Names:</b> <i>$otherName</i>\n";
        $message .= "<b>Status:</b> <i>$status</i>\n";
        $message .= "<b>Type:</b> <i>$type</i>\n";
        $message .= "<b>Genre:</b> <i>$genre</i>\n";
        $message .= "<b>Released:</b> <i>$released</i>\n";
        $message .= "<b>Number of Episodes:</b> <i>$episodesCount</i>\n";


        sendPhotoOrMessage($chat_id, $imageUrl, $message);
        deleteMessage($chat_id, $messageid1);
      exit;
    }
}

    if (preg_match('/^\/watchanime(?:\s+(.+?)\s+(\d+))?$/', $message, $matches) || preg_match('/^\/wa(?:\s+(.+?)\s+(\d+))?$/', $message, $matches)) {

    if (isset($matches[1]) && isset($matches[2])) {
        
        $animeId = $matches[1];
        $episodeNumber = $matches[2];




        $messageid1 =  sendMessage($chat_id,"***Finding Anime Streaming Link ⭕️***");

        editMessage($chat_id, $messageid1, "***Finding Anime Streaming Link ⭕️⭕️***");
      $streamid = "$animeId-episode-$episodeNumber";


        $url = "https://uniqueapi.online/anime/stream.php?id=$streamid";
        
        $ch = curl_init();

   
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      
        $response = curl_exec($ch);



      
        curl_close($ch);

        $jsonData = json_decode($response, true);
        $sources = $jsonData['sources'];

        $array1 = $sources[0]; 
        $array2 = $sources[1]; 
        $array3 = $sources[2]; 
        $array4 = $sources[3]; 


        echo "Array 1 - URL: " . $array1['url'] . ", Quality: " . $array1['quality'] . "\n";
        echo "Array 2 - URL: " . $array2['url'] . ", Quality: " . $array2['quality'] . "\n";
        echo "Array 3 - URL: " . $array3['url'] . ", Quality: " . $array3['quality'] . "\n";
        echo "Array 4 - URL: " . $array4['url'] . ", Quality: " . $array4['quality'] . "\n";

$array1url = $array1['url'];
$array2url = $array2['url'];
$array3url = $array3['url'];
$array4url = $array4['url'];

      $text = "***✨ STREAMING LINKS***\n***1. ". $array1['quality'] ."***\n***Watch Here:*** [Click Here]($array1url)\n***2. ". $array2['quality'] ."***\n***Watch Here:*** [Click Here]($array2url)\n***3. ". $array3['quality'] ."***\n***Watch Here:*** [Click Here]($array3url)\n***4. ". $array4['quality'] ."***\n***Watch Here:*** [Click Here]($array4url)";
      editMessage($chat_id, $messageid1, $text);
exit;
    } else {
        sendMessage($chat_id, "***‼️Please provide both anime ID and episode number after the command.\n/watchanime <Anime ID> <Episode No> OR /wa <Anime ID> <Episode No>***");
      exit;
    }
    }

        if($message == "/checkapis"){
          $message1id = sendMessage($chat_id,"***Checking All API's ⭕️***");
          $api1 = "https://uniqueapi.online/anime/stream.php?id=one-piece-episode-1";
          $api2 = "https://uniqueapi.online/anime/info.php?id=tomo-chan-wa-onnanoko";
          $api3 = "https://uniqueapi.online/anime/search.php?anime=one-piece";

          editMessage($chat_id, $message1id, "***Checking All API's ⭕️⭕️***");
          $message = "***✨ API's INFO***\n\n***Watch Api:*** " . 
           (isApiWorking($api1) ? "Working ✅" : "Not Working ❌") . "\n\n" .
           "***Info Api:*** " . 
           (isApiWorking($api2) ? "Working ✅" : "Not Working ❌") . "\n\n" .
           "***Search Api:*** " . 
           (isApiWorking($api3) ? "Working ✅" : "Not Working ❌") . 
           "\n\n_IF Api Not Working Then Please Inform The Owner Of The Bot_";


          editMessage($chat_id, $message1id, "***Checking All API's ⭕️⭕️⭕️***");
          editMessage($chat_id, $message1id, $message);
          exit;
        }

          if($message == "/help"){

            sendMessage($chat_id, "***✨ COMMANDS\n\n❓Search Anime: /findanime <Anime Name> OR /fa <Anime Name>\n\n🤔Anime Info: /animeinfo <Anime ID> OR /ai <Anime ID>\n\n🌌Watch Anime: /watchanime <Anime ID> <Episode No> OR /wa <Anime ID> <Episode No>\n\nCheck If All APIs Are Working: /checkapis\n\n*** ***⚠️If You Are Facing Any Issues, You Can Contact the Owner @unique_real***");
            exit;
          }

            if($message == "/cmds" || $message == "/commands"){
sendMessage($chat_id, "***✨ COMMANDS\n\n❓Search Anime: /findanime <Anime Name> OR /fa <Anime Name>\n\n🤔Anime Info: /animeinfo <Anime ID> OR /ai <Anime ID>\n\n🌌Watch Anime: /watchanime <Anime ID> <Episode No> OR /wa <Anime ID> <Episode No>\n\nCheck If All APIs Are Working: /checkapis\n\n***");
              exit;
            }
    
  else {
   sendMessage($chat_id,"***You Lost Buddy ❓\nUse /help To Get To Know Sorroundings***");
  }
    

function isApiWorking($url) {

    $ch = curl_init($url);


    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout For 10 Seconds
    $response = curl_exec($ch);


    if (curl_errno($ch)) {

        curl_close($ch);
        return false;
    } else {
    
        curl_close($ch);


        $jsonData = json_decode($response, true);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

function send_emessage($chat_id, $message) {
  $apiToken = $_ENV['API_TOKEN'];
  // Bot Token If you don't Have ENV Remove // From $apitoken 

  //$apiToken = "Bot Token Here";
    $text = urlencode($message);
    $apiUrl = "https://api.telegram.org/bot$apiToken/sendMessage?chat_id=$chat_id&text=$text";
  $response = file_get_contents($apiUrl);
  $responseData = json_decode($response, true);

// Extract the message_id
$message_id = $responseData['result']['message_id'];
}

function isValidImageUrl($imageUrl) {
    $headers = get_headers($imageUrl, 1);
    return (strpos($headers[0], '200 OK') !== false && strpos($headers['Content-Type'], 'image/') !== false);
}

function deleteMessage($chat_id, $messageId) {
  $apiToken = $_ENV['API_TOKEN'];
  // Bot Token If you don't Have ENV Remove // From $apitoken 

  //$apiToken = "Bot Token Here";
    $url = "https://api.telegram.org/bot$apiToken/deleteMessage?chat_id=$chat_id&message_id=$messageId";
    file_get_contents($url);
}

function sendPhotoOrMessage($chat_id, $imageUrl, $caption) {
    // Check if the image URL is valid
    if (!isValidImageUrl($imageUrl)) {
        // Image URL is invalid or inaccessible, send a text message instead
      editMessage($chat_id, $messageid1, $caption);
        return;
    }
  $apiToken = $_ENV['API_TOKEN'];
  // Bot Token If you don't Have ENV Remove // From $apitoken 

  //$apiToken = "Bot Token Here";
  
    // If the image URL is valid, proceed to send the photo
    $url = "https://api.telegram.org/bot$apiToken/sendPhoto";
    $postData = [
        'chat_id' => $chat_id,
        'photo' => $imageUrl,
        'caption' => $caption,
        'parse_mode' => 'HTML'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function editMessage($chatId, $messageId, $newText) {


  $ttext = urlencode($newText);
  $apiToken = $_ENV['API_TOKEN'];
  // Bot Token If you don't Have ENV Remove // From $apitoken 

  //$apiToken = "Bot Token Here";
    $url = "https://api.telegram.org/bot$apiToken/editMessageText?chat_id=$chatId&message_id=$messageId&text=$ttext&parse_mode=Markdown";
    file_get_contents($url);
}

// Function to send a message using the Telegram Bot API
function sendMessage($chat_id, $message) {

  $apiToken = $_ENV['API_TOKEN'];
  // Bot Token If you don't Have ENV Remove // From $apitoken 

  //$apiToken = "Bot Token Here";
  $ttext = urlencode($message);
    $url = "https://api.telegram.org/bot$apiToken/sendMessage?chat_id=$chat_id&text=$ttext&parse_mode=Markdown";
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data['result']['message_id'];
}

    function sendChatAction($chat_id, $action) {
      $apiToken = $_ENV['API_TOKEN'];
      // Bot Token If you don't Have ENV Remove // From $apitoken 

      //$apiToken = "Bot Token Here";
file_get_contents("https://api.telegram.org/bot$apiToken/sendChatAction?chat_id=$chat_id&action=$action");

}

      function send_MDmessage($chat_id,$message_id, $message){
        $text = urlencode($message);
        $apiToken = $_ENV['API_TOKEN'];

        file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?chat_id=$chat_id&reply_to_message_id=$message_id&text=$text&parse_mode=Markdown");
    }

      function 
        send_Cmessage($channel_id, $message){
        $text = urlencode($message);
          $apiToken = $_ENV['API_TOKEN'];
          // Bot Token If you don't Have ENV Remove // From $apitoken 

          //$apiToken = "Bot Token Here";
        file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?chat_id=$channel_id&text=$text");
    }


function sendDice($chat_id,$message_id, $message){
  $apiToken = $_ENV['API_TOKEN'];
  // Bot Token If you don't Have ENV Remove // From $apitoken 

  //$apiToken = "Bot Token Here";
        file_get_contents("https://api.telegram.org/bot$apiToken/sendDice?chat_id=$chat_id&reply_to_message_id=$message_id&text=$message");
    }

?>
