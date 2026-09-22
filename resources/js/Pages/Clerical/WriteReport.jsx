import Layout from "../../Layout/Layout"
import { RoleLayout } from "../../Layout/RoleLayout"
import BaseLinkLine from "../../Components/Common/BaseLinkLine";
import useWriteReportDefinitions from "../../Definition/Clerical/useWriteReportDefinitions";
import useWriteReportActions from "../../Action/Clerical/useWriteReportActions";
import ReportInput from "../../Components/Part/Clerical/WriteReport/ReportInput";
import ViewAttentions from "../../Components/Common/ViewAttentions";
import ReportConfirm from "../../Components/Part/Clerical/WriteReport/ReportConfirm";

// 入力担当が報告書を入力するとき
export default function WriteReport({prefix,what,type,projectSets,reportDataInTheProject,totalPlannedCount}){

    // 案件ごと＝併配がないので、基本的にはclerical独自で考える

    // 定義セット
    const {data, setData, post, processing, errors,clearErrors, reset,dataInReport,setDataInReport,changedId,setChangedId,firstAttention,setFirstAttention,validationHidden,setValidationHidden,isConfirm,setIsConfirm,pageMinWidth,pageMaxWidth}=useWriteReportDefinitions({projectId:Object.keys(projectSets)[0],reportDataInTheProject});

    const {onReportCountChange,onConfirmButtonClick,onStartOverClick,onConfirmCancelClick,onConfirmOKClick}=useWriteReportActions({firstAttention,setFirstAttention,validationHidden,setValidationHidden,errors,isConfirm,setIsConfirm,data,setData,post,dataInReport,setDataInReport,changedId,setChangedId,reportDataInTheProject});

    return(
        <Layout title={`${what}-${type}`}>
          <RoleLayout prefix={prefix}>



            {/* 報告書のテーブル(案件は1つ) */}
            {!isConfirm ?
            <ReportInput {...{what,type,pageMinWidth,pageMaxWidth,validationHidden,errors,projectSets,reportDataInTheProject,changedId,dataInReport,onReportCountChange,onConfirmButtonClick,onStartOverClick,totalPlannedCount,processing}} />

            :
            // 確認
            <ReportConfirm {...{what,type,processing,pageMinWidth,pageMaxWidth,projectSets,changedId,dataInReport,reportDataInTheProject,onConfirmOKClick,onConfirmCancelClick}}/>
            }

            {/* 注意書き */}
            <ViewAttentions {...{minWidth:pageMinWidth,maxWidth:pageMaxWidth,mt:"mt-2","mb":"mb-2",message:"スタッフと日付を指定する場合は\n個々人の報告書編集より行なってください", plusCss:`whitespace-pre-wrap  ${!firstAttention &&  "hidden"} `}} />

            {/* リンク */}
            <div className="mt-1">
                <BaseLinkLine routeName={`${prefix}.top_page`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="入力担当のトップ"/>
                <BaseLinkLine routeName={`${prefix}.logout`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="ログアウト"/>
            </div>

            <p>　</p>
          </RoleLayout>
        </Layout>
    )


}
