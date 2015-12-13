<?php

@define( "LFME_ROOT_PATH", '/leafletmaps' );
@define( "LFME_TEMPLATES_PATH" , '$/leafletmaps/templates' );
@define( "LFME_INDEX_TITLE" , "Каталог карт для ролевых игр на движке Leaflet");

// размеры области отображения leaflet-карты по умолчанию
@define( "LFME_VIEWPORT_WIDTH", 800);
@define( "LFME_VIEWPORT_HEIGHT", 600);

require_once 'websun/websun.php';

/* =====================================================================
* Функции, используемые в проекте. В этом файле описаны только для того,
* чтобы не плодить кучу ненужных мелких файлов.
** ===================================================================== */

/**
 * Instant redirect по указанному URL
 * @param $url
 */
function redirect($url)
{
    if (headers_sent() === false) header('Location: '.$url);
    die();
}


/**
 * Эквивалент isset( array[ key ] ) ? array[ key ] : default ;
 * at PHP 7 useless, z = a ?? b;
 * А точнее z = $array[ $key ] ?? $default;
 * @param $array    - массив, в котором ищем значение
 * @param $key      - ключ
 * @param $default  - значение по умолчанию
 */
function at($array, $key, $default)
{
    return isset($array[$key]) ? $array[$key] : $default;
}


/**
 * Удаляет куку
 * @param $cookie_name
 */
function unsetcookie($cookie_name)
{
    unset($_COOKIE[$cookie_name]);
    setcookie($cookie_name, null, -1, '/');
}
