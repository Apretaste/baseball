<center>
<h1>MLB - {$smarty.now|date_format:"%Y"} </h1>
<!--<font color="red" size="1">{*$tests*}</font></br>-->
{for $n=1 to 6}
	{if $n==1}
		<h2>{$nombresLigas[0]}</h2>
		{space5}
	{else if $n==4}
		<h2>{$nombresLigas[1]}</h2>
		{space5}
	{/if}
	<table style="text-align:center" width="100%" border="1">
		<tr>
		{for $i=1+($n-1)*11 to 12+($n-1)*11}
			{if $i==1+($n-1)*11}
				<th width="40px"><strong>{$ligas['firstColum'][($n-1)*6]}</strong></th>
			{else}
				{if $i-2-($n-1)*11==4}
					<th width="30px"><strong>LOC</strong></th>
				{else}
					<th width="40px"><strong>{$ligas['headers'][$i-2-($n-1)*11]}</strong></th>
				{/if}
			{/if}
		{/for}
		</tr>
		{for $j=1+($n-1)*5 to 5+($n-1)*5}
			<tr>
				{for $i=(0+($j-1)*11) to (11+($j-1)*11)}
					{if $i==($j-1)*11}
						<td><font size="2">{$ligas['firstColum'][$j+($n-1)]}</font></td>
					{else}
						<td><font size="2">{$ligas['data'][$i-1]}</font></td>
					{/if}
				{/for}
			</tr>
		{/for}
	</table>
	{space10}
{/for}
	<table style="text-align:center" width="100%">
		<tr>
			<td><font size="2"><b>LA</b> Liga Americana</font></td>
			<td><font size="2"><b>LN</b> Liga Nacional</font></td>
		</tr>
		<tr>
			<td><font size="1"><b>G:</b> Juegos Ganados</font></td>
			<td><font size="1"><b>P:</b> Juegos Perdidos</font></td>
		</tr>
		<tr>
			<td><font size="1"><b>%:</b> Porcentaje de Victorias</font></td>
			<td><font size="1"><b>JD:</b> Juegos Detrás</font></td>
		</tr>
		<tr>
			<td><font size="1"><b>LOC:</b> Récord como Local</font></td>
			<td><font size="1"><b>VISITANTE:</b> Record de visitante</font></td>
		</tr>
		<tr>
			<td><font size="1"><b>CF:</b> Carreras a favor</font></td>
			<td><font size="1"><b>CC:</b> Carreras en contra</font></td>
		</tr>
		<tr>
			<td><font size="1"><b>DIF:</b> Diferencia de carreras</font></td>
			<td><font size="1"><b>R:</b> Racha actual</font></td>
		</tr>
		<tr>
			<td colspan="2"><font size="1"><b>U10:</b> Récord de los 10 últimos juegos</font></td>
		</tr>
	</table>

{space15}
	<h2>L&iacute;deres de la MLB</h2>
	{foreach $leagueStats as $tableStat}
		{if !empty($tableStat)}
			<table width="100%" border="0" style="margin-left:20%;">
				{for $i=0 to 5}
				{assign var="tr" value=$tableStat[$i]}
					<tr>
						{foreach $tr as $td}
							{if $i == 0}
								<th align="left" width="30%"><strong>{$td}</strong></th>
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
