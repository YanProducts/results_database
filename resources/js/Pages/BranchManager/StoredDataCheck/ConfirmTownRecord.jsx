import useConfirmTownRecordActions from "../../../Action/BranchManager/ConfirmRecord/useConfirmTownRecordActions";
import useConfirmTownRecordDefinitions from "../../../Definition/BranchManager/ConfirmRecord/useConfirmTownRecordDefinitions";
import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BasePageHeader from "../../../Components/Common/BasePageHeader";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import SelectParts from "../../../Components/Common/SelectParts";
import DoubleSelectParts from "../../../Components/Common/DoubleSelectParts";
import AdreessSelects from "../../../Components/Address/AdreessSelects";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";
import AddressInputs from "../../../Components/Address/AddressInputs";
import BaseButton from "../../../Components/Common/BaseButton";
import FlatToggleLists from "../../../Components/Common/FlatToggleLists";


// 町々目データの確認に向けた選択画面
export default function ConfirmTownRecord({prefix,what,type,staffLists,allTownSets}){

    // 定義
    const {dateLists,data, setData, post, processing, errors,clearErrors, reset,selectedStaffs,setSelectedStaffs,selectedStartYear,setSelectedStartYear,selectedEndYear,setSelectedEndYear,townChoiceMode,setTownChoiceMode,prefBySelect,setPrefBySelect,cityBySelect,setCityBySelect,townBySelect,setTownBySelect,townDataByList,setTownDataByList,pageMinWidth,pageMaxWidth}=useConfirmTownRecordDefinitions();

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
                    <div className={`base_frame w-[50%] mt-2 mb-4 pb-4 px-0 mx-0 box-border border-black border-dashed border-b rounded-sm ${pageMaxWidth} ${pageMinWidth}`}>
                    <p className="text-center font-bold my-1 py-1">（特定する場合）スタッフ</p>
                    <FlatToggleLists
                    contents={staffLists} formLists={selectedStaffs} onToggleListsChange={onSelectedStaffsChange} width={"w-[100%]"} {...{pageMaxWidth,pageMinWidth}}
                    />
                    </div>


                    {/* 期限  */}
                    {/* 無期限と現在を両端に含む */}
                    <div className={`base_frame w-[50%] mt-2 mb-4 pb-4 px-0 mx-0 box-border border-black border-dashed border-b rounded-sm ${pageMaxWidth} ${pageMinWidth}`}>
                    <p className="text-center font-bold my-1 py-1">期間</p>
                    <DoubleSelectParts  name1="startYear" name2="endYear" value1={selectedStartYear} value2={selectedEndYear} onChange1={onSelectedStartYearChange} onChange2={onSelectedEndYearChange} prefix={"期間　"} keyValueSets1={{"all":"すべて",...dateLists}} keyValueSets2={{...dateLists,"now":"現在"}} prefixPercent="w-[35%]" needWhiteSpace={true} needSpan={false}/>
                    </div>

                    {/* 町丁目をどちらから選ぶかで変更 */}
                    <div className={`base_frame w-[50%] mt-2 mb-4 pb-4 px-0 mx-0 box-border border-black border-dashed border-b rounded-sm ${pageMaxWidth} ${pageMinWidth}`}>
                        <p className="text-center font-bold my-1 py-1">町丁目</p>
                    {townChoiceMode =="select" ?
                    // 選ぶ形式
                     <AdreessSelects {...{allTownSets,onPrefChange,onCityChange,onTownChange,pageMaxWidth,pageMinWidth,selectedPref:prefBySelect,selectedCity:cityBySelect,selectedTown:townBySelect}}
                     />
                    :
                    // 貼り付ける形式
                     <AddressInputs {...{textName:"addressLists",onAddressListsChange,townDataByLists}}/>
                     }

                     {/* 町丁目の選び方の変更 */}
                     <div className={`base_frame base_backColor text-center ${pageMinWidth} ${pageMaxWidth} mb-4`}>町丁目を<span className={`${!processing ? "active_btn" :"non_active_btn"}`} onClick={onTwonChoiceModeChangeClick}>{townChoiceMode=="select" ? "一覧を貼り付ける" :"一覧から選択"}</span>に変更</div>

                    </div>

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


