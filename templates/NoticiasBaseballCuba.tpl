<center>
<h1>Ultimas Noticias de Baseball en Cuba</h1>
<small>Por el Neno Diaz</small>
<table>
{foreach $noticias as $noticia}
<tr>
  <td><b>{link href="DIARIODECUBA HISTORIA {$noticia['link']}" caption="{$noticia['titulo']}"}</b></td>
</tr>
<tr>
  <td>{$noticia['descripcion']|truncate:200:" ..."}</td>
</tr>
{/foreach}
</table>
