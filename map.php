<?php
require_once 'backend/required.php';

/* project and map aliases */
$project_alias
    = isset($_GET['project'])
    ? $_GET['project']
    : die('No such project!');

$map_alias
    = isset($_GET['map'])
    ? $_GET['map']
    : die('No such map!');

/* load config file */
$filename = 'storage/' . $project_alias . '.json';

$file_data = file_get_contents( $filename );

if ($file_data === FALSE)
    redirect( LFME_ROOT_PATH );

$data = json_decode($file_data , true );

// настройки viewport по умолчанию:

$display_width = LFME_VIEWPORT_WIDTH;
$display_height = LFME_VIEWPORT_HEIGHT;

// прочитать настройки viewport из файла конфигурации для проекта

/*
 * @todo: проапгрейдить at(), чтобы можно было обращаться к ключу многомерного
 * массива... скажем, по ключу: at($config, 'project/viewport', 800);
 */

if (isset($data['viewport'])) {
    $display_width  = at($data['viewport'], 'width', $display_width);
    $display_height = at($data['viewport'], 'height', $display_height);
}

/*
 * Если $config->project->maps_folder пуст, то
 * считается, что все карты лежат в каталоге с именем алиаса проекта.
 */
if ($data['project']['maps_folder'] == '')
    $data['project']['maps_folder'] = $project_alias;

// проверка наличия структуры config->maps (если она пуста - редирект в корень)
if (!isset($data['maps']) || (count($data['maps']) == 0) )
    redirect( LFME_ROOT_PATH );

// load mapinfo from .json file with given map_alias
// ищем описание карты
$map_found = FALSE;
foreach($data['maps'] as $anymap) {
    if ($anymap['info']['alias'] == $map_alias) {
        // карта найдена
        $map_found = TRUE;
        $map = $anymap;

        if (!preg_match('/^(http|https):\/\//' , $map['image']['url'])) {
            $map['image']['link'] = LFME_ROOT_PATH . '/storage/' .
                $data['project']['maps_folder'] . '/' . $map['image']['url'];
        } else {
            $map['image']['link'] = $map['image']['url'];
        }

        // прочитать настройки viewport для конкретной карты (перекрыв глобальные)
        if (isset($map['viewport'])) {
            $display_width  = at($map['viewport'], 'width', $display_width);
            $display_height = at($map['viewport'], 'height', $display_height);
        }
    }
}

// проанализировать GET-параметры, перекрыв уже определенные выше.
$display_width  = at($_GET, 'width', $display_width);
$display_height = at($_GET, 'height', $display_height);

// form template override values

$template_data = array(
    'project_alias'     =>  $project_alias,
    'project_title'     =>  $data['project']['title'],
    'map_alias'         =>  $map_alias,
    'map_title'         =>  $map['info']['title'],
    // map image
    'map_width'         =>  $map['image']['width'],
    'map_height'        =>  $map['image']['height'],
    'map_link'          =>  $map['image']['link'],
    // map zoom settings
    'zoom_min'          =>  $map['zoom']['zoom_min'],
    'zoom_max'          =>  $map['zoom']['zoom_max'],
    'zoom_cur'          =>  $map['zoom']['zoom_cur'],
    // centring settings
    'center_x'          =>  $map['zoom']['center_x'],
    'center_y'          =>  $map['zoom']['center_y'],
    // back url
    'back_url'          =>  LFME_ROOT_PATH . '/' . $project_alias,
    // viewarea size
    'leafletmaparea_width'  => $display_width,
    'leafletmaparea_height' => $display_height
);

// build template

$template_file = 'map_view.html';
$html = websun_parse_template_path($template_data, $template_file, LFME_TEMPLATES_PATH );

// print

echo $html;