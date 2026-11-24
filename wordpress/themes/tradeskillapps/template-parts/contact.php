<?php defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-contact'); $flash = tsa_contact_flash(); $sfx = tsa_uid('contact'); ?>
<section class="band" id="contact<?php echo esc_attr($sec_sfx); ?>" aria-labelledby="contact-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('contact.eyebrow'); ?></p>
				<?php tsa_heading('contact', 'contact-h' . $sec_sfx); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('contact.lede'); ?></p>
		</div>

		<div class="planner rv">
			<form id="tsa-contact<?php echo esc_attr($sfx); ?>" class="tsa-contact" method="post" novalidate
				action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
				<input type="hidden" name="action" value="tsa_contact" />

				<div class="field">
					<label for="cname<?php echo esc_attr($sfx); ?>"><?php tsa_e('contact.nameLabel'); ?></label>
					<input class="textin" id="cname<?php echo esc_attr($sfx); ?>" name="name" autocomplete="name" required />
				</div>
				<div class="field">
					<label for="cemail<?php echo esc_attr($sfx); ?>"><?php tsa_e('contact.emailLabel'); ?></label>
					<input class="textin" id="cemail<?php echo esc_attr($sfx); ?>" name="email" type="email" autocomplete="email" required />
				</div>
				<div class="field">
					<label for="cmsg<?php echo esc_attr($sfx); ?>"><?php tsa_e('contact.messageLabel'); ?></label>
					<textarea class="textin" id="cmsg<?php echo esc_attr($sfx); ?>" name="message" rows="5" required></textarea>
				</div>

				<div aria-hidden="true" style="position:absolute;left:-9999px">
					<label for="company<?php echo esc_attr($sfx); ?>"><?php esc_html_e('Company', 'tradeskillapps'); ?></label>
					<input id="company<?php echo esc_attr($sfx); ?>" name="company" tabindex="-1" autocomplete="off" />
				</div>

				<div class="planner__actions">
					<button class="btn btn--primary" type="submit"
						data-sending="<?php echo esc_attr(tsa_text('contact.sending')); ?>">
						<?php tsa_e('contact.submit'); ?> <span class="arw" aria-hidden="true">&rarr;</span>
					</button>
				</div>

				<p role="status" aria-live="polite" class="privacy"><?php
					if ($flash) echo '<span>' . esc_html($flash) . '</span>'; ?></p>
			</form>
		</div>
	</div>
</section>
