<center>
<h1>Liga Cubana - {$smarty.now|date_format:"%Y"} </h1>
{foreach $ligas as $liga}
<h2>{$liga['titulo']}</h2>
	{foreach $liga['etapas'] as $etapa}
	<table style="text-align:center" width="100%" border="1">
	<tr>
		<td colspan="13"><h2  style="margin:0;">{$etapa['tituloEtapa']}</h2></td>
	</tr>
	{$nfila=0}
		{foreach $etapa['tablaEtapa'] as $fila}
			<tr>
				{$ncol=0}
				{foreach $fila as $columna}
				{if $columna|count_characters>2 and $ncol==0}
				{$nfila=0}
				{/if}
					{if $nfila==0 and $ncol==0}
					<td font-size="2" colspan="2"><strong>{$columna}</strong></td>
					{else if $nfila==0}
					<td font-size="2"><strong>{$columna}</strong></td>
					{else}
					<td font-size="2">{$columna}</td>
					{/if}
					{$ncol=$ncol+1}
				{/foreach}
				{$nfila=$nfila+1}
			</tr>
		{/foreach}
	</table>
	{space15}
	{/foreach}
{/foreach}
	<table style="text-align:center" width="100%">
		<tr>
			<td><font size="2"><b>JJ:</b> Juegos Jugados</font></td>
			<td><font size="2"><b>AVE:</b> Promedio de Victorias</font></td>
		</tr>
		<tr>
			<td><font size="1"><b>JG:</b> Juegos Ganados</font></td>
			<td><font size="1"><b>JP:</b> Juegos Perdidos</font></td>
		</tr>
		<tr>
			<td><font size="1"><b>VIS</b> Visitador</font></td>
			<td><font size="1"><b>HOME:</b> Homeclub</font></td>
		</tr>
		<tr>
			<td><font size="1"><b>CA:</b> Carreras Anotadas</font></td>
			<td><font size="1"><b>CP: </b> Carreras Permitidas</font></td>
		</tr>
		<tr>
			<td><font size="1"><b>DIF:</b> Diferencia</font></td>
			<td><font size="1"><b>R:</b> Racha actual</font></td>
		</tr>
		<tr>
			<td colspan="2"><b>CDIF:</b> Diferencia de Carreras</font></td>
		</tr>
	</table>
{space10}
{button href="BASEBALL CUBANA NOTICIAS" caption="Noticias" color="green"}
{button href="BASEBALL" caption="Ligas" color="green"}
