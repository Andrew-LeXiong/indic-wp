﻿<!DOCTYPE html>
<html>
<div class='jumbotron' id='jumbos'></div><br>
<div class='jumbotron1' id='jumbos1'></div>
<head>
	<form action = "index.php" method = "get">
		<input type="submit" value="Home" style="height:40px;width:100px;float: right; font-size: 100%; cursor:pointer" />
	</form>

	<!--<button style="height:40px;width:100px;float: right; font-size: 100%" type="button" onclick="index.html" >Home</button>-->
	<title>Indic Text Analyzer</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="analysis_style.css"/>
</head>
<body>

<h1 style="text-align:center"><B>Indic Text Analyzer</B></h1>

<div id="mainbody">
	<div id="tabholder">
		<ul class="tabs">
			<li><a href="#numbers" tabindex="1" id="num_tab">Numerical Results</a></li>
			<li><a href="#analitic" tabindex="2" id="anal_tab">Analyzed Results</a></li>
		</ul>
	</div>
	<div id="mainchild">
		<div id="numeric" class="results">
			<br><p style="text-align:center">Copy and paste your input below</p>

			<div><center><p>Select a language<select id="language" onchange="dropdown_update()">
				<option value="Telugu">Telugu</option>
				<option value="English">English</option>
				<option value="Hindi">Hindi</option>
				<option value="Gujarati">Gujarati</option>
				<option value="Malayalam">Malayalam</option>
			</select></p></center>
			</div>

			<textarea rows="10" cols="85" id="inputarea" oninput="process_update()">
				అరక
			</textarea>
			<table id="stats">
				<tr>
					<td>Total Words: <span id="tot_words" class="statbox">..</span></td>
					<td>Total Strength: <span id="tot_strength" class="statbox">..</span></td>
					<td>Average Strength: <span id="avg_strength" class="statbox">..</span></td>
				</tr>

				<tr>
					<td>Total Letters: <span id="tot_letters" class="statbox">..</span></td>
					<td>Total Weight: <span id="tot_weight" class="statbox">..</span></td>
					<td>Average Weight: <span id="avg_weight" class="statbox">..</span></td>
				</tr>
				
				<tr>
					<td>Total Lines: <span id="tot_lines" class="statbox">..</span></td>
					<td style="text-align:right"><span id="status"></span></td>
					<td align="right"><input type="button" id="analyze" value="Analyze" onclick="analyze_this()"></td>
				</tr>
			</table>

		</div>
		<div id="analysis" class="results">
		</div>
	</div>
</div>

<script>
	var auto_anal;

	function analyze_this() {

		if( $('#inputarea').val().trim() == "" ) { // don't process if empty or just whitespace
			$('#status').text("text is empty");
			return;
		}

		xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			// 4 = request has been completed
			// 200 = 200 - server response OK
			if( xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {
				var retdata = xmlhttp.responseText.split("|"); // comes from analyze.php lines 85-88
				$('#tot_words').text( retdata[0] );
				$('#tot_letters').text( retdata[1] );
				$('#tot_lines').text( retdata[2] );
				$('#tot_strength').text( retdata[3] );
				$('#tot_weight').text( retdata[4] );
				$('#avg_strength').text( parseFloat(retdata[5]).toPrecision(2) );
				$('#avg_weight').text( parseFloat(retdata[6]).toPrecision(2) );
				
				
				var analtable = "<br><table id='analysis_table'><tr><th>No.</th><th>Word</th><th>Length</th><th>Frequency<th></tr>";
				var rownum = 0;

				for(var i=7; i < retdata.length; i++) {
					var analdata = retdata[i].split(',');
					// x += y is shorthand for x = x + y
					analtable += "<tr class='analrow" + (rownum++ % 2) + "'><td class='analno'>" + analdata[0];
					analtable += "</td><td class='analword'>" + analdata[1] + "</td>";
					analtable += "<td class='anallen'>" + analdata[2] + "</td>"
					analtable += "<td class='analfreq'>" + analdata[3] + "</td></tr>";
				}
				$('#analysis').html(analtable + '</table><br>');
				$('#status').text("done");
			}
		}

		// Send the request off to a PHP file (analyze.php) on the server
		xmlhttp.open("POST", "analyze.php");
		//To POST data like an HTML form, add an HTTP header with setRequestHeader()
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		// adding a newline to the submission, since analyze.php counts lines wrong
		// if the essay ends with punctuation and no newline. This is a terrible
		// way to fix that problem, but it works for now.

		//this is the way to get two inputs (user input data and language selection)
		var e = document.getElementById("language");
		var strUser = e.options[e.selectedIndex].value;
		var  the_data = 'to_parse='+$('#inputarea').val()+'&to_language='+strUser.toString();
		
		//Specify the data you want to send in the send() method
		xmlhttp.send( the_data );

		$('#status').text("processing...");
	} // end analyze_this

	function dropdown_update(){
		clearTimeout(auto_anal);
		auto_anal = setTimeout(analyze_this, 2000);
		$('.statbox').text("..");
		$('#status').text("text is unprocessed");
	} // end dropdown_update

	function process_update() {
		// prevent analyze_this from executing until user is done typing
		clearTimeout(auto_anal);
		auto_anal = setTimeout(analyze_this, 2000);
		$('.statbox').text("..");
		$('#status').text("text is unprocessed");
	} // end process_update

	// +/- CSS for tabs
	$(document).ready(function() {
		$('.tabs a').click(function() {
			var $this = $(this);
			$this.addClass('fore');
			if($this.attr('href') == "#numbers") {
				$('#anal_tab').removeClass('fore');
				$('#numeric').show();
				$('#analysis').hide();
			}
			else {
				$('#num_tab').removeClass('fore');
				$('#numeric').hide();
				$('#analysis').show();
			}
			return (false);
		}); // end tabs a click
		$('.tabs li:first a').click();
	}); // end document.ready

</script>

<script>
	$('#jumbos').on('click', function () {
		window.location = "index.html";
	});

</script>

</body>
</html>


