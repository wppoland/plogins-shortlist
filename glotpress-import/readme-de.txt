=== Plogins Shortlist - Wishlist for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, wishlist, product wishlist, save for later, favourites
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WooCommerce-Wunschliste und Liste zum späteren Speichern für Gäste und Kunden: AJAX-Umschaltung, Registerkarte „Mein Konto“, Shortcode und Blockierung.

== Description ==

Shortlist fügt deinem WooCommerce-Shop-Loop und deinen Produktseiten eine Schaltfläche „Zur Wunschliste hinzufügen“ hinzu. Käufer speichern Produkte, Favoriten und Artikel für später und kehren dann über die Registerkarte „Wunschliste“ in „Mein Konto“, eine eigene Seite oder überall dort zurück, wo Sie den Shortcode „[shortlist]“ eingeben.

Gäste können Produkte speichern, bevor sie sich anmelden. Eine Gästeliste befindet sich in einem Cookie; Wenn sich der Besucher das nächste Mal anmeldet, werden die gespeicherten Artikel auf sein Konto übertragen, sodass beim Anmeldeschritt nichts verloren geht. Die Listen angemeldeter Kunden werden in einer benutzerdefinierten Datenbanktabelle gespeichert, die mit ihrer Benutzer-ID verknüpft ist.

Das Plugin wurde für Shops geschrieben, die Wert auf Front-End-Gewicht und Zugänglichkeit legen:

* Das Front-End-Skript ist Vanilla-JavaScript ohne jQuery-Abhängigkeit. Es wird zurückgestellt und in die Fußzeile geladen.
* Die Umschalttaste reserviert ihren Platz, sodass beim Wechsel zwischen den Status „Hinzufügen“ und „Entfernen“ die Seite nicht neu umbrochen wird (kein CLS).
* Der Schalter ist ein echter „<Button>“ mit „Aria-Pressed“. Wenn ein Produkt mehr als einmal auf einer Seite erscheint, werden nach dem Speichern alle zugehörigen Schaltflächen gemeinsam aktualisiert und die Änderung wird den Bildschirmlesern über einen höflichen Live-Bereich angekündigt.
* Das Speichern und Entfernen erfolgt über Admin-Ajax, ohne dass die Seite neu geladen werden muss.

Bei variablen Produkten folgt die Schaltfläche der ausgewählten Variante, sodass ein Kunde genau die Größe oder Farbe speichert, die er gewählt hat, und nicht das übergeordnete Produkt. Bis sie Optionen auswählen, bleibt die Schaltfläche deaktiviert, mit einem Hinweis, den du selbst formulieren können.

Die Quelle befindet sich auf GitHub unter https://github.com/wppoland/plogins-shortlist. Dort findest du Fehlerberichte und Patches.

= Documentation and links =

* <strong>Dokumentation</strong> - https://plogins.com/de/plogins-shortlist/docs/
* <strong>Plugin-Seite</strong> - https://plogins.com/de/plogins-shortlist/
* <strong>Quellcode</strong> – https://github.com/wppoland/plogins-shortlist
* <strong>Fehlerberichte und Funktionsanfragen</strong> – https://github.com/wppoland/plogins-shortlist/issues


= Where the button and list can appear =

* Die einzelne Produktseite unterhalb des „In den Warenkorb“-Bereichs.
* Produktkarten in den Shop-, Kategorie- und Tag-Schleifen.
* Eine Registerkarte „Wunschliste“ in WooCommerce Mein Konto, die optional eine Anzahl gespeicherter Artikel wie „Wunschliste (3)“ anzeigt.
* Eine spezielle Seite, die du im Einstellungsbildschirm auswählen oder erstellen.
* Jeder Beitrag oder jede Seite über den Shortcode „[shortlist]“.
* Der Blockeditor über den <strong>Shortlist-Wunschliste</strong>-Block (vom Server gerendert, sodass die Editorvorschau mit dem Frontend übereinstimmt).

Jede Platzierung ist ein separater Schalter auf dem Einstellungsbildschirm.

= Settings =

Das Shortlist-Menü in wp-admin öffnet sich für Shop-Manager (es nutzt die Funktion „manage_woocommerce“), nicht nur für Administratoren. Von dort aus kannst du:

* Schalte die Wunschliste ein oder aus und entscheide, ob Gäste sie verwenden dürfen.
* Wähle aus, wo die Schaltfläche angezeigt werden soll: einzelnes Produkt, Shop-Schleife, Mein Konto und eine spezielle Seite.
* Zeige die Anzahl der gespeicherten Elemente im Menü „Mein Konto“ an oder verberge sie.
* Lege die Beschriftungen für die Schaltflächen „Hinzufügen“ und „Entfernen“ sowie den Variationshinweis fest.
* Gestalte die Liste selbst: Überschrift, Einleitung und leerer Listentext, wie viele Spalten das Raster verwendet und welche Details (Bild, Name, Preis, „Zum Warenkorb hinzufügen“, Schaltfläche „Entfernen“) jedes gespeicherte Produkt anzeigt.

Jede Einstellung hat ein „?“ daneben öffnet sich eine kurze Erklärung dessen, was es tut.

Shortlist lädt sein Stylesheet und Skript nur auf den Seiten, auf denen die Wunschliste tatsächlich erscheint, sodass der Rest Ihres Shops unberührt bleibt.

== Installation ==

1. Lade das Plugin nach „/wp-content/plugins/shortlist“ hoch oder installiere es über Plugins → Neu hinzufügen.
2. Aktiviere es. WooCommerce muss aktiv sein.
3. Besuche das Menü <strong>Shortlist</strong> in wp-admin, um Platzierung und Beschriftungen zu konfigurieren.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Ja. Shortlist erfordert eine aktive WooCommerce-Installation.

= Can guests use the wishlist? =

Ja, wenn du es in den Einstellungen zulassen. Die Liste eines Gastes befindet sich in einem Cookie und wird bei der nächsten Anmeldung in sein Konto eingefügt.

= Does it use jQuery? =

Nein. Das eigene Front-End-Skript des Plugins ist Vanilla-JavaScript ohne jQuery-Abhängigkeit.

= How do I show the wishlist on a page? =

Verwende den Shortcode „[shortlist]“ oder verlasse sich auf die Registerkarte „Wunschliste“, die dem WooCommerce-Bereich „Mein Konto“ hinzugefügt wurde.

= Does it work with variable products? =

Ja. Bei variablen Produkten folgt die Schaltfläche „Wunschliste“ der ausgewählten Variante, sodass der gespeicherte Artikel die gewählte Größe oder Farbe enthalten kann.

= Can I create a dedicated wishlist page? =

Ja. Wähle eine vorhandene Seite aus oder erstelle eine im Bildschirm „Shortlist-Einstellungen“. Das Plugin kann die Liste „[shortlist]“ automatisch einfügen.

= Is the wishlist accessible? =

Ja. Die Schaltfläche „Wunschliste“ ist eine echte Schaltfläche mit „Aria-Pressed“, Bildschirmleseansagen und ohne Layoutverschiebung, wenn sich der gespeicherte Status ändert.


= Does this plugin work on WordPress Multisite? =

Ja. Dieses Plugin ist mit WordPress Multisite kompatibel. Aktiviere es im Netzwerk oder auf einzelnen Websites. Jede Site behält ihre eigenen Einstellungen und Daten.

== Screenshots ==

1. Die Wunschlistenseite mit den gespeicherten Produkten, jeweils mit den Schaltflächen „In den Warenkorb“ und „Entfernen“.
2. Dieselbe Wunschliste auf einem Telefon.
3. Der Bildschirm mit den Shortlist-Einstellungen.

== External Services ==

Shortlist stellt keine Verbindung zu einem externen Dienst her. Das Speichern und Entfernen von Elementen erfolgt über den Admin-Ajax-Endpunkt deiner eigenen Website, und alle Wunschlistendaten bleiben in deiner WordPress-Datenbank: Die Listen angemeldeter Kunden werden in einer benutzerdefinierten „shortlist_items“-Tabelle gespeichert, die mit ihrer Benutzer-ID verknüpft ist, Gästelisten werden in einem Cookie im eigenen Browser des Besuchers gespeichert, bis er sich anmeldet, und Einstellungen werden in der Option „shortlist_settings“ gespeichert. Das Plugin sendet keine E-Mails und lädt keine Schriftarten, Skripte oder Tracker von Drittanbietern.

== Changelog ==

= 1.0.1 =
* Erste stabile Version.

= 0.3.1 =
* Für einen eindeutigeren Plugin-Namen in Plogins Shortlist für WooCommerce umbenannt.

= 0.3.0 =
* Neu: <strong>Wunschlistenseite</strong>, wähle eine vorhandene Seite aus oder erstelle eine aus den Einstellungen; Füge die Liste „[shortlist]“ automatisch ein, wenn die Seite noch keinen Shortcode hat.
* Neu: <strong>Variationsbewusstes Speichern</strong>, bei variablen Produkten verfolgt die Schaltfläche die ausgewählte Variation; konfigurierbarer Hinweis, wenn keine Variante ausgewählt ist.
* Verbessert: Einstellungsbildschirm gruppiert Wunschlistenseite, Variationshinweis und vorhandene Platzierungskontrollen.

= 0.2.0 =
* Polnisch: aktualisierte, thematisch anpassbare Storefront-Stile (Herzsymbol, Dunkelmodus, CLS-sicheres Raster) und ein moderner, kartenbasierter Einstellungsbildschirm mit einem zugänglichen „?“ Hilfe-Popover zu jeder Option.
* Barrierefreiheit: Änderungen an der Wunschliste werden jetzt Bildschirmleseprogrammen mitgeteilt und die Anzahl „Mein Konto“ wird live aktualisiert, ohne dass die Seite neu geladen werden muss.
* Robustheit: freundlicher Leerzustand mit einem Link „Produkte durchsuchen“, klare Fehlermeldungen und Abwehrfunktionen gegen fehlende Produktdaten.
* Neu: <strong>Shortlist-Wunschliste</strong>-Block für den Blockeditor (vom Server gerendert, entspricht dem Shortcode „[shortlist]“).
* Neu: optionale Anzahl gespeicherter Artikel neben der Menübezeichnung „Wunschliste“ in „Mein Konto“.
* Neu: Volle Kontrolle über die Wunschliste, die Überschrift, den Einleitungs- und Leerlistentext, die Spaltenanzahl und die angezeigten Produktdetails (Bild, Name, Preis, „Zum Warenkorb hinzufügen“, Schaltfläche „Entfernen“).
* Neu: Die Deinstallationsbereinigung entfernt die Wunschlistentabelle und die Plugin-Optionen beim Löschen.
* i18n: Domänenpfad und ein „Sprachen“-Verzeichnis für Übersetzungen hinzugefügt.

= 0.1.0 =
* Erstveröffentlichung: zugängliche AJAX-Wunschliste für WooCommerce mit Gastunterstützung, einer Registerkarte „Mein Konto“, einem Shortcode und einer Einstellungsseite für Platzierung und Labels.
