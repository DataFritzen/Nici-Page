<?php
/**
 * Startseite (OnePager). Inhalte kommen aus dem Customizer, siehe functions.php.
 */
get_header();

$offer_icons = array(
	1 => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"><path d="M22 58c2-14 10-30 26-44"/><path d="M48 6c4 16-4 34-22 40-6-14 4-34 22-40Z"/><path d="M26 46c-8-2-14-8-14-16 8 0 14 6 14 16Z"/></svg>',
	2 => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.4"><circle cx="24" cy="32" r="18"/><circle cx="40" cy="32" r="18"/></svg>',
	3 => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" aria-hidden="true"><circle cx="32" cy="32" r="12"/><path d="M32 4v8M32 52v8M4 32h8M52 32h8M12 12l6 6M46 46l6 6M12 52l6-6M46 18l6-6"/></svg>',
);
$offer_ids = array( 1 => 'coaching', 2 => 'mediation', 3 => 'ziele' );

$email = nr( 'contact_email' );
$phone = nr( 'contact_phone' );
$tel   = preg_replace( '/[^\d+]/', '', $phone );
?>
<main>
	<!-- Hero: links Text, rechts Video mit Lichtweg -->
	<section class="hero" id="start">
		<div class="wrap hero-grid">
			<div class="hero-text">
				<?php if ( nr( 'hero_kicker' ) ) : ?>
					<p class="hero-kicker"><?php echo esc_html( nr( 'hero_kicker' ) ); ?></p>
				<?php endif; ?>
				<h1><?php echo nr_kses( nr( 'hero_title' ) ); ?></h1>
				<?php if ( nr( 'hero_lead' ) ) : ?>
					<p class="hero-lead"><?php echo nr_kses( nr( 'hero_lead' ) ); ?></p>
				<?php endif; ?>
			</div>
			<div class="hero-media">
				<!-- Hintergrundvideo: Mixkit „Silhouette of the mountain and the sunrise“ (Mixkit Stock Video Free License) -->
				<video class="hero-video" muted playsinline preload="auto" aria-hidden="true" src="<?php echo esc_url( get_theme_file_uri( 'assets/video/hero-berg.mp4' ) ); ?>"></video>
				<div class="hero-dawn"></div>
				<div class="hero-sunglow"></div>
				<!-- Lichtweg in Video-Koordinaten (1280 × 720): steigt den dunklen Berghang hinauf bis zum Gipfel -->
				<svg class="hero-path" aria-hidden="true"><g>
					<path pathLength="1" d="M600 780C560 720 470 660 400 600 360 565 350 535 360 500 370 465 335 435 330 400 325 360 360 300 425 255"/>
				</g></svg>
				<ul class="hero-claims">
					<li><?php echo esc_html( nr( 'hero_claim_1' ) ); ?></li>
					<li><?php echo esc_html( nr( 'hero_claim_2' ) ); ?></li>
					<li><?php echo esc_html( nr( 'hero_claim_3' ) ); ?></li>
				</ul>
			</div>
		</div>
	</section>

	<!-- Angebote -->
	<section class="offers" aria-label="Angebote">
		<div class="wrap offer-grid">
			<?php foreach ( array( 1, 2, 3 ) as $n ) : ?>
				<article class="offer reveal" id="<?php echo esc_attr( $offer_ids[ $n ] ); ?>">
					<?php echo $offer_icons[ $n ]; // phpcs:ignore WordPress.Security.EscapeOutput -- feste SVG-Icons ?>
					<h3><?php echo esc_html( nr( "offer_{$n}_title" ) ); ?></h3>
					<p><?php echo esc_html( nr( "offer_{$n}_text" ) ); ?></p>
					<?php $items = nr_lines( "offer_{$n}_list" ); ?>
					<?php if ( $items ) : ?>
						<ul>
							<?php foreach ( $items as $item ) : ?>
								<li><?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- Über mich -->
	<section class="about" id="ueber-mich">
		<div class="wrap about-grid">
			<div class="about-photo reveal">
				<img src="<?php echo esc_url( nr( 'about_image' ) ); ?>" alt="Nicole Ried" loading="lazy">
			</div>
			<div class="reveal">
				<h2><?php echo esc_html( nr( 'about_title' ) ); ?></h2>
				<p class="lead"><?php echo esc_html( nr( 'about_lead' ) ); ?></p>
				<?php foreach ( preg_split( '/\n\s*\n/', trim( nr( 'about_text' ) ) ) as $paragraph ) : ?>
					<p><?php echo nl2br( esc_html( trim( $paragraph ) ) ); ?></p>
				<?php endforeach; ?>
				<a class="btn btn-outline" href="<?php echo esc_url( nr_discovery_url() ); ?>"><?php echo esc_html( nr( 'about_button' ) ); ?> <svg><use href="#i-arrow"/></svg></a>
			</div>
		</div>
	</section>

	<!-- Kontakt -->
	<section class="contact" id="kontakt">
		<div class="wrap contact-grid">
			<div>
				<h2 class="contact-title"><?php echo nr_kses( nr( 'contact_title' ) ); ?></h2>
			</div>
			<div class="contact-info">
				<p style="margin:0 0 6px;opacity:.85"><?php echo esc_html( nr( 'contact_intro' ) ); ?></p>
				<?php if ( $email ) : ?>
					<a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><svg><use href="#i-mail"/></svg><?php echo esc_html( antispambot( $email ) ); ?></a>
				<?php endif; ?>
				<?php if ( $phone ) : ?>
					<a href="tel:<?php echo esc_attr( $tel ); ?>"><svg><use href="#i-phone"/></svg><?php echo esc_html( $phone ); ?></a>
				<?php endif; ?>
				<?php if ( nr( 'contact_location' ) ) : ?>
					<a href="#kontakt"><svg><use href="#i-pin"/></svg><?php echo esc_html( nr( 'contact_location' ) ); ?></a>
				<?php endif; ?>
				<?php if ( $email ) : ?>
					<a class="btn btn-sage" href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>?subject=Kontaktanfrage"><?php echo esc_html( nr( 'contact_button' ) ); ?> <svg><use href="#i-arrow"/></svg></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
