<?php
	$mdlTrivia   = new Model_Trivia();
	$result      = $mdlTrivia->randomTrivia();
	$encoded     = ($result->fact);
	$tweet       = strlen($encoded)>70?substr($encoded, 0,70)."...":$encoded;
	$triviaUrl   = BASE_URL.'trivia';
?>

<div class="panel alert alert-danger qt">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Trivia</h3>
	    <div class="share-post qotd-wtf pull-right">
		    <div class="twitter-share ss">
			    <a class="twitter-share-button"
			       href="https://twitter.com/share"
			       data-size="small"
			       data-url="<?php echo $triviaUrl; ?>"
			       data-via="wethementors"
			       data-related="facts,DidYouKnow,trivia"
			       data-hashtags="funfact,DidYouKnow"
			       data-text="<?php echo $tweet; ?>">
				    Tweet
			    </a>
		    </div>
	    </div>
    </div>
    <div class="panel-body">
        <p><?php echo $result->fact;?></p>
    </div>
</div>
