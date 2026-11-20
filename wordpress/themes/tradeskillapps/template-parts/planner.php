<?php
/** The starter planner. Guidance is produced in the browser; nothing is sent. */
defined('ABSPATH') || exit;
$roles  = tsa_lines('planner.roles');
$stages = tsa_lines('planner.stages');
$opts   = tsa_lines('planner.tradeOptions');
?>
<section class="band" id="start" aria-labelledby="start-h">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('planner.eyebrow'); ?></p>
				<?php tsa_heading('planner', 'start-h'); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('planner.lede'); ?></p>
		</div>

		<div class="planner rv" id="planner">
			<form novalidate>
				<fieldset class="field">
					<legend><?php tsa_e('planner.roleLabel'); ?></legend>
					<div class="chips">
						<?php foreach ($roles as $i => $r) : ?>
							<label class="chip">
								<input type="radio" name="role" value="<?php echo esc_attr($r); ?>" <?php checked($i, 0); ?> />
								<span><?php echo esc_html($r); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</fieldset>

				<div class="field">
					<label for="tradeInput"><?php tsa_e('planner.tradeLabel'); ?></label>
					<input class="textin" type="text" id="tradeInput" name="trade" autocomplete="off"
						list="tradeOptions" placeholder="<?php echo esc_attr(tsa_text('planner.tradePlaceholder')); ?>" />
					<datalist id="tradeOptions">
						<?php foreach ($opts as $o) : ?><option value="<?php echo esc_attr($o); ?>"></option><?php endforeach; ?>
					</datalist>
				</div>

				<fieldset class="field">
					<legend><?php tsa_e('planner.stageLabel'); ?></legend>
					<div class="chips">
						<?php foreach ($stages as $i => $s) : ?>
							<label class="chip">
								<input type="radio" name="stage" value="<?php echo esc_attr($s); ?>" <?php checked($i, 0); ?> />
								<span><?php echo esc_html($s); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</fieldset>

				<div class="planner__actions">
					<button class="btn btn--primary" type="submit">
						<?php tsa_e('planner.submit'); ?> <span class="arw" aria-hidden="true">&rarr;</span>
					</button>
					<button class="btn btn--quiet" type="button" data-act="reset">
						<svg width="15" height="15" aria-hidden="true"><use href="#i-reset" /></svg> <?php tsa_e('planner.reset'); ?>
					</button>
				</div>

				<p class="privacy">
					<svg aria-hidden="true"><use href="#i-lock" /></svg>
					<span><?php tsa_e('planner.privacy'); ?></span>
				</p>
			</form>

			<div class="plan-out" hidden></div>
			<p class="vh" role="status" aria-live="polite"></p>
		</div>
	</div>
</section>
