import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import useAssignProjectToStaffDefinitions from "../../../Definition/BranchManager/ProjectAssingment/useAssignProjectToStaffDefinitions";
import useAssignProjectToStaffActions from "../../../Action/BranchManager/ProjectAssignment/useAssingProjectToStaffActions";
import DataInput from "../../../Components/Part/BranchManager/StaffAssign//PartTop/DataInput";
import DataConfirm from "../../../Components/Part/BranchManager/StaffAssign/PartTop/DataConfirm";
import AssignProjectToStaffContext from "../../../Contexts/BranchManager/useAssignProjectToStaffContexts";


// 案件を営業所担当に送信
export default function AssingProjectToStaff({prefix,what,type,projectsAndTowns,dateSets,dateProjectsIndex,assignedDays,staffs,flash={}}){

  // 定義(フォームなど)
  const { data, setData, post, processing, errors,clearErrors, reset, assignPlan,setAssignPlan,isConfirm,setIsConfirm,duplicatedCheck,setDuplicatedCheck,selectedDate,setSelectedDate,selectedMainProject,setSelectedMainProject,needNumber,setNeedNumber,mapMeta,setMapMeta,assignPlanForConfirmView,setAssignPlanForConfirmView,showAllTowns,setShowAllTowns,pageMinWidth,pageMaxWidth}=useAssignProjectToStaffDefinitions();

  // 動き
  const {onSubmitBtnClick,onSelectedDateChange,onClickDateReset,onSelectedMainProjectChange,onChangeMapOrTown,onShowAllTownsClick, onCloseAllTownsClick,handleAssignChangeInMaps,handleAssignChangeInTowns,onConfirmOkClick,onConfirmCancelClick,onDuplicatedOkClick,onMovePageClick}=useAssignProjectToStaffActions({data,post,clearErrors,projectsAndTowns,staffs,assignPlan,setAssignPlan,selectedMainProject,setSelectedMainProject,needNumber,setNeedNumber,mapMeta,setMapMeta,selectedDate,setSelectedDate,isConfirm,setIsConfirm,setShowAllTowns,setAssignPlanForConfirmView,setData,duplicatedCheck,setDuplicatedCheck,flash});



  return(
    <AssignProjectToStaffContext.Provider value={{showAllTowns,onShowAllTownsClick,onCloseAllTownsClick}}>
    <Layout title={`${what}-${type}`}>
    <RoleLayout prefix={prefix}>

    {!isConfirm ?
    // データ入力用
    <DataInput {...{what,type,pageMinWidth,pageMaxWidth,onSubmitBtnClick,selectedDate,onSelectedDateChange,onClickDateReset,dateSets,assignedDays,selectedMainProject,onSelectedMainProjectChange,projectsAndTowns,dateProjectsIndex,onChangeMapOrTown,needNumber,mapMeta,staffs,handleAssignChangeInMaps,assignPlan,handleAssignChangeInTowns,processing}}/>
    :
    // データ確認用
    <DataConfirm {...{what,type,errors,pageMinWidth,pageMaxWidth,processing,selectedDate,onConfirmOkClick,onConfirmCancelClick,assignPlanForConfirmView,duplicatedCheck,flash,onDuplicatedOkClick}} />
    }

    {/* リンク */}
    <div className="mt-4">
    <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.top_page`}  what="営業所担当のトップ"/>
    <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.project_only_assign_staff`}  what="案件版" needAlert={true} needAlertFunc={onMovePageClick}/>
    <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.simple_assign_staff`}  what="地図版" needAlert={true} needAlertFunc={onMovePageClick}/>
    <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.logout`}  what="ログアウト"/>
    </div>

      <p>　</p>

    </RoleLayout>
    </Layout>
    </AssignProjectToStaffContext.Provider>
  )
}

