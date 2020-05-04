<?php
/*
JSON_SQL_Bridge 1.0
Copyright 2016 Frank Vanden berghen
All Right reserved.

JSON_SQL_Bridge is not a free software. The JSON_SQL_Bridge software is NOT licensed under the "Apache License". 
If you are interested in distributing, reselling, modifying, contibuting or in general creating 
any derivative work from JSON_SQL_Bridge, please contact Frank Vanden Berghen at frank@timi.eu.
*/
namespace kibella; function l7t($O7r,$O5v) { $lax=FALSE; if ($O5v) { $lay=$O7r["responseFileFinal"]; $Oay=$O7r["responseFileTmp"]; $l3t=checkdirexistsorcreate(CACHEDIR); if ($l3t === FALSE) { showmessage( __FUNCTION__ ,TAG_ERROR,O8b(CACHEDIR).laz()); return FALSE; } if (file_exists($lay) && !O4p($lay)) { $lax=TRUE; } else if (file_exists($Oay)) { Oax($lay); if (file_exists($lay)) { $lax=TRUE; } } } return $lax; } function O7u($O7r,$O5v) { if (file_exists($O7r["responseFileTmp"]) === TRUE) { $lav=Oaz($O7r); if ($lav !== FALSE && PHP_SAPI !== "cli") { if ($O7r["responseFileFinal"] !== l82(O14)) { setrawcookie(O14,$O7r["responseFileFinal"],strtotime("+365 days"),"/".APPDIRNAME."/"); setrawcookie(l15,time(),strtotime("+365 days"),"/".APPDIRNAME."/"); setrawcookie(O15,$O5v*1,strtotime("+365 days"),"/".APPDIRNAME."/"); } } } } function l7s($O31,$O7q) { if (is_string($O7q)) { $lb0=$O7q; } else { $lb0=serialize($O7q); } $lay=CACHEDIR."/query_".$O31.l1z.md5($O31."\n".$lb0).".json"; $Ob0="_tmp"; $Oay= "$lay$Ob0"; $O7r=array("responseFileFinal" => $lay,"responseFileTmp" => $Oay); return $O7r; } function lau($Oe) { $lb1=substr($Oe,strrpos($Oe,l1z)+1); $Ob1=strrpos($lb1,"."); if ($Ob1>0) { $l66=substr($lb1,0,$Ob1); } else { $l66=$lb1; } return $l66; } function l82($lb2) { if ( isset ($_COOKIE[$lb2])) { return $_COOKIE[$lb2]; } else { return ""; } } function l7v($Oe,$Ob2=5) { $lav=FALSE; if ($Oe !== NULL && $Oe !== FALSE && $Oe !== "") { $lb3=time(); $Ob3=strlen(ob_get_contents()); while ($lav === FALSE && (time()-$lb3)<=$Ob2) { @readfile($Oe); if (PHP_SAPI === "cli" || strlen(ob_get_contents())>$Ob3 && @filesize($Oe)>0) $lav=TRUE; } } return $lav; } function Oaz($O7r,$Ob2=5) { $lav=FALSE; $lb3=time(); while ($lav === FALSE && (time()-$lb3)<=$Ob2) { $lav=@rename($O7r["responseFileTmp"],$O7r["responseFileFinal"]); } return $lav; } function lb4($Ob4,$O55="application/json") { header($_SERVER["SERVER_PROTOCOL"]." ".$Ob4); header("Access-Control-Allow-Origin: *"); header( "Content-Type: $O55; charset=UTF-8"); } function lb5($l7o=TRUE) { $Ob5=l82(O1w); if ($Ob5 == "") { l6b(l1n,$l3s=FALSE); $O69=dbcreatedbconnection(KIBELLADB); $lb6=dbdbhexecutesqlquery($O69->getdbhandle(),"select ".O3." from ".l1n." LIMIT 1"); $O69->close(); if ($lb6 !== FALSE && count($lb6)>0) { $l31=$lb6[0][O3]; } else { $l31="INCOME(income)"; } } else { $l31=$Ob5; } $Ob6="{\"".O1f."\":\"".O13."\",\"".l1g."\":\"config\",\"".O1g."\":\"".l14."\",\"".l1h."\":1,\"".l1k."\":true,\"".l1i."\":{"."\"buildNum\":1000,"."\"defaultIndex\":\"".$l31."\""."}"."}"; if ($l7o) { $Ob6="{\"docs\":[".$Ob6."]}"; } l37($Ob6); } function O87($lb7) { $Ob7="{\"took\":2,\"timed_out\":false,\"".l1j."\":{\"total\":1,\"successful\":1,\"failed\":0},"."\"hits\":{"."\"total\":".count($lb7).","."\"max_score\":1,"."\"hits\":".json_encode($lb7)."}"."}"; l37($Ob7); } function l74($O55,$O1,$O73,$lb8,$Ob8) { $Ob7="{\"".O1f."\":\"".O13."\",\"".l1g."\":\"".$O55."\",\"".O1g."\":\"".$O1."\",\"".l1h."\":".$O73.",\"".l1j."\":{\"total\":2,\"successful\":1,\"failed\":0},\"".$lb8."\":".$Ob8."}"; l37($Ob7); } function lb9($lb2,$O3c,$Ob9="",$lba=NULL,$Oba=NULL) { if ($lba === NULL) $lba=strtotime("+365 days"); if ($Oba === NULL) $Oba="/".APPDIRNAME."/"; if ($Ob9 === "raw") { setrawcookie($lb2,$O3c,$lba,$Oba); } else { setcookie($lb2,$O3c,$lba,$Oba); } } function lbb($Obb,$lbc,$O4y) { $Obc=""; $lbd=""; if (strtolower($O4y) == "begin") { if ($lbc == "" || $lbc == NULL) { $Obd=$Obb; $lbc=""; } else $Obd=str_replace($lbc."/","",$Obb); $O4g=0; $lbe=""; if ($Obd[0] == "/") { $O4g=1; $lbe="/"; } if ($lbc == "") $Obe=""; else $Obe=$lbe.$lbc; $O4y=strpos($Obd,"/",$O4g); if ($O4y !== FALSE) { $Obc=$Obe.substr($Obd,$O4y); $lbd=substr($Obd,$O4g,$O4y-$O4g); } else { $lbf=Obf($Obb); $lbg=$lbf["URI"]; $lbd=substr($lbg,$O4g); } } else { $lbf=Obf($Obb); $lbg=$lbf["URI"]; $Obg=strlen($lbg); if ($lbg[$Obg-1] == "/") $lbg=substr($lbg,0,$Obg-1); $O4y=strrpos($lbg,"/"); if ($O4y !== FALSE) { if ($O4y<strlen($lbg)-1) $lbd=substr($lbg,$O4y+1); $Obc=substr($lbg,0,$O4y+1); } else $lbd=$lbg; } return array("URI" => $Obc,"object" => $lbd); } function Obf($Obb) { $O4y=strpos($Obb,"?"); if ($O4y === FALSE) { $lbh=$Obb; $O39=NULL; } else { $lbh=substr($Obb,0,$O4y); if ($O4y == strlen($Obb)-1) { $O39=NULL; } else { $Obh=substr($Obb,$O4y+1); $lbi=explode("&",$Obh); $O39=l41("=",$lbi); } } return array("URI" => $lbh,"params" => $O39); } function l85($l36) { $l36=preg_replace("/\\s+/","-",$l36); return $l36; } function Obi($l36) { $l36=urldecode($l36); return $l36; } function Oax($l3x,$lbj=O12) { $lb3=time(); while (!file_exists($l3x) && (time()-$lb3)<=$lbj) { sleep(1); } }