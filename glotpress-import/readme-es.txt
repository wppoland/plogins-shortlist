=== Plogins Shortlist - Wishlist for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, wishlist, product wishlist, save for later, favourites
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lista de deseos de WooCommerce y lista de «guardar para más tarde» para invitados y clientes: conmutador AJAX, pestaña Mi cuenta, shortcode y bloque.

== Description ==

Shortlist añade un botón «Añadir a la lista de deseos» al bucle de tienda de WooCommerce y a las páginas de producto. Los compradores guardan productos, favoritos y artículos para más tarde, y luego vuelven a ellos desde una pestaña «Lista de deseos» en Mi cuenta, una página propia o cualquier lugar donde coloques el shortcode `[shortlist]`.

Los invitados pueden guardar productos antes de iniciar sesión. La lista de un invitado se guarda en una cookie; la próxima vez que ese visitante inicie sesión, sus artículos guardados pasan a su cuenta, así que no se pierde nada en el paso de inicio de sesión. Las listas de los clientes que han iniciado sesión se guardan en una tabla de base de datos propia vinculada a su ID de usuario.

El plugin está escrito para tiendas a las que les importa el peso del frontend y la accesibilidad:

* El script del frontend es JavaScript puro sin dependencia de jQuery. Se difiere y se carga en el pie de página.
* El botón conmutador reserva su espacio, así que cambiar entre los estados de añadir y quitar no redistribuye la página (sin CLS).
* El conmutador es un `<button>` real con `aria-pressed`. Cuando un producto aparece más de una vez en una página, todos sus botones se actualizan a la vez tras guardar, y el cambio se anuncia a los lectores de pantalla mediante una región activa educada.
* Guardar y quitar ocurre a través de admin-ajax sin recargar la página.

En los productos variables, el botón sigue la variación seleccionada, así que el cliente guarda exactamente la talla o el color que eligió en lugar del producto superior. Hasta que elige las opciones, el botón permanece desactivado, con una pista que puedes redactar tú mismo.

El código fuente está en GitHub, en https://github.com/wppoland/plogins-shortlist; ese es el lugar para informes de errores y parches.

= Documentation and links =

* <strong>Documentación</strong> - https://plogins.com/es/plogins-shortlist/docs/
* <strong>Página del plugin</strong> - https://plogins.com/es/plogins-shortlist/
* <strong>Código fuente</strong> - https://github.com/wppoland/plogins-shortlist
* <strong>Informes de errores y peticiones de funciones</strong> - https://github.com/wppoland/plogins-shortlist/issues


= Where the button and list can appear =

* La página de producto individual, debajo de la zona de añadir al carrito.
* Fichas de producto en los bucles de tienda, categoría y etiqueta.
* Una pestaña «Lista de deseos» en Mi cuenta de WooCommerce, que opcionalmente muestra un recuento de artículos guardados como «Lista de deseos (3)».
* Una página dedicada que eliges o creas desde la pantalla de ajustes.
* Cualquier entrada o página, mediante el shortcode `[shortlist]`.
* El editor de bloques, mediante el bloque <strong>Shortlist Wishlist</strong> (renderizado en el servidor, así que la vista previa del editor coincide con el frontend).

Cada ubicación es un conmutador independiente en la pantalla de ajustes.

= Settings =

El menú Shortlist en wp-admin está disponible para los gestores de la tienda (usa la capacidad `manage_woocommerce`), no solo para los administradores. Desde ahí puedes:

* Activar o desactivar la lista de deseos y decidir si los invitados pueden usarla.
* Elegir dónde aparece el botón: producto individual, bucle de tienda, Mi cuenta y una página dedicada.
* Mostrar u ocultar el recuento de artículos guardados en el menú de Mi cuenta.
* Definir las etiquetas de los botones de añadir y quitar, y la pista de variación.
* Dar forma a la propia lista: encabezado, texto de introducción y de lista vacía, cuántas columnas usa la cuadrícula y qué detalles (imagen, nombre, precio, añadir al carrito, botón de quitar) muestra cada producto guardado.

Cada ajuste tiene un «?» al lado que abre una breve explicación de lo que hace.

Shortlist solo carga su hoja de estilos y su script en las páginas donde realmente aparece la lista de deseos, así que el resto de tu tienda queda intacto.

== Installation ==

1. Sube el plugin a `/wp-content/plugins/shortlist` o instálalo desde Plugins → Añadir nuevo.
2. Actívalo. WooCommerce debe estar activo.
3. Entra en el menú <strong>Shortlist</strong> en wp-admin para configurar la ubicación y las etiquetas.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Sí. Shortlist requiere una instalación activa de WooCommerce.

= Can guests use the wishlist? =

Sí, si lo permites en los ajustes. La lista de un invitado se guarda en una cookie y se fusiona con su cuenta la próxima vez que inicia sesión.

= Does it use jQuery? =

No. El script del frontend del propio plugin es JavaScript puro sin dependencia de jQuery.

= How do I show the wishlist on a page? =

Usa el shortcode `[shortlist]` o apóyate en la pestaña «Lista de deseos» que se añade a la zona Mi cuenta de WooCommerce.

= Does it work with variable products? =

Sí. En los productos variables, el botón de la lista de deseos sigue la variación seleccionada, así que el artículo guardado puede incluir la talla o el color elegidos.

= Can I create a dedicated wishlist page? =

Sí. Elige una página existente o crea una desde la pantalla de ajustes de Shortlist. El plugin puede insertar la lista `[shortlist]` automáticamente.

= Is the wishlist accessible? =

Sí. El botón de la lista de deseos es un botón real con `aria-pressed`, anuncios para lectores de pantalla y sin saltos de diseño cuando cambia el estado guardado.


= Does this plugin work on WordPress Multisite? =

Sí. Este plugin es compatible con WordPress Multisite. Actívalo para toda la red o en sitios concretos; cada sitio conserva sus propios ajustes y datos.

== Screenshots ==

1. La página de la lista de deseos con los productos guardados, cada uno con botones de añadir al carrito y quitar.
2. La misma lista de deseos en un teléfono.
3. La pantalla de ajustes de Shortlist.

== External Services ==

Shortlist no se conecta a ningún servicio externo. Guardar y quitar artículos ocurre a través del endpoint admin-ajax de tu propio sitio, y todos los datos de la lista de deseos permanecen en tu base de datos de WordPress: las listas de los clientes que han iniciado sesión viven en una tabla propia `shortlist_items` vinculada a su ID de usuario, las listas de invitados viven en una cookie en el propio navegador del visitante hasta que inicia sesión, y los ajustes se guardan en la opción `shortlist_settings`. El plugin no envía ningún correo electrónico y no carga fuentes, scripts ni rastreadores de terceros.

== Translations ==

Plogins Shortlist incluye traducciones al polaco, al alemán y al español para la interfaz del plugin. El dominio de texto es `plogins-shortlist`, por lo que los paquetes de idioma de WordPress.org también pueden sustituir o ampliar estas traducciones incluidas.

== Changelog ==

= 1.0.2 =
* Se han añadido traducciones incluidas al polaco, al alemán y al español para la interfaz del plugin.

= 1.0.1 =
* Primera versión estable.

= 0.3.1 =
* Renombrado a Plogins Shortlist for WooCommerce para tener un nombre de plugin más distintivo.

= 0.3.0 =
* Nuevo: <strong>Página de lista de deseos</strong>: elige una página existente o crea una desde los ajustes; la lista `[shortlist]` se inserta automáticamente cuando la página aún no tiene shortcode.
* Nuevo: <strong>Guardado consciente de variaciones</strong>: en los productos variables el botón sigue la variación seleccionada; pista configurable cuando no se elige ninguna variación.
* Mejorado: la pantalla de ajustes agrupa la página de lista de deseos, la pista de variación y los controles de ubicación existentes.

= 0.2.0 =
* Pulido: estilos de tienda renovados y adaptables al tema (icono de corazón, modo oscuro, cuadrícula segura para CLS) y una pantalla de ajustes moderna basada en tarjetas con un popover de ayuda «?» accesible en cada opción.
* Accesibilidad: los cambios en la lista de deseos ahora se anuncian a los lectores de pantalla, y el recuento de Mi cuenta se actualiza en directo sin recargar la página.
* Robustez: estado vacío amable con un enlace «Explorar productos», mensajes de error claros y protecciones defensivas ante datos de producto que falten.
* Nuevo: bloque <strong>Shortlist Wishlist</strong> para el editor de bloques (renderizado en el servidor, coincide con el shortcode `[shortlist]`).
* Nuevo: recuento opcional de artículos guardados junto a la etiqueta del menú «Lista de deseos» de Mi cuenta.
* Nuevo: control total sobre la lista de deseos: encabezado, texto de introducción y de lista vacía, número de columnas y qué detalles del producto (imagen, nombre, precio, añadir al carrito, botón de quitar) aparecen.
* Nuevo: la limpieza de desinstalación elimina la tabla de la lista de deseos y las opciones del plugin al borrarlo.
* i18n: se ha añadido Domain Path y un directorio `languages` para las traducciones.

= 0.1.0 =
* Versión inicial: lista de deseos AJAX accesible para WooCommerce con soporte para invitados, una pestaña Mi cuenta, un shortcode y una página de ajustes para la ubicación y las etiquetas.
