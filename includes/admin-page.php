<div class='wrap' style="max-width:960px;">
	<h2>Simple Testimonials</h2>

	<?php
		if ( isset($_POST['submit']) ) {
			ejo_save_simple_testimonials($_POST['testimonials']);
		}

		$testimonials = get_option( '_ejo_simple_testimonials' );
		$testimonials = (!empty($testimonials)) ? $testimonials : array();
	?>

	<!-- Referentie Clone -->
	<table style="display:none;">
		<tbody>
			<?php admin_show_simple_testimonial(); ?>
		</tbody>
	</table>

	<form action="admin.php?page=<?php echo EJO_Simple_Testimonials::$slug; ?>" method="post">
		<p>
			<?php submit_button( 'Wijzigingen opslaan', 'primary', 'submit', false ); ?>
			<a href="javascript:void(0)" class="button add_testimonial">Referentie toevoegen</a>
		</p>

		<table class="form-table wp-list-table widefat testimonials-table">
			<tbody>
<?php
				foreach ($testimonials as $position => $testimonial) :
					admin_show_simple_testimonial( $position, $testimonial );
				endforeach;
?>
			</tbody>
		</table>
		<p>
			<?php submit_button( 'Wijzigingen opslaan', 'primary', 'submit', false ); ?>
			<a href="javascript:void(0)" class="button add_testimonial">Referentie toevoegen</a>
		</p>
	</form>

	<hr/>
	<h2 class="title">Extra informatie</h2>
	<p>
		Gebruik de shortcode [simple_testimonials] om alle testimonials te tonen op een pagina.
	</p>

</div>

<?php 
/**
 * Save the testimonials data to WordPress options table
 *
 * @param: array with testimonials
 * @return: none
 */
function ejo_save_simple_testimonials($testimonials = array())
{
	/* TODO: got to use Nonces */

	// Save the order despite the array-key numbers (by resetting them)
	$testimonials = array_values($testimonials);

	// Sanitize
	// Remove slashes which WordPress adds automatically to all $_POST content
	$testimonials = stripslashes_deep($testimonials);

	// Sanitize some more
	foreach ($testimonials as $key => $testimonial) {

		// Sanitize text fields
		$testimonials[$key]['author_name'] = sanitize_text_field($testimonial['author_name']);
		$testimonials[$key]['author_info'] = sanitize_text_field($testimonial['author_info']);
		$testimonials[$key]['review_date'] = sanitize_text_field($testimonial['review_date']);
	}

	// Saving 
	update_option( '_ejo_simple_testimonials', $testimonials );

	// Show message
	echo '<div id="message" class="updated"><p><strong>De testimonials zijn opgeslagen.</strong></p></div>';
}

/**
 * Show the testimonial data
 *
 * @param: position (int) | testimonials (array)
 * @return: none
 */
function admin_show_simple_testimonial( $position = 0, $testimonial = array() )
{
	/* Define the class of the container */
	$row_class           = empty($testimonial) ? 'clone' : 'testimonial';

	/* Create default testimonial values */
	$default_testimonial = array(
		'author_name'		=> '',
		'author_info' 		=> '',
		'author_image'		=> '',
		'review_content'	=> '',
		'review_rating' 	=> '',
		'review_date' 		=> '',
	);

	/* Process the testimonial values */
	$testimonial = wp_parse_args($testimonial, $default_testimonial);

	/* Handle backwards compatibility */
	$testimonial['author_name'] = isset($testimonial['title']) ? $testimonial['title'] : $testimonial['author_name'];
	$testimonial['author_info'] = isset($testimonial['caption']) ? $testimonial['caption'] : $testimonial['author_info'];
	$testimonial['review_content'] = isset($testimonial['content']) ? $testimonial['content'] : $testimonial['review_content'];

	?>

	<tr class="<?php echo $row_class; ?>">
		<td class="cel-1">
			<span class="id"><?php echo $position; ?></span>
			<div class="move-testimonial dashicons-before dashicons-sort"><br/></div>
		</td>
		<td class="cel-2">
			<div class="ejo-form-field">
				<label class="top-label"><?= __('Author name', 'ejo-simple-testimonials'); ?></label>
				<input type="text" class="testimonial-author-name" name="testimonials[<?php echo $position; ?>][author_name]" value="<?= esc_attr($testimonial['author_name']) ?>" placeholder="<?= __('Author name', 'ejo-simple-testimonials'); ?>">
			</div>
			<div class="ejo-form-field">
				<label class="top-label"><?= __('Author Info', 'ejo-simple-testimonials'); ?></label>
				<input type="text" class="testimonial-author-info" name="testimonials[<?php echo $position; ?>][author_info]" value="<?= esc_attr($testimonial['author_info']) ?>" placeholder="<?= __('Location, company...', 'ejo-simple-testimonials'); ?>">
			</div>
		</td>
		<td class="cel-3">
			<div class="ejo-form-field">
				<label class="top-label"><?= __('Testimonial', 'ejo-simple-testimonials'); ?></label>
				<textarea class="testimonial-review-content" name="testimonials[<?php echo $position; ?>][review_content]" placeholder="<?= __('Testimonial', 'ejo-simple-testimonials'); ?>"><?= $testimonial['review_content']; ?></textarea>
			</div>
		</td>
		<td class="cel-4">
			<div class="ejo-form-field">
				<label class="top-label"><?= __('Rating', 'ejo-simple-testimonials'); ?></label>
				<select class="testimonial-review-rating" name="testimonials[<?php echo $position; ?>][review_rating]">
					<option value="" <?php selected($testimonial['review_rating'], ""); ?>>-- <?= __('No rating', 'ejo-simple-testimonials'); ?> --</option>
					<option value="1" <?php selected($testimonial['review_rating'], "1"); ?>><?= __('One star', 'ejo-simple-testimonials'); ?></option>
					<option value="2" <?php selected($testimonial['review_rating'], "2"); ?>><?= __('Two stars', 'ejo-simple-testimonials'); ?></option>
					<option value="3" <?php selected($testimonial['review_rating'], "3"); ?>><?= __('Three stars', 'ejo-simple-testimonials'); ?></option>
					<option value="4" <?php selected($testimonial['review_rating'], "4"); ?>><?= __('Four stars', 'ejo-simple-testimonials'); ?></option>
					<option value="5" <?php selected($testimonial['review_rating'], "5"); ?>><?= __('Five stars', 'ejo-simple-testimonials'); ?></option>
				</select>
			</div>
			<div class="ejo-form-field">
				<label class="top-label"><?= __('Date', 'ejo-simple-testimonials'); ?> (<?= __('dd-mm-yyyy', 'ejo-simple-testimonials'); ?>)</label>
				<input type="text" class="testimonial-review-date" name="testimonials[<?php echo $position; ?>][review_date]" value="<?= esc_attr($testimonial['review_date']) ?>" placeholder="<?= __('dd-mm-yyyy', 'ejo-simple-testimonials'); ?>">
			</div>
		</td>
		<td class="cel-5">
			<div class="remove-testimonial dashicons-before dashicons-dismiss"><br/></div>
		</td>
	</tr>

	<?php
}