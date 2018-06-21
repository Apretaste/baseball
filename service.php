<?php

//http://wap.mlb.com/scores/index.jsp
//www.baseball-reference.com/
//http://mlb.mlb.com/mlb/events/winterleagues/league.jsp?league=car

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class Baseball extends Service
{
	public $apiFD = null;

	/**
	 * Function executed when the service is called
	 *
	 * @param Request
	 * @return Response
	 * */
	public function _main(Request $request)
	{
		if (empty($request->query) || (strtolower($request->query) != 'liga') || (strtolower($request->query) != 'jornada') || (strtolower($request->query) != 'equipo'))
		{
			$response = new Response();
			$response->setCache("day");
			$response->setResponseSubject("¿Cual liga deseas consultar?");
			$response->createFromTemplate("selectLiga.tpl", array("ligas" => array()));
			return $response;
		}
	}

	public function _mlb(Request $request)
	{
		$response = new Response();
		$datos = explode(" ", $request->query);
		$tipoConsulta = $datos[0];
		$dato1 = (isset($datos[1])) ? $datos[1] : "";
		$games = null;
		$juegosEnCurso = 0;
		$juegosProgramados = 0;
		$juegosTerminados = 0;

		// Setup crawler
		$client = new Client();

		if (strtoupper($tipoConsulta) == "JORNADA"){
			$url = "http://scoresline.com/scores.asp?F=MLB&Date=".$dato1;
			$crawler = $client->request('GET', $url);

			// Collect games in progress
			$games = array();

			$crawler->filter('table tr[bgcolor*="#ffffff"],tr[bgcolor*="#e0e0e0"]')->each(function($item, $i) use (&$games,&$juegosEnCurso,&$juegosProgramados,&$juegosTerminados){
				// get title and link from dl dt
				$tds = array();
				$tds = null;
				$item->filter('td')->each(function($td, $c) use (&$tds){
					$tds[] = $td;
				});

				$primeraCelda = $tds[0];
				$type = null;
				$game = null;
				$gameStatus = null;
				$gameHour = null;
				$gameProbs = null;
				$homeTeam = null;
				$homeTeamScore = null;
				$awayTeam = null;
				$awayTeamScore = null;
				$inning = null;

				if (isset($tds[2])){ // no es una fila de una sola celda
					$terceraCelda = $tds[2];
					if (preg_match('/[0-9]{2}:{1}[0-9]{2}\s{1}.{2}\s{1}ET/', $terceraCelda->text() )){ //es una hora
						// entonces es la table de Games Later Today
						$type = "Programado para hoy";
						$gameHour = $terceraCelda->text();
						$game = (isset($tds[3])) ? $tds[3]->text() : "";
						$gameProbs = (isset($tds[4])) ? $tds[4]->text() : "";
						$awayTeam = "--";
						$awayTeamScore = "--";
						$homeTeam = "--";
						$homeTeamScore = "--";
						$inning = "--";
						$juegosProgramados++;
					} else {
						if (preg_match('/.{1,}\s\(.[0-9]{3}\)/', $terceraCelda->text() )){//es un nombre de equipo
							//es la tabla de Final Scores para la fecha
							$type = "Resultado Final";
							$awayTeam = $terceraCelda->text();
							$awayTeamScore = (isset($tds[3])) ? $tds[3]->text() : "";
							$homeTeam = (isset($tds[4])) ? $tds[4]->text() : "";
							$homeTeamScore = (isset($tds[5])) ? $tds[5]->text() : "";
							$inning = (isset($tds[6])) ? preg_replace('/[^0-9A-Za-z\s]+/', '',$tds[6]->text()) : "";
							$inning = preg_replace("/PreviewLinesRecapBox/", "", $inning);
							$inning = preg_replace("/PreviewLinesBox/", "", $inning);
							$inning = preg_replace("/OU\s[0-9]{1,}/", "", $inning);
							$gameHour = "--";
							$game = "--";
							$gameProbs = "--";
							$juegosTerminados++;
						}else{
							if (preg_match('/[0-9]{3}/', $terceraCelda->text() )){//los 3 digitos de Rotation
								//es un juego en curso
								$type = "En curso";
								$awayTeam = (isset($tds[3])) ? $tds[3]->text() : "";
								$awayTeamScore = (isset($tds[4])) ? $tds[4]->text() : "";
								$homeTeam = (isset($tds[5])) ? $tds[5]->text() : "";
								$homeTeamScore = (isset($tds[6])) ? $tds[6]->text() : "";
								$inning = (isset($tds[7])) ? $tds[7]->text() : "";
								$gameHour = "--";
								$game = "--";
								$gameProbs = "--";
								$juegosEnCurso=$juegosEnCurso+1;
							}else{
								$type = "Desconocido";
								$awayTeam = "--";
								$awayTeamScore = "--";
								$homeTeam = "--";
								$homeTeamScore = "--";
								$inning = "--";
								$gameHour = "--";
								$game = "--";
								$gameProbs = "--";
							}
						}
					}

					// store data collected
					$games[] = array(
						"i" => $i,
						"type" => $type,
						"gameHour" => $gameHour,
						"game" => $this->miGetText(preg_replace('/\sat\s/', ' Vs. ', $game)),
						"homeTeam" => $this->miGetText($homeTeam),
						"homeTeamScore" => preg_replace('/[^0-9]+/', '0', $homeTeamScore),
						"awayTeam"	=> $this->miGetText($awayTeam),
						"awayTeamScore" => preg_replace('/[^0-9]+/', '0', $awayTeamScore),
						"inning" => $this->miGetText($inning),
						"gameStatus" => null
					);
				}else{
					$gameStatus = $this->miGetText($primeraCelda->text());
					$games[count($games)-1]["gameStatus"] = $gameStatus;
				}

			});

			$responseContent = array(
				"request" => $request->query,
				"tipoConsulta" => $tipoConsulta,
				"fecha" => $dato1,
				"juegosEnCurso" => $juegosEnCurso,
				"juegosTerminados" => $juegosTerminados,
				"juegosProgramados" => $juegosProgramados,
				"games" => $games
			);

			$response = new Response();
			$response->setCache("720");
			$response->setResponseSubject("Juegos del " . $dato1);
			$response->createFromTemplate("showDateGames.tpl", $responseContent);
		}
		elseif (strtoupper($tipoConsulta) == "LIGA")
		{
			$url = "http://www.espn.com.ve/beisbol/mlb/posiciones";
			$crawler = $client->request('GET', $url);
			$titulo = $crawler->filter('section > div.mb5.flex.justify-between.items-center > h1')->text();
			$nombresLigas = array();
			$crawler->filter("div.Table2__Title")->each(function($item, $i) use (&$nombresLigas){
				$nombresLigas[] = $item->text();
			});

			$firstColum=array();
			$crawler->filter("tbody.Table2__tbody tr td span:not(.hide-mobile):not(.stat-cell):not(.subHeader__item--content):not(.TeamLink__Logo):not(.arrow-icon_cont)")->each(function($item, $i) use (&$firstColum){
				$firstColum[] = strtoupper($item->text());
			});

			$headers=array();
			$crawler->filter("tbody.Table2__tbody span.subHeader__item--content")->each(function($item, $i) use (&$headers){
			$headers[] = $item->text();
			});

			$data=array();
			$crawler->filter("tbody.Table2__tbody span.stat-cell")->each(function($item, $i) use (&$data){
			$data[] = $item->text();
			});
			for ($i=0; $i<36 ; $i+=6) {
				$firstColum[$i]=str_replace("LIGA AMERICANA","LA",$firstColum[$i]);
				$firstColum[$i]=str_replace("LIGA NACIONAL","LN",$firstColum[$i]);
			}
			$ligas=['firstColum' =>$firstColum,
						 'headers' => $headers,
					 	 'data' => $data];

			$url = "http://www.espn.com.ve/beisbol/mlb/estadisticas";
			$crawler = $client->request('GET', $url);
			$titulosEstat = array();
			$crawler->filter("div.mod-header.stathead h4")->each(function($item, $i) use (&$titulosEstat){
				$titulosEstat[] = $item->text();
			});

			$leagueStats = array();
			$crawler->filter("div.mod-content")->each(function($item, $i) use (&$leagueStats,&$titulosEstat){
				$tableStats = array();
				//cada div tiene una tabla
				$tabla = $item->filter("table.tablehead");
				$tabla->filter("tr")->each(function($item, $i) use (&$tableStats){
					$tds = array();
					$item->filter("td")->each(function($item, $i) use (&$tds){
						$contenido = $item->text();
						if ($contenido != "Lista Completa") {
							$tds[] = $contenido;
						}
					});
					//eliminamos la celda de la foto
					if (count($tds) > 2){
						$tds[0] = $tds[1];
						$tds[1] = $tds[2];
						unset($tds[2]);
					}

					$tableStats[] = $tds;
				});
				if ($i > 1){
					$leagueStats[] = $tableStats;
				}
			});


			$responseContent = array(
				"request" => $request->query,
				"tipoConsulta" => $tipoConsulta,
				"titulo" => $titulo,
				"nombresLigas" => $nombresLigas,
				"ligas" => $ligas,
				"leagueStats" => $leagueStats,
				"tests" => print_r($leagueStats,true)
			);

			$response = new Response();
			$response->setCache("720");
			$response->setResponseSubject("MLB");
			$response->createFromTemplate("showLeagueInfoMlb.tpl", $responseContent);
		}

		return $response;
	}

	public function _cubana(Request $request)
	{
		$response = new Response();
		$datos = explode(" ", $request->query);
		$tipoConsulta = $datos[0];
		$dato1 = (isset($datos[1])) ? $datos[1] : "";
		// Setup crawler
		$client = new Client();

		if (strtoupper($tipoConsulta) == "JORNADA"){
			$response = new Response();
			$response->setResponseSubject("No disponible");
			$response->createFromText("Aun no añadimos la jornada de esta liga, en un futuro la añadiremos!");
		}
		elseif (strtoupper($tipoConsulta) == "LIGA"){
			$crawler = $client->request('GET', 'http://www.beisbolencuba.com/series');
			$serieEnCuba=$crawler->filter('#modcontent > div:nth-child(1) > div:nth-child(4) > h2 > a');
			$tituloSerieCuba=$serieEnCuba->text();
			$linkSerieCuba=$serieEnCuba->attr('href');
			$crawler=$client->request('GET', 'http://www.beisbolencuba.com'.$linkSerieCuba);
			$etapas=array();

			$crawler->filter('div.stages')->each(function($item, $i) use (&$etapas,&$client,&$tableStats){
				$tituloEtapa = $item->filter('h3.h3a > a')->text();
				$tableStats=array();
				$crawler2=$client->request('GET', 'http://www.beisbolencuba.com'.$item->filter('div.menustage > a:nth-child(3)')->attr('href'));
				$row=0;
				$crawler2->filter('table.stats:first-of-type > tr')->each(function($item, $i) use (&$tableStats,&$row){
					$tableStats[] = $row;
					$tableStats[$row]=array();
					$item->filter('td, th')->each(function($item, $i) use (&$tableStats,&$actual,$row){
						$text=$item->text();
						$text=str_replace("RACHA","R",$text);
						$text=str_replace("Homeclub","HOME",$text);
						$text=str_replace("Visitador","VIS",$text);
						$tableStats[$row][] = $text;
					});
				$row++;
				});
				$etapas[]=['tituloEtapa' => $tituloEtapa,
									 'tablaEtapa' => $tableStats];
			});
			$dataCuba=['titulo' => $tituloSerieCuba,
								 'etapas' => $etapas];

			$crawler = $client->request('GET', 'http://www.beisbolencuba.com/series');
			$torneoInternac=$crawler->filter('#modcontent > div:nth-child(4) > div:nth-child(2) > h2 > a');
			$tituloIntenac=$torneoInternac->text();
			$linkInternac=$torneoInternac->attr('href');
			$crawler=$client->request('GET', 'http://www.beisbolencuba.com'.$linkInternac);
			$etapas=array();

			$crawler->filter('div.stages')->each(function($item, $i) use (&$etapas, &$client){
				$tituloEtapa= $item->filter('h3.h3a > a')->text();
				$tableStats=array();
				$crawler2=$client->request('GET', 'http://www.beisbolencuba.com'.$item->filter('div.menustage > a:nth-child(3)')->attr('href'));
				$row=0;
				$crawler2->filter('table.stats > tr')->each(function($item, $i) use (&$tableStats,&$row){
					$tableStats[] = $row;
					$tableStats[$row]=array();
					$item->filter('td, th')->each(function($item, $i) use (&$tableStats,&$actual,$row){
						$text=$item->text();
						$text=str_replace("RACHA","R",$text);
						$text=str_replace("Homeclub","HOME",$text);
						$text=str_replace("Visitador","VIS",$text);
						$tableStats[$row][] = $text;
					});
				$row++;
				});
				$etapas[]=['tituloEtapa' => $tituloEtapa,
									 'tablaEtapa' => $tableStats];
			});
			$dataInternacional=['titulo' => $tituloIntenac,
								 					'etapas' => $etapas];
			$ligas=[$dataCuba,$dataInternacional];
			$response = new Response();
			$response->setCache("720");
			$response->setResponseSubject("Liga Cubana");
			$response->createFromTemplate("showLeagueInfoCuba.tpl", array('ligas' => $ligas));
		}
		elseif (strtoupper($tipoConsulta) == "NOTICIAS"){
			$crawler=$client->request('GET','http://www.diariodecuba.com/search/node/neno+diaz?filters=uid%3A5653');
			$noticias=array();
			$crawler->filter('div.search-result')->each(function($item, $i) use (&$noticias,$crawler){
				$noticia=['titulo' => $item->filter('h1.search-title > a')->text(),
									'descripcion' => $item->filter('p.search-snippet')->text(),
								  'link' => substr($item->filter('h1.search-title > a')->attr('href'),27)];
				$noticias[]=$noticia;
			});
			$response = new Response();
			$response->setCache("480");
			$response->setResponseSubject("Liga Cubana");
			$response->createFromTemplate("NoticiasBaseballCuba.tpl", array('noticias' => $noticias));
		}
		return $response;
	}

	private function miGetText($texto)
	{
		$texto = preg_replace('/\s\(.[0-9]{3}\)/', '', $texto);
		$texto = preg_replace('/Top/', 'Alta', $texto);
		$texto = preg_replace('/Bottom/', 'Baja', $texto);
		$texto = preg_replace('/delayed/', 'retrasado', $texto);
		$texto = preg_replace('/Delayed/', 'Retrasado', $texto);
		$texto = preg_replace('/Due Up/', 'Esperando Turno', $texto);
		$texto = preg_replace('/Up/', 'Al bat', $texto);
		$texto = preg_replace('/Pitching/', 'Pitchando', $texto);
		$texto = preg_replace('/On/', 'En', $texto);
		$texto = preg_replace('/1st/', '1ra', $texto);
		$texto = preg_replace('/2nd/', '2da', $texto);
		$texto = preg_replace('/3rd/', '3ra', $texto);
		$texto = preg_replace('/4th/', '4ta', $texto);
		$texto = preg_replace('/5th/', '5ta', $texto);
		$texto = preg_replace('/6th/', '6ta', $texto);
		$texto = preg_replace('/7th/', '7ma', $texto);
		$texto = preg_replace('/8th/', '8va', $texto);
		$texto = preg_replace('/9th/', '9na', $texto);
		$texto = preg_replace('/10th/', '10ma', $texto);
		$texto = preg_replace('/11th/', '11ma', $texto);
		$texto = preg_replace('/12th/', '12ma', $texto);
		$texto = preg_replace('/13th/', '13era', $texto);
		$texto = preg_replace('/14th/', '14ta', $texto);
		$texto = preg_replace('/15th/', '15ta', $texto);
		$texto = preg_replace('/16th/', '16ta', $texto);
		$texto = preg_replace('/17th/', '17ma', $texto);
		$texto = preg_replace('/18th/', '18va', $texto);
		$texto = preg_replace('/19th/', '19na', $texto);
		$texto = preg_replace('/20th/', '20ma', $texto);
		$texto = preg_replace('/20th/', '20ma', $texto);
		$texto = preg_replace('/Arizona/', 'Arizona Diamondbacks', $texto);
		$texto = preg_replace('/Atlanta/', 'Atlanta Braves', $texto);
		$texto = preg_replace('/Baltimore/', 'Baltimore Orioles', $texto);
		$texto = preg_replace('/Boston/', 'Boston Red Sox', $texto);
		$texto = preg_replace('/Chi Cubs/', 'Chicago Cubs', $texto);
		$texto = preg_replace('/Chi White Sox/', 'Chicago White Sox', $texto);
		$texto = preg_replace('/Cincinnati/', 'Cincinnati Reds', $texto);
		$texto = preg_replace('/Cleveland/', 'Cleveland Indians', $texto);
		$texto = preg_replace('/Colorado/', 'Colorado Rockies', $texto);
		$texto = preg_replace('/Detroit/', 'Detroit Tigers', $texto);
		$texto = preg_replace('/Houston/', 'Houston Astros', $texto);
		$texto = preg_replace('/Kansas City/', 'Kansas City Royals', $texto);
		$texto = preg_replace('/Los Angeles/', 'Los Angeles Dodgers', $texto);
		$texto = preg_replace('/La Angels/', 'Los Angeles Angels', $texto);
		$texto = preg_replace('/Miami/', 'Miami Marlins', $texto);
		$texto = preg_replace('/Milwaukee/', 'Milwaukee Brewers', $texto);
		$texto = preg_replace('/Minnesota/', 'Minnesota Twins', $texto);
		$texto = preg_replace('/Ny Mets/', 'New York Mets', $texto);
		$texto = preg_replace('/Ny Yankees/', 'New York Yankees', $texto);
		$texto = preg_replace('/Oakland/', 'Oakland Athletics', $texto);
		$texto = preg_replace('/Philadelphia/', 'Philadelphia Phillies', $texto);
		$texto = preg_replace('/Pittsburgh/', 'Pittsburgh Pirates', $texto);
		$texto = preg_replace('/San Diego/', 'San Diego Padres', $texto);
		$texto = preg_replace('/San Francisco/', 'San Francisco Giants', $texto);
		$texto = preg_replace('/Seattle/', 'Seattle Mariners', $texto);
		$texto = preg_replace('/St Louis/', 'St. Louis Cardinals', $texto);
		$texto = preg_replace('/Tampa Bay/', 'Tampa Bay Rays', $texto);
		$texto = preg_replace('/Texas/', 'Texas Rangers', $texto);
		$texto = preg_replace('/Toronto/', 'Toronto Blue Jays', $texto);
		$texto = preg_replace('/Washington/', 'Washington Nationals', $texto);
		return $texto;
	}

	private function file_get_contents_curl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}
