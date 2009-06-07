<?p# <?php // Tricking TextMate

if (@txpinterface == 'admin') {
	add_privs('egg_anonymizer','1,2,3,4,5');
	register_tab('extensions', 'egg_anonymizer', "Anonymizer");
	register_callback('egg_anon_javascript', 'discuss');
	register_callback('egg_anonymizer', 'egg_anonymizer');
}

function egg_anonymizer ($event, $step) {
	if(!$step or !in_array($step, 
	            array('egg_anon_doit', 'egg_anon_them_all'
	                  ))){
		egg_anon_init();
	} 
	else $step();
}
function egg_anon_init($message='') {	
    pagetop("Anonymizer",$message); 
	echo('<p style="text-align:center"><a href="?event=egg_anonymizer&step=egg_anon_them_all">Anonymize all Comments!</a></p>');
}

function egg_anon_javascript() {
	echo <<<js
<script type="text/javascript">
	var tablerows = $('table#list tr'); 
	var thtext = document.createTextNode('Anonymize');
	var thtd = document.createElement('th');
	thtd.appendChild(thtext);
	tablerows[0].appendChild(thtd);

	for (var i = 1; i < (tablerows.length-1); i++) {
		var thatid = tablerows[i].getElementsByTagName('a')[0].innerHTML;
		var link = document.createElement('a');
		link.setAttribute('href',('?event=egg_anonymizer&step=egg_anon_doit&id='+thatid));
		var linktext = document.createTextNode('Anonymize it!');
		link.appendChild(linktext);
		var tabledata = document.createElement('td');
		tabledata.appendChild(link);
		tablerows[i].appendChild(tabledata);
	};
</script>
js;
}

function egg_anon_doit(){
	pagetop('Anonymizer');
	$id=gps('id');
	safe_update("txp_discuss", "ip='127.0.0.1'", "discussid='".$id."'"); 
	echo('<p style="text-align:center"><a href="'.$_SERVER["HTTP_REFERER"].'">Zurück zu den Kommentaren?</a></p>');
}

function egg_anon_them_all(){
	$done = safe_update("txp_discuss", "ip='127.0.0.1'", "1=1"); 
	if ($done) {
		return egg_anon_init('Alle IP-Adressen erfolgreich gelöscht.');
	} else {
		return egg_anon_init('MySQL-Fehler beim löschen der IP-Daten.');
	}
}

/*
--- PLUGIN METADATA ---
Name: egg_anonymizer
Version: 0.1
Type: 1
Description: Anonymizes commenters ip adresses
Author: Eric Eggert
Link: http://yatil.de/
--- BEGIN PLUGIN HELP ---
<h1>Anonymizer <small>by Eric Eggert, published at <a href="http://yatil.de">yatil.de</a></small></h1>

<p>After a court decision in Germany website owners are not allowed to store personal information not necessary for processing information (like IPs). That?s especially important for comments, so site owners should anonymize comment IP adresses after checking them.</p>
<p>More information at <a href="http://www.advisign.de/datenschutz/2007-10/die-vorratsdatenspeicherung-fuer-den-hausgebrauch-oder-darf-man-ip-adressen-der-websitebesucher-speichern">advisign.de</a> (in German).</p>            

<h2>Usage Instructions</h2>      

<p>This plugin adds links for anonymizing commenters IP adresses to the comments tab. (JavaScript required, and TXP 4.0.5 for jQuery)</p>
<p>It adds a Anonymizer tab under Extensions, where all comments can be anonymized with one click.</p>

<h1>Anonymizer <small>von Eric Eggert, veröffentlicht auf <a href="http://yatil.de">yatil.de</a></small></h1>
   
<p>Nach einer Gerichtsentscheidung in Deutschland ist es Betreibern von Webseiten nicht mehr erlaubt persönliche Informationen (wie IPs) dauerhaft zu speichern. Das gilt vor allem für Kommentare in Textpattern, deren IP-Adressen sollten nachdem sie überprüft wurden gelöscht werden.</p>
<p>Weitere Informationen unter <a href="http://www.advisign.de/datenschutz/2007-10/die-vorratsdatenspeicherung-fuer-den-hausgebrauch-oder-darf-man-ip-adressen-der-websitebesucher-speichern">advisign.de</a>.</p>                     


<h2>Verwendung</h2>

<p>Dieses Plugin fügt einen Link im Kommentar-Tab ein, welcher es erlaubt einzelne Kommentare zu anonymisieren. (JavaScript und TXP 4.0.5 wegen jQuery benötigt)</p>
<p>Zudem gibt es einen Anonymizer-Tab, in dem alle Kommentare auf einmal anonymisiert werden können.</p>
--- END PLUGIN HELP & METADATA ---
*/
?>