<?php

/***
 * Interface para obtener toda la información necesaria de un curso de CAPLAFT para consumirla por PowerBI.
 */
header('Content-Type: application/json; charset=utf-8');

$courseid = $_GET['courseid'];
$quizid = $_GET['quizid'];
$userid = $_GET['userid'];

function getDataFromWebservice($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $data = curl_exec($ch);
    curl_close($ch);

    return json_decode($data, true);
}

$quizid = 37; //ID del quiz de fintra
$userid = 2343; // ID cindy paola acosta salcedo
$status = "all";

$stringWebserviceUserAttepms = 'https://caplaft.com/webservice/rest/server.php?wstoken=d54e552bbbb67360046db48dddb6b6a2&wsfunction=mod_quiz_get_user_attempts&quizid=' . $quizid . '&userid=' . $userid . '&status=' . $status . '&moodlewsrestformat=json';
$request = getDataFromWebservice($stringWebserviceUserAttepms);

echo json_encode($request);

return;

$getCourseGrades = getDataFromWebservice('https://caplaft.com/webservice/rest/server.php?wstoken=d54e552bbbb67360046db48dddb6b6a2&wsfunction=gradereport_user_get_grade_items&courseid=' . $courseid . '&moodlewsrestformat=json');
// $cursos = Get_Api('https://caplaft.com/webservice/rest/server.php?wstoken=d54e552bbbb67360046db48dddb6b6a2&wsfunction=core_course_get_courses&moodlewsrestformat=json');
$ratingItems = $getCourseGrades["usergrades"];
$datajson = [];
for ($i = 0; $i < count($ratingItems); $i++) {
    // $user = Get_Api('https://caplaft.com/webservice/rest/server.php?wstoken=c1147432c77ad1942dbde7b82f1def15&wsfunction=core_user_get_users&criteria[0][key]=id&criteria[0][value]='.$califi[$i]["userid"].'&moodlewsrestformat=json');
    $datajson[$i]["courseid"] = $ratingItems[$i]["courseid"];
    $datajson[$i]["userid"] = $ratingItems[$i]["userid"];
    $datajson[$i]["userfullname"] = ucwords(strtolower($ratingItems[$i]["userfullname"]));
    $datajson[$i]["itemname"] = $ratingItems[$i]["gradeitems"][0]["itemname"];
    $datajson[$i]["gradeformatted"] =  str_replace("-", "0", str_replace(".", ",", $ratingItems[$i]["gradeitems"][0]["gradeformatted"]));
    $datajson[$i]["percentageformatted"] = str_replace("-", "0", str_replace(".", ",", $ratingItems[$i]["gradeitems"][0]["percentageformatted"]));
    $datajson[$i]["dateofissue"] = date('d-m-Y H:i:s', $ratingItems[$i]["gradeitems"][0]["gradedatesubmitted"]);
    // $datajson[] = $califi[$i][];
}

echo json_encode($datajson);