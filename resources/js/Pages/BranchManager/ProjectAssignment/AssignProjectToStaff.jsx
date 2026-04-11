import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import useAssignProjectToStaffDefinitions from "../../../Definition/BranchManager/ProjectAssingment/useAssignProjectToStaffDefinitions";
import useAssignProjectToStaffActions from "../../../Action/BranchManager/ProjectAssignment/useAssingProjectToStaffActions";
import DataInput from "../../../Components/Part/BranchManager/StaffAssign/DataInput";
import DataConfirm from "../../../Components/Part/BranchManager/StaffAssign/DataConfrm";

// 案件を営業所担当に送信
export default function AssingProjectToStaff({prefix,what,type,projectsAndTowns,dateSets,staffs}){

  // 定義(フォームなど)
  const { data, setData, post, processing, errors, reset, assignPlan,setAssignPlan,isConfirm,setIsConfirm,selectedDate,setSelectedDate,selectedMainProject,setSelectedMainProject,needNumber,setNeedNumber,selectedMapNumber,setSelectedMapNumber,townAssign,setTownAssign,pageMinWidth,pageMaxWidth}=useAssignProjectToStaffDefinitions();

  // 動き
  const {onSubmitBtnClick,onSelectedDateChange,onSelectedMainProjectChange,onChangeMapOrTown,handleAssignChangeInMaps,handleAssignChangeInTowns,onConfirmOkClick,onConfirmCancelClick}=useAssignProjectToStaffActions(dateSets,projectsAndTowns,assignPlan,setAssignPlan,selectedMainProject,setSelectedMainProject,needNumber,setNeedNumber,selectedMapNumber,setSelectedMapNumber,setSelectedDate,setIsConfirm);

  {console.log(isConfirm)}

  return(
    <Layout title={`${what}-${type}`}>
    <RoleLayout prefix={prefix}>

    {!isConfirm ?
    // データ入力用
    <DataInput {...{what,type,pageMinWidth,pageMaxWidth,onSubmitBtnClick,selectedDate,onSelectedDateChange,dateSets,selectedMainProject,onSelectedMainProjectChange,projectsAndTowns,onChangeMapOrTown,needNumber,selectedMapNumber,staffs,handleAssignChangeInMaps,assignPlan,handleAssignChangeInTowns,errors,processing}}/>
    :
    <DataConfirm {...{what,type,pageMinWidth,pageMaxWidth,assignPlan,staffs,processing,onConfirmOkClick,onConfirmCancelClick}} />
    }

    {/* リンク */}
      <div className="mt-4">
        <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName="whole_data.logout"  what="ログアウト"/>
      </div>

    </RoleLayout>
    </Layout>
  )
}

