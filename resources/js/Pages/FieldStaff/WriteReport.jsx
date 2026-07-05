import useWriteReportActions from "../../Action/FieldStaffs/useWriteReportActions";
import useWriteReportDefinitions from "../../Definition/FieldStaffs/useWriteReportDefinitions";
import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";
import ReportDataInput from "../../Components/Part/FieldStaff/ReportDataInput";
import ReportConfirm from "../../Components/Part/FieldStaff/ReportConfirm";
import useWriteReportViewData from "../../Computed/FieldStaffs/useWriteReportViewData";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";

export default function WriteReport({what,type,prefix,staff,dateSets,assignDataToStaff,fromSimpleFlag}){

    // assignDataToStaffキーのプロジェクト名はsameProjectFlagなども想定済みのもの
    const {data, setData, post, processing, errors,clearErrors, reset,isConfirm,setIsConfirm,selectedDate,setSelectedDate,issuedCount,setIssuedCount,returnedCount,setReturnedCount,inputValues,setInputValues,inputRefs,pageMinWidth,pageMaxWidth}=useWriteReportDefinitions();

    const {onSelectedDateChange,onIssuedOrReturnedCountsChange,onAssignedInputChange,onInputKeyDown,onSubmitBtnClick,onStartOverClick,onConfirmOkClick,onConfirmCancelClick}=useWriteReportActions({inputValues,setInputValues,inputRefs,selectedDate,setSelectedDate,setIssuedCount,setReturnedCount,setIsConfirm,setData,post});

    //テーブルのUIや変数などに必要な要素の取得(依存配列が変化しなければ再計算されない)
    const [tableSets,differenceExists]=useWriteReportViewData({assignDataToStaff,selectedDate,inputValues,issuedCount,returnedCount});

    return(
    <Layout title={`${what}-${type}`}>
     <RoleLayout prefix={prefix}>

        {/* 確認か入力か */}
        {!isConfirm ?
        <ReportDataInput {...{what,type,pageMinWidth,pageMaxWidth,staff,selectedDate,onSelectedDateChange,issuedCount,returnedCount,onIssuedOrReturnedCountsChange,setIssuedCount,setReturnedCount,
        dateSets,assignDataToStaff,inputValues,inputRefs,onAssignedInputChange,onInputKeyDown,tableSets,onSubmitBtnClick,onStartOverClick,differenceExists,errors,processing,isConfirm,fromSimpleFlag}} />
        :
        <ReportConfirm {...{what,type,pageMaxWidth,pageMinWidth,data,assignDataToStaff,selectedDate,issuedCount,returnedCount,inputRefs,inputValues,onAssignedInputChange,onConfirmOkClick,onConfirmCancelClick,tableSets,errors,processing,isConfirm,fromSimpleFlag}}/>
        }
    {/* リンク */}
      <div className="mt-1">
        <BaseLinkLine routeName={`${prefix}.overview_reports`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="過去の報告書の確認/編集"/>
        <BaseLinkLine routeName={`${prefix}.logout`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="ログアウト"/>
      </div>

      <p>　</p>

     </RoleLayout>
    </Layout>
    )
}
