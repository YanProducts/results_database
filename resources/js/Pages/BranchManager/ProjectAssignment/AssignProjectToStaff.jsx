import Layout from "../../../Layout/Layout";
import InputPageHeader from "../../../Components/Common/InputPageHeader";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";
import BaseButton from "../../../Components/Common/BaseButton";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import useAssignProjectToStaffDefinitions from "../../../Definition/BranchManager/ProjectAssingment/useAssignProjectToStaffDefinitions";
import useAssignProjectToStaffActions from "../../../Action/BranchManager/ProjectAssignment/useAssingProjectToStaffActions";

// 案件を営業所担当に送信
export default function AssingProjectToStaff({placeSets,what,type}){

  // 定義(フォームなど)
  const { data, setData, post, processing, errors, reset}=useAssignProjectToStaffDefinitions();

  // 動き
  const {onPlaceChange,onSubmitBtnClick}=useAssignProjectToStaffActions();

  return(
    <Layout title={`${what}-${type}`}>
     <div className="h-full min-h-screen bg-orange-200">

    {/* タイトル */}
    <InputPageHeader what={what} type={type} inputWhat="下記"/>

    {/* 投稿フォーム */}
    <form onSubmit={onSubmitBtnClick}>
             <div className="base_frame min-w-80 max-w-100 base_backColor md:p-3 sm:p-2 p-0 border-2 border-black rounded-sm mb-5">

                {/* 案件名 */}
                {/* 連続したInputの箱を作成する */}
                {/* 順番を間違えないようにすること */}
                {/* 英語２文字ー数字の形式を厳守 */}
                {/* <InputParts type="text" name="projectName" value="" onChange="" prefix="案件名："/> */}

                {/* 期限 */}
                {/* 2つのselectboxを作成する */}
                {/* <SelectParts name="places" value={data.place} onChange={onPlaceChange} prefix={"期限："} keyValueSets={placeSets} allowEmptyOption={false}/> */}

                {/* 町名(textareaを設定して貼り付ける形にする) */}
                {/* {data.role=="field_staff" &&
                <InputParts type="text" name="staffName" value={data.staffName} onChange={onStaffNameChange} prefix={"スタッフ名："}/>} */}

                {/* 併配リスト(textareaを設定して○✖️を貼り付ける形にする) */}

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
    </div>
    </Layout>
  )
}

