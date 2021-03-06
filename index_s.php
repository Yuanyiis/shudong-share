﻿<?php
require_once("config.php");//基础配置文件
require_once('includes/function.php');//函数库
require_once('includes/smarty.inc.php');//smarty模板配置
require_once('includes/connect.php');
require_once('includes/userShell.php');
$result = mysqli_query($con,"SELECT * FROM sd_setting");//获取数据
while($row = mysqli_fetch_assoc($result)){ 
	$tit = $row['main_tit'];
	$tit1 = $row['tit_2'];
	$theme = $row['theme'];
	$notice = $row['notice'];
	$des = $row['desword'];
	$kw = $row['keyword'];
	$tjcode = $row['tjcode'];
	$zzurl = $row['zzurl'];
}
$userGroup = $userInfo['group'];
$results1 = mysqli_query($con,"SELECT * FROM sd_usergroup where id = $userGroup");
while($row1 = mysqli_fetch_assoc($results1)){ 
	$policyId = $row1['policyid'];
}
$results = mysqli_query($con,"SELECT * FROM sd_policy where id = $policyId");
while($row2 = mysqli_fetch_assoc($results)){ 
	$policyType = $row2['p_type'];
	$fileType = $row2['p_filetype'];
	$fileSize = ceil($row2['p_size']/(1024*1024));
	$autoName = $row2['p_autoname'];
	$nameRule = $row2['p_namerule'];
	$serverUrl = $row2['p_server'];
}
if($policyType!="qiniu"){
	$fileType = 'var min="'.$fileType.'"';
	$filePart = "0";
	if($policyType == "local"){
		$upServer = $zzurl."includes/fileReceive.php";
	}else if($policyType == "server"){
		$upServer = $serverUrl;
	}
}else{
	$fileType = 'var min="'.$fileType.'"';
	$upServer = "http://up.qiniu.com";
	$filePart = "4";
}
$smarty->template_dir = "content/themes/".$theme;
$head ='<script type="text/javascript" src="includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="includes/js/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="includes/js/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="includes/js/ui.js"></script>
<script type="text/javascript" src="includes/js/main.js"></script>
<script type="text/javascript" src="includes/js/qiniu.js"></script>
<meta name="description" content="'.$des.'" />
<meta name="keywords" content="'.$kw.'" />';
$jscode = $tjcode.'
<script type="text/javascript">
var autoname='.$autoName.';'.$fileType.'; var max='.$fileSize.'; var fp="'.$filePart.'"; var upserver ="'.$upServer.'"</script>';
$smarty->assign("isVisitor", $isVisitor); 
$smarty->assign("userinfo", $userInfo); 
$smarty->assign("des", $des);
$smarty->assign("kw", $kw);
$smarty->assign("notice", $notice);
$smarty->assign("tit", $tit."-".$tit1); 
$smarty->assign("head", $head); 
$smarty->assign("jscode", $jscode); 
$smarty->display("index.html");  

?>
