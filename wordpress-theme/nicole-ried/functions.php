<?php
/**
 * Nicole Ried – Theme-Funktionen.
 *
 * Alle Texte, Bilder und Kontaktdaten der Startseite sind unter
 * Design → Customizer → „Nicole Ried – Inhalte“ bearbeitbar.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NR_VERSION', '1.5.1' );

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
} );

/* ------------------------------------------------------------------ *
 * Styles und Skripte – Schriften liegen lokal im Theme (kein Google)
 * ------------------------------------------------------------------ */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'nr-fonts', get_theme_file_uri( 'assets/fonts/fonts.css' ), array(), NR_VERSION );
	if ( nr_is_orientation() ) {
		wp_enqueue_style( 'nr-orientierung', get_theme_file_uri( 'assets/orientierung/style.css' ), array( 'nr-fonts' ), NR_VERSION );
		wp_enqueue_style( 'nr-kennenlernen-header', get_theme_file_uri( 'assets/css/kennenlernen-header.css' ), array( 'nr-orientierung' ), NR_VERSION );
		wp_enqueue_script( 'nr-orientierung', get_theme_file_uri( 'assets/orientierung/app.mjs' ), array(), NR_VERSION, array( 'in_footer' => true ) );
		return;
	}
	if ( is_page( 'kennenlernen' ) || is_page_template( 'page-kennenlernen.php' ) ) {
		wp_enqueue_style( 'nr-kennenlernen', get_theme_file_uri( 'assets/css/kennenlernen.css' ), array( 'nr-fonts' ), NR_VERSION );
		wp_enqueue_style( 'nr-kennenlernen-header', get_theme_file_uri( 'assets/css/kennenlernen-header.css' ), array( 'nr-kennenlernen' ), NR_VERSION );
		wp_enqueue_script( 'nr-kennenlernen', get_theme_file_uri( 'assets/js/kennenlernen.js' ), array(), NR_VERSION, array( 'in_footer' => true ) );
		return;
	}
	wp_enqueue_style( 'nr-style', get_stylesheet_uri(), array( 'nr-fonts' ), NR_VERSION );
	wp_enqueue_script( 'nr-site', get_theme_file_uri( 'assets/js/site.js' ), array(), NR_VERSION, array( 'in_footer' => true ) );
} );

add_filter( 'script_loader_tag', function ( $tag, $handle, $src ) {
	return 'nr-orientierung' === $handle
		? wp_get_script_tag( array( 'type' => 'module', 'src' => $src, 'id' => $handle . '-js' ) )
		: $tag;
}, 10, 3 );

add_action( 'wp_head', function () {
	if ( is_front_page() ) {
		echo wp_get_inline_script_tag( "document.documentElement.classList.add('nr-intro');" );
	}
}, 1 );

// Unnötigen WordPress-Ballast im Frontend entfernen
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/* Favicons des Logos, solange im Customizer kein eigenes Website-Icon gesetzt ist */
add_action( 'wp_head', function () {
	if ( has_site_icon() ) {
		return;
	}
	printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( get_theme_file_uri( 'assets/logo/favicon.svg' ) ) );
	printf( '<link rel="icon" href="%s" sizes="32x32" type="image/png">' . "\n", esc_url( get_theme_file_uri( 'assets/logo/favicon-32.png' ) ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( get_theme_file_uri( 'assets/logo/apple-touch-icon.png' ) ) );
}, 5 );

/* Seitentitel und Beschreibung der Startseite aus dem Customizer */
add_filter( 'pre_get_document_title', function ( $title ) {
	return is_front_page() ? nr( 'seo_title' ) : $title;
} );
add_action( 'wp_head', function () {
	if ( is_front_page() && nr( 'seo_description' ) ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( nr( 'seo_description' ) ) );
	}
}, 1 );

/* ------------------------------------------------------------------ *
 * Inhalte: Standardwerte = Texte der fertigen Seite
 * ------------------------------------------------------------------ */
function nr_defaults() {
	$img = get_theme_file_uri( 'assets/img/' );
	return array(
		'seo_title'        => 'Nicole Ried – Coaching · Mediation · Resilienz',
		'seo_description'  => 'Keinen Bock auf Stress? Coaching, Mediation und Resilienz mit Nicole Ried: Konflikte klären, innerlich wachsen, Ziele erreichen.',
		'brand_sub'        => 'COACHING · MEDIATION · RESILIENZ',
		'nav_cta'          => 'Lass uns herausfinden, was möglich ist',

		'hero_kicker'      => '',
		'hero_title'       => 'Keinen Bock<br><em>auf</em> Stress?',
		'hero_lead'        => '',
		'hero_button'      => 'Einfach mal drüber reden',
		'hero_claim_1'     => 'Konflikte klären',
		'hero_claim_2'     => 'Innerlich wachsen',
		'hero_claim_3'     => 'Ziele erreichen',

		'offer_1_title'    => 'Coaching',
		'offer_1_text'     => 'Reflexion, neue Perspektiven – und die nächsten Schritte gemeinsam angehen.',
		'offer_1_list'     => "Berufliche Neuorientierung\nEntscheidungen treffen\nEinzel- & Teamcoaching",
		'offer_2_title'    => 'Mediation',
		'offer_2_text'     => 'Lernen, mit schwierigen Situationen umzugehen – und den Überblick zu behalten.',
		'offer_2_list'     => "Konflikte im Team & Job\nFamilie & Partnerschaft\nEltern & Kinder",
		'offer_3_title'    => 'Resilienz',
		'offer_3_text'     => 'Endlich Vorsätze auch umsetzen – und dranbleiben, bis dein Erfolg spürbar wird.',
		'offer_3_list'     => "Ich helfe dir, deine Ziele zu erreichen\nMit Stress & Rückschlägen umgehen\nDranbleiben im Alltag",

		'about_image'      => $img . 'portrait.jpg',
		'about_title'      => 'Ich bin Nicole Ried.',
		'about_lead'       => 'Ich kenne das Gefühl, festzustecken – und ich weiß, wie man wieder rauskommt. Diese Erfahrung teile ich mit dir.',
		'about_text'       => "Heute begleite ich dich mit Ruhe, Erfahrung und viel Herz. Nicht als Ratgeberin von oben, sondern an deiner Seite – ehrlich, auf Augenhöhe und so lange, bis du deinen Weg allein gehst.\n\nIch habe selbst so viele schwierige Situationen gemeistert – das glaubt mir kaum jemand. Lass mich dir helfen, einen neuen Blickwinkel zu finden.",
		'about_button'     => 'Mehr über mich',

		'contact_title'    => 'Erfolg kommt nicht von außen.<br><em>Sondern von innen.</em>',
		'contact_intro'    => 'Vielleicht weißt du schon, was du verändern möchtest. Vielleicht möchtest du es erst herausfinden. Beides darf seinen Raum haben.',
		'contact_email'    => 'hallo@nicoleried.com',
		'contact_phone'    => '+49 000 000 00 00',
		'contact_location' => '[Ort] & online',
		'contact_button'   => 'Lass uns herausfinden, was möglich ist',

		'page_imprint'     => 0,
		'page_privacy'     => 0,
	);
}

/** Wert aus dem Customizer, sonst Standardwert. */
function nr( $key ) {
	$defaults = nr_defaults();
	return get_theme_mod( $key, $defaults[ $key ] ?? '' );
}

/** Erlaubt nur einfache Hervorhebungen: kursiv, fett, Zeilenumbruch. */
function nr_kses( $value ) {
	return wp_kses( $value, array( 'em' => array(), 'strong' => array(), 'br' => array() ) );
}

/** Mehrzeiliger Text → Zeilen ohne Leerzeilen. */
function nr_lines( $key ) {
	return array_filter( array_map( 'trim', explode( "\n", (string) nr( $key ) ) ) );
}

/** Link zu Impressum/Datenschutz: gewählte Seite, sonst Seite mit passendem Slug. */
function nr_page_url( $key, $slug ) {
	$id = absint( nr( $key ) );
	if ( ! $id ) {
		$page = get_page_by_path( $slug );
		$id   = $page ? $page->ID : 0;
	}
	return $id ? get_permalink( $id ) : '';
}

/** Erst auf die Unterseite verlinken, sobald sie veröffentlicht wurde. */
function nr_discovery_url() {
	$page = get_page_by_path( 'kennenlernen' );
	return $page && 'publish' === get_post_status( $page )
		? get_permalink( $page )
		: home_url( '/#ueber-mich' );
}

function nr_is_orientation() {
	return is_page( 'wo-fangen-wir-an' ) || is_page_template( 'page-wo-fangen-wir-an.php' );
}

/** Do not expose an entry link until the WordPress page is published. */
function nr_orientation_url() {
	$page = get_page_by_path( 'wo-fangen-wir-an' );
	return $page && 'publish' === get_post_status( $page ) ? get_permalink( $page ) : '';
}

// Der Kennenlern-Entwurf bleibt bis zu den finalen Aufnahmen aus dem Suchindex.
add_filter( 'wp_robots', function ( $robots ) {
	if ( nr_is_orientation() || is_page( 'kennenlernen' ) || is_page_template( 'page-kennenlernen.php' ) ) {
		$robots['noindex'] = true;
	}
	return $robots;
} );

add_action( 'wp_head', function () {
	if ( nr_is_orientation() ) {
		echo '<meta name="description" content="Was beschäftigt dich gerade? Finde Schritt für Schritt einen möglichen Anfang mit Nicole Ried.">' . "\n";
	}
	if ( is_page( 'kennenlernen' ) || is_page_template( 'page-kennenlernen.php' ) ) {
		echo '<meta name="description" content="Nicole kennenlernen. Eine erste Begegnung und ein neuer Blick auf das, was dich gerade beschäftigt.">' . "\n";
	}
}, 1 );

/* ------------------------------------------------------------------ *
 * Customizer
 * ------------------------------------------------------------------ */
add_action( 'customize_register', function ( WP_Customize_Manager $c ) {
	$defaults = nr_defaults();

	$c->add_panel( 'nr_panel', array(
		'title'       => 'Nicole Ried – Inhalte',
		'description' => 'Hier lassen sich alle Texte, Bilder und Kontaktdaten der Startseite ändern. Mit <em>…</em> wird ein Wort kursiv, mit <br> beginnt eine neue Zeile.',
		'priority'    => 20,
	) );

	$sections = array(
		'nr_general' => 'Allgemein & Kopfzeile',
		'nr_hero'    => 'Startbereich (oben)',
		'nr_offers'  => 'Angebote',
		'nr_about'   => 'Über mich',
		'nr_contact' => 'Kontakt',
		'nr_legal'   => 'Impressum & Datenschutz',
	);
	foreach ( $sections as $id => $title ) {
		$c->add_section( $id, array( 'title' => $title, 'panel' => 'nr_panel' ) );
	}

	// Feldtypen: text, html (mit <em>/<br>), textarea, email, image, page
	$fields = array(
		array( 'seo_title', 'nr_general', 'Titel im Browser-Tab und bei Google', 'text' ),
		array( 'seo_description', 'nr_general', 'Beschreibung für Google', 'textarea' ),
		array( 'brand_sub', 'nr_general', 'Untertitel unter dem Namen', 'text' ),
		array( 'nav_cta', 'nr_general', 'Button in der Kopfzeile', 'text' ),

		array( 'hero_kicker', 'nr_hero', 'Kleine Zeile über der Überschrift (leer = ausgeblendet)', 'text' ),
		array( 'hero_title', 'nr_hero', 'Überschrift', 'html' ),
		array( 'hero_lead', 'nr_hero', 'Text unter der Überschrift (leer = ausgeblendet, <strong>…</strong> = grün hervorgehoben)', 'html' ),
		array( 'hero_button', 'nr_hero', 'Button', 'text' ),
		array( 'hero_claim_1', 'nr_hero', 'Begriff am Berg – unten', 'text' ),
		array( 'hero_claim_2', 'nr_hero', 'Begriff am Berg – Mitte', 'text' ),
		array( 'hero_claim_3', 'nr_hero', 'Begriff am Berg – oben', 'text' ),

		array( 'offer_1_title', 'nr_offers', 'Angebot 1 – Titel', 'text' ),
		array( 'offer_1_text', 'nr_offers', 'Angebot 1 – Text', 'textarea' ),
		array( 'offer_1_list', 'nr_offers', 'Angebot 1 – Stichpunkte (einer pro Zeile)', 'textarea' ),
		array( 'offer_2_title', 'nr_offers', 'Angebot 2 – Titel', 'text' ),
		array( 'offer_2_text', 'nr_offers', 'Angebot 2 – Text', 'textarea' ),
		array( 'offer_2_list', 'nr_offers', 'Angebot 2 – Stichpunkte (einer pro Zeile)', 'textarea' ),
		array( 'offer_3_title', 'nr_offers', 'Angebot 3 – Titel', 'text' ),
		array( 'offer_3_text', 'nr_offers', 'Angebot 3 – Text', 'textarea' ),
		array( 'offer_3_list', 'nr_offers', 'Angebot 3 – Stichpunkte (einer pro Zeile)', 'textarea' ),

		array( 'about_image', 'nr_about', 'Foto', 'image' ),
		array( 'about_title', 'nr_about', 'Überschrift', 'text' ),
		array( 'about_lead', 'nr_about', 'Einleitung (fett)', 'textarea' ),
		array( 'about_text', 'nr_about', 'Text (Absätze mit Leerzeile trennen)', 'textarea' ),
		array( 'about_button', 'nr_about', 'Button', 'text' ),

		array( 'contact_title', 'nr_contact', 'Überschrift', 'html' ),
		array( 'contact_intro', 'nr_contact', 'Einleitung', 'textarea' ),
		array( 'contact_email', 'nr_contact', 'E-Mail-Adresse', 'email' ),
		array( 'contact_phone', 'nr_contact', 'Telefon', 'text' ),
		array( 'contact_location', 'nr_contact', 'Ort', 'text' ),
		array( 'contact_button', 'nr_contact', 'Button', 'text' ),

		array( 'page_imprint', 'nr_legal', 'Seite für das Impressum', 'page' ),
		array( 'page_privacy', 'nr_legal', 'Seite für die Datenschutzerklärung', 'page' ),
	);

	$sanitize = array(
		'text'     => 'sanitize_text_field',
		'html'     => 'nr_kses',
		'textarea' => 'sanitize_textarea_field',
		'email'    => 'sanitize_email',
		'image'    => 'esc_url_raw',
		'page'     => 'absint',
	);

	foreach ( $fields as list( $key, $section, $label, $type ) ) {
		$c->add_setting( $key, array(
			'default'           => $defaults[ $key ],
			'sanitize_callback' => $sanitize[ $type ],
		) );
		if ( 'image' === $type ) {
			$c->add_control( new WP_Customize_Image_Control( $c, $key, array( 'label' => $label, 'section' => $section ) ) );
			continue;
		}
		$c->add_control( $key, array(
			'label'   => $label,
			'section' => $section,
			'type'    => array( 'html' => 'textarea', 'page' => 'dropdown-pages' )[ $type ] ?? $type,
		) );
	}
} );
