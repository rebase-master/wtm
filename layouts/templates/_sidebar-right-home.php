<?php include_once APPLICATION_PATH.'/Misc/Util.php';?>

<?php include_once APPLICATION_PATH.'/../layouts/templates/social/fb.php'; ?>
<div class="sb_ext">
	<?php include_once APPLICATION_PATH.'/../layouts/templates/ads/gad-sidebar-right.php'; ?>
</div>
<?php include_once APPLICATION_PATH.'/../layouts/templates/dyk.php'; ?>
<?php include_once APPLICATION_PATH.'/../layouts/templates/qotd.php'; ?>
<?php include_once APPLICATION_PATH.'/../layouts/templates/social/gplus.php'; ?>
<div class="sbu">
	<?php include_once APPLICATION_PATH.'/../layouts/templates/ads/flipkart_affiliate.php'; ?>
</div>

<?php if(isset($this->questions['cs_practical_questions'])): ?>
	<?php   $cgq = $this->questions['cs_practical_questions'];
	if(count($cgq) > 0): ?>
		<div class="panel-group" id="accordion2" role="tablist" aria-multiselectable="true">

			<?php foreach ($cgq as $index => $question): ?>
				<?php $guessQuesUrl = $this->baseUrl('isc/'.$question['url_name'].'/'.$question['type'].'/'.$question['slug']); ?>
				<div class="panel alert alert-danger">
					<div class="panel-heading" role="tab" id="headingOne">
						<h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $index + 1; ?>" aria-expanded="true" aria-controls="collapseOne">
								ISC Computer Practical <?php echo $question['year']; ?> - Question <?php echo $index + 1; ?>
							</a>
						</h4>
					</div>
					<div id="collapse-<?php echo $index + 1; ?>" class="panel-collapse collapse<?php echo $index == 0?" in":""; ?>" role="tabpanel" aria-labelledby="headingOne">
						<div class="panel-body">
							<?php
							if(strlen($question['question']) > 120)
								echo strip_tags(substr($question['question'], 0, strpos(wordwrap($question['question'], 120), "\n")))."...";
							else
								echo strip_tags($question['question']);
							?>
						</div>
						<div class="text-right">
							<a class="btn btn-xs btn-info" href="<?php echo $guessQuesUrl?>">Read More</a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>


		</div><!--accordion2-->
	<?php endif; ?>
<?php endif; ?>


<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<div class="panel alert alert-success">
		<div class="panel-heading" role="tab" id="headingOne">
			<h2 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					Download ISC Specimen Papers 2017
				</a>
			</h2>
		</div>
		<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
			<?php $docRoot = 'docs/isc/specimen/2017'; ?>
			<ul class="list-group spec_list">
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/isc_accounts_specimen_paper_2017.pdf'); ?>">Accounts</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/isc_biology_specimen_paper_2017.pdf'); ?>">Biology Paper 1</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/isc_bio_technology_specimen_paper_2017.pdf'); ?>">Bio-Technology Paper-1</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/isc_commerce_specimen_paper_2017.pdf'); ?>">Commerce</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/isc_environmental_science_specimen_paper_2017.pdf'); ?>">Environmental Science</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/isc_hindi_specimen_paper_2017.pdf'); ?>">Hindi</a></li>
			</ul>
		</div>
	</div>

	<div class="panel alert alert-info">
		<div class="panel-heading" role="tab" id="headingTwo">
			<h2 class="panel-title">
				<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
					Download ICSE Specimen Papers 2017
				</a>
			</h2>
		</div>
		<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
			<?php $docRoot = 'docs/icse/specimen/2017'; ?>
			<ul class="list-group spec_list">
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_biology_specimen_paper_2017.pdf'); ?>">Biology</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_chemistry_specimen_paper_2017.pdf'); ?>">Chemistry</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_commercial_applications_specimen_paper_2017.pdf'); ?>">Commercial Applications</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_commercial_studies_specimen_paper_2017.pdf'); ?>">Commercial Studies</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_computer_applications_specimen_paper_2017.pdf'); ?>">Computer Applications</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_economic_applications_specimen_paper_2017.pdf'); ?>">Economic Applications</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_economics_specimen_paper_2017.pdf'); ?>">Economics</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_english_paper_1_specimen_paper_2017.pdf'); ?>">English Language Paper 1</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_environmental_applications_specimen_paper_2017.pdf'); ?>">Environmental Applications</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_environmental_science_specimen_paper_2017.pdf'); ?>">Environmental Science</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_geography_specimen_paper_2017.pdf'); ?>">Geography</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_hindi_specimen_paper_2017.pdf'); ?>">Hindi</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_history_civics_specimen_paper_2017.pdf'); ?>">History/Civics</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_english_paper_2_specimen_paper_2017.pdf'); ?>">Literature in English</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_mathematics_specimen_paper_2017.pdf'); ?>">Mathematics</a></li>
				<li class="list-group-item"><a target="_blank" href="<?php echo $this->baseUrl($docRoot.'/icse_physics_specimen_paper_2017.pdf'); ?>">Physics</a></li>
			</ul>
		</div>
	</div>

</div>


