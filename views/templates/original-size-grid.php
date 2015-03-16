		<button id="gifToggle" data-filtered="off" style="display: none;">Test only GIF</button>
		<?php if(isset($images['after'])) { ?>
			<button id="next" data-after="<?php echo $images['after']; ?>" style="display: none;">Next</button>
		<?php } /*else { var_dump($images); exit; }*/ ?>
		<div class="wrap">
			<div class="wrap_center">
				<h1>B<i class="icon-bosom"></i><i class="icon-bosom"></i>BS.IN / <i id="titleSource" class="fa <?php echo $source; ?>"></i> ‌ ‌ </h1>
			</div>

			<section class="photos">
			<?php foreach($images AS $key => $img) {
				if($key == 'after') { continue; }
				?>
				<div class="newBox">
					<div class="newBoxInner">
						<a href="<?php echo isset($urls[$key]) ? $urls[$key] : '';?>" target="_blank">
							<?php echo img(array('src' => $img, 'alt' => isset($usernames[$key]) ? $usernames[$key] : '')); ?>

							<div class="titleBox"><i class="fa <?php echo $source; ?>"></i>  <?php echo isset($usernames[$key]) ? $usernames[$key] : 'UNKNOWN' ?></div>
						</a>
					</div>
				<?php if($sfw) { ?>
					<div class="safe-boobs"></div>
				<?php } ?>

				</div>  <!-- newBox -->

				<?php } ?>

			</section>

		</div>
