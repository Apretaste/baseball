<center>
<h1>{$titulo}</h1>
<!--<font color="red" size="1">{*$tests*}</font></br>-->
{foreach $nombresLigas as $nombreLiga}
	{space10}
	<h1>{$nombreLiga}</h1>
	{space5}
	<table style="text-align:center" width="100%" border="1">
		<tr>
		{for $i=0 to 11}
			<th><h2>{$ligas[$nombreLiga]['cabecera'][$i]|regex_replace: '/GF/':'CF'|regex_replace: '/GC/':'CC'}</h2></th>
		{/for}
		</tr>

		{for $j=0 to 4}
			{assign var="array" value= $ligas[$nombreLiga]['posiciones'][$j]}
			<tr>
				{foreach $array as $td}
					<td><font size="2">{$td|regex_replace: '/[A-Z]{2,3}/':''}</font></td>
				{/foreach}
			</tr>
		{/for}
	</table>

	{space10}

	<table style="text-align:center" width="100%" border="1">
		<tr>
		{for $i=12 to 23}
			<th><h2>{$ligas[$nombreLiga]['cabecera'][$i]|regex_replace: '/GF/':'CF'|regex_replace: '/GC/':'CC'}</h2></th>
		{/for}
		</tr>
		{for $j=5 to 9}
			{assign var="array" value= $ligas[$nombreLiga]['posiciones'][$j]}
			<tr>
				{foreach $array as $td}
					<td><font size="2">{$td|regex_replace: '/[A-Z]{2,3}/':''}</font></td>
				{/foreach}
			</tr>
		{/for}
	</table>
	{space10}
	<table style="text-align:center" width="100%" border="1">
		<tr>
		{for $i=24 to 35}
			<th><h2>{$ligas[$nombreLiga]['cabecera'][$i]|regex_replace: '/GF/':'CF'|regex_replace: '/GC/':'CC'}</h2></th>
		{/for}
		</tr>
		{for $j=10 to 14}
			{assign var="array" value= $ligas[$nombreLiga]['posiciones'][$j]}
			<tr>
				{foreach $array as $td}
					<td><font size="2">{$td|regex_replace: '/[A-Z]{2,3}/':''}</font></td>
				{/foreach}
			</tr>
		{/for}
	</table>
{/foreach}

{space10}

<table style="text-align:center" width="100%">
	<tr>
		<td><font size="1"><b>G:</b> Juegos Ganados</font></td>
		<td><font size="1"><b>P:</b> Juegos Perdidos</font></td>
		<td><font size="1"><b>%:</b> Porcentaje de Victorias</font></td>
		<td><font size="1"><b>JD:</b> Juegos Detrás</font></td>
	</tr>
	<tr>
		<td><font size="1"><b>LOCAL:</b> Récord como Local</font></td>
		<td><font size="1"><b>VISITANTE:</b> Record de visitante</font></td>
		<td><font size="1"><b>CF:</b> Carreras a favor</font></td>
		<td><font size="1"><b>CC:</b> Carreras en contra</font></td>
	</tr>
	<tr>
		<td><font size="1"><b>DIF:</b> Diferencia de carreras</font></td>
		<td><font size="1"><b>R:</b> Racha actual</font></td>
		<td><font size="1"><b>U10:</b> Récord de los 10 últimos juegos</font></td>
	</tr>
</table>

{space15}

<h1>L&iacute;deres de la MLB</h1>
{foreach $leagueStats as $tableStat}
	{if !empty($tableStat)}
		<table width="100%" border="0">
			{for $i=0 to 5}
			{assign var="tr" value=$tableStat[$i]}
				<tr>
					{foreach $tr as $td}
						{if $i == 0}
							<th align="left"><h2>{$td}</h2></th>
						{else}
							<td>{$td}</td>
						{/if}
					{/foreach}
				</tr>
			{/for}
		</table>
		{space10}
	{/if}
{/foreach}

{space15}

{button href="BASEBALL MLB jornada {$smarty.now|date_format:"%d/%m/%Y"}" caption="Jornada Actual" color="green"}
{button href="BASEBALL MLB jornada {'+1 day'|date_format:"%d/%m/%Y"}" caption="Pr&oacute;xima Jornada" color="green"}

{space10}
