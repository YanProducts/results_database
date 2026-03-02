import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import InputPageHeader from "../../../Components/Common/InputPageHeader";
import SelectParts from "../../../Components/Common/SelectParts";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";
import BaseButton from "../../../Components/Common/BaseButton";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import useSendProjectDefinitions from "../../../Definition/ProjectOperator/useSendProjectDefinitions";
import useSendProjectActions from "../../../Action/ProjectOperator/useSendProjectActions";
import DoubleSelectParts from "../../../Components/Common/DoubleSelectParts";
import InputFiles from "../../../Components/Common/InputFiles";

// 案件を営業所担当に送信
export default function SendProjectToBranch({prefix,what,type,startDateLists,endDateLists,placeSets}){


  // 定義(フォームなど)
  const { data, setData, post, processing, errors, reset}=useSendProjectDefinitions();

  // 動き
  const {onStartDateChange,onEndDateChange,onPlaceChange,onFileChange,onFileDeleteClick,onSubmitBtnClick}=useSendProjectActions(data,setData);

  return(
    <Layout title={`${what}-${type}`}>
        <RoleLayout prefix={prefix}>


    {/* 投稿フォーム */}
    <form onSubmit={onSubmitBtnClick}>
            {/* 選択項目 */}
            <InputPageHeader what={what} type={type} specialMessage="以下を選択してください"/>

             <div className="base_frame min-w-110 max-w-160 base_backColor p-0 border-2 border-black rounded-sm mb-5">

            {/* 期限 */}
            {/* 2つのselectboxを作成する(~50日後まで) */}
            {/* 現段階ではファイルから変換 */}
            {/* <DoubleSelectParts name1="startDate" name2="endDate" value1={data.startDate} value2={data.endDate} onChange1={onStartDateChange} onChange2={onEndDateChange} prefix={"期限："} keyValueSets1={startDateLists} keyValueSets2={endDateLists} /> */}

            {/* 営業所名 */}
            <SelectParts name="place" value={data.place} onChange={onPlaceChange} prefix={"営業所名："} keyValueSets={placeSets} allowEmptyOption={false}/>

            {/* ファイル */}
            <InputFiles name="fileSets"
            onChange={onFileChange} prefix="案件CSV(複数可)："
            attributes={{multiple:true}}
            data={data}
            onFileDeleteClick={onFileDeleteClick}
            />

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

