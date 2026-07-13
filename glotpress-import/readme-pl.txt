=== Plogins Shortlist - Wishlist for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, wishlist, product wishlist, save for later, favourites
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lista życzeń WooCommerce oraz lista „zapisz na później” dla gości i klientów: przełącznik AJAX, zakładka Moje konto, shortcode i blok.

== Description ==

Shortlist dodaje przycisk „Dodaj do listy życzeń” do pętli sklepu WooCommerce i stron produktów. Kupujący zapisują produkty, ulubione i pozycje odkładane na później, a potem wracają do nich z zakładki „Lista życzeń” w Moim koncie, z własnej strony lub z dowolnego miejsca, w którym umieścisz shortcode `[shortlist]`.

Goście mogą zapisywać produkty przed zalogowaniem. Lista gościa jest przechowywana w pliku cookie; przy następnym logowaniu odwiedzającego zapisane pozycje są przenoszone na jego konto, więc nic nie ginie na etapie logowania. Listy zalogowanych klientów są przechowywane w osobnej tabeli bazy danych powiązanej z identyfikatorem użytkownika.

Wtyczka została napisana z myślą o sklepach, którym zależy na lekkości front-endu i dostępności:

* Skrypt front-endu to czysty JavaScript bez zależności od jQuery. Jest ładowany z opóźnieniem w stopce.
* Przycisk przełączający rezerwuje swoje miejsce, więc przełączanie między stanem dodawania i usuwania nie powoduje przeskoku układu strony (bez CLS).
* Przełącznik to prawdziwy element `<button>` z atrybutem `aria-pressed`. Gdy produkt pojawia się na stronie więcej niż raz, po zapisaniu wszystkie jego przyciski są aktualizowane jednocześnie, a zmiana jest ogłaszana czytnikom ekranu przez uprzejmy region live.
* Zapisywanie i usuwanie odbywa się przez admin-ajax bez ponownego ładowania strony.

W przypadku produktów zmiennych przycisk podąża za wybraną odmianą, więc klient zapisuje dokładnie wybrany rozmiar lub kolor, a nie produkt nadrzędny. Dopóki klient nie wybierze opcji, przycisk pozostaje nieaktywny, z podpowiedzią, którą możesz sformułować samodzielnie.

Kod źródłowy znajduje się na GitHubie pod adresem https://github.com/wppoland/plogins-shortlist — to miejsce na zgłoszenia błędów i poprawki.

= Documentation and links =

* <strong>Dokumentacja</strong> - https://plogins.com/pl/plogins-shortlist/docs/
* <strong>Strona wtyczki</strong> - https://plogins.com/pl/plogins-shortlist/
* <strong>Kod źródłowy</strong> - https://github.com/wppoland/plogins-shortlist
* <strong>Zgłoszenia błędów i propozycje funkcji</strong> - https://github.com/wppoland/plogins-shortlist/issues


= Where the button and list can appear =

* Strona pojedynczego produktu, poniżej obszaru dodawania do koszyka.
* Karty produktów w pętlach sklepu, kategorii i tagów.
* Zakładka „Lista życzeń” w WooCommerce Moje konto, opcjonalnie z liczbą zapisanych pozycji, np. „Lista życzeń (3)”.
* Dedykowana strona, którą wybierasz lub tworzysz na ekranie ustawień.
* Dowolny wpis lub strona za pomocą shortcode’u `[shortlist]`.
* Edytor bloków, za pomocą bloku <strong>Shortlist Wishlist</strong> (renderowanego po stronie serwera, więc podgląd w edytorze odpowiada front-endowi).

Każde umiejscowienie to osobny przełącznik na ekranie ustawień.

= Settings =

Menu Shortlist w wp-admin jest dostępne dla menedżerów sklepu (korzysta z uprawnienia `manage_woocommerce`), nie tylko dla administratorów. Stamtąd możesz:

* Włączyć lub wyłączyć listę życzeń i zdecydować, czy mogą z niej korzystać goście.
* Wybrać, gdzie pojawia się przycisk: strona pojedynczego produktu, pętla sklepu, Moje konto i dedykowana strona.
* Pokazać lub ukryć liczbę zapisanych pozycji w menu Moje konto.
* Ustawić etykiety przycisków dodawania i usuwania oraz podpowiedź dotyczącą odmian.
* Ukształtować samą listę: nagłówek, tekst wprowadzający i tekst pustej listy, liczbę kolumn siatki oraz to, które szczegóły (obrazek, nazwa, cena, dodaj do koszyka, przycisk usuwania) pokazuje każdy zapisany produkt.

Każde ustawienie ma obok znak „?”, który otwiera krótkie wyjaśnienie jego działania.

Shortlist ładuje swój arkusz stylów i skrypt tylko na stronach, na których faktycznie pojawia się lista życzeń, więc reszta sklepu pozostaje nietknięta.

== Installation ==

1. Wgraj wtyczkę do `/wp-content/plugins/shortlist` lub zainstaluj przez Wtyczki → Dodaj nową.
2. Włącz ją. WooCommerce musi być aktywne.
3. Wejdź w menu <strong>Shortlist</strong> w wp-admin, aby skonfigurować rozmieszczenie i etykiety.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Tak. Shortlist wymaga aktywnej instalacji WooCommerce.

= Can guests use the wishlist? =

Tak, jeśli zezwolisz na to w ustawieniach. Lista gościa jest przechowywana w pliku cookie i zostaje scalona z jego kontem przy następnym logowaniu.

= Does it use jQuery? =

Nie. Własny skrypt front-endu wtyczki to czysty JavaScript bez zależności od jQuery.

= How do I show the wishlist on a page? =

Użyj shortcode’u `[shortlist]` lub skorzystaj z zakładki „Lista życzeń” dodanej do obszaru Moje konto WooCommerce.

= Does it work with variable products? =

Tak. W przypadku produktów zmiennych przycisk listy życzeń podąża za wybraną odmianą, więc zapisana pozycja może zawierać wybrany rozmiar lub kolor.

= Can I create a dedicated wishlist page? =

Tak. Wybierz istniejącą stronę lub utwórz nową na ekranie ustawień Shortlist. Wtyczka może automatycznie wstawić listę `[shortlist]`.

= Is the wishlist accessible? =

Tak. Przycisk listy życzeń to prawdziwy przycisk z atrybutem `aria-pressed`, komunikatami dla czytników ekranu i bez przeskoku układu przy zmianie stanu zapisu.


= Does this plugin work on WordPress Multisite? =

Tak. Ta wtyczka jest zgodna z WordPress Multisite. Włącz ją dla całej sieci lub w pojedynczych witrynach; każda witryna zachowuje własne ustawienia i dane.

== Screenshots ==

1. Strona listy życzeń z zapisanymi produktami, każdy z przyciskami dodawania do koszyka i usuwania.
2. Ta sama lista życzeń na telefonie.
3. Ekran ustawień Shortlist.

== External Services ==

Shortlist nie łączy się z żadną usługą zewnętrzną. Zapisywanie i usuwanie pozycji odbywa się przez punkt końcowy admin-ajax Twojej własnej witryny, a wszystkie dane listy życzeń pozostają w bazie danych WordPress: listy zalogowanych klientów znajdują się w osobnej tabeli `shortlist_items` powiązanej z identyfikatorem użytkownika, listy gości znajdują się w pliku cookie w przeglądarce odwiedzającego do czasu zalogowania, a ustawienia są przechowywane w opcji `shortlist_settings`. Wtyczka nie wysyła e-maili ani nie ładuje czcionek, skryptów czy modułów śledzących innych firm.

== Translations ==

Plogins Shortlist zawiera polskie, niemieckie i hiszpańskie tłumaczenia interfejsu wtyczki. Domena tekstowa to `plogins-shortlist`, dzięki czemu paczki językowe z WordPress.org mogą również nadpisywać lub rozszerzać dołączone tłumaczenia.

== Changelog ==

= 1.0.2 =
* Dodano dołączone polskie, niemieckie i hiszpańskie tłumaczenia interfejsu wtyczki.

= 1.0.1 =
* Pierwsza stabilna wersja.

= 0.3.1 =
* Zmieniono nazwę na Plogins Shortlist for WooCommerce, aby uzyskać bardziej charakterystyczną nazwę wtyczki.

= 0.3.0 =
* Nowość: <strong>Strona listy życzeń</strong> — wybierz istniejącą stronę lub utwórz nową w ustawieniach; automatyczne wstawianie listy `[shortlist]`, gdy strona nie ma jeszcze shortcode’u.
* Nowość: <strong>Zapisywanie uwzględniające odmiany</strong> — w przypadku produktów zmiennych przycisk śledzi wybraną odmianę; konfigurowalna podpowiedź, gdy nie wybrano odmiany.
* Ulepszono: ekran ustawień grupuje stronę listy życzeń, podpowiedź dotyczącą odmian i istniejące ustawienia rozmieszczenia.

= 0.2.0 =
* Dopracowanie: odświeżone, dostosowywalne do motywu style sklepu (ikona serca, tryb ciemny, siatka bezpieczna dla CLS) oraz nowoczesny, oparty na kartach ekran ustawień z dostępnym dymkiem pomocy „?” przy każdej opcji.
* Dostępność: zmiany na liście życzeń są teraz ogłaszane czytnikom ekranu, a licznik w Moim koncie aktualizuje się na żywo bez ponownego ładowania strony.
* Niezawodność: przyjazny stan pusty z odnośnikiem „Przeglądaj produkty”, czytelne komunikaty o błędach i zabezpieczenia przed brakującymi danymi produktu.
* Nowość: blok <strong>Shortlist Wishlist</strong> dla edytora bloków (renderowany po stronie serwera, zgodny z shortcode’em `[shortlist]`).
* Nowość: opcjonalna liczba zapisanych pozycji obok etykiety menu „Lista życzeń” w Moim koncie.
* Nowość: pełna kontrola nad listą życzeń — nagłówek, tekst wprowadzający i tekst pustej listy, liczba kolumn oraz to, które szczegóły produktu (obrazek, nazwa, cena, dodaj do koszyka, przycisk usuwania) są wyświetlane.
* Nowość: czyszczenie przy odinstalowaniu usuwa tabelę listy życzeń i opcje wtyczki podczas usuwania.
* i18n: dodano Domain Path i katalog `languages` na tłumaczenia.

= 0.1.0 =
* Pierwsze wydanie: dostępna lista życzeń AJAX dla WooCommerce z obsługą gości, zakładką Moje konto, shortcode’em i stroną ustawień rozmieszczenia i etykiet.
