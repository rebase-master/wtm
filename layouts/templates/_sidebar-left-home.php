<?php if(count($this->questions['array']) > 0): ?>
	<div class="qgroupc">
		<h3 class="text-info">Java Array Questions</h3>
		<div class="qgroup">
			<ul>
				<?php foreach ($this->questions['array'] as $question): ?>
					<?php if(!empty($question['heading'])): ?>
						<li>
							<a href="<?php echo $this->baseUrl('/program/'.$question['slug']);?>" title="<?php echo $question['heading']; ?>">
								<?php echo $question['heading']; ?>
							</a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="text-center cbal">
			<a class="btn btn-default btn-sm" href="<?php echo $this->baseUrl('/category/arrays-1d');?>">
				SEE ALL
			</a>
		</div>
	</div>
<?php endif; ?>

<?php if(count($this->questions['strings']) > 0):?>
	<div class="qgroupc">
		<h3 class="text-danger">Java String Questions</h3>
		<div class="qgroup">
			<ul>
				<?php foreach ($this->questions['strings'] as $question): ?>
					<?php if(!empty($question['heading'])): ?>
						<li>
							<a href="<?php echo $this->baseUrl('/program/'.$question['slug']);?>" title="<?php echo $question['heading']; ?>">
								<?php echo $question['heading']; ?>
							</a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="text-center cbal">
			<a class="btn btn-default btn-sm" href="<?php echo $this->baseUrl('/category/strings-basic');?>">
				SEE ALL
			</a>
		</div>
	</div>
<?php endif; ?>

<?php if(count($this->questions['sorting']) > 0): ?>
	<div class="qgroupc">
		<h3 class="text-primary">Sorting in Java</h3>
		<div class="qgroup">
			<ul>
				<?php foreach ($this->questions['sorting'] as $question): ?>
					<?php if(!empty($question['heading'])): ?>
						<li>
							<a href="<?php echo $this->baseUrl('/program/'.$question['slug']);?>" title="<?php echo $question['heading']; ?>">
								<?php echo $question['heading']; ?>
							</a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="text-center cbal">
			<a class="btn btn-default btn-sm" href="<?php echo $this->baseUrl('/category/sorting');?>">
				SEE ALL
			</a>
		</div>
	</div>
<?php endif; ?>

<?php if(count($this->questions['search']) > 0): ?>
	<div class="qgroupc">
		<h3 class="text-success">Searching in Java</h3>
		<div class="qgroup">
			<ul>
				<?php foreach ($this->questions['search'] as $question): ?>
					<?php if(!empty($question['heading'])): ?>
						<li>
							<a href="<?php echo $this->baseUrl('/program/'.$question['slug']);?>" title="<?php echo $question['heading']; ?>">
								<?php echo $question['heading']; ?>
							</a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
<?php endif; ?>

<?php if(count($this->questions['series']) > 0): ?>
	<div class="qgroupc">
		<h3 class="text-warning">Java Series Questions</h3>
		<div class="qgroup">
			<ul>
				<?php foreach ($this->questions['series'] as $question): ?>
					<?php if(!empty($question['heading'])): ?>
						<li>
							<a href="<?php echo $this->baseUrl('/program/'.$question['slug']);?>">
								<?php echo $question['question']; ?>
							</a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="text-center cbal">
			<a class="btn btn-default btn-sm" href="<?php echo $this->baseUrl('/category/series');?>">
				SEE ALL
			</a>
		</div>
	</div>
<?php endif; ?>

