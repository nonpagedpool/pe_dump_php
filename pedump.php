<html>
<title>PE Dump</title>
	<form enctype="multipart/form-data" action="pedump.php" method="POST">
		<input type="hidden" name="MAX_FILE_SIZE" value="200000" />
			Choose a PE file to upload: <input name="pefile" type="file" /><br />
		<input type="submit" value="Upload File" />
	</form>
	<hr>
</html>
<?php

function run_pefile($pe_file_path)
{
	$out_file = $pe_file_path . ".txt";
	echo exec("python pedump.py " . $pe_file_path . " " . $out_file);
	echo "<pre>";
	echo file_get_contents($out_file);
	echo "</pre>";
	unlink($out_file);
}

if(isset($_FILES['pefile']))
{
    if (!isset($_FILES['pefile']['error']) ||
        is_array($_FILES['pefile']['error'])) 
	{
        throw new RuntimeException('Invalid parameters.');
    }

    switch ($_FILES['pefile']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded file size limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

	$target_path = "uploads/";
	$pe_name = basename($_FILES['pefile']['name']);
	$target_path = $target_path . $pe_name; 

	if($_FILES['pefile']['size'] > 200000)
	{
		echo "File has exceeded a reasonable size and will not be processed...";
	}
	else
	{
		if(move_uploaded_file($_FILES['pefile']['tmp_name'], $target_path)) 
		{
			echo "PE File: " . $pe_name;
			echo "<hr>";
			run_pefile($target_path);
			unlink($target_path);
		} 
		else
		{
			echo "There was an error uploading the file";
		}
	}
}
?>
