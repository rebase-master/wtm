<?php if(!empty($this->results)): ?>
	<div id="unit-list">
		<?php foreach ($this->results as $key => $results): ?>
			<div class="qgroupc">
				<h3><?php echo ucwords($key); ?></h3>
				<div class="qgroup">
					<ul>
						<?php foreach ($results as $result): ?>
							<?php if(!empty($result['sub_category'])): ?>
							<?php $link = $this->baseUrl('/notes/'.str_replace(" ","-", $result['category']).'/'.urlencode($result['slug'])); ?>
								<li>
									<a href="<?php echo $link; ?>" title="<?php echo ucwords($result['sub_category']); ?>">
										<?php echo ucwords($result['sub_category']); ?>
									</a>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="center-block cbal">
					<a class="btn btn-warning btn-block" href="<?php echo $this->baseUrl('/category/arrays-1d');?>">
						SEE ALL
					</a>
				</div>
			</div><!-- .qgroupc -->
		<?php endforeach; ?>
	</div><!-- #unit-list -->
<?php endif; ?>



<div class="google-ad">
	<?php include_once APPLICATION_PATH.'/../layouts/templates/ads/gad-sidebar-left.php'; ?>
</div>

