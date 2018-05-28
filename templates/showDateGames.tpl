<center>
<h1>Juegos del d&iacute;a: {$fecha}</h1>
{if $juegosEnCurso > 0}
	<h2>Juegos en curso</h2>
	<table style="text-align:center" width="100%">
		<tr>
			<th><strong>Entrada</strong></th>
			<th><strong>Local</strong></th>
			<th></th>
			<th><h2>Visitante</h2></th>
			<th colspan="3"><h2>Resultado</h2></th>
		</tr>
		{foreach from=$games item=game name=fila} <!-- por cada row -->
			{strip}
			{if $game['type'] == 'En curso'}
				<tr>
					<td>{$game['inning']}</td>
					<td>{$game['homeTeam']}</td>
					<td>-</td>
					<td>{$game['awayTeam']}</td>
					<td><b>{$game['homeTeamScore']}</b></td>
					<td><b>:</b></td>
					<td><b>{$game['awayTeamScore']}</b></td>
				</tr>
				<tr>
					<td colspan="7"><small><font color="blue" size="1">{$game['gameStatus']}</font></small></td>
				</tr>
			{/if}
			{/strip}
		{/foreach}
	</table>
{/if}
{if $juegosProgramados > 0}
	<table style="text-align:center" width="100%">
		<tr>
			<th><strong>Juego</strong></th>
			<th><strong>Hora</strong></th>
		</tr>
		{foreach from=$games item=game name=fila} <!-- por cada row -->
			{strip}
			{if $game['type'] == 'Programado para hoy'}
				<tr>
					<td align="left">{$game['game']}</td>
					<td width="30%">{$game['gameHour']}</td>
				</tr>
			{/if}
			{/strip}
		{/foreach}
	</table>
	{space15}
{/if}

{if $juegosTerminados > 0}
	<h2>Resultados finales</h2>
	<table style="text-align:center" width="100%">
		<tr>
			<th><h2>Entrada</h2></th>
			<th><h2>Local</h2></th>
			<th></th>
			<th><h2>Visitante</h2></th>
			<th colspan="3"><h2>Resultado</h2></th>
		</tr>
		{foreach from=$games item=game name=fila} <!-- por cada row -->
			{strip}
			{if $game['type'] == 'Resultado Final'}
				<tr>
					<td>{$game['inning']}</td>
					<td>{$game['homeTeam']}</td>
					<td>-</td>
					<td>{$game['awayTeam']}</td>
					<td><b>{$game['homeTeamScore']}</b></td>
					<td><b>:</b></td>
					<td><b>{$game['awayTeamScore']}</b></td>
				</tr>
			{/if}
			{/strip}
		{/foreach}
	</table>
	{space15}
{/if}

<table style="text-align:center;" width="100%">
	<tr>
		<td style="" colspan="1">
			{button href="BASEBALL MLB LIGA" caption="Ver Liga" color="green" }
		</td>
	</tr>
</table>
{space10}
</center>
