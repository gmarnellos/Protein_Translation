<?php

$topdir = dirname(dirname(dirname(dirname(dirname(__FILE__)))));

require_once "$topdir/GlobalConfig.php";

$page  = new PageTemplate("Protein Translation Results");

if(isset($_POST['Output'])) $outfasta=$_POST['Output'];
if(isset($_POST['NumFrames'])) $numframes=$_POST['NumFrames'];
if(isset($_POST['MinLength'])) $minlength=$_POST['MinLength'];

$uploaddir = '/tmp/';
$tempname = basename($_FILES['uploadFile']['tmp_name']);
$uploadfile = $uploaddir . basename($_FILES['uploadFile']['name']);

$myerror = $_FILES['uploadFile']['error'];

if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadfile)) {
  //  echo "File was successfully uploaded.\n";
} else {
 //   echo "File $uploadfile  was not successfully uploaded.\n";
}

system("mkdir -p /tmp/$tempname");

$output=system("perl translate_all_frames1_3.pl -in $uploadfile -out /tmp/$tempname/$outfasta -frames $numframes -minlength $minlength", $retval);

$htmlResults = '<br/><br/><br/> </pre><hr /> </pre>' . "The results have been written to: /tmp/$tempname/$outfasta" . ' </pre><hr /> </pre>';

//echo nl2br( file_get_contents("/tmp/$tempname/$outfasta") );

print $page->toHTML("Protein Translation Results", $htmlResults);

?>

