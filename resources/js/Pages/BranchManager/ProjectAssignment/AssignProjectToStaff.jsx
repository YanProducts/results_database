import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import useAssignProjectToStaffDefinitions from "../../../Definition/BranchManager/ProjectAssingment/useAssignProjectToStaffDefinitions";
import useAssignProjectToStaffActions from "../../../Action/BranchManager/ProjectAssignment/useAssingProjectToStaffActions";
import DataInput from "../../../Components/Part/BranchManager/StaffAssign/DataInput";
import DataConfirm from "../../../Components/Part/BranchManager/StaffAssign/DataConfirm";

// 案件を営業所担当に送信
export default function AssingProjectToStaff({prefix,what,type,projectsAndTowns,dateSets,staffs,flash={}}){

  // 定義(フォームなど)
  const { data, setData, post, processing, errors, reset, assignPlan,setAssignPlan,isConfirm,setIsConfirm,duplicatedCheck,setDuplicatedCheck,selectedDate,setSelectedDate,selectedMainProject,setSelectedMainProject,needNumber,setNeedNumber,mapMeta,setMapMeta,assignPlanForConfirmView,setAssignPlanForConfirmView,pageMinWidth,pageMaxWidth}=useAssignProjectToStaffDefinitions();

  // 動き
  const {onSubmitBtnClick,onSelectedDateChange,onClickDateReset,onSelectedMainProjectChange,onChangeMapOrTown,handleAssignChangeInMaps,handleAssignChangeInTowns,onConfirmOkClick,onConfirmCancelClick}=useAssignProjectToStaffActions({dateSets,projectsAndTowns,staffs,assignPlan,setAssignPlan,selectedMainProject,setSelectedMainProject,needNumber,setNeedNumber,mapMeta,setMapMeta,selectedDate,setSelectedDate,isConfirm,setIsConfirm,setAssignPlanForConfirmView,setData,setDuplicatedCheck,flash});

  return(
    <Layout title={`${what}-${type}`}>
    <RoleLayout prefix={prefix}>

    {!isConfirm ?
    // データ入力用
    <DataInput {...{what,type,pageMinWidth,pageMaxWidth,onSubmitBtnClick,selectedDate,onSelectedDateChange,onClickDateReset,dateSets,selectedMainProject,onSelectedMainProjectChange,projectsAndTowns,onChangeMapOrTown,needNumber,mapMeta,staffs,handleAssignChangeInMaps,assignPlan,handleAssignChangeInTowns,errors,processing}}/>
    :
    // データ確認用
    <DataConfirm {...{what,type,pageMinWidth,pageMaxWidth,processing,selectedDate,onConfirmOkClick,onConfirmCancelClick,assignPlanForConfirmView,duplicatedCheck,flash}} />
    }

    {/* バリデーションエラー */}
    <ViewValidationErrors errors={errors} />


    {/* リンク */}
      <div className="mt-4">
        <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName="whole_data.logout"  what="ログアウト"/>
      </div>

      <p>　</p>

    </RoleLayout>
    </Layout>
  )
}

