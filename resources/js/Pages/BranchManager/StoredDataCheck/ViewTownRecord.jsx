import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BasePageHeader from "../../../Components/Common/BasePageHeader";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import useViewTownRecordDefinitions from "../../../Definition/BranchManager/ConfirmRecord/useViewTownRecordDefinitions";
import useViewTownRecordActions from "../../../Action/BranchManager/ConfirmRecord/useViewTownRecordActions";
import BaseTable from "../../../Components/Common/BaseTable";
import getJpnWord from "../../../Support/Common/getJpnWord";


// 町々目データの確認に向けた選択画面
export default function ViewTownRecord({prefix,what,type,searchStaffs,searchRange,allData}){

    // allDataは[各町目データ=>平均・最大・中央・具体的な数リスト]がスタッフごと、営業所ごと、全体で分かれて入っている

    // 定義
    const {staffNameLists,pageMinWidth,pageMaxWidth}=useViewTownRecordDefinitions({searchStaffs});

    // 動き
    const {}=useViewTownRecordActions({});

    return(
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>

                 <BasePageHeader {...{what,type,pageMinWidth,pageMaxWidth,"subtitle":("スタッフ:"+ (staffNameLists.length>0 ? staffNameLists.join("・") : "指定なし")) +"、範囲:"+searchRange }}/>

                {/* 結果 */}
                {Object.entries(allData).map(([eachPattern,eachDataset],index)=>
                    <BaseTable key={eachPattern} {...{tableTheme:getJpnWord(eachPattern),allData:Object.values(eachDataset),thSets:{"address_name":"住所","average":"平均値","max":"最大値","center":"中央値","stddev":"標準偏差","all_past_data":"過去の全値"},width:"w-[95%]",minWidth:pageMinWidth,maxWidth:pageMaxWidth,mb:"mb-2"}}/>
                )}


                  <div className="mt-4">
                    <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.confirm_project_record`}  what="選び直す"/>
                    <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.top_page`}  what="営業所担当のトップ"/>
                    <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.logout`}  what="ログアウト"/>
                  </div>

                  <p>　</p>

            </RoleLayout>
        </Layout>

    )

}


