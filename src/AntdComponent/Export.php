<?php

namespace Qs\TopButton\AntdComponent;

use AntdAdmin\Component\Table\ActionType\BaseAction;

class Export extends BaseAction
{
    public function __construct(string $title)
    {
        parent::__construct($title);
        $this->render_data['export']['pageNum'] = 100;
        $this->render_data['export']['url'] = U('export');
        $this->render_data['export']['filename'] = date('Ymd') . '导出';
        $this->render_data['export']['cols'] = [];
    }

    protected function getType()
    {
        return 'export';
    }

    /**
     * 设置按钮属性 {type: primary|default, danger: true|false}
     * @see https://ant.design/components/button-cn#api
     * @param array $props
     * @return $this
     */
    public function setProps(array $props)
    {
        $this->render_data['props'] = $props;
        return $this;
    }


    /**
     * 设置导出链接
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url)
    {
        $this->render_data['export']['url'] = $url;
        return $this;
    }

    /**
     * 设置导出文件名
     * @param string $filename
     * @return $this
     */
    public function setFilename(string $filename)
    {
        $this->render_data['export']['filename'] = $filename;
        return $this;
    }


    /**
     * 设置导出列
     * @param array $cols
     * @return $this
     */
    public function setCols(array $cols)
    {
        $this->render_data['export']['cols'] = $cols;
        return $this;
    }

    /**
     * 设置导出每页数量
     * @param int $pageNum
     * @return $this
     */
    public function setPageNum(int $pageNum)
    {
        $this->render_data['export']['pageNum'] = $pageNum;
        return $this;
    }
}