<?php
require_once 'config.php';
require_once 'websun/websun.php';

// проверяем константы и устанавливаем их, если они не определены в файле config.php
// paths
defined("LFME_ROOT_PATH")       or define( "LFME_ROOT_PATH", '/leafletmaps' );
defined("LFME_TEMPLATES_PATH")  or define( "LFME_TEMPLATES_PATH" , '$/leafletmaps/templates' );
defined("LFME_INDEX_TITLE")     or define( "LFME_INDEX_TITLE" , "Каталог карт для ролевых игр на движке Leaflet");
// viewport
defined("LFME_VIEWPORT_WIDTH")  or define( "LFME_VIEWPORT_WIDTH", 800);
defined("LFME_VIEWPORT_HEIGHT") or define( "LFME_VIEWPORT_HEIGHT", 600);
// version
defined("LFME_VERSION")         or define( "LFME_VERSION", "0.2.2");

/* =====================================================================
* Функции, используемые в проекте. В этом файле описаны только для того,
* чтобы не плодить кучу ненужных мелких файлов.
** ===================================================================== */

/**
 * Instant redirect to URL
 * @param $url
 */
function redirect($url)
{
    if (headers_sent() === false) header('Location: '.$url);
    die();
}


/**
 * Equivalent for isset( array[ key ] ) ? array[ key ] : default ;
 * at PHP 7 will be useless, use
 * z = $array[ $key ] ?? $default;
 *
 * @param $array    - массив, в котором ищем значение
 * @param $key      - ключ
 * @param $default  - значение по умолчанию
 */
function at($array, $key, $default)
{
    return isset($array[$key]) ? $array[$key] : $default;
}