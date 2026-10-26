// 簡易版(mapのみから選択)
import useSimpleAssignProjectToStaffDefinitions from "../../../Definition/BranchManager/ProjectAssingment/useSimpleAssignProjectToStaffDefinitions";
import useSimpleAssignProjectToStaffActions from "../../../Action/BranchManager/ProjectAssignment/useSimpleAssingProjectToStaffActions";
import AssignProjectToStaffContext from "../../../Contexts/BranchManager/useAssignProjectToStaffContexts";
import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import DataInputForSimple from "../../../Components/Part/BranchManager/StaffAssign/PartTop/DataInputForSimple";
import DataConfirmForSimple from "../../../Components/Part/BranchManager/StaffAssign/PartTop/DataConfirmForSimple";


// 案件を営業所担当に送信
export default function SimpleAssingProjectToStaff({prefix,what,type,dateSets,dateProjectsIndex,staffs,planIdsAndMapsByMainProjects,assignedDays,flash=""}){


  // 定義(フォームなど)
  const {data, setData, post, processing, errors,clearErrors, reset,assignPlan,setAssignPlan,isConfirm,setIsConfirm,duplicatedCheck,setDuplicatedCheck,selectedDate,setSelectedDate,nonDataInTheDayFlag,setNonDataInTheDayFlag,choicedMap,setChoicedMap,staffInChoice,setStaffInChoice,popUpVisible,setPopUpVisible,choicedByProjects,setChoicedByProjects,showAllTowns,setShowAllTowns,pageMinWidth,pageMaxWidth}=useSimpleAssignProjectToStaffDefinitions();

  // 動き
  const {onSelectedDateChange,onClickDateReset,onMapChoiceClick,onMapDecide,onMapChoiceClose,onShowAllTownsClick, onCloseAllTownsClick,onSubmitBtnClick,onConfirmOkClick,onConfirmCancelClick,onMovePageClick}=useSimpleAssignProjectToStaffActions({data,setData,post,clearErrors,staffs,dateProjectsIndex,isConfirm,setIsConfirm,selectedDate,setSelectedDate,setNonDataInTheDayFlag,choicedMap,setChoicedMap,choicedByProjects,setChoicedByProjects,planIdsAndMapsByMainProjects,staffInChoice,setStaffInChoice,setShowAllTowns,setPopUpVisible});

    return(
       <AssignProjectToStaffContext.Provider value={{showAllTowns,onShowAllTownsClick,onCloseAllTownsClick,pageMaxWidth,pageMinWidth}}>
        <Layout title={`${what}-${type}`}>
            <RoleLayout  prefix={prefix} >

            {!isConfirm ?
            // データ入力用
            <DataInputForSimple {...{what,type,pageMinWidth,pageMaxWidth,onSubmitBtnClick,selectedDate,nonDataInTheDayFlag,onSelectedDateChange,onClickDateReset,dateSets,assignedDays,onMapChoiceClick,onMapDecide,onMapChoiceClose,planIdsAndMapsByMainProjects,dateProjectsIndex,staffs,staffInChoice,popUpVisible,choicedMap,processing}}/>
            :
            // データ確認用
            <DataConfirmForSimple {...{what,type,errors,pageMinWidth,pageMaxWidth,processing,selectedDate,staffs,choicedMap,choicedByProjects,onConfirmOkClick,onConfirmCancelClick,flash}}/>
            }


            {/* リンク */}
          <div className="mt-4">
            <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.top_page`}  what="営業所担当のトップ"/>
            <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.assign_staff`}  what="町目版" needAlert={true} needAlertFunc={onMovePageClick}/>
            <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.project_only_assign_staff`}  what="案件版" needAlert={true} needAlertFunc={onMovePageClick}/>
            <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.logout`}  what="ログアウト"/>
          </div>
          <p>　</p>
        </RoleLayout>
    </Layout>
    </AssignProjectToStaffContext.Provider>
    )
}
