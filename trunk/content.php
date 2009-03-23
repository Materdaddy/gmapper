<script language="JavaScript" type="text/javascript">
var opt = new OptionTransfer("pics_not_used","pics_used");
opt.setAutoSort(true);
opt.setDelimiter(",");
opt.init(document.forms['gmap']);
function updatePic(num)
{
}
</script>

<form action="/mmrosko/wp-gmapper/post.html" method="post" name="gmap" id="gmap">
	<input type="hidden" name="map_post_id" id="map_post_id" value="from PHP post var">

<h1>Main options</h1>
<h3>map size</h3>
width: <input type="textbox" id="gmap_width" name="gmap_width">
height: <input type="textbox" id="gmap_height" name="gmap_height">
<h3>style</h3>
<input type="radio" name="gmap_style" id="gmap_style" value="map">Map
<input type="radio" name="gmap_style" id="gmap_style" value="satellite">Satellite
<input type="radio" name="gmap_style" id="gmap_style" value="hybrid">Hybrid
<!--
<h3>other options</h3>
none at this time!
-->

<h1>Pictures</h1>

<table border=1>
<tr>
<td rowspan=2>
<select name="pics_not_used" multiple size=10 onDblClick="opt.transferRight()" onChange="updatePic(this.value)">
	<option value="post_id1">dsc1</option>
	<option value="post_id2">dsc2</option>
	<option value="post_id3">dsc3</option>
	<option value="post_id4">dsc4</option>
	<option value="post_id5">dsc5</option>
	<option value="post_id6">dsc6</option>
</select>
</td>
<td>
<img src="blah">
</td>
<td rowspan=2>
<select name="pics_used" multiple size=10 onDblClick="opt.transferLeft()">
</select>
</td>
</tr>
<tr>
<td>
		<input type="button" name="right" value="&gt;&gt;" onClick="opt.transferRight()">
		<input type="button" name="right" value="All &gt;&gt;" onClick="opt.transferAllRight()">
		<input type="button" name="left" value="&lt;&lt;" onClick="opt.transferLeft()">
		<input type="button" name="left" value="All &lt;&lt;" onClick="opt.transferAllLeft()">
</td>
</tr>
</table>
</form>
