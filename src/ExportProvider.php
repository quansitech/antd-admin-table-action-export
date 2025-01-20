<?php

namespace Qs\TopButton;

use AntdAdmin\Component\Table\ActionsContainer;
use Bootstrap\Provider;
use Bootstrap\RegisterContainer;
use Qs\TopButton\AntdComponent\Export as AntdExport;
use Qs\TopButton\Export\Export;

class ExportProvider implements Provider
{

    public function register()
    {
        RegisterContainer::registerListTopButton('export', Export::class);

        RegisterContainer::registerSymLink(WWW_DIR . '/Public/export-excel', __DIR__ . '/../asset/export-excel');

        ActionsContainer::registerHelperMethod('exportButton', function (ActionsContainer $container, ...$params) {
            $btn = new AntdExport(...$params);
            $container->addAction($btn);
            return $btn;
        });
    }
}