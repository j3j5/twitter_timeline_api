		<div class="wrap">
			<div class="wrap_center">
				<h1>B<i class="icon-bosom"></i><i class="icon-bosom"></i>BS.IN</h1>
			</div>
			<section>
				<div class="colTitles">
				<a href="/tumblr/" ><span id="title1" class="titleCol1"><i class="fa fa-tumblr"></i></span></a>
					<a href="/reddit/" ><span id="title2" class="titleCol2"><i class="fa fa-reddit"></i></span></a>
					<a href="/twitter/" ><span id="title3" class="titleCol3"><i class="fa fa-twitter"></i></span></a>
				</div>
				<div class ="colSearchBox">
					<form id="search1" class="search titleCol1" data-baseurl="/tumblr/" action="">
						<input id="tumblrSearch" class="searchField" type="text" placeholder="Search on Tumblr..." required>
<!-- 						<input type="submit" id="search-submit" value="" /> -->
					</form>
					<form id="search2" class="search titleCol2" data-baseurl="/reddit/" action="">
					<input id="redditSearch" class="searchField" type="text" placeholder="Search on a subreddit..." required>
<!-- 						<input type="submit" id="search-submit" value="" /> -->
						</form>
						<form id="search3" class="search titleCol3" data-baseurl="/twitter/" action="">
						<input id="twitterSearch" class="searchField" type="text" placeholder="Search boobs on Twitter..." required>
<!-- 						<input type="submit" id="search-submit" value="" /> -->
					</form>
				</div>
				<div id="container3">
					<div id="container2">
						<div id="container1">
							<div id="col1">
							<?php foreach($images['tumblr'] AS $key => $img) { ?>
								<div class="box">
									<a href="<?php echo isset($urls['tumblr'][$key]) ? $urls['tumblr'][$key] : ''; ?>">
								<?php echo img(array(
											'src' => $img,
											'class' => "colImg",
											'alt' => isset($usernames['tumblr'][$key]) ? $usernames['tumblr'][$key] : '',
										), TRUE); ?>
									</a>
							<?php if($sfw) { ?>
								<div class="safe-boobs"></div>
							<?php } ?>
								</div>
							<?php } ?>
							</div>
							<div id="col2">
							<?php foreach($images['reddit'] AS $key => $img) { ?>
								<div class="box">
									<a href="<?php echo isset($urls['reddit'][$key]) ? $urls['reddit'][$key] : ''; ?>">
								<?php echo img(array(
											'src' => $img,
											'class' => "colImg",
											'crossOrigin' => "anonymous",
											'alt' => isset($usernames['reddit'][$key]) ? $usernames['reddit'][$key] : ''
											), TRUE); ?>
									</a>
								<?php if($sfw) { ?>
									<div class="safe-boobs"></div>
								<?php } ?>
								</div>
							<?php } ?>
							</div>
							<div id="col3">
							<?php foreach($images['twitter'] AS $key => $img) { ?>
								<div class="box">
									<a href="<?php echo isset($urls['twitter'][$key]) ? $urls['twitter'][$key] : ''; ?>">
									<?php echo img(array(
											'src' => $img,
											'class' => "colImg",
											'alt' => isset($usernames['twitter'][$key]) ? $usernames['twitter'][$key] : ''
										), TRUE); ?>
									</a>
								<?php 	if($sfw) { ?>
									<div class="safe-boobs"></div>
								<?php 	} ?>
									</div>
							<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
