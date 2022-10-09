<?php
    $loopsSeriesT=$this->url(array('controller'=>'qa','action'=>'questions','tagged'=>'loops-series'),null,true);
    $loopsPatternsT=$this->url(array('controller'=>'qa','action'=>'questions','tagged'=>'loops-patterns'),null,true);
    $loopsO=$this->url(array('controller'=>'qa','action'=>'questions','tagged'=>'loops-other'),null,true);
    $functionsT=$this->url(array('controller'=>'qa','action'=>'questions','tagged'=>'functions'),null,true);
    $dataStructuresT=$this->url(array('controller'=>'qa','action'=>'questions','tagged'=>'data-structures'),null,true);
    $arrays1=$this->url(array('controller'=>'qa','action'=>'questions','tagged'=>'arrays-1-d'),null,true);
    $arrays2=$this->url(array('controller'=>'qa','action'=>'questions','tagged'=>'arrays-2-d'),null,true);
    $strings=$this->url(array('controller'=>'qa','action'=>'questions','tagged'=>'strings'),null,true);
    $recF=$this->url(array('controller'=>'qa','action'=>'questions','tagged'=>'recursive-functions'),null,true);
    $classObj=$this->url(array('controller'=>'qa','action'=>'questions','tagged'=>'classes-and-Objects'),null,true);
    $ll=$this->url(array('controller'=>'qa','action'=>'questions','tagged'=>'linked-lists'),null,true);

	$tags = array(
			'Loops-Series','Loops-Patterns','Loops-Other', 'Functions',
			'Data Structures', 'Arrays 1D', 'Arrays 2D', 'Strings',
			'Recursive Functions','Classes/Objects', 'Linked Lists'
	)
?>

<div class="panel qt alert-warning">
    <div class="panel-heading">
        <h3 class="panel-title">Popular Tags</h3>
    </div>
    <div class="panel-body tagged">
	    <?php foreach ($tags as $tag): ?>
		    <?php $link = $this->url(array('controller'=>'qa','action'=>'questions',
			    'tagged' => strtolower(preg_replace('/[^a-zA-Z\d]/', '-', $tag))
		    ),null,true); ?>
            <a class="label" href="<?php echo $link; ?>">
	            <?php echo $tag ?>
            </a>
	    <?php endforeach; ?>
    </div>
</div>