<?php
function unique_code()
{
    $lastRecord = \Illuminate\Support\Facades\DB::table('users')->get()->last();
    $lastRecord ? $id = $lastRecord->id + 1 : $id = 0;
    return strtoupper(substr(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36), -6, 5)) . $id;
}

function slackIntegration($data, $error){
    if($error){
        $url = env('SLACK_ERROR');// error hook to urgent_error channel
    } else {
        $url = env('SLACK_LEAD');// lead hook to website channel
    }
    //create a new cURL resource
    $ch = curl_init($url);
    $payload = json_encode(array("text" => $data));
    //attach encoded JSON string to the POST fields
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    //set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    //return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //execute the POST request
    $result = curl_exec($ch);
    //close cURL resource
    curl_close($ch);
}
?>

