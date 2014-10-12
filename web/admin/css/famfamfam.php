<?php
if ($handle = opendir('icons')) {

    while (false !== ($file = readdir($handle))) {
		
		if($file!='.' && $file!='..')
		{
			$nazwa=str_replace('.png','',$file);
			echo ".icon-$nazwa { background-image: url('icons/$file'); }\n";
		}
    }



}
/*
if ($handle = opendir('icons')) {
    echo "<h2>Ikony</h2>\n
	
	<table>
	";
    while (false !== ($file = readdir($handle))) {
		
		if($file!='.' && $file!='..')
		{
			echo'<div style="width: 300px; float: left; margin: 3px; padding: 2px; border: 1px #eee solid; font-family: Arial,sans-serif; color: #333; font-size: 10px;">
			<img src="icons/'.$file.'" alt="'.$file.'"/>
			'.$file.'
			</div>';
		}
    }

}
*/
?>