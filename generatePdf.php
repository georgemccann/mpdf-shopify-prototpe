<?php
$files = glob($_SERVER['DOCUMENT_ROOT'].'/generatedDocs/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file)) {
    unlink($file); // delete file
  }
}


$data = json_decode(stripslashes($_POST['data']));
$posterSize = $data[0]->posterSize;
$posterOrientatiton = $data[0]->posterOrientatiton;
$content = '';

if ($posterOrientatiton == 'L') {
	$aType = '-L';
  $headerImage = '';
	if ($posterSize == 'a2') {
		$logoHeight = '15mm';
		$logoHeight2 = '15mm';
		$logoMargin = '60px';
		$headerImage = '2';
		$containerPadding = "10mm";
		$bootSize = "30mm";
    $titleSize = "4mm";
    $textSize = "3mm";
    $col = "3";
    $footerSize = "3mm";
    $titleMarginTop = '17mm';
	} else if ($posterSize == 'a3') {
    $logoHeight = '15mm';
    $logoHeight2 = '15mm';
    $logoMargin = '65px';
    $containerPadding = "8mm";
    $bootSize = "40mm";
    $titleSize = "4mm";
    $textSize = "3mm";
    $col = "3";
    $footerSize = "4mm";
    $titleMarginTop = '15.5mm';
	} else {
		$logoHeight = '15mm';
		$logoHeight2 = '15mm';
		$logoMargin = '25px';
		$containerPadding = "4mm";
		$bootSize = "48mm";
		$titleSize = "3mm";
		$textSize = "2mm";
		$col = "4";
		$footerSize = "2mm";
		$titleMarginTop = '5.5mm';
	}
} else {
	$aType = '';
	if ($posterSize == 'a2') {
		$logoHeight = '20mm';
		$logoHeight2 = '15mm';
		$logoMargin = '25px';
		$containerPadding = "23mm";
		$bootSize = "44mm";
    $titleSize = "6mm";
    $textSize = "4mm";
    $col = "3";
    $footerSize = "4mm";
    $titleMarginTop = '5.5mm';
	} else if ($posterSize == 'a3') {
    $logoHeight = '15mm';
    $logoHeight2 = '10mm';
    $logoMargin = '65px';
    $containerPadding = "8mm";
    $bootSize = "48mm";
    $titleSize = "5mm";
    $textSize = "4mm";
    $col = "4";
    $footerSize = "4mm";
    $titleMarginTop = '15.5mm';
	} else {
		$logoHeight = '15mm';
		$logoHeight2 = '10mm';
		$logoMargin = '25px';
		$containerPadding = "4mm";
		$bootSize = "48mm";
		$titleSize = "3mm";
		$textSize = "2mm";
		$col = "6";
		$footerSize = "2mm";
		$titleMarginTop = '5.5mm';
	}
}

foreach($data as $d){
 
  $content .= '<div class="col-xs-'.$col.' text-center"  style="margin-bottom: 10mm; padding:0px 0px 0px 0px">
			        <div><img style="height: '.$bootSize.'" src="'.$d->image.'" /></div>
			        <div style="font-size: '.$titleSize.'; margin-top:4mm"><strong>'.$d->title.'</strong></div>
			        <div style="font-size:'.$textSize.'"><strong>'.$d->sizes.'</strong></div> 
			        <div style="font-size:'.$textSize.'"><strong>'.$d->spec.'</strong></div> 
			        <div style="font-size:'.$textSize.'"><strong>'.$d->additionalInfo.'</strong></div> 
			      </div>';
}

require_once __DIR__ . '/vendor/autoload.php';


if ($posterSize == 'a2') {
	$mpdf = new \Mpdf\Mpdf([
      'margin_header' => 0,
      'margin_footer' => 0,
      'margin_left' => 5,
      'margin_right' =>5,
      'mode' => 'utf-8',
	    'format' => 'A2'.$aType,
	    'orientation' => $posterOrientatiton,
	    'img_dpi' => 300, 
      'mirrorMargins'         => true,
      'bleedMargin'           => 3,
    ]);

} else if ($posterSize == 'a3') {
	$mpdf = new \Mpdf\Mpdf([
      'margin_header' => 0,
      'margin_footer' => 0,
      'margin_left' => 5,
      'margin_right' =>5,
      'mode' => 'utf-8',
	    'format' => 'A3'.$aType,
	    'orientation' => $posterOrientatiton,
	    'img_dpi' => 300, 
      'mirrorMargins'         => true,
      'bleedMargin'           => 3,
    ]);

} else {
	$mpdf = new \Mpdf\Mpdf([
      'margin_header' => 0,
      'margin_footer' => 0,
      'margin_left' => 5,
      'margin_right' =>5,
      'mode' => 'utf-8',
	    'format' => 'A4'.$aType,
	    'orientation' => $posterOrientatiton,
	    'img_dpi' => 300, 
      'mirrorMargins'         => true,
      'bleedMargin'           => 3,
    ]);

}

 
$stylesheet = file_get_contents('mpdfCss.css');
 
$mpdf->defaultheaderline = 0;
$mpdf->defaultfooterline = 0;
$mpdf->SetDisplayMode('real');
$mpdf->WriteHTML($stylesheet, 1); // CSS Script goes here.

if ($posterSize == 'a2') {

	if ($posterOrientatiton == 'P') {
		$mpdf->WriteHTML('
		<style>
		    @page {  
		        size: 420mm 594mm; 
		        margin-header: 0;
		        margin-footer: 0;
		        header: header;
		        footer: footer;
		    }
		</style> 
		');

	} else {
		$mpdf->WriteHTML('
		<style>
		    @page {  
		        size: landscape; 
		        margin-header: 0;
		        margin-footer: 0;
		        header: header;
		        footer: footer;
		    }
		</style> 
		');
	}
	

} else if ($posterSize == 'a3') {

	if ($posterOrientatiton == 'P') {
		$mpdf->WriteHTML('
		<style>
		    @page { 
		        size: 297mm 420mm; 
		        margin-header: 0;
		        margin-footer: 0;
		        header: header;
		        footer: footer;
		    }
		</style> 
		');

	} else {
		$mpdf->WriteHTML('
		<style>
		    @page { 
		        size: landscape;
		        margin-header: 0;
		        margin-footer: 0;
		        header: header;
		        footer: footer;
		    }
		</style> 
		');
	}
	

} else {

	if ($posterOrientatiton == 'P') {
		$mpdf->WriteHTML('
		<style>
		    @page { 
		        size: 210mm 297mm; 
		        margin-header: 0;
		        margin-footer: 0;
		        header: header;
		        footer: footer;
		    }
		</style> 
		');
	} else {
		$mpdf->WriteHTML('
		<style>
		    @page { 
		        size: landscape;
		        margin-header: 0;
		        margin-footer: 0;
		        header: header;
		        footer: footer;
		    }
		</style> 
		');
	}
	
}

if ($data[0]->logoOne) {
	$logoOne = '<img style="height: '.$logoHeight2.'" src="'.$data[0]->logoOne.'" />';
} else {
	$logoOne = '&nbsp;';
}

if ($data[0]->logoTwo) {
	$logoTwo = '<img style="height: '.$logoHeight2.'" src="'.$data[0]->logoTwo.'" />';
} else {
	$logoTwo = '&nbsp;';
}

$mpdf->SetHTMLHeader('
 
	
	<div id="header" style="position:relative; margin: 0 5mm"> 
	  <img style="float: left; margin-top:5mm" src="V12Header'.$headerImage.'.png" />
	  <div class="container">
	    <div class="row">
	      <div class="col-xs-6"  style="padding:5mm 0mm">
	        <div class="row align-items-center jusitfy-content-center bg-white" style="padding: 5mm 0mm; margin-left: 15px;margin-right:15px">
			      <div class="col-xs-6 text-center"  style="padding:0px 0px 0px 0px">
			        &nbsp;
			      </div>
			      <div class="col-xs-6 text-center"  style="padding:0px 0px 0px 0px"> 
			        <h3 style="color:white; margin-top:'.$titleMarginTop.'; margin-bottom:0.5mm; font-size: '.$titleSize.';">'.$data[0]->posterTitle.'</h3> 
			      </div> 
			    </div> 
	      </div>
	      <div class="col-xs-6"  style="margin: 0px; padding:0px 0px 0px 0px">
	        <div class="row bg-white" style="padding: 5mm 0mm; margin-left: 15px;margin-right:15px">
			      <div class="col-xs-6 text-center"  style="padding:'.$logoMargin.' 0px 0px 0px">
			        '.$logoTwo.'
			      </div>
			      <div class="col-xs-6 text-center"  style="padding:'.$logoMargin.' 0px 0px 0px"> 
			        '.$logoOne.'
			      </div> 
			    </div>
	      </div> 
	    </div>
    </div>
	</div>');
$mpdf->WriteHTML('
  <div class="container" style="padding-top:'.$containerPadding.'; margin: 0 5mm">
	  <div class="row">
	    <div class="col-xs-12">
	      <div class="bg-white" style="padding:5mm 0mm">
	        
	        <div class="row bg-white" style="padding: 2.5mm 0mm; margin-left: 15px;margin-right:15px; padding-top:10mm">
			      
			      '.$content.'

			    </div>
	      </div>
	    </div> 
	  </div>
	</div>', 2);
$mpdf->setFooter('<div id="footer" style="color:black;border:0; margin: 0 5mm"> 
	    <div class="row">
	      <div class="col-xs-12 text-center"  style="padding:2.5mm 15mm">
	        <div style="color:black;font-size: '.$footerSize.'; font-weight: normal;">'.$data[0]->companyName.'</div>
	        <div style="color:black;font-size: '.$footerSize.'; font-weight: normal;">'.$data[0]->footerTextAddress.'</div>
	        <div style="color:black;font-size: '.$footerSize.'; font-weight: normal;">'.$data[0]->footerTextPhone.'</div>
	        <div style="color:black;font-size: '.$footerSize.'; font-weight: normal;">'.$data[0]->footerTextEmail.'</div>
	      </div> 
	    </div>
   
	</div>');

$pdfFilePath ="/generatedDocs/v12-".time()."-download.pdf";
$mpdf->Output($_SERVER['DOCUMENT_ROOT'].$pdfFilePath, "F");
echo $pdfFilePath;

?>