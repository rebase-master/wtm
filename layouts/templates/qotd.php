<?php include_once APPLICATION_PATH.'/Misc/Util.php';?>

<?php

	$allQuotes  =   $this->url(array('controller'=>'quotes', 'action'=>''),null, true);
	$mdlQuote   =   new Model_Quotes();
	$quote      =   $mdlQuote->randomQuote();
	$slug       =   null;

	if(!empty($quote)){
	    $escapedQuote = htmlspecialchars_decode($quote->quote);
	    $author       = htmlspecialchars_decode($quote->author);
		$slug         = $quote->slug;
	}else{
	    $escapedQuote = "Teach you to wield the force, I shall.";
	    $author       = "Yoda";
	}

	if(empty($slug))
		$dataUrl    = BASE_URL.'quotes';
	else
		$dataUrl    = BASE_URL.'quote/'.$quote->slug;

	$tweet = strlen($escapedQuote)>70?substr($escapedQuote, 0,70)."...":$escapedQuote;



?>
<div class="panel alert alert-warning qt">
	<div class="panel-heading clearfix">
		<h3 class="panel-title pull-left">Quote Of The Day</h3>
		<div class="share-post qotd-wtf pull-right">
			<div class="twitter-share ss">
				<a class="twitter-share-button"
				   href="https://twitter.com/share"
				   data-size="small"
				   data-url="<?php echo $dataUrl; ?>"
				   data-via="wethementors"
				   data-related="quotes, proverbs"
				   data-hashtags="quotes, quoteoftheday"
				   data-text="<?php echo $tweet; ?>">
					Tweet
				</a>
			</div>
		</div>
	</div>
	<div class="panel-body">
        <p><i>&quot;
                <?php echo $escapedQuote;?>
            </i>&quot;
        </p>
        <p class="text-right"> - <?php echo $author?>
        </p>
    </div>
</div>

