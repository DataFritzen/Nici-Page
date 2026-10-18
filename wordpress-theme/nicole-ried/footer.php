<?php
/**
 * Fußzeile mit Impressum und Datenschutz.
 */
$imprint = nr_page_url( 'page_imprint', 'impressum' );
$privacy = nr_page_url( 'page_privacy', 'datenschutz' );
?>
<footer class="site-footer">
	<div class="wrap">
		<span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> Nicole Ried</span>
		<nav>
			<?php if ( $imprint ) : ?><a href="<?php echo esc_url( $imprint ); ?>">Impressum</a><?php endif; ?>
			<?php if ( $privacy ) : ?><a href="<?php echo esc_url( $privacy ); ?>">Datenschutz</a><?php endif; ?>
		</nav>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
