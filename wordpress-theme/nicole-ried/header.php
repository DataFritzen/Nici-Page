<?php
/**
 * Kopfbereich: Logo und Kontakt-Button.
 */
$home = is_front_page() ? '#start' : home_url( '/' );
$cta  = is_front_page() ? '#kontakt' : home_url( '/#kontakt' );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Wiederverwendbare Icons -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
	<symbol id="i-arrow" viewBox="0 0 24 24"><path d="M4 12h15M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></symbol>
	<linearGradient id="gold" gradientUnits="userSpaceOnUse" x1="10" y1="110" x2="110" y2="10">
		<stop offset="0" stop-color="#a87b3f"/><stop offset=".55" stop-color="#e2c48f"/><stop offset="1" stop-color="#f7ecd6"/>
	</linearGradient>
	<symbol id="i-mail" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3.5 6 8.5 7 8.5-7"/></g></symbol>
	<symbol id="i-phone" viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></symbol>
	<symbol id="i-pin" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 21s-7-6.2-7-12a7 7 0 0 1 14 0c0 5.8-7 12-7 12Z"/><circle cx="12" cy="9" r="2.5"/></g></symbol>
</svg>

<header class="site-header">
	<div class="wrap nav">
		<a class="brand" href="<?php echo esc_url( $home ); ?>" aria-label="Nicole Ried – zur Startseite">
			<!-- Logo „Balance Circle“, zeichnet sich beim Laden ein -->
			<svg class="logo-anim" viewBox="0 0 120 120" aria-hidden="true"><g fill="none" stroke-linecap="round" stroke-linejoin="round">
				<path class="d1" pathLength="1" stroke="url(#gold)" stroke-width="2.4" d="M95.2 89.6A46 46 0 1 1 95.2 30.4"/>
				<path class="d2" pathLength="1" stroke="url(#gold)" stroke-width=".9" opacity=".6" d="M30 30A43 43 0 0 1 87.6 27.1"/>
				<path class="d3" pathLength="1" stroke="currentColor" stroke-width="1.1" d="M36 81V41"/>
				<path class="d4" pathLength="1" stroke="currentColor" stroke-width="2.8" d="M36.5 41 61.5 81"/>
				<path class="d5" pathLength="1" stroke="currentColor" stroke-width="1.1" d="M62 81V41"/>
				<path class="d6" pathLength="1" stroke="currentColor" stroke-width="1.8" d="M62 41h7a10 10 0 0 1 0 20h-7"/>
				<path class="d7" pathLength="1" stroke="url(#gold)" stroke-width="2.2" d="M67 61c5 9 11 19 24 24 5 2 10 2 14 0"/>
			</g></svg>
			<span>
				<span class="brand-name">NICOLE RIED</span><br>
				<span class="brand-sub"><?php echo esc_html( nr( 'brand_sub' ) ); ?></span>
			</span>
		</a>
		<a class="btn btn-sage nav-cta" href="<?php echo esc_url( $cta ); ?>"><?php echo esc_html( nr( 'nav_cta' ) ); ?> <svg><use href="#i-arrow"/></svg></a>
	</div>
</header>
