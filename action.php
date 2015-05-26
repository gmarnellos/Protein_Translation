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

$output=system("perl translate_all_frames1_3.pl -in $uploadfile -out /var/www/html/minilims/files/$outfasta -frames $numframes -minlength $minlength", $retval);


//$htmlResults = '<br/><br/><br/> </pre><hr /> </pre>' . "The results have been written to: /tmp/$tempname/$outfasta" . ' </pre><hr /> </pre>';

$htmlResults = '<br/><br/><br/> </pre><hr /> </pre>' . "The results have been written to: <a href=http://msprl.rc.fas.harvard.edu/minilims/misc/MiniFileView.php/$outfasta> $outfasta </a>" . ' </pre><hr /> </pre>';

print $page->toHTML("Protein Translation Results", $htmlResults);

?>

