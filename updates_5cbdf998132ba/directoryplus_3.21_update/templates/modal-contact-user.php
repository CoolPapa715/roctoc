<!-- modal contact user -->
<div id="contact-user-modal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<?php
					if($route[0] == 'listing') {
						?>
						<?= $txt_contact_business ?>
						<?php
					}

					if(in_array($route[0], array('profile', 'favorites', 'reviews'))) {
						if(!empty($profile_id)) {
							?>
							<?= $txt_contact_user ?>
							<?php
						}
					}
					?>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?= $txt_close ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
				if(!empty($wa_country_code) && !empty($wa_area_code) && !empty($wa_phone)) {
					?>
					<div class="mb-5">
						<p><strong><?= $txt_click_to_chat ?></strong></p>
						<a href="https://wa.me/<?= $wa_country_code . $wa_area_code . $wa_phone ?>" class="btn" style="background-color:#25d366;color:white" target="_blank"><i class="fab fa-whatsapp"></i> Whatsapp</a>
					</div>
					<?php
				}
				?>

				<div id="contact-user-result"></div>
				<form id="contact-user-form" method="post">
					<?php
					if($route[0] == 'listing') {
						?>
						<input type="hidden" id="place_id" name="place_id" value="<?= $place_id ?>">
						<input type="hidden" id="from_page" name="from_page" value="listing">
						<input type="hidden" id="listing_url" name="listing_url" value="<?= $canonical ?>">
						<?php
					}

					if(in_array($route[0], array('profile', 'favorites', 'reviews'))) {
						if(!empty($profile_id)) {
							?>
							<input type="hidden" id="recipient_id" name="recipient_id" value="<?= $profile_id ?>">
							<input type="hidden" id="from_page" name="from_page" value="profile">
							<?php
						}
					}
					?>

					<div class="form-group">
						<input type="text" id="sender_name" class="form-control" name="sender_name" placeholder="<?= $txt_name ?>" required>
					</div>

					<div class="form-group">
						<input type="email" id="sender_email" class="form-control" name="sender_email" placeholder="<?= $txt_email ?>" required>
					</div>

					<div class="form-group">
						<textarea id="sender_msg" class="form-control" name="sender_msg" rows="5" placeholder="<?= $txt_message ?>" required></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-dark" data-dismiss="modal"><?= $txt_cancel ?></button>
				<button type="submit" id="contact-user-submit" class="btn btn-dark"><?= $txt_submit ?></button>
			</div>
		</div>
	</div>
</div>