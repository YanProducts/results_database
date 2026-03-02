import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import InputPageHeader from "../../../Components/Common/InputPageHeader";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";
import BaseButton from "../../../Components/Common/BaseButton";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import useAssignProjectToStaffDefinitions from "../../../Definition/BranchManager/ProjectAssingment/useAssignProjectToStaffDefinitions";
import useAssignProjectToStaffActions from "../../../Action/BranchManager/ProjectAssignment/useAssingProjectToStaffActions";

// 案件を営業所担当に送信
export default function AssingProjectToStaff({prefix,what,type}){

  // 定義(フォームなど)
  const { data, setData, post, processing, errors, reset}=useAssignProjectToStaffDefinitions();

  // 動き
  const {onPlaceChange,onSubmitBtnClick}=useAssignProjectToStaffActions();

  return(
    <Layout title={`${what}-${type}`}>
    <RoleLayout prefix={prefix}>

    {/* タイトル */}
    <InputPageHeader what={what} type={type} inputWhat="日付"/>

    {/* 投稿フォーム */}
    <form onSubmit={onSubmitBtnClick}>
             <div className="base_frame min-w-80 max-w-100 base_backColor md:p-3 sm:p-2 p-0 border-2 border-black rounded-sm mb-5">

                {/* 日付 */}
                {/* <SelectParts name="places" value={data.place} onChange={onPlaceChange} prefix={"期限："} keyValueSets={placeSets} allowEmptyOption={false}/> */}

                {/* メイン案件名 */}
                {/* <SelectParts name="places" value={data.place} onChange={onPlaceChange} prefix={"期限："} keyValueSets={placeSets} allowEmptyOption={false}/> */}

                {/* 町名(textareaを設定して貼り付ける形にする)とスタッフ */}
                {/* 併配リスト(textareaを設定して○✖️を貼り付ける形にする) */}
                {/* {data.role=="field_staff" &&
                <InputParts type="text" name="staffName" value={data.staffName} onChange={onStaffNameChange} prefix={"スタッフ名："}/>} */}


              </div>

      {/* バリデーションエラー */}
      <ViewValidationErrors errors={errors} />

      {/* 提出ボタン */}
      <BaseButton processing={processing}/>
    </form>
    {/* リンク */}
      <div className="mt-4">
        <BaseLinkLine routeName="whole_data.logout"  what="ログアウト"/>
      </div>
    </RoleLayout>
    </Layout>
  )
}

