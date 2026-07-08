=== Plogins Shortlist - Wishlist for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, wishlist, product wishlist, save for later, favourites
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lista życzeń WooCommerce i lista zapisanych na później dla gości i klientów: przełącznik AJAX, zakładka Moje konto, krótki kod i blok.

== Description ==

Shortlist dodaje przycisk „Dodaj do listy życzeń” do pętli sklepu WooCommerce i stron produktów. Kupujący zapisują produkty, ulubione i elementy, które odkładają na później, a następnie wracają do zakładki „Lista życzeń” na Moim koncie, osobnej strony lub w dowolnym miejscu, w którym upuścisz krótki kod `[shortlist]`.

Goście mogą zapisywać produkty przed zalogowaniem. Lista gości znajduje się w pliku cookie; Następnym razem, gdy odwiedzający się zaloguje, zapisane przez niego elementy zostaną przeniesione na jego konto, więc nic nie zostanie utracone na etapie logowania. Listy zalogowanych klientów są przechowywane w niestandardowej tabeli bazy danych powiązanej z ich identyfikatorem użytkownika.

Wtyczka przeznaczona jest dla sklepów, którym zależy na wadze front-endu i dostępności:

* Skrypt front-end to waniliowy JavaScript bez zależności od jQuery. Jest ono odraczane i ładowane w stopce.
* Przycisk przełączający rezerwuje swoje miejsce, więc przełączanie między stanami dodawania i usuwania nie powoduje ponownego przepływu strony (bez CLS).
* Przełącznik to prawdziwy „<przycisk>” z „wciśniętym arią”. Gdy produkt pojawia się na stronie więcej niż raz, każdy jego przycisk jest aktualizowany razem po zapisaniu, a zmiana zostaje ogłoszona czytelnikom ekranowym za pośrednictwem grzecznego regionu na żywo.
* Zapisywanie i usuwanie odbywa się za pośrednictwem admin-ajax, bez konieczności ponownego ładowania strony.

W przypadku produktów zmiennych przycisk podąża za wybraną odmianą, więc klient zapisuje dokładnie wybrany rozmiar lub kolor, a nie produkt nadrzędny. Dopóki nie wybiorą opcji, przycisk pozostaje wyłączony, z wskazówką, którą możesz sam sformułować.

Źródło znajduje się na GitHubie pod adresem https://github.com/wppoland/plogins-shortlist, tam znajdują się raporty o błędach i poprawki.

= Documentation and links =

* <strong>Dokumentacja</strong> - https://plogins.com/pl/plogins-shortlist/docs/
* <strong>Strona wtyczki</strong> - https://plogins.com/pl/plogins-shortlist/
* <strong>Kod źródłowy</strong> - https://github.com/wppoland/plogins-shortlist
* <strong>Raporty o błędach i prośby o nowe funkcje</strong> - https://github.com/wppoland/plogins-shortlist/issues


= Where the button and list can appear =

* Strona pojedynczego produktu, poniżej obszaru dodawania do koszyka.
* Karty produktów w sklepie, pętle kategorii i tagów.
* Zakładka „Lista życzeń” na Moim koncie WooCommerce, opcjonalnie pokazująca liczbę zapisanych przedmiotów, np. „Lista życzeń (3)”.
* Dedykowana strona, którą wybierasz lub tworzysz na ekranie ustawień.
* Dowolny post lub strona za pomocą krótkiego kodu `[shortlist]`.
* Edytor bloku, poprzez blok <strong>Shortlist Listy życzeń</strong> (renderowany na serwerze, więc podgląd edytora pasuje do interfejsu użytkownika).

Każde miejsce docelowe to osobny przełącznik na ekranie ustawień.

= Settings =

Menu Lista skrótów w wp-admin otwiera się dla menadżerów sklepów (wykorzystuje funkcję `manage_woocommerce`), a nie tylko dla administratorów. Stamtąd możesz:

* Włącz lub wyłącz listę życzeń i zdecyduj, czy goście mogą z niej korzystać.
* Wybierz, gdzie ma się wyświetlać przycisk: pojedynczy produkt, pętla sklepu, Moje konto i dedykowana strona.
* Pokaż lub ukryj liczbę zapisanych pozycji w menu Moje konto.
* Ustaw etykiety przycisków dodawania i usuwania oraz wskazówkę dotyczącą odmian.
* Kształtuj samą listę: nagłówek, tekst wprowadzenia i pustej listy, ile kolumn wykorzystuje siatka i jakie szczegóły (obrazek, nazwa, cena, przycisk „dodaj do koszyka”, przycisk „usuń”) wyświetla każdy zapisany produkt.

Każde ustawienie ma znak „?” obok niego otwiera się krótkie wyjaśnienie jego działania.

Shortlist ładuje swój arkusz stylów i skrypt tylko na stronach, na których faktycznie pojawia się lista życzeń, więc reszta Twojego sklepu pozostaje nietknięta.

== Installation ==

1. Prześlij wtyczkę do `/wp-content/plugins/shortlist` lub zainstaluj poprzez Wtyczki → Dodaj nową.
2. Aktywuj. WooCommerce musi być aktywny.
3. Odwiedź menu <strong>Krótka lista</strong> w wp-admin, aby skonfigurować rozmieszczenie i etykiety.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Tak. Krótka lista wymaga aktywnej instalacji WooCommerce.

= Can guests use the wishlist? =

Tak, jeśli zezwolisz na to w ustawieniach. Lista gości znajduje się w pliku cookie i jest łączona z ich kontem przy następnym logowaniu.

= Does it use jQuery? =

Nie. Własnym skryptem front-end wtyczki jest waniliowy JavaScript, bez zależności od jQuery.

= How do I show the wishlist on a page? =

Użyj krótkiego kodu `[shortlist]` lub skorzystaj z zakładki „Lista życzeń” dodanej do obszaru Moje konto WooCommerce.

= Does it work with variable products? =

Tak. W przypadku produktów zmiennych przycisk listy życzeń podąża za wybraną odmianą, więc zapisany artykuł może zawierać wybrany rozmiar lub kolor.

= Can I create a dedicated wishlist page? =

Tak. Wybierz istniejącą stronę lub utwórz ją na ekranie ustawień krótkiej listy. Wtyczka może automatycznie wstawić listę `[shortlist]`.

= Is the wishlist accessible? =

Tak. Przycisk listy życzeń to prawdziwy przycisk z naciśniętymi ariami, ogłoszeniami w czytniku ekranu i brakiem zmiany układu w przypadku zmiany stanu zapisanego.


= Does this plugin work on WordPress Multisite? =

Tak. Ta wtyczka jest kompatybilna z WordPress Multisite. Aktywuj go w sieci lub aktywuj na poszczególnych stronach; każda witryna przechowuje własne ustawienia i dane.

== Screenshots ==

1. Strona listy życzeń pokazująca zapisane produkty, każdy z przyciskami „dodaj do koszyka” i „usuń”.
2. Ta sama lista życzeń na telefonie.
3. Ekran ustawień krótkiej listy.

== External Services ==

Shortlist nie łączy się z żadną usługą zewnętrzną. Zapisywanie i usuwanie elementów odbywa się za pośrednictwem punktu końcowego admin-ajax Twojej witryny, a wszystkie dane listy życzeń pozostają w bazie danych WordPress: listy zalogowanych klientów znajdują się w niestandardowej tabeli `shortlist_items`, powiązanej z ich identyfikatorem użytkownika, listy gości znajdują się w pliku cookie w przeglądarce odwiedzającego do czasu zalogowania się, a ustawienia są przechowywane w opcji `shortlist_settings`. Wtyczka nie wysyła wiadomości e-mail ani nie ładuje czcionek, skryptów ani modułów śledzących innych firm.

== Changelog ==

= 1.0.1 =
* Pierwsza stabilna wersja.

= 0.3.1 =
* Zmieniono nazwę na Krótka lista Plogins dla WooCommerce, aby uzyskać bardziej charakterystyczną nazwę wtyczki.

= 0.3.0 =
* Nowość: <strong>Strona listy życzeń</strong>, wybierz istniejącą stronę lub utwórz ją w ustawieniach; automatycznie wstrzyknij listę `[shortlist]`, gdy strona nie ma jeszcze krótkiego kodu.
* Nowość: <strong>Zapisywanie uwzględniające różnice</strong>, w przypadku produktów zmiennych przycisk śledzi wybraną odmianę; konfigurowalna wskazówka, jeśli nie wybrano żadnej odmiany.
* Ulepszono: ekran ustawień grupuje stronę listy życzeń, wskazówkę dotyczącą odmian i istniejące elementy sterujące rozmieszczeniem.

= 0.2.0 =
* Polski: odświeżony, tematyczny styl witryny sklepowej (ikona serca, tryb ciemny, siatka bezpieczna dla CLS) oraz nowoczesny, oparty na kartach ekran ustawień z dostępnym „?” pomóż popover przy każdej opcji.
* Dostępność: zmiany na liście życzeń są teraz ogłaszane czytnikom ekranu, a licznik Mojego konta jest aktualizowany na bieżąco bez konieczności ponownego ładowania strony.
* Solidność: przyjazny stan pusty z łączem „Przeglądaj produkty”, jasne komunikaty o błędach i zabezpieczenia przed brakującymi danymi produktów.
* Nowość: blok <strong>Shortlist Listy życzeń</strong> dla edytora bloków (renderowany na serwerze, pasuje do krótkiego kodu `[shortlist]`).
* Nowość: opcjonalna liczba zapisanych pozycji obok etykiety menu Moje konto „Lista życzeń”.
* Nowość: pełna kontrola nad listą życzeń, nagłówkiem, tekstem wprowadzenia i pustej listy, liczbą kolumn i wyświetlanymi szczegółami produktu (obrazek, nazwa, cena, przycisk „dodaj do koszyka”, przycisk „usuń”).
* Nowość: czyszczenie po odinstalowaniu usuwa tabelę życzeń i opcje wtyczek po usunięciu.
* i18n: dodano ścieżkę domeny i katalog „języki” dla tłumaczeń.

= 0.1.0 =
* Pierwsza wersja: dostępna lista życzeń AJAX dla WooCommerce z obsługą gości, zakładką Moje konto, krótkim kodem i stroną ustawień rozmieszczenia i etykiet.
