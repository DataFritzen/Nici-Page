<?php
/**
 * Unterseiten wie Impressum und Datenschutz – und Rückfall für alle übrigen Ansichten.
 */
get_header();
?>
<main class="page-content">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?>>
					<h1><?php the_title(); ?></h1>
					<?php the_content(); ?>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<h1>Seite nicht gefunden</h1>
			<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Zur Startseite</a></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
