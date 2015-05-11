<?php

$topdir = dirname(dirname(dirname(dirname(dirname(__FILE__)))));

require_once "$topdir/GlobalConfig.php";

$page  = new PageTemplate("Protein Translation");

$html = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">

<html xmlns='http://www.w3.org/1999/xhtml'>
   <head >
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title >Protein Translation</title>
   </head>
<body>
<!-- <h1>Protein Translation</h1> -->

<form enctype='multipart/form-data' id='proteintranslate' method='post' action='action.php'>

   <p>
   Number of Frames (either 3 or 6): <input type='text' name='NumFrames' />
   </p>

   <p>
   Minimum Length of peptides to return: <input type='text' name='MinLength' />
   </p>

   <p>
   Output Fasta file name : <input type='text' name='Output' />
   </p>

   <p>
   <!-- MAX_FILE_SIZE in bytes preceding the file input field -->
 <!--    <input type='hidden' name='MAX_FILE_SIZE' value='2000000' /> -->
   <!-- Name of input element determines name in $_FILES array -->
   Input Fasta file to upload: <input type='file' name='uploadFile'>
   <input type='submit' value='Upload file and Process'>
   </p>

<!--   <p> 
     <input type='submit' name='Submit' value='Submit' />
   </p> -->
</form>

</body>
</html>";

print $page->toHTML("Protein Translation", $html);

?>
