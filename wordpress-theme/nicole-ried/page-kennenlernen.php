<?php
/**
 * Template Name: Nicole kennenlernen
 * Persönliche Kennenlernseite, basierend auf dem abgestimmten Entwurf.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$email = sanitize_email( nr( 'contact_email' ) );
$contact_href = $email ? 'mailto:' . $email . '?subject=' . rawurlencode( 'Ein erstes Gespräch' ) : home_url( '/#kontakt' );
$imprint = nr_page_url( 'page_imprint', 'impressum' );
$privacy = nr_page_url( 'page_privacy', 'datenschutz' );
get_header();
?>
  <a class="skip-link" href="#inhalt">Zum Inhalt</a>

  <main id="inhalt">
    <section class="voice" id="stimme" aria-labelledby="voice-title">
      <div class="voice-top"><span class="eyebrow">Eine erste Begegnung</span><span class="draft-label">Höre zu</span></div>
      <h1 id="voice-title">Hör erst mal<br>meine <em>Stimme.</em></h1>
      <p class="voice-intro">Bevor du mir deine Geschichte erzählst,<br>hör dir in Ruhe an, was ich dir sagen möchte.</p>
      <div class="voice-track">
        <div class="wave" aria-hidden="true"></div>
        <button class="play-button" type="button" data-dialog="audio-dialog" aria-label="Nachricht von Nicole: Textentwurf öffnen" title="Textentwurf öffnen; Stimmaufnahme folgt"><span aria-hidden="true">▶</span></button>
        <span class="track-caption">Ein paar Worte von mir an dich.<small>Stimmaufnahme folgt · Textentwurf öffnen</small></span>
      </div>
      <div class="voice-bottom"><button class="text-link" type="button" data-dialog="audio-dialog">Lieber lesen <span aria-hidden="true">↗</span></button><a class="scroll-link" href="#nicole">Lern mich kennen <span aria-hidden="true">↓</span></a></div>
    </section>

    <section class="person" id="nicole" aria-labelledby="person-title">
      <div class="section-index"><span>01 / Der Mensch</span><span>Schön, dass du da bist.</span></div>
      <div class="person-grid">
        <div class="portrait">
          <img src="<?php echo esc_url( nr( 'about_image' ) ); ?>" alt="Nicole Ried, lächelnd" width="378" height="217" loading="lazy">
          <button class="video-button" type="button" data-dialog="video-dialog" aria-label="Videoporträt: Gesprächsentwurf öffnen" title="Gesprächsentwurf öffnen; Video folgt"><span class="video-play" aria-hidden="true">▶</span><span>Ein Moment mit Nicole<small>Videoporträt folgt</small></span><span aria-hidden="true">↗</span></button>
        </div>
        <div class="person-text">
          <p class="eyebrow">Hi, ich bin Nicole.</p>
          <h2 id="person-title">Das Leben hält sich<br>selten an <em>unseren Plan.</em></h2>
          <p>Ich glaube nicht an perfekte Lebensläufe, perfekte Beziehungen oder daran, dass wir immer alles im Griff haben müssen.</p>
          <p>Was ich aber glaube: Wir können lernen, besser damit umzugehen. Mit einem neuen Blick auf die Dinge. Einer klaren Entscheidung. Und manchmal mit der Erkenntnis, dass wir nicht alles ändern können.</p>
          <p class="person-closing">Dabei unterstütze ich dich. Mit Empathie, Humor und einem klaren Blick auf das, was möglich ist.</p>
          <span class="signature" aria-hidden="true">Nicole</span>
        </div>
      </div>
    </section>

    <section class="thoughts" id="gedanken" aria-labelledby="thought-title">
      <div class="section-index"><span>02 / Was dich beschäftigt</span><span>Es darf gerade viel sein.</span></div>
      <div class="thought-heading"><h2 id="thought-title">Und bei dir?<br><em>Was ist gerade los?</em></h2><p>Vielleicht findest du hier<br>einen deiner Gedanken wieder.</p></div>
      <div class="thought-map" aria-label="Gedanken zum Entdecken">
        <canvas class="connections" aria-hidden="true"></canvas>
        <span class="map-note note-a" aria-hidden="true">wo anfangen?</span>
        <span class="map-note note-b" aria-hidden="true">alles auf einmal.</span>
        <span class="map-note note-c" aria-hidden="true">und ich?</span>
        <span class="map-center" aria-hidden="true">Im Kopf ist<br>gerade <em>viel.</em></span>
        <button class="thought thought-a" data-topic="grenzen" aria-haspopup="dialog"><span class="thought-category">Grenzen</span>Wie sage ich Nein,<br>ohne mich mies zu fühlen?<span class="thought-plus" aria-hidden="true">+</span></button>
        <button class="thought thought-b" data-topic="konflikte" aria-haspopup="dialog"><span class="thought-category">Miteinander</span>Warum streiten wir<br>immer über dasselbe?<span class="thought-plus" aria-hidden="true">+</span></button>
        <button class="thought thought-c" data-topic="gedankenkreisen" aria-haspopup="dialog"><span class="thought-category">Gedankenkreisen</span>Warum lässt mich<br>das nicht los?<span class="thought-plus" aria-hidden="true">+</span></button>
        <button class="thought thought-d" data-topic="richtung" aria-haspopup="dialog"><span class="thought-category">Eigener Weg</span>Was will ich<br>eigentlich selbst?<span class="thought-plus" aria-hidden="true">+</span></button>
        <button class="thought thought-e" data-topic="anfangen" aria-haspopup="dialog"><span class="thought-category">Losgehen</span>Ich weiß es doch.<br>Warum fange ich nicht an?<span class="thought-plus" aria-hidden="true">+</span></button>
        <button class="thought thought-f" data-topic="veraenderung" aria-haspopup="dialog"><span class="thought-category">Veränderung</span>Und wenn alles<br>anders kommt?<span class="thought-plus" aria-hidden="true">+</span></button>
      </div>
      <div class="thought-outro"><p>Du musst nicht alles auf einmal lösen.</p></div>
    </section>

    <section class="order" id="sortieren" aria-labelledby="order-title">
      <div class="section-index"><span>03 / Ein nächster Schritt</span><span>Aus Gedanken wird Richtung.</span></div>
      <div class="order-heading"><h2 id="order-title">Lass uns das<br><em>sortieren.</em></h2><p>Was dich beschäftigt, bekommt Raum.<br>Und dann schauen wir, was dir weiterhilft.</p></div>
      <div class="work-list">
        <article class="work" id="coaching-weg">
          <div class="work-name"><span class="work-no">01</span><h3>Coaching</h3><span class="work-symbol" aria-hidden="true">↗</span></div>
          <div class="work-description"><h4>Deinen eigenen Weg sehen.</h4><p>Wenn du vor einer Entscheidung stehst, dich neu orientieren möchtest oder ins Handeln kommen willst: Wir schauen, was dir wichtig ist und welcher nächste Schritt zu dir passt.</p><div class="topic-links"><button data-topic="richtung">Was will ich selbst? ↗</button><button data-topic="anfangen">Wie fange ich an? ↗</button></div></div>
        </article>
        <article class="work" id="mediation-weg">
          <div class="work-name"><span class="work-no">02</span><h3>Mediation</h3><span class="work-symbol" aria-hidden="true">↔</span></div>
          <div class="work-description"><h4>Wieder miteinander sprechen.</h4><p>Wenn Gespräche feststecken, geht es zunächst darum, einander wieder zu verstehen. Wir machen unterschiedliche Sichtweisen sichtbar und suchen nach Lösungen, die für beide Seiten tragfähig sind.</p><div class="topic-links"><button data-topic="konflikte">Warum immer derselbe Streit? ↗</button></div></div>
        </article>
        <article class="work" id="resilienz-weg">
          <div class="work-name"><span class="work-no">03</span><h3>Resilienz</h3><span class="work-symbol sun-symbol" aria-hidden="true">☼</span></div>
          <div class="work-description"><h4>Mit dir selbst in Verbindung bleiben.</h4><p>Wenn dich Veränderungen, Stress oder Rückschläge fordern: Wir schauen auf deine Grenzen und auf das, was dir Halt gibt. Damit du im Alltag bewusster mit deinen Kräften umgehen kannst.</p><div class="topic-links"><button data-topic="grenzen">Wie sage ich Nein? ↗</button><button data-topic="veraenderung">Wenn alles anders kommt ↗</button></div></div>
        </article>
      </div>
      <div class="approach"><p class="eyebrow">Wie ich dir begegne</p><p>Ich höre zu.<br>Ich frage nach.<br><em>Wir schauen gemeinsam hin.</em></p><span>Du bringst deine Erfahrung mit.<br>Ich meinen Blick von außen.</span></div>
      <details class="credentials"><summary>Ausbildung &amp; Erfahrung <span aria-hidden="true">+</span></summary><p class="draft-note">Dieser Bereich wird mit Nicoles konkreten Ausbildungen, Abschlüssen und beruflichen Stationen ergänzt. Im Entwurf werden keine Qualifikationen vorweggenommen.</p></details>
    </section>

    <section class="contact" id="kontakt" aria-labelledby="contact-title">
      <span class="eyebrow">Du. Ich. Ein erster Schritt.</span>
      <h2 id="contact-title">Bist du bereit,<br>es <em>anzugehen?</em></h2>
      <p class="no-stress">No Stress!</p>
      <p>Wir fangen mit einem Gespräch an.</p>
      <a class="contact-button" href="<?php echo esc_url( $contact_href ); ?>">Lass uns sprechen <span aria-hidden="true">↗</span></a>
      <?php if ( $email ) : ?><a class="contact-email" href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a><?php endif; ?>
    </section>
  </main>
  <footer><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Nicole Ried</a><span>Konzeptentwurf · Texte zur Abstimmung</span><?php if ( $imprint ) : ?><a href="<?php echo esc_url( $imprint ); ?>">Impressum</a><?php endif; ?><?php if ( $privacy ) : ?><a href="<?php echo esc_url( $privacy ); ?>">Datenschutz</a><?php endif; ?><a href="#stimme">Zurück nach oben ↑</a></footer>

  <dialog id="audio-dialog" aria-labelledby="audio-title">
    <button class="dialog-close" aria-label="Schließen" title="Schließen">×</button>
    <p class="eyebrow">Nicoles Nachricht · Textentwurf</p><h2 id="audio-title">Ein paar Worte<br><em>an dich.</em></h2>
    <p>Vielleicht bist du hier, weil dir gerade etwas im Kopf herumgeht. Ein Gespräch. Eine Entscheidung. Oder dieses Gefühl, dass es so irgendwie nicht weitergehen kann.</p>
    <p>Du musst noch nicht wissen, wo du anfangen möchtest. Wir können gemeinsam hinschauen: Was beschäftigt dich? Was ist dir wichtig? Und was wäre ein erster Schritt, der sich für dich richtig anfühlt?</p>
    <p>Ich bin Nicole. Schau dich in Ruhe um. Vielleicht begegnet dir hier schon ein Gedanke, an dem wir anknüpfen können.</p>
    <p class="draft-note">Die persönliche Stimmaufnahme wird noch aufgenommen. Dieser Text ist ein Vorschlag zur Abstimmung mit Nicole.</p>
  </dialog>
  <dialog id="video-dialog" aria-labelledby="video-title">
    <button class="dialog-close" aria-label="Schließen" title="Schließen">×</button>
    <p class="eyebrow">Videoporträt · Gesprächsimpuls</p><h2 id="video-title">Einfach erst mal<br><em>ankommen.</em></h2>
    <p class="interview-question">„Was machst du, wenn jemand bei dir sitzt und selbst noch nicht weiß, wo er anfangen soll?“</p>
    <p class="draft-note">Hier folgt Nicoles eigene Antwort als Video. Das vorhandene Porträt dient im Entwurf als Standbild.</p>
  </dialog>
  <dialog id="thought-dialog" aria-labelledby="answer-title">
    <button class="dialog-close" aria-label="Schließen" title="Schließen">×</button>
    <p class="eyebrow" id="answer-category"></p><h2 id="answer-title"></h2>
    <p class="answer-lead" id="answer-lead"></p><p id="answer-body"></p>
    <a class="answer-path" id="answer-path" href="#sortieren">Daran können wir arbeiten <span aria-hidden="true">↗</span></a>
    <p class="draft-note">Antwortentwurf zur Abstimmung mit Nicole.</p>
  </dialog>
  <?php wp_footer(); ?>
</body>
</html>
