<center>
	<h1>Selecciona tu liga:</h1>
</center>

<table style="text-align:center;" width="100%">
	<tr>
		<th><h2>Liga</h2></th>
		<th><h2>Jornada Actual</h2></th>
		<th><h2>M&aacute;s informaci&oacute;n</h2></th>
	</tr>
	<tr bgcolor="{cycle values="#f2f2f2,white"}">
		<td style="font-weight: bold;">Major League Baseball</td>
		<td style="">{link href="BASEBALL MLB jornada {$smarty.now|date_format:"%d/%m/%Y"}" caption="Juegos de hoy"}</td>
		<td style="" colspan="1">{button href="BASEBALL MLB LIGA" caption="Ver Liga" color="green" size="small"}</td>
	</tr>
	<tr bgcolor="{cycle values="#f2f2f2,white"}">
		<td style="font-weight: bold;">Liga Cubana</td>
		<td style="">{link href="BASEBALL CUBANA jornada {$smarty.now|date_format:"%d/%m/%Y"}" caption="Juegos de hoy"}</td>
		<td style="" colspan="1">{button href="BASEBALL CUBANA LIGA" caption="Ver Liga" color="green" size="small"}</td>
	</tr>
	<tr bgcolor="{cycle values="#f2f2f2,white"}">
		<td colspan="4">
			{space10}
			<small>&iexcl;Proximamente las ligas del caribe!</small>
		</td>
	</tr>
</table>

{space10}
