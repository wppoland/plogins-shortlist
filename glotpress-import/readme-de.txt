=== Plogins Shortlist - Wishlist for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, wishlist, product wishlist, save for later, favourites
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WooCommerce-Wunschliste und Für-später-speichern-Liste für Gäste und Kunden: AJAX-Umschalter, „Mein Konto“-Tab, Shortcode und Block.

== Description ==

Shortlist fügt der Shop-Schleife und den Produktseiten deines WooCommerce-Shops einen Button „Zur Wunschliste hinzufügen“ hinzu. Käufer speichern Produkte, Favoriten und Artikel für später und kommen dann über einen „Wunschliste“-Tab in „Mein Konto“, eine eigene Seite oder überall dort, wo du den Shortcode `[shortlist]` platzierst, zu ihnen zurück.

Gäste können Produkte speichern, bevor sie sich anmelden. Die Liste eines Gastes wird in einem Cookie gehalten; wenn sich dieser Besucher das nächste Mal anmeldet, wandern die gespeicherten Artikel in sein Konto, sodass beim Anmelden nichts verloren geht. Die Listen angemeldeter Kunden werden in einer eigenen Datenbanktabelle gespeichert, die mit ihrer Benutzer-ID verknüpft ist.

Das Plugin wurde für Shops geschrieben, denen das Frontend-Gewicht und die Barrierefreiheit wichtig sind:

* Das Frontend-Skript ist reines JavaScript ohne jQuery-Abhängigkeit. Es wird verzögert und im Footer geladen.
* Der Umschalt-Button reserviert seinen Platz, sodass der Wechsel zwischen dem Hinzufügen- und Entfernen-Zustand die Seite nicht neu umbricht (kein CLS).
* Der Umschalter ist ein echter `<button>` mit `aria-pressed`. Erscheint ein Produkt mehrfach auf einer Seite, werden nach dem Speichern alle zugehörigen Buttons gemeinsam aktualisiert, und die Änderung wird Screenreadern über eine höfliche Live-Region angekündigt.
* Das Speichern und Entfernen läuft über admin-ajax ohne Neuladen der Seite.

Bei variablen Produkten folgt der Button der ausgewählten Variante, sodass ein Kunde genau die gewählte Größe oder Farbe speichert und nicht das übergeordnete Produkt. Bis er Optionen auswählt, bleibt der Button deaktiviert – mit einem Hinweis, den du selbst formulieren kannst.

Der Quellcode liegt auf GitHub unter https://github.com/wppoland/plogins-shortlist – dort kannst du Fehler melden und Patches einreichen.

= Documentation and links =

* <strong>Dokumentation</strong> - https://plogins.com/de/plogins-shortlist/docs/
* <strong>Plugin-Seite</strong> - https://plogins.com/de/plogins-shortlist/
* <strong>Quellcode</strong> - https://github.com/wppoland/plogins-shortlist
* <strong>Fehlerberichte und Funktionswünsche</strong> - https://github.com/wppoland/plogins-shortlist/issues


= Where the button and list can appear =

* Die einzelne Produktseite, unterhalb des „In den Warenkorb“-Bereichs.
* Produktkarten in den Shop-, Kategorie- und Schlagwort-Schleifen.
* Ein „Wunschliste“-Tab in WooCommerce „Mein Konto“, optional mit einer Anzahl gespeicherter Artikel wie „Wunschliste (3)“.
* Eine eigene Seite, die du im Einstellungsbildschirm auswählst oder erstellst.
* Jeder Beitrag oder jede Seite über den Shortcode `[shortlist]`.
* Der Blockeditor über den Block <strong>Shortlist Wishlist</strong> (serverseitig gerendert, sodass die Editor-Vorschau dem Frontend entspricht).

Jede Platzierung ist ein eigener Schalter im Einstellungsbildschirm.

= Settings =

Das Shortlist-Menü in wp-admin steht Shop-Managern offen (es nutzt die Berechtigung `manage_woocommerce`), nicht nur Administratoren. Von dort aus kannst du:

* Die Wunschliste ein- oder ausschalten und entscheiden, ob Gäste sie nutzen dürfen.
* Wählen, wo der Button erscheint: einzelnes Produkt, Shop-Schleife, „Mein Konto“ und eine eigene Seite.
* Die Anzahl gespeicherter Artikel im „Mein Konto“-Menü anzeigen oder ausblenden.
* Die Beschriftungen für den Hinzufügen- und Entfernen-Button sowie den Variantenhinweis festlegen.
* Die Liste selbst gestalten: Überschrift, Einleitung und Text für die leere Liste, wie viele Spalten das Raster nutzt und welche Details (Bild, Name, Preis, „In den Warenkorb“, Entfernen-Button) jedes gespeicherte Produkt zeigt.

Neben jeder Einstellung gibt es ein „?“, das eine kurze Erklärung öffnet, was sie bewirkt.

Shortlist lädt sein Stylesheet und Skript nur auf den Seiten, auf denen die Wunschliste tatsächlich erscheint, sodass der Rest deines Shops unberührt bleibt.

== Installation ==

1. Lade das Plugin nach `/wp-content/plugins/shortlist` hoch oder installiere es über Plugins → Installieren.
2. Aktiviere es. WooCommerce muss aktiv sein.
3. Öffne das Menü <strong>Shortlist</strong> in wp-admin, um Platzierung und Beschriftungen zu konfigurieren.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Ja. Shortlist erfordert eine aktive WooCommerce-Installation.

= Can guests use the wishlist? =

Ja, wenn du es in den Einstellungen erlaubst. Die Liste eines Gastes wird in einem Cookie gehalten und bei der nächsten Anmeldung in sein Konto zusammengeführt.

= Does it use jQuery? =

Nein. Das eigene Frontend-Skript des Plugins ist reines JavaScript ohne jQuery-Abhängigkeit.

= How do I show the wishlist on a page? =

Verwende den Shortcode `[shortlist]` oder nutze den „Wunschliste“-Tab, der dem WooCommerce-Bereich „Mein Konto“ hinzugefügt wird.

= Does it work with variable products? =

Ja. Bei variablen Produkten folgt der Wunschliste-Button der ausgewählten Variante, sodass der gespeicherte Artikel die gewählte Größe oder Farbe enthalten kann.

= Can I create a dedicated wishlist page? =

Ja. Wähle eine vorhandene Seite aus oder erstelle im Shortlist-Einstellungsbildschirm eine neue. Das Plugin kann die `[shortlist]`-Liste automatisch einfügen.

= Is the wishlist accessible? =

Ja. Der Wunschliste-Button ist ein echter Button mit `aria-pressed`, Screenreader-Ansagen und ohne Layout-Verschiebung, wenn sich der gespeicherte Zustand ändert.


= Does this plugin work on WordPress Multisite? =

Ja. Dieses Plugin ist mit WordPress Multisite kompatibel. Aktiviere es netzwerkweit oder auf einzelnen Websites; jede Website behält ihre eigenen Einstellungen und Daten.

== Screenshots ==

1. Die Wunschlisten-Seite mit den gespeicherten Produkten, jeweils mit „In den Warenkorb“- und Entfernen-Button.
2. Dieselbe Wunschliste auf einem Smartphone.
3. Der Shortlist-Einstellungsbildschirm.

== External Services ==

Shortlist verbindet sich mit keinem externen Dienst. Das Speichern und Entfernen von Artikeln läuft über den admin-ajax-Endpunkt deiner eigenen Website, und alle Wunschlisten-Daten bleiben in deiner WordPress-Datenbank: Die Listen angemeldeter Kunden liegen in einer eigenen Tabelle `shortlist_items`, die mit ihrer Benutzer-ID verknüpft ist, Gästelisten liegen in einem Cookie im Browser des Besuchers, bis er sich anmeldet, und die Einstellungen werden in der Option `shortlist_settings` gespeichert. Das Plugin sendet keine E-Mails und lädt keine Schriftarten, Skripte oder Tracker von Drittanbietern.

== Translations ==

Plogins Shortlist enthält polnische, deutsche und spanische Übersetzungen für die Plugin-Oberfläche. Die Textdomain ist `plogins-shortlist`, sodass Sprachpakete von WordPress.org diese mitgelieferten Übersetzungen ebenfalls überschreiben oder erweitern können.

== Changelog ==

= 1.0.2 =
* Mitgelieferte polnische, deutsche und spanische Übersetzungen für die Plugin-Oberfläche hinzugefügt.

= 1.0.1 =
* Erste stabile Version.

= 0.3.1 =
* Umbenannt in Plogins Shortlist for WooCommerce für einen unverwechselbareren Plugin-Namen.

= 0.3.0 =
* Neu: <strong>Wunschlisten-Seite</strong> – wähle eine vorhandene Seite aus oder erstelle eine in den Einstellungen; die `[shortlist]`-Liste wird automatisch eingefügt, wenn die Seite noch keinen Shortcode hat.
* Neu: <strong>Variantenbewusstes Speichern</strong> – bei variablen Produkten verfolgt der Button die ausgewählte Variante; konfigurierbarer Hinweis, wenn keine Variante gewählt ist.
* Verbessert: Der Einstellungsbildschirm gruppiert Wunschlisten-Seite, Variantenhinweis und die vorhandenen Platzierungsoptionen.

= 0.2.0 =
* Feinschliff: aufgefrischte, thembare Shop-Stile (Herz-Icon, Dunkelmodus, CLS-sicheres Raster) und ein moderner, kartenbasierter Einstellungsbildschirm mit einem barrierefreien Hilfe-Popover „?“ zu jeder Option.
* Barrierefreiheit: Änderungen an der Wunschliste werden jetzt Screenreadern angekündigt, und die Anzahl in „Mein Konto“ aktualisiert sich live ohne Neuladen der Seite.
* Robustheit: freundlicher Leerzustand mit einem Link „Produkte durchstöbern“, klare Fehlermeldungen und Schutzmechanismen gegen fehlende Produktdaten.
* Neu: Block <strong>Shortlist Wishlist</strong> für den Blockeditor (serverseitig gerendert, entspricht dem Shortcode `[shortlist]`).
* Neu: optionale Anzahl gespeicherter Artikel neben der Menübeschriftung „Wunschliste“ in „Mein Konto“.
* Neu: volle Kontrolle über die Wunschliste – Überschrift, Einleitung und Text für die leere Liste, Spaltenanzahl und welche Produktdetails (Bild, Name, Preis, „In den Warenkorb“, Entfernen-Button) erscheinen.
* Neu: Die Deinstallations-Bereinigung entfernt beim Löschen die Wunschlisten-Tabelle und die Plugin-Optionen.
* i18n: Domain Path und ein `languages`-Verzeichnis für Übersetzungen hinzugefügt.

= 0.1.0 =
* Erstveröffentlichung: barrierefreie AJAX-Wunschliste für WooCommerce mit Gast-Unterstützung, einem „Mein Konto“-Tab, einem Shortcode und einer Einstellungsseite für Platzierung und Beschriftungen.
