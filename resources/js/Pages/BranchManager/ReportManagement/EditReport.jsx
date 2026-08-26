// 営業所担当における報告書の編集
// 幾つかのパーツは現場スタッフの報告書記入のところを使う
import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import ReportDataInput from "../../../Components/Part/FieldStaff/ReportDataInput"; //field_staffで使用している報告書記入のサンプル
import ReportConfirm from "../../../Components/Part/FieldStaff/ReportConfirm"; //field_staffで使用している報告書の確認
import useWriteOrEditReportViewData from "../../../Computed/Share/useWriteOrEditReportViewData"; //field_staffと共同で使用している
import WriteReportContext from "../../../Contexts/FieldStaffs/useWriteReportContexts";//field_staffで使用しているcontext
import useEditReportDefinitions from "../../../Definition/BranchManager/ReportManagement/useEditReportDefinitions";
import useEditReportActions from "../../../Action/BranchManager/ReportManagement/useEditReportActions";

export default function EditReport({what,type,prefix,staff,dateSet,assignWithRecords}){

    // assignWithRecordsキーのプロジェクト名はsameProjectFlagなども想定済みのもの
    const {data, setData, post, processing, errors,clearErrors, reset,isConfirm,setIsConfirm,issuedCount,setIssuedCount,returnedCount,setReturnedCount,inputValues,setInputValues,inputRefs,changedData,setChangedData,pageMinWidth,pageMaxWidth,isBigMedia,setIsBigMedia,date}=useEditReportDefinitions({staff,dateSet,assignWithRecords});

    const {onIssuedOrReturnedCountsChange,onAssignedInputChange,onInputKeyDown,onSetOtherProjectToSameValueClick,onSubmitBtnClick,onConfirmOkClick,onConfirmCancelClick}=useEditReportActions({assignWithRecords,inputValues,setInputValues,inputRefs,setChangedData,setIssuedCount,setReturnedCount,setIsConfirm,setData,post,isBigMedia,setIsBigMedia});

    //テーブルのUIや変数などに必要な要素の取得(依存配列が変化しなければ再計算されない)
    const [tableSets,differenceExists]=useWriteOrEditReportViewData({assignDataToStaff:assignWithRecords,selectedDate:date,inputValues,issuedCount,returnedCount,isBigMedia});

    return(
    <WriteReportContext.Provider value={{onSetOtherProjectToSameValueClick,isBigMedia,isEdit:true}}>

    <Layout title={`${what}-${type}`}>
     <RoleLayout prefix={prefix}>

        {/* 確認か入力か */}
        {!isConfirm ?
        <ReportDataInput {...{what,type,pageMinWidth,pageMaxWidth,staff,issuedCount,returnedCount,onIssuedOrReturnedCountsChange,setIssuedCount,setReturnedCount,
        dateSet,assignDataToStaff:assignWithRecords,inputValues,inputRefs,onAssignedInputChange,onInputKeyDown,tableSets,onSubmitBtnClick,differenceExists,errors,processing,isConfirm,changedData,fromSimpleFlag:false,selectedDate:date,onSelectedDateChange:()=>{},onStartOverClick:()=>{}}} />
        :
        <ReportConfirm {...{what,type,pageMaxWidth,pageMinWidth,data,assignWithRecords,issuedCount,returnedCount,inputRefs,inputValues,onAssignedInputChange,onConfirmOkClick,onConfirmCancelClick,tableSets,errors,processing,isConfirm,fromSimpleFlag}}/>
        }
    {/* リンク */}
      <div className="mt-1">
            {/* 営業所担当のトップへ */}
        <BaseLinkLine routeName={`${prefix}.top_page`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="営業所担当のトップ"/>
        <BaseLinkLine routeName={`${prefix}.logout`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="ログアウト"/>
      </div>

      <p>　</p>

     </RoleLayout>
    </Layout>
  </WriteReportContext.Provider>
    )
}
