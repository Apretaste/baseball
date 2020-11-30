<?php

use Apretaste\Request;
use Apretaste\Response;
use Framework\Crawler;
use Rct567\DomQuery\DomQuery;

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
		$provincesCode = [
			'artemisa' => 'ART',
			'ciego de avila' => 'CAV',
			'cienfuegos' => 'CFG',
			'camaguey' => 'CMG',
			'granma' => 'GRA',
			'guantanamo' => 'GTM',
			'holguin' => 'HOL',
			'isla de la juventud' => 'IJV',
			'industriales' => 'IND',
			'las tunas' => 'LTU',
			'mayabeque' => 'MAY',
			'matanzas' => 'MTZ',
			'pinar del rio' => 'PRI',
			'santiago de cuba' => 'SCU',
			'santi spiritus' => 'SSP',
			'villa clara' => 'VCL'
		];

		$html = Crawler::getCache("http://www.beisbolencuba.com/series/serie-nacional-beisbol-2020-2021/todos-contra/posiciones");

		$items = [];
		$dom = new DomQuery($html);
		$rows = $dom->find('table.stats tr');

		$j = 0;
		foreach ($rows as $row) {

			$j++;

			// avoid header
			if ($j == 1) continue;

			$columns = $row->children('td');
			$i = 0;
			$newItem = ['province' => '', 'jg' => 0, 'jp' => 0, 'ave' => ''];
			foreach($columns as $column) {
				$i++;
				if ($i == 2) $newItem['province'] = $provincesCode[strtolower($column->text())] ?? '';
				if ($i == 4) $newItem['jg'] = $column->text();
				if ($i == 5) $newItem['jp'] = $column->text();
				if ($i == 6) $newItem['ave'] = $column->text();

				if ($i > 6 ) break;
			}
			$items[] = $newItem;
		}

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
		$html = Crawler::getCache("http://www.beisbolencuba.com/series/serie-nacional-beisbol-2020-2021/todos-contra");

		$items = [];
		$dom = new DomQuery($html);
		$rows = $dom->find('table.games tr');

		$date = null;
		$i = 0;
		foreach ($rows as $row) {
			if ($row->hasClass('gdate')) {
				$data = $row->find("h3 a");
				$date = $data[0]->text();
				$items[++$i] = [
					"date" => $date,
					"games" => []
				];
			}

			if ($row->hasClass('gdata')) {

				$columns = $row->find('td');

				foreach ($columns as $column) {

					$tableGame = $column->find('table')[0];
					$gameRows = $tableGame->find('tr');
					$firstRow = $gameRows[1];
					$firstColumns = $firstRow->find('td');
					$secondRow = $gameRows[2];
					$secondColumns = $secondRow->find('td');

					$teamA = strip_tags($firstColumns[1]->html());
					if (empty(trim($teamA))) continue;

					$items[$i]['games'][] = [
						"teamA" => $teamA,
						"scoreA" => strip_tags($firstColumns[2]->html()),
						"teamB" => strip_tags($secondColumns[1]->html()),
						"scoreB" => strip_tags($secondColumns[2]->html())
					];

				}
			}

		}

		// get the filter
		// NOTE: Reciente devuelve el proximo juego y los tres ultimos 
		$filter = $request->input->data->filter ?? "recientes";
		$filtered = [];
		$today = strtotime(date('Y-m-d'));
		$i = 0;
		$last3 = [];
		foreach($items as $item) {
			$fStr = str_ireplace(
				[' de ', 'enero','febrero','marzo','abril','mayo','junio',
					'julio','agosto','septiembre',
					'octubre','noviembre','diciembre'],
				[' ', 'jan','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dec'], $item['date']);
			$fecha = strtotime($fStr);

			switch($filter) {
				case 'recientes':
					if ($fecha >= $today || !isset($items[$i + 1])) {
						if (isset($items[$i - 3])) $filtered[] = $items[$i - 3];
						if (isset($items[$i - 2])) $filtered[] = $items[$i - 2];
						if (isset($items[$i - 1])) $filtered[] = $items[$i - 1];
						$filtered[] = $item;
						break 2;
					}
					break;
				case 'hoy':
					if ($fecha == $today) {
						$filtered[] = $item;
						break 2;
					}
					break;
				case 'pasados':
					if ($fecha < $today) {
						$filtered[] = $item;
					}
					break;
				case 'futuros':
					if ($fecha >= $today) {
						$filtered[] = $item;
					}
					break;
				default:
					$filtered = $items;
					break 2;
			}
			$i++;
		}


		$response->setTemplate("juegos.ejs", ['items' => (array) $filtered, 'filter' => $filter]);
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
		// bateo
		$html = Crawler::getCache("https://www.beisbolencuba.com/series/serie-nacional-beisbol-2020-2021/todos-contra/estadisticas/promedio-de-bateo");

		$dom = new DomQuery($html);
		$stats = $dom->find('table.stats')[0];
		$rows = $stats->find('tr');
		$bat = [];
		foreach($rows as $row){
			$td = $row->find('td');

			if (count($td) == 0) continue;

			$name = strip_tags($td[1]->html());
			$name = explode(',', $name);
			$team = $name[1] ?? '';
			$name = $name[0];
			$ave = strip_tags($td[9]->html());

			$bat[] = [
				"name" => $name,
				"team" => $team,
				"ave" => $ave
			];
		}

		$html = Crawler::getCache("https://www.beisbolencuba.com/series/serie-nacional-beisbol-2020-2021/todos-contra/estadisticas/promedio-de-ganados");

		$dom = new DomQuery($html);
		$stats = $dom->find('table.stats')[0];
		$rows = $stats->find('tr');
		$pitch = [];
		foreach($rows as $row){
			$td = $row->find('td');

			if (count($td) == 0) continue;

			$name = strip_tags($td[1]->html());
			$name = explode(',', $name);
			$team = $name[1] ?? '';
			$name = $name[0];
			$pcl = strip_tags($td[13]->html());

			$pitch[] = [
				"name" => $name,
				"team" => $team,
				"pcl" => $pcl
			];
		}

		$response->setTemplate("lideres.ejs", ['bat' => $bat, 'pitch' => $pitch]);
	}
}
