<?php
/**
 * Select Element to choose thank you page.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Custom_Thank_You\Templates
 * 
 * @var array $thank_you_pages 
 * @var array $chosen_values that contains custom thank you page id(s)
 * @var bool $allow_multiple_select that decides if the select supports multiple selection.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<select style="width:100%" name="nv_thank_you_page_id<?php echo $allow_multiple_select ? '[]' : ''; ?>" class="nv-thank-you-select" <?php echo $allow_multiple_select ? 'multiple' : ''; ?>>
	<?php 
	if ( ! $allow_multiple_select ) {
		?>
		<option value="0"><?php esc_html_e( 'None', 'neve' ); ?></option><?php } ?>
	<?php foreach ( $thank_you_pages as $page_id => $page_title ) { ?>
		<option <?php echo in_array( $page_id, $chosen_values ) ? 'selected' : ''; ?> value="<?php echo esc_attr( $page_id ); ?>"><?php echo esc_html( $page_title ); ?></option>
	<?php } ?>
</select>
