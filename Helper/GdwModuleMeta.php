<?php
declare(strict_types=1);

namespace GDW\OpenGraph\Helper;

final class GdwModuleMeta
{
    /** @return array{desc:string, config_path:string, config_anchor:string, repo_url:string, docs_url:string} */
    public static function getMeta(): array
    {
        return [
            'desc' => 'Agrega etiquetas OpenGraph en paginas, productos y categorias.',
            'config_path' => 'adminhtml/system_config/edit/section/gdwseo',
            'config_anchor' => '#gdwseo_opengraph-link',
            'repo_url' => 'https://github.com/josecruzchavez/GDW_OpenGraph',
            'docs_url' => 'https://docs.gdw.mx/modulos/gdw_opengraph',
        ];
    }
}