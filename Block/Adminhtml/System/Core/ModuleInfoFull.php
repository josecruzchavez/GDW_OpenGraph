<?php
namespace GDW\OpenGraph\Block\Adminhtml\System\Core;

use GDW\Core\Block\Adminhtml\System\Core\ModuleInfoFull as Fieldset;

class ModuleInfoFull extends Fieldset
{    
    const GDW_MODULE_CODE = 'GDW_OpenGraph';
    const GDW_MODULE_LINK = 'adminhtml/system_config/edit/section/gdwseo';
    const GDW_MODULE_LINK_SECC = '#gdwseo_opengraph-link';

    public function getDescFull()
    {
        $html  = '<p>Agrega las etiquetas OpenGraph en páginas, productos y categorías, <a href="https://github.com/josecruzchavez/GDW_OpenGraph" target="_blank">Más información >> </a></p>';
        return $html;
    }
}