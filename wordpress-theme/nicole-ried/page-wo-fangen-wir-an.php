<?php
/**
 * Template Name: Wo fangen wir an?
 * Browser-local question flow; no answers are submitted to WordPress.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$email = sanitize_email( nr( 'contact_email' ) );
$contact_href = $email ? 'mailto:' . $email . '?subject=' . rawurlencode( 'Orientierungsgespräch' ) : home_url( '/#kontakt' );
$discovery = nr_discovery_url();
$imprint = nr_page_url( 'page_imprint', 'impressum' );
$privacy = nr_page_url( 'page_privacy', 'datenschutz' );
get_header();
?>
  <a class="skip-link" href="#stage">Zum Fragenweg</a>
  <main class="orientation-page">
    <div class="page-top"><a href="<?php echo esc_url( $discovery ); ?>">Nicole kennenlernen</a><span class="prototype">Ein Anfang, der zu dir passt.</span></div>
    <div class="journey-layout">
      <section class="stage" id="stage" aria-labelledby="stage-title">
        <div class="step-toolbar">
          <button id="back" class="text-button" type="button" hidden title="Eine Frage zurück"><span aria-hidden="true">←</span> Zurück</button>
          <span class="eyebrow" id="step-count">Dein Anliegen</span>
          <button id="restart" class="restart" type="button" title="Neu beginnen" aria-label="Neu beginnen" disabled><span aria-hidden="true">↻</span></button>
        </div>
        <p class="section-label" id="stage-label">Ein Satz. Ein erster Schritt.</p>
        <h1 id="stage-title" tabindex="-1">Wo fangen<br>wir <em>an?</em></h1>
        <p class="stage-description" id="stage-description">Was beschäftigt dich gerade?</p>
        <form id="intro-form" autocomplete="off">
          <label for="concern" class="sr-only">Was beschäftigt dich gerade? Deine Eingabe ist freiwillig.</label>
          <div class="concern-field"><textarea id="concern" rows="4" maxlength="700" autocomplete="off" placeholder="Was möchtest du gerade für dich verändern?" aria-describedby="input-hint privacy-note"></textarea><span id="character-count" aria-hidden="true">0 / 700</span></div>
          <p class="input-hint" id="input-hint">Ein Satz reicht. Du kannst das Feld auch leer lassen.</p>
          <button class="primary-button" id="start" type="submit" disabled>Schritt für Schritt <span aria-hidden="true">→</span></button>
          <p class="privacy-note" id="privacy-note"><span aria-hidden="true">○</span> Deine Eingabe und Antworten bleiben in diesem Tab.<br>Sie werden weder gespeichert noch versendet.</p>
        </form>
        <div id="question-panel" hidden><div id="options" class="answers" role="group" aria-labelledby="stage-title"></div><p class="question-note">Auch „Nicht sicher“ ist eine Antwort. Du musst dich nicht festlegen.</p></div>
        <div id="result-panel" hidden>
          <p class="result-kind" id="result-kind"></p>
          <div class="reasons"><h2>Warum dieser Vorschlag?</h2><ul id="reason-list"></ul></div>
          <div id="first-step" class="first-step"><span class="eyebrow">Ein Gedanke für dich</span><p id="first-step-question"></p></div>
          <a class="primary-button" id="result-action" href="<?php echo esc_url( $contact_href ); ?>">Mit Nicole darüber sprechen <span aria-hidden="true">↗</span></a>
          <p class="result-note" id="result-note"></p>
          <button class="text-button result-back" id="review" type="button">Letzte Antwort noch einmal ansehen <span aria-hidden="true">↗</span></button>
        </div>
        <p class="load-status" id="load-status" role="status">Fragenweg wird geladen …</p>
        <button class="text-button" id="retry" type="button" hidden>Erneut laden ↻</button>
        <noscript><p>Für den Fragenweg muss JavaScript aktiviert sein. <a href="<?php echo esc_url( $discovery ); ?>">Hier kannst du Nicole kennenlernen.</a></p></noscript>
      </section>
      <aside class="rail" aria-labelledby="path-title">
        <div class="rail-heading"><h2 id="path-title">Dein Weg</h2><span id="path-count">01</span></div>
        <div id="concern-preview" class="concern-preview" hidden><span class="eyebrow">Was dich herführt</span><p id="concern-text"></p><button class="text-button" id="edit-concern" type="button" title="Anliegen bearbeiten und den Fragenweg neu beginnen">Anliegen bearbeiten ↗</button></div>
        <ol class="path" id="path"><li class="path-current"><span>Dein Anliegen</span><small>Hier fangen wir an.</small></li><li class="path-pending"><span>Ein paar Fragen</span></li><li class="path-pending"><span>Ein möglicher Anfang</span></li></ol>
        <div class="nicole-note"><img src="<?php echo esc_url( nr( 'about_image' ) ); ?>" width="52" height="52" alt="Nicole Ried"><div><span>Ein Schritt nach dem anderen.</span><a href="<?php echo esc_url( $discovery ); ?>">Nicole kennenlernen ↗</a></div></div>
      </aside>
    </div>
    <footer class="page-footer"><span>Erste Orientierung · Ansätze zur Abstimmung mit Nicole</span><button class="text-button" id="about" type="button">Zur Einordnung ↗</button><?php if ( $imprint ) : ?><a href="<?php echo esc_url( $imprint ); ?>">Impressum</a><?php endif; ?><?php if ( $privacy ) : ?><a href="<?php echo esc_url( $privacy ); ?>">Datenschutz</a><?php endif; ?></footer>
  </main>
  <div id="announcer" class="sr-only" aria-live="polite" aria-atomic="true"></div>
  <dialog id="restart-dialog" aria-labelledby="restart-title"><button class="dialog-close" data-close aria-label="Schließen" title="Schließen">×</button><p class="eyebrow">Noch einmal von vorn</p><h2 id="restart-title">Neu anfangen?</h2><p>Deine Eingabe und bisherigen Antworten in diesem Durchgang werden verworfen.</p><div class="dialog-actions"><button class="secondary-button" data-close type="button">Beim bisherigen Weg bleiben</button><button class="primary-button" id="confirm-restart" type="button">Neu beginnen</button></div></dialog>
  <dialog id="about-dialog" aria-labelledby="about-title"><button class="dialog-close" data-close aria-label="Schließen" title="Schließen">×</button><p class="eyebrow">Zur Einordnung</p><h2 id="about-title">Ein möglicher Anfang.</h2><p>Der Vorschlag entsteht aus deinen ausgewählten Antworten. Dein freier Text wird nicht automatisch analysiert. Du ordnest dein Thema im Fragenweg selbst ein.</p><p>Die fünf Arbeitsansätze sind vorläufige Vorschläge. Welche Angebote Nicole tatsächlich macht und was für dein Anliegen passt, wird mit ihr persönlich abgestimmt.</p><p>Das Ergebnis ist eine erste Orientierung. Es ersetzt keine persönliche fachliche Einschätzung.</p><p>Deine Angaben bleiben im Arbeitsspeicher dieses Tabs. Beim Neuladen oder Schließen endet der Durchgang. Wenn du den Kontaktlink nutzt, öffnet sich eine E-Mail mit Betreff. Dein Anliegen und deine Antworten werden nicht übernommen.</p></dialog>
  <?php wp_footer(); ?>
</body>
</html>
