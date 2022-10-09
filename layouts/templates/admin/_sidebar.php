<?php


	$analytics =  $this->url(array('controller'=>'admin','action'=>'analytics'),null,true);
	$readUser = $this->url(array('controller'=>'admin','action'=>'read-users'),null,true);
	$readQuizParticipants = $this->url(array('controller'=>'admin','action'=>'read-quiz-participants'),null,true);
	$readNoOfQuizQuestions = $this->url(array('controller'=>'admin','action'=>'read-no-of-questions'),null,true);
	$readJavaQuizParticipants = $this->url(array('controller'=>'admin','action'=>'read-java-quiz-participants'),null,true);
	$readUnverifiedUsers = $this->url(array('controller'=>'admin','action'=>'read-unverified-users'),null,true);

	$createTrivia = $this->url(array('controller'=>'admin','action'=>'create-trivia'),null,true);
	$readTrivia = $this->url(array('controller'=>'admin','action'=>'read-trivia'),null,true);

	$createQuote = $this->url(array('controller'=>'admin','action'=>'create-quote'),null,true);
	$readQuotes = $this->url(array('controller'=>'admin','action'=>'read-quotes'),null,true);

	$createRiddle = $this->url(array('controller'=>'admin','action'=>'create-riddle'),null,true);
	$readRiddles = $this->url(array('controller'=>'admin','action'=>'read-riddles'),null,true);
	
	$createSubject = $this->url(array('controller'=>'admin','action'=>'create-subject'),null,true);
	$readSubjects   = $this->url(array('controller'=>'admin','action'=>'read-subjects'),null,true);

	$createTopic = $this->url(array('controller'=>'admin','action'=>'create-topic'),null,true);
	$readTopics   = $this->url(array('controller'=>'admin','action'=>'read-topics'),null,true);

	$createProgram = $this->url(array('controller'=>'admin','action'=>'create-program'),null,true);
	$readPrograms = $this->url(array('controller'=>'admin','action'=>'read-programs'),null,true);

	$createVideo = $this->url(array('controller'=>'admin','action'=>'create-video'),null,true);
	$readVideos = $this->url(array('controller'=>'admin','action'=>'read-videos'),null,true);
	
	$createNotesCategory = $this->url(array('controller'=>'admin','action'=>'create-notes-category'),null,true);
	$readNotesCategory = $this->url(array('controller'=>'admin','action'=>'read-notes-category'),null,true);
	
	$createNote = $this->url(array('controller'=>'admin','action'=>'create-note'),null,true);
	$readNotes = $this->url(array('controller'=>'admin','action'=>'read-notes'),null,true);

	$createNotesContent = $this->url(array('controller'=>'admin','action'=>'create-notes-content'),null,true);
	$readNotesContent   = $this->url(array('controller'=>'admin','action'=>'read-notes-content'),null,true);

	$createJavaQuestion = $this->url(array('controller'=>'admin','action'=>'create-java-question'),null,true);
	$readJavaQues = $this->url(array('controller'=>'admin','action'=>'read-java-questions'),null,true);
	
	$createYearlyQuestion  = $this->url(array('controller'=>'admin','action'=>'create-yearly-question'),null,true);
	$readYearlyQuestion = $this->url(array('controller'=>'admin','action'=>'read-yearly-questions'),null,true);

    $createQuiz = $this->url(array('controller'=>'admin','action'=>'create-quiz'),null,true);
	$readQuiz = $this->url(array('controller'=>'admin','action'=>'read-quiz'),null,true);

?>

<div class="panel-group" id="accordion">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-users">Users
	                <span class="badge pull-right"><?php echo $this->usersCount; ?></span>
                </a>
            </h4>
        </div>
        <div id="collapse-users" class="panel-collapse collapse">
            <div class="panel-body">
                <ul>
                    <li><a href="<?php echo $readUser; ?>">Users List</a></li>
                    <li><a href="<?php echo $readUnverifiedUsers; ?>">Unverified Users List</a></li>
                    <li><a href="#"> Deleted users</a></li>
                    <li><a href="#"> Blocked users</a></li>
                </ul>
            </div>
        </div>
    </div><!--users-->

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php $totalJQQues = $this->javaBeg+$this->javaInter+$this->javaAd; ?>
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-java-quiz">
                    Java Quiz
                    <span class="badge pull-right"><?php echo $totalJQQues ?></span>
                </a>
            </h4>
        </div>
        <div id="collapse-java-quiz" class="panel-collapse collapse">
            <div class="panel-body">
                <ul class="read-group">
                    <li class="read-group-item">
                        <div class="btn btn-primary btn-block">
                            #Attempts <span class="badge"><?php echo ($this->begAttempts+$this->interAttempts+$this->adAttempts) ?></span>
                        </div>
                        <ol>
                            <li><p>Beginner <span class="badge"><?php echo $this->begAttempts ?></span></p></li>
                            <li><p>Intermdiate <span class="badge"><?php echo $this->interAttempts ?></span></p></li>
                            <li><p>Advanced <span class="badge"><?php echo $this->adAttempts ?></span></p></li>
                        </ol>
                    </li>
                    <li class="read-group-item">
                        <div class="btn btn-warning btn-block">
                            #Questions <span class="badge"><?php echo ($this->javaBeg+$this->javaInter+$this->javaAd) ?></span>
                        </div>

                        <ol>
                            <li><p>Beginner <span class="badge"><?php echo $this->javaBeg ?></span></p></li>
                            <li><p>Intermediate <span class="badge"><?php echo $this->javaInter ?></span></p></li>
                            <li><p>Advanced <span class="badge"><?php echo $this->javaAd ?></span></p></li>
                        </ol>
                    </li>
                    <li><a href="<?php echo $createJavaQuestion;?>">Create Java question</a></li>
                    <li><a href="<?php echo $readJavaQuizParticipants;?>">Java Quiz Participants List</a></li>
                    <li><a href="<?php echo $readJavaQues; ?>">Java Questions List</a></li>
                </ul>
            </div>
        </div>
    </div><!-- java quiz -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-topics">Topics
	                <span class="badge pull-right"><?php echo $this->topicsCount ?></span>
                </a>
            </h4>
        </div>
        <div id="collapse-topics" class="panel-collapse collapse">
            <div class="panel-body">
                <ul>
                    <li><a href="<?php echo $createTopic; ?>">Add Topic</a></li>
                    <li><a href="<?php echo $readTopics; ?>">Topics List</a></li>
                </ul>
            </div>
        </div>
    </div><!-- Topics -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-subjects">Subjects
	                <span class="badge pull-right"><?php echo $this->subjectsCount ?></span>
                </a>
            </h4>
        </div>
        <div id="collapse-subjects" class="panel-collapse collapse">
            <div class="panel-body">
                <ul>
                    <li><a href="<?php echo $createSubject; ?>">Add Subject</a></li>
                    <li><a href="<?php echo $readSubjects; ?>">Subject List</a></li>
                </ul>
            </div>
        </div>
    </div><!-- subjects -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-programs">Programs
	                <span class="badge pull-right"><?php echo $this->programsCount ?></span>
                </a>
            </h4>
        </div>
        <div id="collapse-programs" class="panel-collapse collapse">
            <div class="panel-body">
                <ul>
                    <li><a href="<?php echo $createProgram; ?>">Add Program</a></li>
                    <li><a href="<?php echo $readPrograms; ?>">Programs List</a></li>
	                <li class="divider" role="separator"></li>
	                <li><a href="<?php echo $createYearlyQuestion; ?>">Add Yearly Questions</a></li>
                    <li><a href="<?php echo $readYearlyQuestion; ?>">Yearly Questions List</a></li>
                </ul>
            </div>
        </div>
    </div><!-- programs -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-trivia">
	                Trivia
                    <span class="badge pull-right"><?php echo $this->triviaCount ?></span>
                </a>
            </h4>
        </div>
        <div id="collapse-trivia" class="panel-collapse collapse">
            <div class="panel-body">
                <ul>
                    <li><a href="<?php echo $createTrivia; ?>">Add Trivia</a></li>
                    <li><a href="<?php echo $readTrivia; ?>">Trivia List</a></li>
                </ul>
            </div>
        </div>
    </div><!-- Trivia -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-riddles">Riddles
	                <span class="badge pull-right"><?php echo $this->riddlesCount ?></span>
                </a>
            </h4>
        </div>
        <div id="collapse-riddles" class="panel-collapse collapse">
            <div class="panel-body">
                <ul>
                    <li><a href="<?php echo $createRiddle; ?>">Add Riddle</a></li>
                    <li><a href="<?php echo $readRiddles; ?>">Riddles List</a></li>
                </ul>
            </div>
        </div>
    </div><!-- Riddles -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-quiz">Quiz
	                <span class="badge pull-right"><?php echo $this->quizCount ?></span>
                </a>
            </h4>
        </div>
        <div id="collapse-quiz" class="panel-collapse collapse">
            <div class="panel-body">
                <ul>
                    <li><a href="<?php echo $createQuiz; ?>">Add Quiz</a></li>
                    <li><a href="<?php echo $readNoOfQuizQuestions; ?>">Quiz Categories List</a></li>
                    <li><a href="<?php echo $readQuizParticipants; ?>">Quiz attempts List</a></li>
                </ul>
            </div>
        </div>
    </div><!-- Quiz -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-videos">
                    Videos
                    <span class="badge pull-right"><?php echo $this->videosCount ?></span>
                </a>
            </h4>
        </div>
        <div id="collapse-videos" class="panel-collapse collapse">
            <div class="panel-body">
                <ul>
                    <li><a href="<?php echo $createVideo; ?>">Add Video</a></li>
                    <li><a href="<?php echo $readVideos; ?>">Videos List</a></li>
                </ul>
            </div>
        </div>
    </div><!-- Videos -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-quotes">
                    Quotes
                    <span class="badge pull-right"><?php echo $this->quotesCount ?></span>
                </a>
            </h4>
        </div>
        <div id="collapse-quotes" class="panel-collapse collapse">
            <div class="panel-body">
                <ul>
                    <li><a href="<?php echo $createQuote; ?>">Add Quote</a></li>
                    <li><a href="<?php echo $readQuotes; ?>">Quotes List</a></li>
                </ul>
            </div>
        </div>
    </div><!-- Quotes -->

    <div class="panel panel-default">
	    <div class="panel-heading">
		    <h4 class="panel-title">
			    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-note">Notes
				    <span class="badge pull-right"><?php echo $this->notesCount ?></span>
			    </a>
		    </h4>
	    </div>
	    <div id="collapse-note" class="panel-collapse collapse">
		    <div class="panel-body">
			    <ul>
				    <li><a href="<?php echo $createNote;?>">Add note</a></li>
				    <li><a href="<?php echo $readNotes; ?>">Notes List</a></li>
				    <li><a href="<?php echo $createNotesContent;?>">Add notes Content</a></li>
				    <li><a href="<?php echo $readNotesContent; ?>">Notes Content List</a></li>
				    <li><a href="<?php echo $createNotesCategory;?>">Add Notes Category</a></li>
				    <li><a href="<?php echo $readNotesCategory; ?>">Notes Category List</a></li>
			    </ul>
		    </div>
	    </div>
    </div><!-- Notes -->

    <div class="panel panel-default">
	    <div class="panel-heading">
		    <h4 class="panel-title">
			    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-misc">Miscellaneous</a>
		    </h4>
	    </div>
	    <div id="collapse-misc" class="panel-collapse collapse">
		    <div class="panel-body">
			    <ul>
				    <li><a href="<?php echo $analytics; ?>">Analytics</a></li>
			    </ul>
		    </div>
	    </div>
    </div><!-- Miscellaneous -->

</div><!--#accordion-->
