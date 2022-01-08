# GDW OpenGraph para Magento 2
Este módulo tiene la finalidad de agregar las etiquetas opengraph a magento 2.
Las etiquetas opengraph sirven para darle un mejor aspecto a los enlaces cuando son compartidos en facebook, twitter, whatsapp principalmente.
## Compatibilidad
✓ Magento 2.3.x, ✓ Magento 2.4.x

![gdw_opengraph](https://gestiondigitalweb.com/github_assets/gdw_opengraph/gdw_open_graph_base.png)

## Funciones destacadas
* Agrega tag OpenGraph en páginas(cms), productos y categorías.
* Sustituye el OpenGraph simple de productos.
* Utiliza los campos "meta" nativos de magento.
* Compaltible con multitiendas.
* Muestra la moneda e idioma por tienda.
* Crea un nuevo atributo para la imagen destacada de forma global.
* Se puede editar la imagen destacada por página(cms), productos y categorías.
* Permite seleccionar el campo para la condición del producto.
* Permite seleccionar el campo para la Marca del producto.
* Da prioridad a la imagen base en los porductos.
* Este módulo es totalmente gratis.
<br/>

###### Ejecuta los siguientes comandos en la ruta base de Magento.

### Instalación

```
composer require gdw/opengraph

php bin/magento module:enable GDW_OpenGraph
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento cache:flush
```

### Actualización

```
composer update gdw/opengraph

php bin/magento module:enable GDW_OpenGraph
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento cache:flush
```

### Eliminación

```
php bin/magento module:disbale GDW_OpenGraph
composer remove gdw/opengraph
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento cache:flush
```

### Expresiones de Gratitud

* Comenta a otros sobre este proyecto 📢
* Da las gracias públicamente
* [Invítame una cerveza 🍺](https://www.paypal.me/gestiondigitalweb)
* [Visita mi sitio web](https://gestiondigitalweb.com/?utm_source=github&utm_medium=gdw&utm_campaign=opengraph&utm_id=link)
* [Sígueme en YouTube](https://www.youtube.com/c/Gestiondigitalweb)
* [Sígueme en Facebook](https://www.facebook.com/GestionDigitalWeb)