## xlsx导出excel（antd-admin版）

升级 [quansitech/qscmf-topbutton-export](https://github.com/quansitech/qscmf-topbutton-export) 至antd-admin版

+ 安装

```shell
composer require quansitech/qscmf-topbutton-export
npm i --save https://cdn.sheetjs.com/xlsx-0.20.3/xlsx-0.20.3.tgz
```

+ 使用样例

```php
...

class PostController extends GyListController{
    //继承ExportExcelByXlsx
    use ExportExcelByXlsx;
    public function index() {
...
        // ListBuilder
        $builder = new \Common\Builder\ListBuilder();
        //第一个参数指定export类型，第二个参数是指定需要覆盖的html组件属性
        //title为按钮名称，默认导出excel
        //data-url为点击导出按钮后ajax请求的地址,必填
        //data-filename 为生成的excel文件名,默认为浏览器的默认生成文件名
        //data-streamrownum 为每次请求获取的数据数
        $builder->addTopButton('export', array('title' => '样例导出', 'data-url' => U('/admin/post/export'), 'data-filename' => '文章列表', 'data-streamrownum' => '10'));
        
        // antd-admin table
        $table = new \AntdAdmin\Component\Table();
        $table->actions(function (\AntdAdmin\Component\Table\ActionsContainer $container){
            $container->exportButton('导出');
        });
...
    }
    //导出excel请求的action
    public function export(){
    
        //exportExcelByXlsx为 Qscmf\Builder\ExportExcelByXlsx trait提供的方法
        //参数为一个闭包函数，接收两个参数， page为请求的页数， rownnum为请求的数据行数
        $this->exportExcelByXlsx(function($page, $rownum){
             //闭包函数必须返回如下数据格式
             //[
             //    [  'excel表头1' =>  行1数据1, 'excel表头2' => 行1数据2 ..... ]
             //    [  'excel表头1' =>  行2数据1, 'excel表头2' => 行2数据2 ..... ]
             //    ...
             //]
            return [
                 [  '姓名' =>  'tt', '性别' => 'male', '年龄' => 23 ]
                 [  '姓名' =>  'ff', '性别' => 'female', '年龄' => 19 ]
            ];
        });

    }

```

#### 筛选导出列

```php
//列配置，default为true表示默认选中状态, required为true表示必选
$cols_options = [
    [
        'key' => 'name',
        'title' => '商家名称',
        'default' => true,
        'required' => true
    ],
    [
        'key' => 'account',
        'title' => '账号',
        'default' => true
    ],
    [
        'key' => 'address',
        'title' => '商家地址'
    ],
    [
        'key' => 'num',
        'title' => '核销次数'
    ],
    [
        'key' => 'status',
        'title' => '状态'
    ],
    [
        'key' => 'explain',
        'title' => '优惠券使用说明'
    ]
];

// listbuilder
//将列配置复制给第二个参数的键值 export_cols
//控件会将选择的列数据post至url,可通过I('post.exportCol')获取，再进行数据筛选逻辑处理。
$builder->addTopButton('export', array('export_cols' => $cols_options, 'title' => '样例导出', 'data-url' => U('/admin/post/export'), 'data-filename' => '文章列表', 'data-streamrownum' => '10'));

// antd-admin table action
/** @var \AntdAdmin\Component\Table\ActionsContainer $container */
$container->exportButton('导出')->setCols($cols_options);

```

#### 导出数据为多张工作表

```php
 $export_arr = [
    ['sheetName' => 'Sheet1', 'url' => U('/admin/post/export'), 'rownum' => '15'],
    ['sheetName' => 'Sheet2', 'url' => U('/admin/post/export'), 'rownum' => '15'],
 ];
 
 $builder->addTopButton('export', ['data-url' => json_encode($export_arr), 'data-filename' => '导出列表', 'data-streamrownum' => intval($export_arr[0]['rownum'])]);
```

#### 业务层错误提示

```php
可在导出数据处理的action位置进行错误验证，使用$this->errro("test")
插件可自动获取错误信息并alert提示用户
```

#### 导出辅助类

[ExportExcelByXlsx使用说明](https://github.com/quansitech/qscmf-topbutton-export/blob/master/ExportExcelByXlsx.md)

#### 导出有合并单元格的表格

```text
每一行数据添加字段 _cellProperties 来配置对应的单元格需要合并的范围。

_cellProperties 包括 rowSpan colSpan值，为0表示被合并的单元格
```

+ 用法
  ```php
  // 原始数据为
  $source_data = [
    ['name' => 'name1','nick_name' => 'nick_name1','phone' => 'phone1','tel' => 'tel1'],
    ['name' => 'name2','nick_name' => 'nick_name2','phone' => 'phone2','tel' => 'tel2'],
    ['name' => 'name3','nick_name' => 'nick_name3','phone' => 'phone3','tel' => 'tel3'],
  ];
  
  // 想要实现的效果
  // 将第二行的 name 和 第三行的 name 行合并，并展示 第二行 的值； 
  // 将第二行的 phone 和 tel 列合并，并展示 phone； 
  
  // 设置每一行的 _cellProperties 
  $source_data = [
    ['name' => 'name1','nick_name' => 'nick_name1','phone' => 'phone1','tel' => 'tel1', 
     '_cellProperties' => ['name' => '','nick_name' => '','phone' => '','tel' => '']],
    ['name' => 'name2','nick_name' => 'nick_name2','phone' => 'phone2','tel' => 'tel2',
     '_cellProperties' => ['name' => ['rowSpan' => 2],'nick_name' => '','phone' => ['colSpan' => 2],'tel' => ['colSpan' => 0]]],
    ['name' => 'name3','nick_name' => 'nick_name3','phone' => 'phone3','tel' => 'tel3', 
     '_cellProperties' => ['name' => ['rowSpan' => 0],'nick_name' => '','phone' => '','tel' => '']],
  ];
  
  // 导出的数据格式为 
  $export_data = [
    ['表头字段1' => 'name1','表头字段2' => 'nick_name1','表头字段3' => 'phone1','表头字段4' => 'tel1', 
     '_cellProperties' => ['表头字段1' => '','表头字段2' => '','表头字段3' => '','表头字段4' => '']],
    ['表头字段1' => 'name2','表头字段2' => 'nick_name2','表头字段3' => 'phone2','表头字段4' => 'tel2', 
     '_cellProperties' => ['表头字段1' => ['rowSpan' => 2],'表头字段2' => '','表头字段3' => ['colSpan' => 2],'表头字段4' => ['colSpan' => 0]]],
    ['表头字段1' => 'name3','表头字段2' => 'nick_name3','表头字段3' => 'phone3','表头字段4' => 'tel3', 
     '_cellProperties' => ['表头字段1' => ['rowSpan' => 0],'表头字段2' => '','表头字段3' => '','表头字段4' => '']],
  ];
 
  // 原始数据与导出数据的差异为 每一行数据的键名不同
  // 可以使用辅助函数 combineListWithNameMapping 将 $source_data 转为 $export_data
  
  $header_mapping = [
    'name' => '表头字段1',
    'nick_name' => '表头字段2',
    'phone' => '表头字段3',
    'tel' => '表头字段4',
  ];
  
  $export_data = $this->combineListWithNameMapping($source_data, $header_mapping);
  
  ```
  导出效果如图所示

  ![合并表格效果](https://user-images.githubusercontent.com/35066497/192722889-16e62813-7ca8-4f10-89aa-1efd8768f23e.png)

