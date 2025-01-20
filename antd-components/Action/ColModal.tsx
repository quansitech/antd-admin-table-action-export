import {Checkbox} from 'antd'

export default function (props: {
    colsOptions: any[],
    setCols: (cols: any) => void
}) {
    return <>
        <Checkbox.Group style={{width: '100%'}}
                        options={props.colsOptions.map(v => {
                            return {
                                label: v.title,
                                value: v.key,
                                disabled: v.required,
                            }
                        })}
                        defaultValue={props.colsOptions.filter(v => v.default).map(v => v.key)}
                        onChange={(cols) => {
                            props.setCols(cols)
                        }}
        />
    </>
}