// 簡易版(mapのみから選択)
import useSimpleAssignProjectToStaffDefinitions from "../../../Definition/BranchManager/ProjectAssingment/useSimpleAssignProjectToStaffDefinitions";
import useSimpleAssignProjectToStaffActions from "../../../Action/BranchManager/ProjectAssignment/useSimpleAssingProjectToStaffActions";
import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import DataInputForSimple from "../../../Components/Part/BranchManager/StaffAssign/DataInputForSimple";
import DataConfirmForSimple from "../../../Components/Part/BranchManager/StaffAssign/DataConfirmForSimple";


// 案件を営業所担当に送信
export default function SimpleAssingProjectToStaff({prefix,what,type,dateSets,dateProjectsIndex,staffs,planIdsAndMapsByMainProjects}){

  // 定義(フォームなど)
  const {data, setData, post, processing, errors,clearErrors, reset,assignPlan,setAssignPlan,isConfirm,setIsConfirm,duplicatedCheck,setDuplicatedCheck,selectedDate,setSelectedDate,choicedMap,setChoicedMap,staffInChoice,setStaffInChoice,popUpVisible,setPopUpVisible,choicedByProjects,setChoicedByProjects,pageMinWidth,pageMaxWidth}=useSimpleAssignProjectToStaffDefinitions();

  // 動き
  const {onSelectedDateChange,onClickDateReset,onMapChoiceClick,onMapDecide,onMapChoiceClose,onSubmitBtnClick,onConfirmOkClick,onConfirmCancelClick,}=useSimpleAssignProjectToStaffActions({data,setData,post,clearErrors,staffs,isConfirm,setIsConfirm,selectedDate,setSelectedDate,choicedMap,setChoicedMap,choicedByProjects,setChoicedByProjects,planIdsAndMapsByMainProjects,staffInChoice,setStaffInChoice,setPopUpVisible});


    return(
        <Layout title={`${what}-${type}`}>
            <RoleLayout  prefix={prefix} >
    {!isConfirm ?
    // データ入力用
    <DataInputForSimple {...{what,type,pageMinWidth,pageMaxWidth,onSubmitBtnClick,selectedDate,onSelectedDateChange,onClickDateReset,dateSets,onMapChoiceClick,onMapDecide,onMapChoiceClose,planIdsAndMapsByMainProjects,dateProjectsIndex,staffs,staffInChoice,popUpVisible,choicedMap,processing}}/>
    :
    // データ確認用
    <DataConfirmForSimple {...{what,type,errors,pageMinWidth,pageMaxWidth,processing,selectedDate,staffs,choicedMap,choicedByProjects,onConfirmOkClick,onConfirmCancelClick}}/>
    }

            {/* リンク */}
            <div className="mt-4">
            <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.assign_staff`}  what="割当の確認(未)"/>
            </div>
            <div className="mt-4">
            <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.assign_staff`}  what="案件の編集(未)"/>
            </div>

            <div className="mt-4">
            <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.assign_staff`}  what="詳細版"/>
            </div>
            <div className="mt-4">
            <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.logout`}  what="ログアウト"/>
            </div>
            <p>　</p>
        </RoleLayout>
    </Layout>
    )
}
