<?php

namespace Qs\TopButton\Export;

trait ExportExcelByXlsx
{

    protected function exportExcelByXlsx(\Closure $genExcelList)
    {
        $page = I('get.page');
        $rownum = I('get.rownum');

        $list = call_user_func($genExcelList, $page, $rownum);
        $this->ajaxReturn($list);
    }

    protected function genExportCols($col_options, $selected_cols)
    {
        $res = collect($col_options)->map(function ($option) {
            if ($option['required'] == true) {
                return $option['key'];
            }
        })->filter()->all();

        $selected_cols = $selected_cols === "" ? [] : (array)$selected_cols;

        $res = array_merge($res, $selected_cols);
        $res = array_unique($res);

        return $res;
    }

    protected function transcodeOneCellProperties(array $cell_properties, array $header_mapping): array
    {
        $new_cell_properties = [];
        collect($header_mapping)->each(function ($name_item, $name_key) use ($cell_properties, &$new_cell_properties) {
            isset($cell_properties[$name_key]) && $new_cell_properties[$name_item] = $cell_properties[$name_key];
        });

        return $new_cell_properties;
    }

    protected function combineListWithNameMapping(array $list, array $header_mapping): array
    {
        return collect($list)->map(function ($data) use ($header_mapping) {
            $new_list = [];
            collect($header_mapping)->each(function ($name_item, $name_key) use ($data, &$new_list) {
                $new_list[$name_item] = $data[$name_key];
            });
            if (isset($data['_cellProperties'])) {
                $new_list['_cellProperties'] = $this->transcodeOneCellProperties($data['_cellProperties'], $header_mapping);;
            }
            return $new_list;
        })->toArray();
    }

}