<?php
    $iscSamplePapers        =   $this->baseUrl('/isc/sample-papers');
    $iscPreviousYears       =   $this->baseUrl('/isc/previous-years');
    $iscComputerPractical   =   $this->baseUrl('/isc/isc-computer-practical');
    $iscComputerViva        =   $this->baseUrl('/isc/viva-voce');

    $icseSamplePapers       =   $this->baseUrl('/icse/sample-papers');
    $icsePreviousYears      =   $this->baseUrl('/isc/previous-years');

    $iscComputerTheory      =   $this->baseUrl('/isc/isc-comp-theory-sample-ques');
    $iscComputerOutputType  =   $this->baseUrl('/isc/isc-comp-find-output-type-ques');

?>
<div class="sbl">
    <div class="qgroupc">
	    <h3 class="text-primary">Useful Links</h3>
	    <div class="qgroup">
		    <ul>
			    <li><a href="<?php echo $iscSamplePapers ?>">ISC Sample Papers</a></li>
			    <li><a href="<?php echo $iscPreviousYears ?>">ISC Previous Years Papers</a></li>
			    <li><a href="<?php echo $icseSamplePapers ?>">ICSE Sample Papers</a></li>
			    <li><a href="<?php echo $icsePreviousYears ?>">ICSE Previous Years Papers</a></li>
			    <li><a href="<?php echo $iscComputerPractical ?>">ISC Computer Practical</a></li>
			    <li><a href="<?php echo $iscComputerViva ?>">ISC Computer Viva</a></li>
	        </ul>
        </div>
    </div>

</div>

<?php include_once APPLICATION_PATH.'/../layouts/templates/ads/google-ad-vertical.php'; ?>
