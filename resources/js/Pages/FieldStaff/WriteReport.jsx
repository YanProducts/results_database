import useWriteReportActions from "../../Action/FieldStaffs/useWriteReportActions";
import useWriteReportDefinitions from "../../Definition/FieldStaffs/useWriteReportDefinitions";
import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";

import ReportDataInput from "../../Components/Part/FieldStaff/ReportDataInput";
import ReportConfirm from "../../Components/Part/FieldStaff/ReportConfirm";

export default function WriteReport({what,type,prefix,staff,dateSets,assignDataToStaff}){

    const {data, setData, post, processing, errors,clearErrors, reset,isConfirm,setIsConfirm,selectedDate,setSelectedDate,issuedCount,setIssuedCount,returnedCount,setReturnedCount,inputValues,setInputValues,inputRefs,pageMinWidth,pageMaxWidth}=useWriteReportDefinitions();

    const {onSelectedDateChange,onIssuedOrReturnedCountsChange,onAssignedInputChange,onSubmitBtnClick,onConfirmOkClick,onConfirmCancelClick}=useWriteReportActions({inputValues,setInputValues,inputRefs,selectedDate,setSelectedDate,setIssuedCount,setReturnedCount,setIsConfirm,setData,post});

    return(
    <Layout title={`${what}-${type}`}>
     <RoleLayout prefix={prefix}>
        {/* 確認か入力か */}
        {!isConfirm ?
        <ReportDataInput {...{what,type,pageMinWidth,pageMaxWidth,onSubmitBtnClick,selectedDate,onSelectedDateChange,issuedCount,returnedCount,onIssuedOrReturnedCountsChange,setIssuedCount,setReturnedCount,
        dateSets,assignDataToStaff,inputValues,inputRefs,onAssignedInputChange,errors,processing,isConfirm}} />
        :
        <ReportConfirm {...{what,type,pageMaxWidth,pageMinWidth,data,assignDataToStaff,selectedDate,inputRefs,inputValues,onAssignedInputChange,onConfirmOkClick,onConfirmCancelClick,errors,processing,isConfirm}}/>
        }
     </RoleLayout>
    </Layout>
    )
}
