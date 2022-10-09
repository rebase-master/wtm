<?php
    $sitemap = $this->baseUrl('/Home/sitemap');

    $notes = $this->baseUrl('/notes');
    $poi = $this->baseUrl('/resources/people');
    $ant = $this->baseUrl('/resources/installing-ant');

    $x_sample_papers = $this->baseUrl('/icse/sample-papers');
    $x_previous_year = $this->baseUrl('/icse/previous-years');

    $iscSamplePapers    =   $this->baseUrl('/isc/sample-papers');
    $iscPreviousYears   =   $this->baseUrl('/isc/previous-years');
    $practical          =   $this->baseUrl('/isc/isc-computer-practical');
    $viva               =   $this->baseUrl('/isc/viva-voce');
    $comp_project       =   $this->baseUrl('/isc/computer-project');
	$iscGuess           =   $this->baseUrl('isc/isc-computer-guess-questions');

    $quotes = $this->baseUrl('/quotes');
    $trivia = $this->baseUrl('/trivia');
    $riddles = $this->baseUrl('/riddles');
    $videos = $this->baseUrl('/videos');
    $qa = $this->baseUrl('/qa');
?>

<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Class 10<span class="caret"></span></a>
    <ul class="dropdown-menu" role="menu">
        <li><a href='<?php echo $x_sample_papers; ?>'>Sample Papers</a></li>
        <li><a href='<?php echo $x_previous_year; ?>'>Previous Years</a></li>
    </ul>
</li>
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Class 12<span class="caret"></span></a>
    <ul class="dropdown-menu" role="menu">
        <li><a href='<?php echo $iscSamplePapers; ?>'>Sample Papers</a></li>
        <li><a href='<?php echo $iscPreviousYears; ?>'>Previous Years</a></li>
        <li><a href='<?php echo $practical; ?>'>ISC Computer Practical</a></li>
        <li><a href='<?php echo $iscGuess; ?>'>ISC Computer Guess</a></li>
        <li><a href='<?php echo $viva; ?>'>Viva</a></li>
        <li><a href='<?php echo $comp_project ?>'>Computer Project</a></li>
    </ul>
</li>
<li><a href="<?php echo $notes; ?>">Notes</a></li>
<li><a href="<?php echo $qa; ?>">QA</a></li>

<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Misc<span class="caret"></span></a>
    <ul class="dropdown-menu" role="menu">
        <li><a href='<?php echo $trivia; ?>'>Trivia</a></li>
        <li><a href='<?php echo $quotes; ?>'>Quotes</a></li>
        <li><a href='<?php echo $riddles; ?>'>Riddles</a></li>
    </ul>
</li>
