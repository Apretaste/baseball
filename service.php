<?php

use Apretaste\Request;
use Apretaste\Response;

class Service
{
	/**
	 * Main entry point
	 *
	 * @param Request
	 * @param Response
	 */
	public function _main(Request $request, Response $response)
	{
		$this->_posiciones($request, $response);
	}

	/**
	 * Display the list of positions
	 *
	 * LINK: http://www.beisbolencuba.com/series/serie-nacional-beisbol-2020-2021/todos-contra/posiciones
	 *
	 * @param Request
	 * @param Response
	 */
	public function _posiciones(Request $request, Response $response)
	{
		$items = [
			["province" => "MTZ", "jg" => 26, "jp" => 14, "ave" => ".650"],
			["province" => "CMG", "jg" => 23, "jp" => 13, "ave" => ".639"],
			["province" => "CFG", "jg" => 23, "jp" => 15, "ave" => ".606"],
			["province" => "GRA", "jg" => 24, "jp" => 16, "ave" => ".600"],
			["province" => "SSP", "jg" => 23, "jp" => 16, "ave" => ".590"],
			["province" => "SCU", "jg" => 23, "jp" => 16, "ave" => ".590"],
			["province" => "IND", "jg" => 22, "jp" => 18, "ave" => ".550"],
			["province" => "PRI", "jg" => 19, "jp" => 18, "ave" => ".514"],
			["province" => "VCL", "jg" => 19, "jp" => 18, "ave" => ".514"],
			["province" => "LTU", "jg" => 18, "jp" => 19, "ave" => ".487"],
			["province" => "HOL", "jg" => 18, "jp" => 20, "ave" => ".474"],
			["province" => "MAY", "jg" => 17, "jp" => 21, "ave" => ".448"],
			["province" => "CAV", "jg" => 14, "jp" => 22, "ave" => ".389"],
			["province" => "GTM", "jg" => 13, "jp" => 26, "ave" => ".334"],
			["province" => "ART", "jg" => 11, "jp" => 26, "ave" => ".298"],
			["province" => "IJV", "jg" => 8, "jp" => 23, "ave" => ".259"],
		];

		$response->setTemplate("posiciones.ejs", ['items' => $items]);
	}

	/**
	 * Display next game and latest results
	 *
	 * LINK: http://www.beisbolencuba.com/series/serie-nacional-beisbol-2020-2021/todos-contra
	 *
	 * @param Request
	 * @param Response
	 */
	public function _juegos(Request $request, Response $response)
	{
		// get the filter
		// NOTE: Reciente devuelve el proximo juego y los tres ultimos 
		$filter = $request->input->data->filter ?? "recientes";

		$items = [
			["date" => "09/12/2020", "games" => [
				["teamA" => "CMG", "scoreA" => 15, "teamB" => "MTZ", "scoreB" => 8],
				["teamA" => "MAY", "scoreA" => 9, "teamB" => "LTU", "scoreB" => 5],
				["teamA" => "ART", "scoreA" => 3, "teamB" => "GRA", "scoreB" => 2],
				["teamA" => "IJV", "scoreA" => 6, "teamB" => "SCU", "scoreB" => 7],
				["teamA" => "IND", "scoreA" => 10, "teamB" => "GTM", "scoreB" => 4]
			]],
			["date" => "09/11/2020", "games" => [
				["teamA" => "CMG", "scoreA" => 15, "teamB" => "MTZ", "scoreB" => 8],
				["teamA" => "ART", "scoreA" => 3, "teamB" => "GRA", "scoreB" => 2],
				["teamA" => "IJV", "scoreA" => 6, "teamB" => "SCU", "scoreB" => 7],
				["teamA" => "IND", "scoreA" => 10, "teamB" => "GTM", "scoreB" => 4]
			]],
		];

		$response->setTemplate("juegos.ejs", ['items' => $items, 'filter' => $filter]);
	}

	/**
	 * Display the list of leaders
	 *
	 * LINK: http://www.beisbolencuba.com/
	 *
	 * @param Request
	 * @param Response
	 */
	public function _lideres(Request $request, Response $response)
	{
		// bat leaders
		$bat = [
			["name" => "Humberto Bravo", "team" => "CMG", "ave" => ".426"],
			["name" => "Pavel Quesada", "team" => "CFG", "ave" => ".400"],
			["name" => "César Prieto", "team" => "CFG", "ave" => ".396"],
			["name" => "Loidel Chapelli", "team" => "CMG", "ave" => ".391"],
			["name" => "Yordanis Samón", "team" => "CMG", "ave" => ".391"]
		];

		// pitcher leaders
		$pitch = [
			["name" => "Reinier Rivero", "team" => "MTZ", "pcl" => "1.27"],
			["name" => "Yankiel Mauri", "team" => "SSP", "pcl" => "1.30"],
			["name" => "Pablo Luis Guillén", "team" => "VCL", "pcl" => "1.85"],
			["name" => "Lázaro Blanco", "team" => "GRA", "pcl" => "2.01"],
			["name" => "Yoandri Montero", "team" => "SCU", "pcl" => "2.61"]
		];

		$response->setTemplate("lideres.ejs", ['bat' => $bat, 'pitch' => $pitch]);
	}
}
