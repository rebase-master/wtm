<?php if(in_array(APPLICATION_ENV, array('production', 'staging','test'))): ?>
	<div class="sbu">
		<!-- Place this tag where you want the widget to render. -->
		<div class="g-page" style="margin: auto" data-href="//plus.google.com/u/0/112434687978129571118" data-theme="dark" data-rel="publisher"></div>

		<!-- Place this tag after the last widget tag. -->
		<script type="text/javascript">
			(function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/platform.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			})();
		</script>
	</div>
<?php endif; ?>