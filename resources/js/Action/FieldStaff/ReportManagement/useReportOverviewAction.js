import { router } from "@inertiajs/react"
import useDisappearValidation from "../../Share/useDisappearValidation";

// 報告書一覧における動きの定義
export default function useReportOverviewAction({errors,setValidationHidden}){
    // バリデーションを見えなくする(errorsがformではないので、見せなくする方法がベター)
    useDisappearValidation({errors,setValidationHidden})

    const onDecideReport=({date})=>{
        // 現段階でdateは必ずtodayなので渡す必要はないが、今後の変化を見据えて渡す
        router.visit(route("field_staff.edit_report",{date:date}));
    }
    return { onDecideReport }
}
