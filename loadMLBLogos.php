<?php

function file_get_contents_curl($url, $headers = [], &$info = [])
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_URL, $url);

	$data = curl_exec($ch);

	/* Check for 404 (file not found). */
	if (!is_resource($ch)) {
		return false;
	}

	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if ($httpCode === 404) {
		$data = false;
	}

	$info = @curl_getinfo($ch);

	curl_close($ch);

	return $data;
}

function getData($url)
{
	$jsonData = file_get_contents_curl($url);
	return @json_decode($jsonData);
}

$teams = getData("http://site.api.espn.com/apis/site/v2/sports/baseball/mlb/teams");
//var_dump($teams->sports[0]->leagues[0]->teams[0]->);

foreach ($teams->sports[0]->leagues[0]->teams as $team) {

	$logoUrl = $team->team->logos[0]->href;
	$logoData = file_get_contents_curl($logoUrl);
	echo $logoUrl."\n";
	file_put_contents("images/MLB_TEAM_{$team->team->abbreviation}.png", $logoData);
}
