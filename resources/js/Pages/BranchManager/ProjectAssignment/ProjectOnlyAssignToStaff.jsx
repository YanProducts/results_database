import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import useProjectOnlyAssignToStaffDefinitions from "../../../Definition/BranchManager/ProjectAssignment/useProjectOnlyAssignToStaffDefinitions";
import useProjectOnlyAssignToStaffActions from "../../../Action/BranchManager/ProjectAssignment/useProjectOnlyAssignToStaffActions";
import checkProjectOnlyAssignToStaffProps from "../../../Support/ConfirmProps/BranchManager/ProjectAssignment/checkProjectOnlyAssignToStaffProps";
import DataInputForProjectOnly from "../../../Components/Part/BranchManager/StaffAssign/PartTop/DataInputForProjectOnly";
import AssignProjectToStaffContext from "../../../Contexts/BranchManager/useAssignProjectToStaffContexts";
import DataConfirmForProjectOnly from "../../../Components/Part/BranchManager/StaffAssign/PartTop/DataConfirmForProjectOnly";

export default function ProjectOnlyAssignToStaff({ prefix, what, type,dateSets,dateProjectsIndex,staffs,assignedDays,flash=null }) {

    //dataProjectIndexには[$date]=>{[idという文字列+project_planned_countのid]project_plan_id,project_name_for_view,address_setsで格納}
    // 案件の識別はidでできており、view用の名称も既に格納済み

    // propsの確認
    // checkProjectOnlyAssignToStaffProps({})

    // 定義セット
    const {data, setData, post, processing, errors,clearErrors, reset,validationHidden,setValidationHidden,isConfirm,setIsConfirm,selectedDate,setSelectedDate,nonDataInTheDayFlag,setNonDataInTheDayFlag,staffInChoice,setStaffInChoice,choicedProjects,setChoicedProjects,showAllTowns,setShowAllTowns,popUpVisible,setPopUpVisible,pageMinWidth,pageMaxWidth} = useProjectOnlyAssignToStaffDefinitions({});

    // 動き
    const {onSelectedDateChange,onClickDateReset,onProjectChoiceClick,onProjectDecide,onProjectChoiceClose,onShowAllTownsClick,onCloseAllTownsClick,onSubmitBtnClick,onConfirmOkClick,onConfirmCancelClick,onMovePageClick} = useProjectOnlyAssignToStaffActions({data,setData,post,errors,processing,clearErrors,setValidationHidden,dateProjectsIndex,selectedDate,setSelectedDate,setNonDataInTheDayFlag,setShowAllTowns,staffInChoice,setStaffInChoice,choicedProjects,setChoicedProjects,setPopUpVisible,setIsConfirm});

    return (
      <AssignProjectToStaffContext.Provider value={{showAllTowns,onShowAllTownsClick,onCloseAllTownsClick,dateProjectsIndex}}>
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>

                {/* ページ内容 */}

            {!isConfirm ?
            // データ入力用
            <DataInputForProjectOnly {...{what,type,pageMinWidth,pageMaxWidth,onSubmitBtnClick,selectedDate,onSelectedDateChange,nonDataInTheDayFlag,onClickDateReset,dateSets,assignedDays,
            onProjectChoiceClick,onProjectDecide,onProjectChoiceClose,dateProjectsIndex,staffs,staffInChoice,popUpVisible,choicedProjects,processing}}/>
            :
            // データ確認用
            <DataConfirmForProjectOnly {...{what,type,errors,pageMinWidth,pageMaxWidth,processing,selectedDate,staffs,choicedProjects,onConfirmOkClick,onConfirmCancelClick,flash}}/>
            }


                {/* リンク */}
                <div className="mt-1">
                    <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.top_page`}  what="営業所担当のトップ"/>
                    <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.assign_staff`}  what="町目版" needAlert={true} needAlertFunc={onMovePageClick}/>
                    <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.simple_assign_staff`}  what="地図版" needAlert={true} needAlertFunc={onMovePageClick}/>
                    <BaseLinkLine
                        routeName={`${prefix}.logout`}
                        what="ログアウト"
                    />
                </div>

                <p>　</p>
            </RoleLayout>
        </Layout>
     </AssignProjectToStaffContext.Provider>
    );
}
