<?
require_once("class.phpmailer.php");
require_once("mailhost.inc.php");

$monthText = array("01" => "January", "02" => "February", "03" => "March", "04" => "April", "05" => "May", "06" => "June", "07" => "July", "08" => "August", "09" => "September", "10" => "October", "11" => "November", "12" => "December");

$accepted_file_types = array("image/gif","image/jpeg","image/pjpeg","application/msword","text/plain","application/pdf","application/mspowerpoint","application/powerpoint","image/bmp","text/richtext","application/excel","application/x-excel","application/x-msexcel","application/vnd.ms-excel","application/x-troff-msvideo","video/avi","video/msvideo","video/x-msvideo","video/quicktime","video/x-flv","application/vnd.ms-powerpoint","video/x-ms-wmv","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/x-mspublisher","application/vns.ms-publisher","application/x-shockwave-flash");

function lightupRequired ($fieldName) {
  if($_SESSION['regadmin_missingFields']) {
    if (in_array($fieldName,$_SESSION['regadmin_missingFields'])===TRUE) { $lightup = "style=\"color: red; font-weight: bold;\""; }
  }
  return $lightup;
}

function monthDrop($compare=NULL) {
  global $monthText;
  foreach($monthText as $key => $value) {
    if ($compare == $key) { $sel = "selected"; } else { $sel = ""; }
    $vals .= "<option value=\"$key\" $sel>$value</option>";
  }
  return $vals;
}

function showSelected($needle,$haystack,$attr="selected") {
  if (is_array($haystack)) { 
    if (in_array($needle,$haystack)) { $sel = $attr; } 
  } else { 
    if ($haystack==$needle) { $sel = $attr; } else { $sel = ""; } 
  }
  return $sel;
}

function yearDrop($startYear, $endYear, $compare=NULL, $reverse=NULL) {
  for($iy=$startYear;$iy<=$endYear;$iy++){ 
    if ($compare == $iy) { $sel = "selected"; } else { $sel = ""; }
    if ($reverse==1) { 
      $vals = "<option value=\"$iy\" $sel>$iy</option>".$vals; 
    } else {
        $vals .= "<option value=\"$iy\" $sel>$iy</option>";
    }
   } 
   return $vals;
}
function dayDrop($compare=NULL) {
  for($id=01;$id<=31;$id++){ 
    if ($compare == $id) { $sel = "selected"; } else { $sel = ""; }
    $vals .= "<option value=\"$id\" $sel>$id</option>";
   } 
   return $vals;
}

// FILTER SEARCH STRING
function searchFilter($string) {
  $newString=preg_replace('#[^0-9A-Za-z/-]#i', '', $string);
  $newString = mysql_real_escape_string($string);
  RETURN $newString;
}

function sizeupBytes($bytes) {
  $size_MB = round($bytes / 1048576);
  $size_GB = round(($bytes / 1073741824),2) ;
  if ($size_GB < 1) { $size = $size_MB."MB"; } else { $size = $size_GB."GB"; }
  return $size;
}

function sltd($value, $match) {
  if ($value==$match) { $sel = "selected"; }
  else { $sel = ""; }
  return $sel;
}

function friendLink($user_ID) {
  global $_SESSION;
  if (is_array($_SESSION['user_friends']) && in_array($user_ID,$_SESSION['user_friends'])) { $alreadyFriend = "1"; }
  if ($alreadyFriend)
    $theLink =  "<span style=\"color: green;\">Added Friend</span>";
  else 
    $theLink = "<a href=\"friends_add.php?friend_ID=$user_ID\">Add to Friends</a>";
    
  return $theLink;
}
	
function substrword($thestring, $thelength, $trailingstring)
{
$part = substr($thestring, 0,$thelength);
$part2 = substr($thestring, $thelength);
$x = strpos($part, " ")+1;
$y = strlen($part);

  $pos = strpos($part2, " ");
  $part3 = substr($part2, 0, $pos);
  $part4 = $trailingstring;
  return "$part$part3$part4";
}
// CREATE COLLAPSABLE USER FOLDERS
function createFolders($parentID, $sc_ID, $type, $level) {
  global $db;
  if ($type=="course") {
    $f_res1 = mysql_query("SELECT * FROM course_dirs WHERE switch!='1' AND parent_ID='". $parentID ."' AND course_ID='".$sc_ID."' ORDER BY CAST(SUBSTR(name,9) AS UNSIGNED INTEGER)");
  } elseif ($type=="perm") {
    $f_res1 = mysql_query("SELECT * FROM user_folders WHERE parent_ID='". $parentID ."' AND perm='1' ORDER BY CAST(SUBSTR(name,9) AS UNSIGNED INTEGER)");
  } elseif ($type=="library") {
    $f_res1 = mysql_query("SELECT * FROM library_folders WHERE parent_ID='". $parentID ."' ORDER BY CAST(SUBSTR(name,9) AS UNSIGNED INTEGER)");
  } else {
    $f_res1 = mysql_query("SELECT * FROM user_folders WHERE parent_ID='". $parentID ."' AND student_ID='".$sc_ID."' AND perm!='1' ORDER BY CAST(SUBSTR(name,9) AS UNSIGNED INTEGER)");
  }
  
   if (mysql_num_rows($f_res1) > 0){
     while($row = mysql_fetch_array($f_res1)){
       $folderID = $row['ID'];
       $name = $row['name'];
       echo "<div style=\"margin-left: ". (10 * $level) ."px; margin-bottom: 5px;\"><table cellspacing=0 cellpadding=0><tr><td width=20><img src=\"images/folder_icon_small_w.jpg\"></td><td><a href=\"javascript:ReverseContentDisplay('folder_".$folderID."')\" style=\"font-size: 14pt;\">".$name."</a></td>";
       if ($type=="user_files") { 
         $f_count_subfolders = countRows("user_folders","parent_ID=$folderID AND student_ID=$sc_ID");
         $f_allowDelete = "0";
         if ($f_count_subfolders < 1) { $f_allowDelete = "1"; }
         echo "<td>";
         echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"user_files_folderedit.php?folder_ID=$folderID&r=1\">Edit</a>";
         if ($f_allowDelete == "1") { echo "| <a href=\"user_files_folderdel.php?folder_ID=$folderID&r=1\" onClick=\"return confirm('WARNING!!! This will remove all files in this folder!!');\">Delete</a>"; }
         echo "</td>";                 
       }
       echo "</tr></table></div>\n";
       echo "<div class=\"folderCollapsed\" id=\"folder_".$folderID."\" style=\"margin-left: ". (10 * $level) ."px; margin-bottom: 5px;\">\n";
       createFolders($folderID, $sc_ID, $type, $level+1);
       echo "</div>\n";
     }
   }
    
    // this will echo out any files on the current level.
    createFiles($parentID, $sc_ID, $type, $level);
}

// USER FILES
function createFiles($parentID, $sc_ID, $type, $level) {
  global $db;
  if ($type=="course") { 
    $f_res2 = mysql_query("SELECT * FROM course_files WHERE switch!='1' AND parent_ID = '". $parentID ."' AND course_ID='".$sc_ID."' ORDER BY filename,name ASC");
  } elseif ($type=="perm") { 
    $f_res2 = mysql_query("SELECT * FROM user_files WHERE parent_ID = '". $parentID ."' AND perm='1' ORDER BY CAST(SUBSTR(filename,9) AS UNSIGNED INTEGER)");
  } elseif ($type=="library") { 
    $f_res2 = mysql_query("SELECT * FROM library WHERE parent_ID = '". $parentID ."' ORDER BY CAST(SUBSTR(filename,9) AS UNSIGNED INTEGER)");
  } else {
    $f_res2 = mysql_query("SELECT * FROM user_files WHERE parent_ID = '". $parentID ."' AND student_ID='".$sc_ID."' AND perm!='1' ORDER BY CAST(SUBSTR(filename,9) AS UNSIGNED INTEGER)");
  }
  if (mysql_num_rows($f_res2) > 0){
    while ($row = mysql_fetch_array($f_res2)){
      $fileID = $row['ID'];
      $doc_name = $row['name'];
      $filename = $row['filename'];
      $filepath = $row['filepath'];
      $link = $row['link'];
      if (!$doc_name) { $doc_name = $filename; }
      $file_ext = substr($filename, -4);
      switch($file_ext) {
        case ".pdf": $doc_icon = "pdf_icon.gif"; break;
        case ".gif": $doc_icon = "pic_icon.gif"; break;
        case ".jpg": $doc_icon = "pic_icon.gif"; break;
        case ".jpeg": $doc_icon = "pic_icon.gif"; break;
        case ".bmp": $doc_icon = "pic_icon.gif"; break;
        case ".png": $doc_icon = "pic_icon.gif"; break;
        case ".avi": $doc_icon = "vid_icon.gif"; break;
        case ".mov": $doc_icon = "vid_icon.gif"; break;
        case ".swf": $doc_icon = "vid_icon.gif"; break;
        case ".wmv": $doc_icon = "vid_icon.gif"; break;
        case ".flv": $doc_icon = "flv_icon.gif"; break;
        case ".mp4": $doc_icon = "vid_icon.gif"; break;
        case ".xls": $doc_icon = "xls_icon.gif"; break;
        case ".doc": $doc_icon = "doc_icon.gif"; break;
        case "docx": $doc_icon = "doc_icon.gif"; break;
        case ".ppt": $doc_icon = "ppt_icon.gif"; break;
        case ".wmv": $doc_icon = "vid_icon.gif"; break;
        default: $doc_icon = "qdoc_icon.gif";
      }
      if ($link) { $doc_icon = "vid_icon.gif"; }
      if ($type=="course") { 
        if ($file_ext==".swf") {
          $doc_flash_height = $row['flash_height'];
          $doc_flash_width = $row['flash_width'];
          if (!$doc_flash_width) { $doc_flash_width = "640"; }
          if (!$doc_flash_height) { $doc_flash_height = "480"; }
          $dl_link = "view_course_flash.php?ID=".$fileID."&height=".$doc_flash_height."&width=".$doc_flash_width;
        } elseif ($link) {
          $dl_link = $link;
          $lnk_class = "class=\"pop_800\"";
        } else {
          $dl_link = "course_files_dl.php?file_ID=".$fileID;
        }
      } elseif ($type=="library") {
        if ($file_ext==".swf") {
          $doc_flash_height = $row['flash_height'];
          $doc_flash_width = $row['flash_width'];
          if (!$doc_flash_width) { $doc_flash_width = "640"; }
          if (!$doc_flash_height) { $doc_flash_height = "480"; }
          $dl_link = "view_course_flash.php?t=l&ID=".$fileID."&height=".$doc_flash_height."&width=".$doc_flash_width;
        } else {
          $dl_link = "library_dl.php?ID=".$fileID;
        }
      } else {
        $dl_link = "student_download.php?f_t=2&file_ID=".$fileID;
      }
	echo "<div style=\"margin-left: ". (10 * $level) ."px; margin-bottom: 3px;\">";
	echo "<table cellspacing=0 cellpadding=0><tr><td>&nbsp;</td><td width=30><img src=\"images/".$doc_icon."\" style=\"border: 0; height: 20px;\" alt=\"".$doc_name."\" title=\"".$doc_name."\"></td><td><a href=\"".$dl_link."\"".$lnk_class." style=\"font-size: 14pt;\">".$doc_name."</a></td>";
	if ($type=="user_files") { echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"user_files_edit.php?file_ID=$fileID\">Edit</a> | <a href=\"user_files_del.php?file_ID=$fileID\" onClick=\"return confirm('Deleting file from your personal files.');\">Delete</a></td>"; }
	echo "</tr></table></div>\n";
		}
	}
}

function GetAge($Birthdate)
{
        // Explode the date into meaningful variables
        list($BirthYear,$BirthMonth,$BirthDay) = explode("-", $Birthdate);
        // Find the differences
        $YearDiff = date("Y") - $BirthYear;
        $MonthDiff = date("m") - $BirthMonth;
        $DayDiff = date("d") - $BirthDay;
        // If the birthday has not occured this year
        if ($DayDiff < 0 || $MonthDiff < 0)
          $YearDiff--;
        return $YearDiff;
}


function get_dir_size($dir_name){
        $dir_size =0;
           if (is_dir($dir_name)) {
               if ($dh = opendir($dir_name)) {
                  while (($file = readdir($dh)) !== false) {
                        if($file !="." && $file != ".."){
                              if(is_file($dir_name."/".$file)){
                                   $dir_size += filesize($dir_name."/".$file);
                             }
                             /* check for any new directory inside this directory */
                             if(is_dir($dir_name."/".$file)){
                                $dir_size +=  get_dir_size($dir_name."/".$file);
                              }
                           }
                     }
             }
       }
closedir($dh);
return $dir_size;
}

function countryDropDown($fieldName) {
  global $_SESSION,$edit_row;
  $countryList = file_get_contents("countrylist.php");
  $countryList = str_replace ("{".$_SESSION["regadmin_".$fieldName]."}","selected",$countryList);
  $countryList = str_replace ("{".$edit_row[$fieldName]."}","selected",$countryList);
  print "<select name=\"".$fieldName."\">";
  print $countryList;
  print "</select>";
}

function statesDropDown($fieldName) {
  global $_SESSION,$edit_row;
  $statesList = file_get_contents("statesList.html");
  $statesList = str_replace ("{".$_SESSION["regadmin_".$fieldName]."}","selected",$statesList);
  $statesList = str_replace ("{".$edit_row[mailing_state]."}","selected",$statesList);
  print "<select name=\"".$fieldName."\">";
  print $statesList;
}

function textDate($theDate, $big=NULL, $showTime=1) {
  $date_dmy = date("m/d/y", strtotime($theDate));
  $full_date = date("n/d/y g:i A", strtotime($theDate));
  $time = date("g:i A", strtotime($theDate));
  if ($showTime!="1") { $time = ""; }
  $monthday = date("M jS", strtotime($theDate));

  $date_day = date("l", strtotime($date_dmy));
  $yesterday  = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
  $aweekago = mktime(0, 0, 0, date("m")  , date("d")-7, date("Y"));
  $today = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));

  if (strtotime($date_dmy) == $today) { 
    if ($big) { $date_disp = "Today <br /><span style=\"color: #666666; font-size: 12pt;\">".$time."</span>"; }
    else { $date_disp = "Today $time"; }
  } elseif (strtotime($date_dmy) == $yesterday) { 
    if ($big) { $date_disp = "Yesterday <br /><span style=\"color: #666666; font-size: 13pt;\">".$time."</span>"; }
    else { $date_disp = "Yesterday $time"; }
  } elseif (strtotime($date_dmy) > $aweekago) {
    if ($big) { $date_disp .= $date_day."<br /><span style=\"color: #666666;\">".$monthday."</span>"; }
    else { $date_disp = $date_day." ".$time; }
  } else {
    if ($big) {
      $date_disp = $monthday."<br />";
    } else {
      $date_disp = $full_date;
    }
  }
 return $date_disp;
}


function get_row($tbl,$where,$field) {
  global $conn;
  $gr_sql = "SELECT * FROM $tbl where $where";
  $gr_result = mysql_query($gr_sql, $conn);
  $gr_row = mysql_fetch_array($gr_result);
  $gr_value = $gr_row[$field];
  return $gr_value;
}

// Function for adding new Restricted Discussion - Removing duplicates from strings
function removeDuplicates($theVar) {
  if (substr($theVar, -1)==",") { $theVar = substr($theVar, 0, -1); } 
  $theVar = explode(",",$theVar);
  $theVar = array_unique($theVar);
  $theVar = implode(",",$theVar).",";
  if (substr($theVar, 0, 1)==",") { $theVar = substr($theVar, 1); }   
  return $theVar;
}

// Count number of rows
function countRows($tbl,$sql){
 global $db;
 $thesql = "SELECT ID FROM $tbl WHERE $sql";
 $func_res = mysql_query($thesql) or die(mysql_error());
 $func_rows = mysql_numrows($func_res);
 return $func_rows;
}

// Remove commas before and after a string intended for an array (IE:  ",asdfasf,asf,asdf,asdf," becomes "asdfasf,asf,asdf,asdf")
function removeExtraCommas($theArrayString,$clearEmpty = 0){
  if ($clearEmpty!="1") {
    $theArrayString = str_replace(",,",",",$theArrayString);
    $theArrayString = str_replace(",,,",",",$theArrayString);
  }
  if (substr($theArrayString, -1)==",") { $theArrayString = substr($theArrayString, 0, -1); }
  if (substr($theArrayString, 0, 1)==",") { $theArrayString = substr($theArrayString, 1); }
  return $theArrayString;
}

// CK EDITOR
function ckEditor($field_name,$width="600",$height="350",$toolbar="Post",$skin="moonocolor_v1.1") { 
  print "<script type=\"text/javascript\">";
  print "CKEDITOR.replace('".$field_name."',{
  filebrowserUploadUrl : 'http://www.astonishingbeautyshop.com/ckeditor/filemanager/connectors/php/upload.php',
  filebrowserImageBrowseUrl : 'http://www.astonishingbeautyshop.com/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=http://www.astonishingbeautyshop.com/ckeditor/filemanager/connectors/php/connector.php',
  filebrowserImageUploadUrl : 'http://www.astonishingbeautyshop.com/ckeditor/filemanager/connectors/php/upload.php?Type=Image',
  skin:'".$skin."',toolbar:'".$toolbar."',width:'".$width."',height:'".$height."',resize_enabled:0,toolbarCanCollapse:0,startupShowBorders:0,enterMode:CKEDITOR.ENTER_BR,extraPlugins:'panelbutton,floatpanel,colorbutton,font',stylesSet:'custom'});CKEDITOR.add;";
  print "</script>";
}

function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}


function filterBadWords($str){

 // words to filter
 $badwords=array("fuck","fuuck","fucck","fuccck","fuuuucccck","fuuucccck","ffuucck","fuuuccck","anus","arse","cock","dick","d1ck","cracker","nigger","n1gger","jew","nazi","holocaust","jack off","homosexual","shit","sshhiit","sssssshhhhhhiiiiit","sshit","shhhhhit","shhit","shhhit","shhhhit","damn","whore","cunt","beaner","wigger","nigga","bitch","blowjob","blow job","boner","chink","clit","polesmoker","niglet","cheeky","coochy","cooter","semen","ejaculate","doochbag","douchebag","douche","dumbass","dyke","faggot","queer","fatass","gaylord","goddamn","gook","gringo","guido","heeb","jerk off","jerkoff","jigaboo","jizz","jungle bunny","junglebunny","kunt","kyke","lesbo","lezzie","lesbian","motherfucking","motherfuck","motherfucker","muffdiver","negro","nutsack","ballsack","paki","pissing","pissed","pissflaps","porchmonkey","pussy","twat");

 // replace filtered words with
 $replacements=array("*****");

 for($i=0;$i < sizeof($badwords);$i++){
  srand((double)microtime()*1000000); 
  $rand_key = (rand()%sizeof($replacements));
  $str=str_replace($badwords[$i], $replacements[$rand_key], $str);
 }
 return $str;
}

function makeLinksClick($text) {
  $text = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $text);  
  return $text;
}

function reduceCourseName($name) {
  $name = str_replace("Introduction","Intro",$name);
  $name = str_replace("and","&",$name);
  return $name;
}

function cleanFileName($fname) {
  $fname = preg_replace("/[^a-zA-Z0-9\.-_ ]/","",$fname);
  $ext = end(explode(".", $fname));
  $fname = str_replace(".".$ext,"",$fname);
  $ext = strtolower($ext);
  $fname = $fname . "." . $ext;
  return $fname;
}

function appStage($program,$stage) {

  if ($program=="DIHM") { 
     switch ($stage) {
       case 2:
         $app_stage_title = "Mailing Addresses";
         break;
       case 3:
         $app_stage_title = "Education/Work History";
         break;
       case 4:
         $app_stage_title = "Essay 1";
         break;
       case 5:
         $app_stage_title = "Essay 2";
         break;
       case 6:
         $app_stage_title = "Document uploads";
         break;
       case 7:
         $app_stage_title = "Choose grant or scholarship";
         break;
       case 8:
         $app_stage_title = "Grant Application";
         break;
       case 9:
         $app_stage_title = "Choose Scholarship";
         break;
       case 10:
         $app_stage_title = "Scholarship Application";
         break;
       case 11:
         $app_stage_title = "Make Payment";
         break;
       case 12:
         $app_stage_title = "Application Complete";
         break; 
       default:
         $app_stage_title = "Mailing Address";
     }
  } elseif ($program=="IHMC") { 
     switch ($stage) {
       case 2:
         $app_stage_title = "Mailing Addresses";
         break;
       case 3:
         $app_stage_title = "Education/Work History";
         break;
       case 4:
         $app_stage_title = "Essay";
         break;
       case 5:
         $app_stage_title = "Document uploads";
         break;
       case 6:
         $app_stage_title = "Make Payment";
         break;
       case 7:
         $app_stage_title = "Application Complete";
         break; 
       default:
         $app_stage_title = "Mailing Address";
     }
  } elseif ($program=="PDC") { 
     switch ($stage) {
       case 2:
         $app_stage_title = "Mailing Addresses";
         break;
       case 3:
         $app_stage_title = "Education/Work History";
         break;
       case 4:
         $app_stage_title = "Document uploads";
         break;
       case 5:
         $app_stage_title = "Make Payment";
         break;
       case 6:
         $app_stage_title = "Application Complete";
         break; 
       default:
         $app_stage_title = "Mailing Address";
     }
  } elseif ($program=="TEC") { 
     switch ($stage) {
       case 2:
         $app_stage_title = "Mailing Addresses";
         break;
       case 3:
         $app_stage_title = "Education/Work History";
         break;
       case 4:
         $app_stage_title = "Document uploads";
         break;
       case 5:
         $app_stage_title = "Make Payment";
         break;
       case 6:
         $app_stage_title = "Application Complete";
         break; 
       default:
         $app_stage_title = "Mailing Address";
     }
  } elseif ($program=="HEC") { 
     switch ($stage) {
       case 2:
         $app_stage_title = "Mailing Addresses";
         break;
       case 3:
         $app_stage_title = "Education/Work History";
         break;
       case 3:
         $app_stage_title = "Essay";
         break;
       case 4:
         $app_stage_title = "Document uploads";
         break;
       case 5:
         $app_stage_title = "Make Payment";
         break;
       case 6:
         $app_stage_title = "Application Complete";
         break; 
       default:
         $app_stage_title = "Mailing Address";
     }
  } elseif ($program=="OEC") { 
     switch ($stage) {
       case 2:
         $app_stage_title = "Education/Work History";
         break;
       case 3:
         $app_stage_title = "Questions";
         break;
       case 4:
         $app_stage_title = "Photo Upload";
         break;
       case 5:
         $app_stage_title = "Application Complete";
         break; 
       default:
         $app_stage_title = "Mailing Address";
     }
   }
 return $app_stage_title;
}

function termDisp($term) {
   global $conn;
   $termSql = "SELECT * FROM terms WHERE ID='$term'";
   $termResult = mysql_query($termSql, $conn);
   $termRow = mysql_fetch_array($termResult);
   $termDisp = $termRow[title];
   return $termDisp;
}

// Program Display Names
function programDisp($program) {
  switch ($program) {
    case "IHMC":
      $program_text = "Interntaional Hospitality Management Certificate"; 
      break;
    case "HPC":
      $program_text = "Hospitality Professional Certificate"; 
      break;
    case "PDC":
      $program_text = "Professional Development Certificate"; 
      break;
    case "TEC":
      $program_text = "Teaching Effectiveness Certificate"; 
      break;
    case "OEC":
      $program_text = "Online Educator Certificate"; 
      break;
    case "DIHM":
      $program_text = "Diploma in International Hospitality Management"; 
      break;
    case "HEC":
      $program_text = "Hospitality English Certificate"; 
      break;
  }
  return $program_text;
}

//Notify Office of Orientation
function ORINotifyOffice($student) {
  global $mail, $ori_notice_email, $tpl_company_name;
  $ori_notify_name = get_row("users","ID=$student","first_name")." ".get_row("users","ID=$student","last_name");
  $ori_notify_ID = get_row("users","ID=$student","aha_id");
  $ori_notify_start = date("m/d/Y", strtotime(get_row("users","ID=$student","start_date")));
  $ori_notify_end = date("m/d/Y", strtotime(get_row("users","ID=$student","end_date")));
  $ori_notify_office = get_row("users","ID=$student","office");
  $ori_notify_host = get_row("users","ID=$student","host_property");
  $ori_notify_pos = get_row("users","ID=$student","position");
  $ori_notify_visa = get_row("users","ID=$student","visa_type");

  $message_body = "A new user has completed their orientation at ".$tpl_company_name."<br /><br />";
  $message_body .= "<strong class=\"strong\">Name:</strong> ".$ori_notify_name."<br /><br />";

  $mail->Subject = "Certificate Orientation Notification: ".$ori_notify_name;
	$mail->From = "no-reply@ahaecampus.com";
	$mail->FromName = $tpl_company_name;
	$mail->Body    = $message_body;
	$mail->AddAddress($ori_notice_email);
	$mail->Send();
}

/*********************************************************************************************************
***  FOLLOWING FUNCTION IS DEVELOPED BY KAMAL JOSHI AND IT'S FOR TOPIC SUBSCRIPTION 
**********************************************************************************************************/
	function subscribe_topic($topic_ID=null) {
		if($topic_ID!=null && isset($_SESSION['user_ID'])) {
			$query ="SELECT ";
			$query.="`ID` ";
			$query.="FROM ";
			$query.="`user_subscription` ";
			$query.="WHERE ";
			$query.="`topic_ID`='".$topic_ID."' ";
			$query.="AND ";
			$query.="`user_ID`='".$_SESSION['user_ID']."'";
			$result = mysql_query($query) or die(mysql_error());
			if(mysql_num_rows($result) == 0 ) {
				$query ="INSERT ";
				$query.="INTO ";
				$query.="`user_subscription` ";
				$query.="(";
					$query.="`topic_ID`, ";
					$query.="`user_ID`, ";
					$query.="`time`";
				$query.=") VALUES( ";
					$query.="'".$topic_ID."', ";
					$query.="'".$_SESSION['user_ID']."', ";
					$query.="'".time()."'";
				$query.=")";
				$result = mysql_query($query) or die(mysql_error());
				get_subscribed_topic_IDs();
			}
		}
	}
	
	function get_subscribed_topic_IDs() {
		if(isset($_SESSION['user_ID'])) {
			$query ="SELECT ";
			$query.="`topic_ID` ";
			$query.="FROM ";
			$query.="`user_subscription` ";
			$query.="WHERE ";
			$query.="`user_ID`='".$_SESSION['user_ID']."'";
			$result = mysql_query($query) or die(mysql_error());
			$_SESSION['subscribed_topic_ID']=array();
			if($result && mysql_num_rows($result)) {				
				while($row=mysql_fetch_array($result)) {
					$_SESSION['subscribed_topic_ID'][]=$row['topic_ID'];
				}
			}
		}
	}
	
	function unsubscribe_topic($topic_ID=null) {
		if($topic_ID!=null && isset($_SESSION['user_ID'])) {
			$query ="DELETE ";
			$query.="FROM ";
			$query.="`user_subscription` ";
			$query.="WHERE ";
			$query.="`topic_ID`='".$topic_ID."' ";
			$query.="AND ";
			$query.="`user_ID`='".$_SESSION['user_ID']."'"; 
			$result = mysql_query($query) or die(mysql_error());
			get_subscribed_topic_IDs();
		}
	}
	
	function send_notifications_to_subscribers($topic_ID=null) {
		global $tpl_company_url,$tpl_company_name,$mail;
		if($topic_ID!=null && isset($_SESSION['user_ID'])) {
			$query ="SELECT ";
			$query.="`first_name`, ";
			$query.="`last_name`, ";
			$query.="`email` ";
			$query.="FROM ";
			$query.="`user_subscription`, `users` ";
			$query.="WHERE ";
			$query.="`topic_ID`='".$topic_ID."' ";
			$query.="AND ";
			$query.="`user_ID`=`users`.`ID`";
			$result = mysql_query($query) or die(mysql_error());
			if($result && mysql_num_rows($result)) {
				
				$query ="SELECT `ID`, `title`, `course_ID` FROM `db_topics` WHERE `ID`='".$topic_ID."' LIMIT 0,1";
				$result_topic = mysql_query($query) or die(mysql_error());
				if($result_topic && mysql_num_rows($result_topic)) {
					$arrTopic = mysql_fetch_array($result_topic);
					
					$message_body = file_get_contents("email_template_topic_notification.html");
					$message_body = str_replace ("{site_url}",$tpl_company_url,$message_body);
					$message_body = str_replace ("{tpl_company_name}",$tpl_company_name,$message_body);
					$topic_link='http://'.$tpl_company_url.'/db_view.php?topic_ID='.$arrTopic['ID'].'&bb_type=course&course_ID='.$arrTopic['course_ID'];
					$message_body = str_replace ("{topic_link}",$topic_link,$message_body);
					$message_body = str_replace ("{topic_title}",$arrTopic['title'],$message_body);
					
					$mail->Subject = "A new post has been made to [".$arrTopic['title']."].";
				  	$mail->Body    = $message_body;
				  	$mail->AltBody = $message_body;				  						
					while($row=mysql_fetch_array($result)) {
						$mail->AddBCC($row['email']);
					}
					$mail->Send();
				}				
			}			
		}
	}
/*********************************************************************************************************
***  AVBOVE FUNCTION IS DEVELOPED BY KAMAL JOSHI AND IT'S FOR TOPIC SUBSCRIPTION 
**********************************************************************************************************/

?>