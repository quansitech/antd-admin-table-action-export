## ExportExcelByXlsx

导出excel辅助类

#### exportExcelByXlsx

默认传递 page rownum 参数，便捷分页读取数据

| 参数           | 说明      | 数据格式     | 默认值 |
|--------------|---------|----------|-----|
| genExcelList | 处理数据的回调 | \Closure |     |

#### genExportCols

获取用户选中的列

| 参数            | 说明                       | 数据格式   | 默认值 |
|---------------|--------------------------|--------|-----|
| col_options   | 导出列的设置                   | array  |     |
| selected_cols | I('post.exportCol') 的列数据 | string |     |

#### transcodeOneCellProperties

将行数据单元格属性键名转为表头字段

| 参数              | 说明         | 数据格式  | 默认值 |
|-----------------|------------|-------|-----|
| cell_properties | 某行数据的单元格属性 | array |     |
| header_mapping  | 表头与字段的映射值  | array |     |

#### combineListWithNameMapping

将数据键名转换为表头字段

| 参数             | 说明        | 数据格式  | 默认值 |
|----------------|-----------|-------|-----|
| list           | 需要导出的数据   | array |     |
| header_mapping | 表头与字段的映射值 | array |     |


