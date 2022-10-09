<?php

//$this->baseUrl('/controller/action/param/'.$this->param_id);

$askQuestion = $this->baseUrl('/qa/ask-question');
$allQuestions = $this->baseUrl('/qa');
$guess = $this->baseUrl('/isc/isc-computer-practical?v=guess-questions');
$comp = $this->baseUrl('/programming-in-java');
$eng_lang=$this->baseUrl('/resources/english-language');
$practical=$this->baseUrl('/isc/isc-computer-practical');
$viva=$this->baseUrl('/isc/viva-voce');
$for=$this->baseUrl('/programming-in-java/for-loop');
$while=$this->baseUrl('/programming-in-java/while-loop');
$doWhile=$this->baseUrl('/programming-in-java/do-while-loop');
$arrays1=$this->baseUrl('/programming-in-java/array1-D');
$arrays2=$this->baseUrl('/programming-in-java/array2-D');
$strings=$this->baseUrl('/programming-in-java/strings');
$functions=$this->baseUrl('/programming-in-java/functions');
$overloading=$this->baseUrl('/programming-in-java/function-overloading');
$recursive=$this->baseUrl('/programming-in-java/recursive-function');
?>
<div class="panel panel-warning">
    <div class="panel-heading">
        <h3 class="panel-title">Quick Links</h3>
    </div>
    <div class="panel-body topics">
        <a href="<?php echo $comp;?>">Programming In Java</a>
        <a href="<?php echo $for;?>">For-loop</a>
        <a href="<?php echo $viva;?>">Viva-Voce</a>
        <a href="<?php echo $eng_lang;?>">English</a>
        <a href="<?php echo $practical;?>">Computer Practical</a>
        <a href="<?php echo $while;?>">While-loop</a>
        <a href="<?php echo $doWhile;?>">Do-while loop</a>
        <a href="<?php echo $arrays1;?>">Arrays 1D</a>
        <a href="<?php echo $arrays2;?>">Arrays 2D</a>
        <a href="<?php echo $strings;?>">Strings</a>
        <a href="<?php echo $functions?>">Functions</a>
        <a href="<?php echo $overloading;?>">Function overloading</a>
        <a href="<?php echo $recursive;?>">Recursive Functions</a>
    </div>
</div>

