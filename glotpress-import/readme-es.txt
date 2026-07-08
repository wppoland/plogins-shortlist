=== Plogins Shortlist - Wishlist for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, wishlist, product wishlist, save for later, favourites
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lista de deseos de WooCommerce y lista de guardar para más tarde para invitados y clientes: alternancia AJAX, pestaña Mi cuenta, código corto y bloqueo.

== Description ==

Shortlist añade un botón "Añadir a la lista de deseos" al bucle de su tienda WooCommerce y a las páginas de productos. Los compradores guardan productos, favoritos y artículos guardados para más adelante, luego regresan desde una pestaña "Lista de deseos" en Mi cuenta, una página propia o cualquier lugar donde coloque el código corto `[shortlist]`.

Los invitados pueden guardar productos antes de iniciar sesión. Una lista de invitados se encuentra en una cookie; la próxima vez que el visitante inicie sesión, sus elementos guardados se trasladarán a su cuenta, por lo que no se perderá nada en el paso de inicio de sesión. Las listas de clientes que han iniciado sesión se almacenan en una tabla de base de datos personalizada codificada según su identificación de usuario.

El complemento está escrito para tiendas que se preocupan por el peso y la accesibilidad del front-end:

* El script de interfaz de usuario es JavaScript básico sin dependencia de jQuery. Se difiere y se carga en el pie de página.
* El botón de alternancia reserva su espacio, por lo que cambiar entre los estados añadir y eliminar no redistribuye la página (sin CLS).
* El interruptor es un `<botón>` real con `aria-presionado`. Cuando un producto aparece más de una vez en una página, todos los botones se actualizan juntos después de guardarlo y el cambio se anuncia a los lectores de pantalla a través de una región en vivo educada.
* Guardar y eliminar se realiza a través de admin-ajax sin recargar la página.

En productos variables, el botón sigue la variación seleccionada, por lo que el cliente guarda el tamaño o color exacto que eligió en lugar del producto principal. Hasta que elijan opciones, el botón permanece desactivado, con una pista que puede expresar tú mismo.

La fuente se encuentra en GitHub en https://github.com/wppoland/plogins-shortlist, ese es el lugar para informes de errores y parches.

= Documentation and links =

* <strong>Documentación</strong> - https://plogins.com/es/plogins-shortlist/docs/
* <strong>Página de complementos</strong> - https://plogins.com/es/plogins-shortlist/
* <strong>Código fuente</strong> - https://github.com/wppoland/plogins-shortlist
* <strong>Informes de errores y solicitudes de funciones</strong> - https://github.com/wppoland/plogins-shortlist/issues


= Where the button and list can appear =

* La página de un solo producto, debajo del área de añadir al carrito.
* Fichas de productos en la tienda, categoría y bucles de etiquetas.
* Una pestaña "Lista de deseos" en Mi cuenta de WooCommerce, que muestra opcionalmente un recuento de elementos guardados como "Lista de deseos (3)".
* Una página dedicada que eliges o crea desde la pantalla de configuración.
* Cualquier publicación o página, a través del shortcode `[shortlist]`.
* El editor de bloques, a través del bloque <strong>Shortlist Wishlist</strong> (renderizado por el servidor, por lo que la vista previa del editor coincide con la interfaz).

Cada ubicación es un interruptor independiente en la pantalla de configuración.

= Settings =

El menú Lista corta en wp-admin se abre para los administradores de tiendas (utiliza la capacidad `manage_woocommerce`), no solo para los administradores. Desde allí puedes:

* Activa o desactiva la lista de deseos y decide si los invitados pueden usarla.
* Elija dónde aparece el botón: producto único, bucle de compra, Mi cuenta y una página dedicada.
* Mostrar u ocultar el recuento de elementos guardados en el menú Mi cuenta.
* Configure las etiquetas de los botones para añadir y quitar, y la sugerencia de variación.
* Dale forma a la lista en sí: encabezado, introducción y texto de lista vacía, cuántas columnas usa la cuadrícula y qué detalles (imagen, nombre, precio, añadir al carrito, botón de eliminar) muestra cada producto guardado.

Cada configuración tiene un "?" al lado que se abre una breve explicación de lo que hace.

Shortlist solo carga su hoja de estilo y secuencia de comandos en las páginas donde realmente aparece la lista de deseos, por lo que el resto de su tienda permanece intacta.

== Installation ==

1. Cargue el complemento en `/wp-content/plugins/shortlist`, o instálelo a través de Complementos → Añadir nuevo.
2. Actívalo. WooCommerce debe estar activo.
3. Visite el menú <strong>Lista corta</strong> en wp-admin para configurar la ubicación y las etiquetas.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Sí. La lista corta requiere una instalación activa de WooCommerce.

= Can guests use the wishlist? =

Sí, si lo permites en la configuración. La lista de invitados reside en una cookie y se fusiona con su cuenta la próxima vez que inicia sesión.

= Does it use jQuery? =

No. El script de interfaz del complemento es JavaScript básico sin dependencia de jQuery.

= How do I show the wishlist on a page? =

Utilice el código abreviado `[shortlist]` o confíe en la pestaña "Lista de deseos" agregada al área Mi cuenta de WooCommerce.

= Does it work with variable products? =

Sí. En productos variables, el botón de lista de deseos sigue la variación seleccionada, por lo que el artículo guardado puede incluir el tamaño o color elegido.

= Can I create a dedicated wishlist page? =

Sí. Elija una página existente o cree una desde la pantalla de configuración de la lista corta. El complemento puede inyectar la lista `[shortlist]` automáticamente.

= Is the wishlist accessible? =

Sí. El botón de lista de deseos es un botón real con "aria presionada", anuncios en el lector de pantalla y sin cambio de diseño cuando cambia el estado guardado.


= Does this plugin work on WordPress Multisite? =

Sí. Este complemento es compatible con WordPress Multisite. Activarlo en red o activarlo en sitios individuales; Cada sitio mantiene su propia configuración y datos.

== Screenshots ==

1. La página de la lista de deseos que muestra los productos guardados, cada uno con botones para añadir al carrito y eliminar.
2. La misma lista de deseos en un teléfono.
3. La pantalla de configuración de la lista corta.

== External Services ==

Shortlist no se conecta a ningún servicio externo. Guardar y eliminar elementos se realiza a través del punto final admin-ajax de tu propio sitio, y todos los datos de la lista de deseos permanecen en tu base de datos de WordPress: las listas de clientes que han iniciado sesión viven en una tabla personalizada `shortlist_items` vinculada a su identificación de usuario, las listas de invitados viven en una cookie en el propio navegador del visitante hasta que inician sesión, y las configuraciones se almacenan en la opción `shortlist_settings`. El complemento no envía correos electrónicos y no carga fuentes, scripts ni rastreadores de terceros.

== Changelog ==

= 1.0.1 =
* Primera versión estable.

= 0.3.1 =
* Renombrado a Plogins Shortlist para WooCommerce para obtener un nombre de complemento más distintivo.

= 0.3.0 =
* Nuevo: <strong>Página de lista de deseos</strong>, elija una página existente o cree una desde la configuración; Inyecta automáticamente la lista `[shortlist]` cuando la página aún no tiene un código corto.
* Nuevo: <strong>guardados que tienen en cuenta las variaciones</strong>; en productos variables, el botón rastrea la variación seleccionada; sugerencia configurable cuando no se elige ninguna variación.
* Mejorado: la pantalla de configuración agrupa la página de lista de deseos, sugerencia de variación y controles de ubicación existentes.

= 0.2.0 =
* Polaco: estilos de escaparate renovados y temáticos (icono de corazón, modo oscuro, cuadrícula segura para CLS) y una pantalla de configuración moderna basada en tarjetas con un "?" accesible popover de ayuda en cada opción.
* Accesibilidad: los cambios en la lista de deseos ahora se anuncian a los lectores de pantalla y el recuento de Mi cuenta se actualiza en vivo sin recargar la página.
* Robustez: estado vacío amigable con un enlace "Buscar productos", mensajes de error claros y protecciones defensivas contra datos faltantes de productos.
* Nuevo: bloque <strong>Lista de deseos de lista corta</strong> para el editor de bloques (renderizado por el servidor, coincide con el código abreviado `[shortlist]`).
* Nuevo: recuento opcional de elementos guardados junto a la etiqueta del menú "Lista de deseos" de Mi cuenta.
* Nuevo: control total sobre la lista de deseos, encabezado, texto de introducción y lista vacía, recuento de columnas y qué detalles del producto (imagen, nombre, precio, añadir al carrito, botón eliminar) aparecen.
* Nuevo: la limpieza de desinstalación elimina la tabla de la lista de deseos y las opciones de complementos al eliminar.
* i18n: ruta de dominio agregada y un directorio de "idiomas" para traducciones.

= 0.1.0 =
* Lanzamiento inicial: lista de deseos AJAX accesible para WooCommerce con soporte para invitados, una pestaña Mi cuenta, un código corto y una página de configuración para ubicación y etiquetas.
