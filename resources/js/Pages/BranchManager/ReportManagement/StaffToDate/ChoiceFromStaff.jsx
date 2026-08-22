// 報告書の確認をスタッフから選択する場合
import useChoiceFromStaffActions from "../../../../Action/BranchManager/ReportManagement/StaffToDate/useChoiceFromStaffActions";
import BaseButton from "../../../../Components/Common/BaseButton";
import BasePageHeader from "../../../../Components/Common/BasePageHeader";
import ToggleLists from "../../../../Components/Common/ToggleLists";
import ViewValidationErrors from "../../../../Components/Common/ViewValidationErrors";
import useChoiceFromStaffDefinitions from "../../../../Definition/BranchManager/ReportManagement/StaffToDate/useChoiceFromStaffDefinitions";
import Layout from "../../../../Layout/Layout";
import { RoleLayout } from "../../../../Layout/RoleLayout";
import BaseLinkLine from "../../../../Components/Common/BaseLinkLine";

export default function ChoiceFromStaff({what,type,prefix,staffs}){
    // staffsにはidとnameForUIが入っている

    const {data,setData,post,processing, errors,clearErrors, reset, selectedStaffs,setSelectedStaffs,pageMinWidth,pageMaxWidth}=useChoiceFromStaffDefinitions({});

    const {onStaffListsChange,onSubmitBtnClick}=useChoiceFromStaffActions({data,setData,selectedStaffs,setSelectedStaffs,post});


    return(
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>
                <BasePageHeader {...{what,type,pageMinWidth,pageMaxWidth,"subtitle":"スタッフを選択してください"}}/>
                {/* バリデーションエラー */}
                <ViewValidationErrors errors={errors} />
                <form onSubmit={onSubmitBtnClick} className={`base_frame ${pageMaxWidth} ${pageMinWidth} base_backColor`}>
                 <p>　</p>
                 <ToggleLists contents={staffs} formLists={selectedStaffs} onToggleListsChange={onStaffListsChange} onlyClick={true} />
                 <p>　</p>
                 <BaseButton {...{processing,minWidth:pageMinWidth,maxWidth:pageMaxWidth}} />
                 <p>　</p>
                </form>

                <p>　</p>

                {/* リンク */}
                <div className="mt-1">
                        {/* 営業所担当のトップへ */}
                        <BaseLinkLine routeName={`${prefix}.top_page`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="営業所担当のトップ"/>

                        {/* 以前の報告書 */}

                        {/* ログアウト */}
                        <BaseLinkLine routeName={`${prefix}.logout`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="ログアウト"/>
                </div>
            </RoleLayout>
        </Layout>
    )
}
