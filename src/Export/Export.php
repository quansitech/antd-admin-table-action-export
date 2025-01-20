<?php

namespace Qs\TopButton\Export;

use AntdAdmin\Component\Table\ActionType\BaseAction;
use Illuminate\Support\Str;
use Qscmf\Builder\Antd\BuilderAdapter\ListAdapter\IAntdTableButton;
use Qscmf\Builder\ButtonType\ButtonType;
use Qscmf\Builder\ListBuilder;
use Think\View;

class Export extends ButtonType implements IAntdTableButton
{


    /**
     * @param array $option
     * @param ListBuilder|null $listBuilder
     * @return string
     * @deprecated
     */
    public function build(array &$option, ?ListBuilder $listBuilder)
    {
        $my_attribute['type'] = 'export';
        $my_attribute['title'] = '导出excel';
        $my_attribute['target-form'] = 'ids';
        $my_attribute['class'] = 'btn btn-primary export_excel';

        if ($option['attribute'] && is_array($option['attribute'])) {
            $option['attribute'] = array_merge($my_attribute, $option['attribute']);
        }

        $gid = Str::uuid()->getHex();
        $option['attribute']['id'] = $gid;

        $view = new View();
        $view->assign('gid', $gid);
        $view->assign('button', $option['attribute']);
        $content = $view->fetch(__DIR__ . '/export.html');

        return $content;
    }

    public function tableButtonAntdRender($options, $listBuilder): BaseAction|array
    {
        $btn = new \Qs\TopButton\AntdComponent\Export($options['attribute']['title']);
        $btn->setUrl($options['attribute']['data-url'])
            ->setCols($options['attribute']['export_cols'])
            ->setPageNum($options['attribute']['data-streamrownum'])
            ->setFilename($options['attribute']['data-filename']);
        return $btn;
    }
}