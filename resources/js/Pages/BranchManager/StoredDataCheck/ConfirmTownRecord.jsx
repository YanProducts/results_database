import useConfirmTownRecordActions from "../../../Action/BranchManager/ConfirmRecord/useConfirmTownRecordActions";
import useConfirmTownRecordDefinitions from "../../../Definition/BranchManager/ConfirmRecord/useConfirmTownRecordDefinitions";
import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BasePageHeader from "../../../Components/Common/BasePageHeader";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import SelectParts from "../../../Components/Common/SelectParts";
import DoubleSelectParts from "../../../Components/Common/DoubleSelectParts";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";
import BaseButton from "../../../Components/Common/BaseButton";
import FlatToggleLists from "../../../Components/Common/FlatToggleLists";
import UserChoisePart from "../../../Components/Part/BranchManager/ConfirmRecord/UserChoicePart";
import AddressPartWhich from "../../../Components/Part/BranchManager/ConfirmRecord/AddressPartWhich";


// 町々目データの確認に向けた選択画面
export default function ConfirmTownRecord({prefix,what,type,staffLists,allTownSets}){

    // 定義
    const {data, setData, post, processing, errors,clearErrors, reset,selectedStaffs,setSelectedStaffs,dateLists,selectedStartYear,setSelectedStartYear,selectedEndYear,setSelectedEndYear,townChoiceMode,setTownChoiceMode,prefBySelect,setPrefBySelect,cityBySelect,setCityBySelect,townBySelect,setTownBySelect,townDataByList,setTownDataByList,pageMinWidth,pageMaxWidth}=useConfirmTownRecordDefinitions();

    // 動き
    const {onSelectedStaffsChange,onSelectedStartYearChange,onSelectedEndYearChange,onTwonChoiceModeChangeClick,onPrefChange,onCityChange,onTownChange,onAddressListsChange,onDecideSearhDataClick}=useConfirmTownRecordActions({staffLists,setData,post,selectedStaffs,setSelectedStaffs,setSelectedStartYear,setSelectedEndYear,townChoiceMode,setTownChoiceMode,prefBySelect,setPrefBySelect,cityBySelect,setCityBySelect,townBySelect,setTownBySelect,townDataByList,setTownDataByList});

    // バリデーション(例：貼り付けた住所が指定の住所に入っていなかった時)
    <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth} />

    return(
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>

                    <BasePageHeader {...{what,type,pageMinWidth,pageMaxWidth,"subtitle":"スタッフと年月の選択"}}/>

                    <form className={`base_frame base_backColor px-0 py-2 border-2 border-black rounded-sm mb-5 ${pageMaxWidth} ${pageMinWidth}`} onSubmit={onDecideSearhDataClick}>

                    {/* スタッフ(複数選択もOK、選択なしなら全員) */}
                    <UserChoisePart {...{pageMaxWidth,pageMinWidth,theme:"スタッフ",subtitile:"*選択しなくても全体平均や合計は出てきます"}}>
                      <FlatToggleLists contents={staffLists} formLists={selectedStaffs} onToggleListsChange={onSelectedStaffsChange} width={"w-[100%]"} {...{pageMaxWidth,pageMinWidth}} />
                    </UserChoisePart>

                    {/* 期限  無期限と現在を両端に含む */}
                     <UserChoisePart {...{pageMaxWidth,pageMinWidth,theme:"期間"}}>
                        <DoubleSelectParts  name1="startYear" name2="endYear" value1={selectedStartYear} value2={selectedEndYear} onChange1={onSelectedStartYearChange} onChange2={onSelectedEndYearChange} prefix={"期間　"} keyValueSets1={{...dateLists,"all":"限度なし"}} keyValueSets2={{0:"現在",...dateLists}} prefixPercent="w-[35%]" needWhiteSpace={true} needSpan={false}/>
                    </UserChoisePart>


                    {/* 町丁目選択(どちらで選ぶかで変更) */}
                    <UserChoisePart {...{pageMaxWidth,pageMinWidth,theme:"町丁目"}}>
                        {/* 住所検索をどちらから選ぶか&それぞれの具体的なコンポーネント */}
                        <AddressPartWhich {...{townChoiceMode,allTownSets,onPrefChange,onCityChange,onTownChange,pageMaxWidth,pageMinWidth,prefBySelect,cityBySelect,townBySelect,onAddressListsChange,townDataByList,onTwonChoiceModeChangeClick,processing}} />
                    </UserChoisePart>

                    {/* 提出ボタン */}
                    <BaseButton processing={processing} minWidth={pageMinWidth} maxWidth={pageMaxWidth} mb="mb-2"/>

                    </form>

                  <div className="mt-4">
                    <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.top_page`}  what="営業所担当のトップ"/>
                    <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.logout`}  what="ログアウト"/>
                  </div>

                  <p>　</p>

            </RoleLayout>
        </Layout>

    )

}


