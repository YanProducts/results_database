import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";
import BasePageHeader from "../../Components/Common/BasePageHeader";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";
import usePurchaseOrderDefinitions from "../../Definition/Clerical/usePurchaseOrderDefinitions";
import usePurchaseOrderActions from "../../Action/Clerical/usePurchaseOrderActions";
import BaseButton from "../../Components/Common/BaseButton";
import SelectParts from "../../Components/Common/SelectParts";
import DoubleSelectParts from "../../Components/Common/DoubleSelectParts";
import usePurchaseOrderViewData from "../../Computed/Clerical/usePurchaseOrderViewData";

// 発注書作成
export default function PurchaseOrder({prefix,what,type,staffsGroupByPlaces,defaultStartDateForPurchaseLists}){

    // 定義
     const { data, setData, post, processing, errors,clearErrors,reset,limitMonth,setLimitMonth,limitYearLists,selectedStaff,setSelectedStaff,selectedStartMonth,setSelectedStartMonth,selectedEndMonth,setSelectedEndMonth,pageMinWidth,pageMaxWidth}=usePurchaseOrderDefinitions({defaultStartDateForPurchaseLists});

    // 動き
    const {onStaffChange,onSelectedStartMonthChange,onSelectedEndMonthChange,onLimitMonthChange,onDecidePurchase}=usePurchaseOrderActions({post,data,setData,setSelectedStaff,selectedStartMonth,setSelectedStartMonth,selectedEndMonth,setSelectedEndMonth,limitMonth,setLimitMonth});

    const monthSets=usePurchaseOrderViewData({limitMonth})

    return(
        <Layout title={`${what}-${type}`}>
          <RoleLayout prefix={prefix}>

            <BasePageHeader {...{what,type,pageMinWidth,pageMaxWidth,"subtitle":"スタッフと年月の選択"}}/>

            <form className={`base_frame base_backColor md:p-3 sm:p-2 p-0 border-2 border-black rounded-sm mb-5 ${pageMaxWidth} ${pageMinWidth}`} onSubmit={onDecidePurchase}>

            {/* スタッフ名 */}
            <SelectParts prefix={"スタッフ　"} minWidth={pageMinWidth} maxWidth={pageMaxWidth} prefixPercent={"w-[35%]"} selectPercent="w-[45%]" needWhiteSpace={true} withOpt={true} allowEmptyOption={false} onChange={onStaffChange} name={"staff"} value={selectedStaff} keyValueSets={staffsGroupByPlaces}/>


            {/* 開始月と終了月 */}
            <DoubleSelectParts  name1="startMonth" name2="endMonth" value1={selectedStartMonth} value2={selectedEndMonth} onChange1={onSelectedStartMonthChange} onChange2={onSelectedEndMonthChange} prefix={"開始と終了　"} keyValueSets1={monthSets} keyValueSets2={monthSets} prefixPercent="w-[35%]" needWhiteSpace={true}/>

            {/* 選択できる範囲(1年前~10年前) */}
            <SelectParts minWidth={pageMinWidth} maxWidth={pageMaxWidth} prefix={"選択範囲　"} prefixPercent={"w-[35%]"} selectPercent="w-[45%]" needWhiteSpace={true}  withOpt={false} allowEmptyOption={false} onChange={onLimitMonthChange} name={"limitMonth"} value={limitMonth/12} keyValueSets={limitYearLists} />

            {/* 提出ボタン */}
            <BaseButton processing={processing} minWidth={pageMinWidth} maxWidth={pageMaxWidth}/>
            </form>

            {/* リンク */}
            <div className="mt-4">
                <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.logout`}  what="ログアウト"/>
            </div>

            </RoleLayout>


            <p>　</p>

        </Layout>

    )
}
