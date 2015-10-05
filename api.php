<?php
//
// API for BIN, VAT
// By: Zawad
//
// This API retrieves BIN , VAT info from www.nbr.gov.bd server.
// You're free to use it to build your own web app and other apps.
//
// If you directly want to use API without hosting the codes, use http://www.zawad.science/api/bin.php?binz=$bin_id
// In $bin_id input the BIN no, eg. http://www.zawad.science/api/bin.php?binz=19151043940
// For any issues and support use Issues in Github
//

//Defining variables
$bin=$_GET['binz'];

//Initiating cURL
$ch = curl_init();
//Sending request to server
curl_setopt($ch, CURLOPT_URL,"http://nbr.gov.bd/getbinfield.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"txtSearch=".$bin."&btnSubmit=Search");

// Receiving server response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$unfilteredoutput= curl_exec ($ch);

//Closing cURL
curl_close ($ch);

//Checking if bin exist or not
if(!strpos($unfilteredoutput,'No Result Found')){
	
//Filtering forms and etc.
	$firstfilter='<form id="frmThis" name="frmThis" method="post" action="">
		<div class="usefull_links" style="width: 184px; margin-right:8px;">
			<div class="links_btm" style="width: 180px;">
				<div class="link_btm2" style="width: 180px;">
					<h2 align="center" style="padding-top:10px; margin-top: 0px; font-size:14px;">BIN Status</h2>
				</div>
				<div class="links_text" style="width:175px;">
					<p style="padding-left:10px; font-size:12px;">Do you need to know the present status of a business firm ?</p>
					<p style="padding-left:10px; margin-bottom:0px; font-size:12px;">Enter BIN</p>
					<p style="padding-left:10px; margin-top:0px; font-size:12px;"><input name="txtSearch" type="text" id="txtSearch" size="20" autocomplete="off">
					</p>
					<p style=" font-size:12px; padding-left:85px;"><input name="btnSubmit" type="submit" id="btnSubmit"  value="Search" /></p>';



	$filtered1=str_replace($firstfilter, "",$unfilteredoutput);

	//Filtering useless closing tags.
	$secondfilter='</div>
			</div>
		</div>
	</form>';
	$filtered2=str_replace($secondfilter,"",$filtered1);

	//Filtering output colors.
	$thirdfilter='<p style="font-size:12px; color:green; font-weight:bold;" align="center" style="color:#FF0000">';
	$filtered3=str_replace($thirdfilter,"",$filtered2);

	//Filtering useless tag.
	$fourthfilter='</p>';
	$filtered4=str_replace($fourthfilter,"",$filtered3);

	//Removing 'Name:' from output.
	$namefilter="Name:";
	$filtered5=str_replace($namefilter,"",$filtered4);

	//Removing 'Address:' from output.
	$addressfilter="Address:";
	$filtered6=str_replace($addressfilter,"",$filtered5);

	//Finalizing.
	$filteredf=$filtered6;

	//Exploding by <br>
	$result=explode("<br>",$filteredf);

	//Trimming and making two different varibles for name and address
	$name=trim($result[0]);
	$address=trim($result[1]);

	//Making array with existance of bin, name and address
	$resarr=array("valid"=>'1',"name"=>$name,"address"=>$address);

	//Encoding in JSON
	$json=json_encode($resarr);

	//Sending response to client
	header('Content-Type: application/json');
	echo $json;
}
else {
	//Making array with existance of bin, name and address
	$resarr=array("valid"=>'0',"name"=>"none","address"=>"none");
	
	//Encoding in JSON
	$json=json_encode($resarr);
	
	//Sending response to client
	header('Content-Type: application/json');
	echo $json;
}
?>