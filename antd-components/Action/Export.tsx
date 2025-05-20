import {useContext, useState} from "react";
import {Button} from "antd";
import global from "@quansitech/antd-admin/dist/lib/global";
import ColModal from "./ColModal";
import ExportExcel from "./ExportExcel";
import {TableContext} from "@quansitech/antd-admin/dist//components/TableContext";

export default function (props: {
    props: any,
    title: string,
    export: {
        url: string,
        filename: string,
        pageNum: number,
        cols: any[],
    }
}) {
    let modal;

    const tableContext: any = useContext(TableContext)
    const [btnTitle, setBtnTitle] = useState(props.title)
    const [loading, setLoading] = useState(false)
    const [cols, setCols] = useState(props.export.cols.filter(v => v.default).map(v => v.key))

    const startExport = () => {
        let url: string | any[] = props.export.url;

        const pageNum = props.export.pageNum;
        const filename = props.export.filename;
        let query = tableContext?.getFormRef()?.getFieldsValue();
        for (const queryKey in query) {
            if (typeof query[queryKey] === "undefined" || query[queryKey] === null) {
                delete query[queryKey]
            }
        }
        query = (new URLSearchParams(query)).toString()

        if (typeof url === 'string') {
            url = [{sheetName: 'Sheet1', url: url}];
        }
        const ids = tableContext.getSelectedRows()?.map(row => {
            return row[tableContext.getTableProps().rowKey]
        })

        for (let i = 0; i < url.length; i++) {
            if (query) {
                url[i].url += url[i].url.indexOf('?') > 0 ? '&' + query : '?' + query;
            }
            if (ids) {
                url[i].url += url[i].url.indexOf('?') > 0 ? '&ids=' + ids : '?ids=' + ids;
            }
        }

        var opt = {
            'url': url,
            'fileName': filename,
            'before': function () {
                setLoading(true)
                setBtnTitle('导出中')
            },
            'reqType': 'post',
            'reqBody': {
                exportCol: cols
            },
            'after': function () {
                setLoading(false);
                setBtnTitle(props.title);
            },
            'progress': function (progress) {
                setBtnTitle(progress)
            },
            'error': function (info) {
                global.message.error({content: info});
                setLoading(false)
                setBtnTitle(props.title);
            }
        }
        const excel = new ExportExcel(opt);
        if (pageNum > 0) {
            excel.streamExport(pageNum);
        } else {
            excel.export();
        }
    }

    const onClick = () => {
        if (props.export.cols?.length) {
            modal = global.modal.confirm({
                title: '导出列筛选',
                width: 800,
                icon: null,
                content: <>
                    <ColModal setCols={setCols} colsOptions={props.export.cols}></ColModal>
                </>,
                onOk() {
                    modal.destroy()
                    startExport()
                },
            })
            return
        }

        startExport()

    }

    return <>
        <Button loading={loading} onClick={onClick} {...props.props}>{btnTitle}</Button>
    </>
}