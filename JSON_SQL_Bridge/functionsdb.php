<?php
/*
JSON_SQL_Bridge 1.0
Copyright 2016 Frank Vanden berghen
All Right reserved.

JSON_SQL_Bridge is not a free software. The JSON_SQL_Bridge software is NOT licensed under the "Apache License".
If you are interested in distributing, reselling, modifying, contibuting or in general creating
any derivative work from JSON_SQL_Bridge, please contact Frank Vanden Berghen at frank@timi.eu.
*/
namespace kibella; use l33\O33\l64\O64\l65; function O4c($lp) { global $O65; assert(array_search($lp,$O65)>=0,"Invalid database engine '$lp'. Valid values are: '".implode("' , '",$O65)."'"); } function l66($O66,$O30,$O5a) { $l67=O67($O66,$O30); if ($l67 === FALSE) { return FALSE; } foreach ($l67 as $l60) { if (l3x(strtolower($l60["name"])) === strtolower($O5a)) { return TRUE; } } return FALSE; } function l68($O30,$O3r=FALSE) { if ($O3r) { switch ($O30) { case (O1m): $O68="registered tables"; break; case (l3): $O68="saved objects"; break; case (l1s): $O68="run queries"; break; default : break; } } $O3s=checkdirexistsorcreate(TABLESDIR,$O3r=$O3r,$l3s="The temporary directory with the application's information '".TABLESDIR."' was not found and is being recreated.\n"); if ($O3s === FALSE) { showmessage( __FUNCTION__ ,TAG_ERROR,l69().O69()); return FALSE; } $O66=dbcreatedbh(KIBELLADB); if ($O66->lu() === RC_ERROR_NOTFOUND_DB) { if ($O3r) showmessage("",l16,"Internal database storing the application's information was not found and is being recreated.\n"); $O3s=l6a($O3r=FALSE); return $O3s; } $O3s=O6a($O66,$O30); if ($O3s === RC_ERROR_NOTFOUND_TABLE) { if ($O3r) showmessage("",l16,"The internal table storing the $O68 was not found and is being recreated.\n"); $O3s=l6b($O66->getdbhandle(),$O30,FALSE); } $O66->close(); return $O3s; } function O6a($O66,$O30) { $O5h=O6b($O66,$O30,O19); $l67=dbdbhexecutesqlquery($O66->getdbhandle(),$O5h); if ($l67 === FALSE) { return FALSE; } if (count($l67) == 0) { return RC_ERROR_NOTFOUND_TABLE; } return TRUE; } function O6b($O66,$O30,$l4p) { $l6c=O6c($O66,$O30); return O4o($O66->lp ,$l4p,$l6c); } function O6c($O66,$O30) { $l6c=$O30; return $l6c; } function dbcreatedbh($lo,$Oo=O17,$l6d=array("dir" => TABLESDIR)) { switch ($Oo) { case (O17): return O6d($lo,$l3q=$l6d["dir"]); break; default : global $l6e; showmessage( __FUNCTION__ ,TAG_ERROR,"Invalid DBMS type '$Oo'. Valid values are: '".implode(" , ",$l6e)."'"); return FALSE; break; } } function O6d($l9,$l3q=TABLESDIR,$O6e=array("dbenginePDO" => O18,"attributesPDO" => array( \pdo::ATTR_PERSISTENT => true,\pdo::ATTR_STRINGIFY_FETCHES => false,\pdo::ATTR_ERRMODE => \pdo::ERRMODE_SILENT))) { assert($O6e !== NULL && count($O6e)>=1 && array_key_exists("dbenginePDO",$O6e),"Input array \$optionsPDO contains the minimum required attributes"); if ($l3q !== "") $l9= "$l3q/$l9"; $O4a=$O6e["dbenginePDO"]; $O4b=l4a($O4a); if (array_key_exists("attributesPDO",$O6e) === FALSE) { $O6e["attributesPDO"]=NULL; } $O66=new On($l9,"$O4b:$l9" ,O17,$O4a,$O6e["attributesPDO"]); return $O66; } function l6f($Op,$O30) { $O5h=O4o(O18,O19,$O30); $l67=dbdbhexecutesqlquery($Op,$O5h); if ($l67 === FALSE) { return FALSE; } if (count($l67) == 0) { return RC_ERROR_NOTFOUND_TABLE; } return TRUE; } function O6f($O66,$l6g=FALSE,$O6g="",$l6h=NULL) { if ($l6h == NULL) { $O66->close(); } else { assert(count($l6h)>=2 && array_key_exists("dbhandle",$l6h) && array_key_exists("attachname",$l6h),"Input array \$dbAttachInfo contains the neccessary keys"); $O6h="DETACH DATABASE ".$l6h["attachname"]; dbdbhexecutesqlquery($O66->getdbhandle(),$O6h,$O5="exec"); $l6h["dbhandle"]->close(); } if ($l6g === TRUE && $O6g != "" && $O6g != ":memory:") { unlink($O6g); } } function Ot(&$Op,$l6i=lt) { switch ($l6i) { case (lt): $Op=NULL; break; case (l17): $Op->close(); break; default : $Op=NULL; break; } unset ($Op); } function l6b($Op,$O30,$O3r=FALSE) { assert($Op !== NULL && $Op != FALSE,"The database handle is not null nor false"); $O3s=O6i($Op,$O30); if ($O3s === FALSE) { showmessage( __FUNCTION__ ,l16,l6j($O30)); } else { switch ($O30) { case (O1m): $O6j=O3." TEXT, ".l1n." TEXT, ".O1n." TEXT, ".l1o." TEXT, ".O1l." INT, ".O1o." TEXT, ".l1p." TEXT, ".O1p." TEXT, ".l1q." NUMBER".",imageFields TEXT".",linkTemplate TEXT".",linkTemplateLabel TEXT".",imageTemplate TEXT".",imageTemplateLabel TEXT".",".l1m." TEXT".", PRIMARY KEY(".O3.")"; $l6k="Registered tables"; break; case (l3): $O6j=O1q." TEXT, ".l4." TEXT, ".O3." TEXT, ".O1l." INT, ".l1r." TEXT, ".O1r." TEXT, ".O2." INT, ".l1m." TEXT"; $l6k="Saved objects (visualizations, dashboards, searches)"; break; case (l1s): $O6j=O1s." TEXT, ".O1t." TEXT, ".l1u." TEXT, ".O1u." INT, ".l1t." TEXT"; $l6k="Run queries"; break; default : $O6j=""; $l6k=""; break; } $O3s=dbdbhexecutesqlquery($Op,"create table ".$O30." ($O6j)" ,$O5="exec"); if ($O3s === FALSE) { showmessage( __FUNCTION__ ,TAG_ERROR,O6k($O30)); } else if ($O3r) { echo l6l($l6k); } } return $O3s; } function O6l($Op,$Os) { if ($Os != NULL) { foreach ($Os as $l6m => $l3c) { $Op->setattribute($l6m,$l3c); } } } function O6i($Op,$O30) { $O3s=l6f($Op,$O30); if ($O3s === TRUE) { dbdbhexecutesqlquery($Op,"drop table $O30" ,$O5="exec"); } return $O3s; } function dbdbhexecutesqlquery($Op,$O5h,$O5="query",$l39=NULL) { $l5=FALSE; if ($O5 == "exec") { try { if ($l39 != NULL) { $O6m=@$Op->prepare($O5h); foreach ($l39 as $l6n => $l3c) { $O6m->bindvalue($l6n,$l3c); } $l5=@$O6m->execute(); unset ($O6m); } else { $l5=@$Op->exec($O5h); } } catch ( \pdoexception $O9) { } } else { $O6m=O6n($Op,$O5h); if ($O6m) { if (get_class($O6m) == "PDOStatement") { $l5=$O6m->fetchall( \pdo::FETCH_ASSOC); } else if (get_class($O6m) == "SQLite3Result") { $l5=array(); while ($l6o=$O6m->fetcharray(SQLITE3_ASSOC)) { $l5[]=$l6o; } } } unset ($O6m); } return $l5; } function O6o($O1,$lo,$O30,$lp,$l6p,$O6p,$l6d=array("dir" => DATADIR)) { $l6q=O6q($lo,$O30,$lp,$l6d=$l6d); if ($l6q === FALSE) { return FALSE; } $l6r=""; $O6r=l3t($l6p); $l6s=l3t($O6p); $l5b=""; $O6s=0; $l6t=$l6s; for ($O3u=0; $O3u<count($l6q); $O3u ++) { $O6t=$l6q[$O3u]["name"]; $l6u=$l6q[$O3u]["type"]; $O6u=l6v($O6t,$l6u,$O6r,$l6s,$l6t); $O6v=$O6u["FieldTypeIndex"]; if ($O6v !== "") { if ($O6s>0) $l5b=","; $l6r.=$l5b.dbgeneratefieldmetadatashort($O6t,$O6v); $O6s ++; } } if ($l6s != NULL) { l6w($l6r,$l6s,$l6q,"kibella\\dbGenerateFieldMetadataShort"); } $O6w="{\"docs\":[{\"".l1f."\":\"".O12."\",\"".O1f."\":\"index-pattern\",\"".l1g."\":\"".$O1."\",\"".O1g."\":1,\"".O1j."\":true,\"".O1h."\":{\"title\":\"".$O1."\","."\"fields\":".l3x($l6r,TRUE)."}"."}]"."}"; return $O6w; } function l6x($O1,$lo,$O30,$lp,$l6p,$O6p,$l6d=array("dir" => DATADIR)) { $l6q=O6q($lo,$O30,$lp,$l6d=$l6d); if ($l6q === FALSE) { return FALSE; } $O6x="{\"".$O1."\":{"."\"mappings\":{"."\"row\":{"."\"_ttl\":{"."\"full_name\":\"_ttl\","."\"mapping\":{"."\"_ttl\":{"."\"enabled\":false,"."\"default\":-1"."}"."}"."}"; $O6r=l3t($l6p); $l6s=l3t($O6p); $l5b=","; $l6t=$l6s; for ($O3u=0; $O3u<count($l6q); $O3u ++) { $O6t=$l6q[$O3u]["name"]; $l6u=$l6q[$O3u]["type"]; $O6u=l6v($O6t,$l6u,$O6r,$l6s,$l6t); $O6v=$O6u["FieldTypeMappings"]; if ($O6v !== "") { $O6x.=$l5b.dbgeneratefieldmetadatalong($O6t,$O6v); } } if ($l6s != NULL) { l6w($O6x,$l6s,$l6q,"kibella\\dbGenerateFieldMetadataLong"); } $O6x.="}"."}"."}"."}"; return $O6x; } function O6q($lo,$O30,$lp,$l6d=array("dir" => DATADIR)) { global $O4q; $O66=dbcreatedbh($lo,$Oo=$O4q[$lp],$l6d=$l6d); if ($O66->lu()<0) { return FALSE; } $O3s=O6a($O66,$O30); if ($O3s === FALSE || $O3s<0) { return FALSE; } $l6q=O67($O66,$O30); if ($l6q === FALSE) { return FALSE; } assert(count($l6q)>0,"The number of fields in the table is > 0"); $O66->close(); return $l6q; } function l6y($O6y,$l52,$O1) { l68($O6y,$O3r=FALSE); switch ($l52) { case (O1d): $l6z="where lower(".O3.") = '".strtolower($O1)."'"; break; default : $l6z="where ".l4." = '$l52' and ".O3." = '".$O1."'"; break; } $l5v=dbcreatedbh(KIBELLADB); $l67=dbdbhexecutesqlquery($l5v->getdbhandle(),"select ".O1l." from $O6y $l6z"); if ($l67 !== FALSE && count($l67)>0) { $O6z="true"; $l70=$l67[0][O1l]*1; dbdbhexecutesqlquery($l5v->getdbhandle(),"delete from $O6y $l6z" ,$O5="exec"); } else { $O6z="false"; $l70=1; } if ($l52 == O1d) { l68(l3,$O3r=FALSE); dbdbhexecutesqlquery($l5v->getdbhandle(),"delete from ".l3." where ".O1q." = '$O1'" ,$O5="exec"); } $l5v->close(); O70($l52,$O1,$l70,O1j,$O6z); } function l6v($O6t,$O6v,&$O6r,&$l6s,$l6t) { $l71=FALSE; if ($l6t !== NULL && count($l6t)>0) { $O71="/_geohash[".O1k."-".l1l."]\$/i"; if (preg_match($O71,$O6t) === 1 && array_search(preg_replace($O71,"",strtolower($O6t)),$l6t) !== FALSE) { $l72=""; $O72=""; $l71=TRUE; } } if (!$l71) { $l73=array_search(strtolower($O6t),$O6r); if ($l73 !== FALSE) { $l72="date"; $O72="date"; unset ($O6r[$l73]); } else { $O73=array_search(strtolower($O6t),$l6s); if ($O73 !== FALSE) { $l72="geo_point"; $O72="geo_point"; unset ($l6s[$O73]); } else { switch (( string) strtoupper($O6v)) { case ("DOUBLE"): case ("FLOAT"): case ("REAL"): $l72="number"; $O72="double"; break; case ("BIGINT"): case ("INT"): $l72="number"; $O72="long"; break; case ("TEXT"): case ("VARCHAR"): $l72="string"; $O72="string"; break; default : l74($O6v); $l72="string"; $O72="string"; break; } } } } return array("FieldTypeIndex" => $l72,"FieldTypeMappings" => $O72); } function dbgeneratefieldmetadatalong($O6t,$O6v) { $O35="\"".$O6t."\":{"."\"full_name\":\"".$O6t."\","."\"mapping\":{\"".$O6t."\":{"."\"type\":\"".$O6v."\","; if ($O6v === "date") { $O35.="\"analyzer\":\"_date/16\","."\"search_analyzer\":\"_date/max\","."\"format\":\"yyyy/MM/dd HH:mm:ss||yyyy/MM/dd||epoch_second\","."\"numeric_resolution\":\"seconds\","; } $O35.="\"index\":\"not_analyzed\""."}"."}"."}"; return $O35; } function dbgeneratefieldmetadatashort($O6t,$O6v) { return "{"."\"name\":\"".$O6t."\","."\"type\":\"".$O6v."\","."\"count\":0,"."\"scripted\":false,"."\"indexed\":true,"."\"analyzed\":false,"."\"doc_values\":true"."}"; } function l6w(&$O35,&$l6s,$O74,$l75) { assert($l6s != NULL,"the input geofields array is not null"); foreach ($l6s as $O75) { $O75=strtolower($O75); $l76=FALSE; $O76=FALSE; $l77=FALSE; foreach ($O74 as $l60) { $O6t=$l60["name"]; $O77=strtolower($O6t); if ($O77 === $O75."_lat") $l76=TRUE; if ($O77 === $O75."_lon") $O76=TRUE; $l78=$O75."_geohash"; $O78=substr($O77,0,strlen($l78)); $l79=substr($O77,strlen($l78)); if ($O78 === $l78 && O1k<=$l79 && $l79<=l1l) $l77=TRUE; if ($l76 && $O76 && $l77) break; } if ($l76 && $O76 && $l77) { if ($O35 == "") $l5b=""; else $l5b=","; $O79=substr($O6t,0,strlen($O75)); $O35.=$l5b.call_user_func($l75,$O79,"geo_point"); } unset ($l6s[$O75]); } return $O35; } function O31($O30,$l30) { $l7a=strstr($l30,".",TRUE); if (empty($l7a)) { $l7a=$l30; } return strtoupper($l7a)."(".strtolower($O30).")"; } function O7a() { if (PHP_SAPI !== "cli") { $l7b=str_replace([":","."],"_",$_SERVER["REMOTE_ADDR"]); $O7b=$l7b."_"; } else { $O7b=""; } return "_$O7b".floor(microtime(1))."_".rand(0,023420); } function l7c($O1) { $O6u=FALSE; $O6w=O7c($O1); $l7d=json_decode($O6w,TRUE); if ($l7d != NULL && array_key_exists("docs",$l7d) && count($l7d["docs"])>0) { $O7d=$l7d["docs"][0][O1h]["fields"]; $l7e=json_decode($O7d,TRUE); $O6u=array(); foreach ($l7e as $O7e) { $O6u["\"".$O7e["name"]."\""]=$O7e["type"]; } } return $O6u; } function O5x($l31) { l68(l3,$O3r=FALSE); $l5v=dbcreatedbh(KIBELLADB); $l5x=dbdbhexecutesqlquery($l5v->getdbhandle(),"select ".O1q.", ".O3.", ".l1r." from ".l3." where ".l4." = '".O4."' and ".O1q." = '".$l31."'"); $l5v->close(); if ($l5x == FALSE or count($l5x) == 0) { return FALSE; } return $l5x; } function O62($l31,$O60=0) { l68(l1s,$O3r=FALSE); $l5v=dbcreatedbh(KIBELLADB); $l62=dbdbhexecutesqlquery($l5v->getdbhandle(),"select ".O1s.", ".O1t.", ".l1u.", ".O1u." from ".l1s." where ".O1t." = '".$l31."' and ".O1u." >= $O60"." order by ".O1u." desc"); $l5v->close(); if ($l62 == FALSE or count($l62) == 0) { return FALSE; } return $l62; } function l5z($l31) { l68(l1s,$O3r=FALSE); $l5v=dbcreatedbh(KIBELLADB); $l7f=dbdbhexecutesqlquery($l5v->getdbhandle(),"select ".O1u.", count(*) as n "." from ".l1s." where ".O1t." = '".$l31."'"." group by ".O1u." order by ".O1u." desc"); $l5v->close(); if ($l7f === FALSE or count($l7f) == 0) { return FALSE; } return $l7f; } function O7f($lo,$lp) { if ($lp === O18) { return basename(realpath(DATADIR."/$lo")); } else { return $lo; } } function l5t($l31) { l68(O1m,$O3r=FALSE); $l5v=dbcreatedbh(KIBELLADB); $l7g=dbdbhexecutesqlquery($l5v->getdbhandle(),"select ".l1n.", ".O1n.", ".l1o.", ".l1q." from ".O1m." where ".O3." = '$l31'"); $l5v->close(); if ($l7g == FALSE or count($l7g) == 0) { return FALSE; } $O30=$l7g[0][l1n]; $l30=$l7g[0][O1n]; $lp=l4c($l7g[0][l1o]); $O7g=strtolower(CACHEMODE) !== "none" && ($l7g[0][l1q] == 1); $l7h=array("table" => $O30,"db" => $l30,"dbengine" => $lp,"cache" => $O7g); return $l7h; } function O7c($l31) { l68(O1m,$O3r=FALSE); $l5v=dbcreatedbh(KIBELLADB); $l67=dbdbhexecutesqlquery($l5v->getdbhandle(),"select ".l1n.", ".O1n.", ".l1o.", ".O1o.", ".l1p.", ".O1p." from ".O1m." where lower(".O3.") = '".strtolower($l31)."'"); $l5v->close(); if ($l67 !== FALSE && count($l67)>0) { $O30=$l67[0][l1n]; $l30=$l67[0][O1n]; $lp=l4c($l67[0][l1o]); $l6p=$l67[0][O1o]; $O6p=$l67[0][l1p]; $O7h=$l67[0][O1p]; $O6w=O6o($l31,$l30,$O30,$lp,$l6p,$O6p); } else { $O6w=FALSE; } return $O6w; } function l7i($O1) { l68(O1m,$O3r=FALSE); $l5v=dbcreatedbh(KIBELLADB); $l67=dbdbhexecutesqlquery($l5v->getdbhandle(),"select ".l1n.", ".O1n.", ".l1o.", ".O1o.", ".l1p.", ".O1p." from ".O1m." where lower(".O3.") = '".strtolower($O1)."'"); $l5v->close(); if ($l67 !== FALSE && count($l67)>0) { $O30=$l67[0][l1n]; $l30=$l67[0][O1n]; $lp=l4c($l67[0][l1o]); $l6p=$l67[0][O1o]; $O6p=$l67[0][l1p]; $O7h=$l67[0][O1p]; $O6x=l6x($O1,$l30,$O30,$lp,$l6p,$O6p); } else { $O6x=FALSE; } return $O6x; } function O67($O66,$O30) { $O5h=O6b($O66,$O30,l1a); $l67=dbdbhexecutesqlquery($O66->getdbhandle(),$O5h); return $l67; } function O7i($l7j) { $O7j=json_decode($l7j["kibanaSavedObjectMeta"]["searchSourceJSON"],TRUE); return $O7j["index"]; } function l7k($l52,$O1,$O7k=TRUE) { l68(l3,$O3r=FALSE); $l5v=dbcreatedbh(KIBELLADB); $l7l=dbdbhexecutesqlquery($l5v->getdbhandle(),"select * from ".l3." where ".l4." = '".$l52."' and ".O3." = '".$O1."'"); $l5v->close(); if ($l7l === FALSE) { echo TAG_ERROR_INTERNAL." when reading from Objects table '".l3."'. ".O16.".\n"; } else { if (count($l7l) == 0) { $O7l="{\"".l1f."\":\"".O12."\",\"".O1f."\":\"".$l52."\",\"".l1g."\":\"".$O1."\",\"".O1j."\":false"."}"; if ($O7k) { $O7l="{\"docs\":[".$O7l."]}"; } } else { $O7l="{\"".l1f."\":\"".O12."\",\"".O1f."\":\"".$l7l[0][l4]."\",\"".l1g."\":\"".$l7l[0][O3]."\",\"".O1g."\":".$l7l[0][O1l]*1 .",\"".O1j."\":true,\"".O1h."\":".$l7l[0][l1r]; $l7m=$l7l[0][l1i]; if ($l7m) $O7l.=",\"".l1i."\":".$l7m; $O7l.="}"; if ($O7k) { $O7l="{\"docs\":[".$O7l."]}"; } } O36($O7l); } } function O7m($l31,$l7n,$O7n) { $l7h=l5t($l31); $O30=$l7h["table"]; $l30=$l7h["db"]; $lp=$l7h["dbengine"]; $O5s=$l7h["cache"]; $l7o=O7o($l31,$l7n); $l7p=O7p($l7o,$O5s); if ($l7p === FALSE) { $l7q["xaggs"]=array(); $l7q["yaggs"]=array(); $l7q["filter"]="where ".l1v." = $O7n"; $l7q["onerec"]=$l7q["filter"]; $l7q["discover"]=array("tab" => TRUE,"limit" => 1); global $O4q; O7q($O30,$l30,$O4q[$lp],$l7q,$l7o["responseFileTmp"]); l7r($l7o,$O5s); } O7r($l7o["responseFileFinal"]); } function l7s($O1) { $O6w=O7c($O1); if ($O6w !== FALSE) { $l55=$O6w; } else { $O30=l1y; $l30=l1x; $O1=O31($O30,$l30); $l55="{\"docs\":[{\"".l1f."\":\"".O12."\",\"".O1f."\":\"index-pattern\",\"".l1g."\":\"".$O1."\",\"".O1g."\":1,\"".O1j."\":true,\"".O1h."\":{\"title\":\"".$O1."\","."\"fields\":\"[{}]\""."}"."}]"."}"; } O36($l55); } function O7s($O1) { $O6x=l7i($O1); if ($O6x !== FALSE) { $l55=$O6x; } else { $O30=l1y; $l30=l1x; $O1=O31($O30,$l30); $O6t="_dummy"; $O6v="string"; $l55="{\"".$O1."\":{"."\"mappings\":{"."\"row\":{"."\"_ttl\":{"."\"full_name\":\"_ttl\","."\"mapping\":{"."\"_ttl\":{"."\"enabled\":false,"."\"default\":-1"."}"."}"."},\"".$O6t."\":{"."\"full_name\":\"".$O6t."\","."\"mapping\":{\"".$O6t."\":{"."\"type\":\"".$O6v."\","."\"index\":\"not_analyzed\""."}"."}"."}"."}"."}"."}"."}"; } O36($l55); } function O6n($Op,$O5h) { $O6m=FALSE; if ($Op !== FALSE) { $O6m=@$Op->query($O5h); } return $O6m; } function l7t($O6m) { $l5=FALSE; if ($O6m) { if (get_class($O6m) == "PDOStatement") { $l5=$O6m->fetch( \pdo::FETCH_ASSOC); } else if (get_class($O6m) == "SQLite3Result") { $l5=$O6m->fetcharray(SQLITE3_ASSOC); } } return $l5; } function O7t($l31,$O5h,$l63) { $O3s=l68(l1s,$O3r=FALSE); $l5v=dbcreatedbh(KIBELLADB); $l67=dbdbhexecutesqlquery($l5v->getdbhandle(),"select ".O1s.", ".O1u." from ".l1s." where ".O1s." = '".$l63."'"." and ".O1t." = '$l31'"); $l7u=strftime("%Y/%m/%d %H:%M:%S",time()); if (count($l67) == 0) { $O7u="true"; $O63=1; $l5=dbdbhexecutesqlquery($l5v->getdbhandle(),"insert into ".l1s." values(\t'$l63',\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t'$l31',\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t'$O5h',\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t$O63,\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t'$l7u'\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t)" ,$O5="exec"); } else { $O63=$l67[0][O1u]+1; $l5=dbdbhexecutesqlquery($l5v->getdbhandle(),"update ".l1s." set ".O1s." = '$l63', ".O1t." = '$l31', ".l1u." = '$O5h', ".O1u." = $O63, ".l1t." = '$l7u'"." where ".O1s." = '$l63'" ,$O5="exec"); } $l5v->close(); } function dbregistertable($l7v) { $l7=new user(); if (PHP_SAPI !== "cli" && !$l7->isloggedin()) return FALSE; $O30=$l7v["table"]; $l30=$l7v["db"]; $O4b=$l7v["dbengine"]; $l6p=$l7v["datefields"]; $O6p=$l7v["geofields"]; $O7h=$l7v["linkfields"]; $O7v=$l7v["enablecache"]; if ($l30 == "") { showmessage("","note",l7w("Database")); } if ($O30 == "") { showmessage("","note",l7w("Table")); } if ($l30 == "" || $O30 == "") { return FALSE; } $O4a=l4c($O4b); global $O4q; $O66=dbcreatedbh($l30,$Oo=$O4q[$O4a],$l6d=array("dir" => DATADIR)); if ($O66->lu()<0) { $O1=""; $O3s=$O66->lu(); } else { $O3s=O6a($O66,$O30); if ($O3s === FALSE || $O3s<0) { $O1=""; } else { $O3s=FALSE; $l30=O7f($l30,$O4a); $O1=O31($O30,$l30); $O3s=l68(O1m,$O3r=TRUE); if ($O3s === FALSE) { return array("id" => $O1,"rc" => FALSE); } $O3s=FALSE; $l7u=strftime("%Y/%m/%d %H:%M:%S",time()); $l5v=dbcreatedbh(KIBELLADB); $l67=dbdbhexecutesqlquery($l5v->getdbhandle(),"select ".O1l." from ".O1m." where lower(".O3.") = '".strtolower($O1)."'"); if ($l67 === FALSE) { $O3s=FALSE; } else if (count($l67) == 0) { $l70=1; $l5=dbdbhexecutesqlquery($l5v->getdbhandle(),"insert into ".O1m." (".O3.",".l1n.",".O1n.",".l1o.",".O1l.",".O1o.",".l1p.",".O1p.",".l1q.",".l1m.")"." values(\t'$O1',\n\t\t\t\t\t\t\t\t\t\t'$O30',\n\t\t\t\t\t\t\t\t\t\t'$l30',\n\t\t\t\t\t\t\t\t\t\t'$O4b',\n\t\t\t\t\t\t\t\t\t\t$l70,\n\t\t\t\t\t\t\t\t\t\t'$l6p',\n\t\t\t\t\t\t\t\t\t\t'$O6p',\n\t\t\t\t\t\t\t\t\t\t'$O7h',\n\t\t\t\t\t\t\t\t\t\t'$O7v',\n\t\t\t\t\t\t\t\t\t\t'$l7u'\n\t\t\t\t\t\t\t\t\t)" ,$O5="exec"); if ($l5 !== FALSE) $O3s=RC_NOTE_TABLE_ADDED; } else { $l70=$l67[0][O1l]+1; $l5=dbdbhexecutesqlquery($l5v->getdbhandle(),"update ".O1m." set ".l1n." = '$O30', ".O1n." = '$l30', ".l1o." = '$O4b', ".O1l." =  $l70, ".O1o." = '$l6p', ".l1p." = '$O6p', ".O1p." = '$O7h', ".l1q." = $O7v, ".l1m." = '$l7u'"." where lower(".O3.") = '".strtolower($O1)."'",$O5="exec"); if ($l5 !== FALSE) $O3s=RC_NOTE_TABLE_UPDATED; } $l5v->close(); } } return array("id" => $O1,"rc" => $O3s); } function l6a($O3r=FALSE) { $O3s=TRUE; $l5v=dbcreatedbh(KIBELLADB); if ($l5v === FALSE) { return FALSE; } $O7w=array(O1m,l3,l1s); $O3s=TRUE; foreach ($O7w as $O30) { $O3s=$O3s && l6b($l5v->getdbhandle(),$O30,$O3r=$O3r); } $l5v->close(); return $O3s; } function l7x($l52,$O7x) { $l7=new user(); if (PHP_SAPI !== "cli" && !$l7->isloggedin()) return FALSE; $l7y=json_decode($O7x,TRUE); switch ($l52) { case (l1e): case (O1e): $l31=O7i($l7y); $O5h=O7y(O1w); break; case (O4): $l31=O7y(l1w); $O5h=""; break; default : $l31=""; $O5h=""; break; } $O3s=l68(l3,$O3r=FALSE); if (ADDTABLEPREFIX == 1) { $l7z= "$l31: "; $O7z=strpos($O7x,$l7z); if ($O7z === strlen("{\"title\":\"")) { $O7x=substr_replace($O7x,"",$O7z,strlen($l7z)); } } else { $l7z=""; } $l80="\"title\":\"([^\"]+)\""; $O7x=preg_replace( "/$l80/" ,"\"title\":\"".$l7z."\$1\"",$O7x); preg_match( "/$l80/" ,$O7x,$l4h); if (count($l4h)>1) { $O80=$l4h[1]; $l81=O81($O80); } else { $l81=""; } $l5v=dbcreatedbh(KIBELLADB); $l67=dbdbhexecutesqlquery($l5v->getdbhandle(),"select ".O3.", ".O1l." from ".l3." where ".l4." = '$l52' and ".O3." = '".$l81."'"); $l7u=strftime("%Y/%m/%d %H:%M:%S",time()); if (count($l67) == 0) { $O7u="true"; $l70=1; $l5=dbdbhexecutesqlquery($l5v->getdbhandle(),"insert into ".l3." values(\t'$l31',\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t'$l52',\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t'$l81',\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t$l70,\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t'$O7x',\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t'$O5h', ".O1v.", "."'$l7u'".")",$O5="exec"); } else { $O7u="false"; $l70=$l67[0][O1l]+1; $l5=dbdbhexecutesqlquery($l5v->getdbhandle(),"update ".l3." set ".O1q." = '$l31', ".l4." = '$l52', ".O3." = '$l81', ".O1l." = $l70, ".l1r." = '$O7x', ".O1r." = '$O5h', ".l1m." = '$l7u'"." where ".O3." = '$l81'" ,$O5="exec"); } $l5v->close(); O70($l52,$l81,$l70,l1j,$O7u); } function l82($O6y,$l52,$O5m) { l68($O6y,$O3r=FALSE); switch ($l52) { case (O4): case (l1e): case (O1e): $O82=l4; $l83=O3; $O83=l1r; $l6z= "where $O82 = '$l52'"; if (LISTONLYTABLEOBJECTS === 1) { $O30=O7y(l1w); if ($O30 !== "") { $l6z.=" and ".O1q." = '$O30'"; } } if ($O5m != "*") { $O5m=str_replace(" ","-",$O5m); $l6z.= " and lower($l83) GLOB lower('*".$O5m."')"; } $l5v=dbcreatedbh(KIBELLADB); $l5f=dbdbhexecutesqlquery($l5v->getdbhandle(),"select $O82, $l83, $O83\n\t\t\t\t\t\t\t\t\t\t\t\tfrom $O6y\n\t\t\t\t\t\t\t\t\t\t\t\t$l6z\n\t\t\t\t\t\t\t\t\t\t\t\torder by $l83"); $l5v->close(); break; case (O1d): $l83=O3; $O83=O1l; $l5v=dbcreatedbh(KIBELLADB); $l5f=dbdbhexecutesqlquery($l5v->getdbhandle(),"select $l83, $O83\n\t\t\t\t\t\t\t\t\t\t\t\tfrom $O6y\n\t\t\t\t\t\t\t\t\t\t\t\torder by $l83"); $l5v->close(); break; default : showmessage( __FUNCTION__ ,TAG_ERROR_INTERNAL,"Invalid object type ($l52). ".O16.".\n"); break; } if ($l5f === FALSE) { showmessage( __FUNCTION__ ,TAG_ERROR_INTERNAL,"when reading from table '$O6y'. ".O16.".\n"); } else { if ($l52 == O4 || $l52 == l1e || $l52 == O1e) { for ($O3u=0; $O3u<count($l5f); $O3u ++) { $l5f[$O3u][$O83]=json_decode($l5f[$O3u][$O83],TRUE); } } else if ($l52 == O1d) { for ($O3u=0; $O3u<count($l5f); $O3u ++) { $l5f[$O3u][l4]=O1d; $l5f[$O3u][$O83]=$l5f[$O3u][$O83]*1; } } l84($l5f); } }