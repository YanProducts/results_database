import AddressSelects from "../../../Address/AddressSelects";
import BaseTextArea from "../../../Common/BaseTextArea";

// 統計検索において住所選択をどちらから選ぶか&それぞれのコンポーネント
export default function AddressPartWhich({townChoiceMode,allTownSets,onPrefChange,onCityChange,onTownChange,pageMaxWidth,pageMinWidth,prefBySelect,cityBySelect,townBySelect,onAddressListsChange,townDataByList,onTwonChoiceModeChangeClick,processing}){
    return(
      <>
        {townChoiceMode =="select" ?
        // 選ぶ形式
            <AddressSelects {...{allTownSets,onPrefChange,onCityChange,onTownChange,pageMaxWidth,pageMinWidth,selectedPref:prefBySelect,selectedCity:cityBySelect,selectedTown:townBySelect}}
            />
        :
        // 貼り付ける形式
        <BaseTextArea {...{textName:"addressLists",changeFunc:onAddressListsChange,textData:townDataByList,pageMaxWidth,pageMinWidth,needTitle:true,textAreaTitle:"市区町村&町名を\nスペースなしに張り付けてください\n町目の区切れは改行です",placeholder:"例：○○市△△町１"}} />
        }

            {/* 町丁目の選び方の変更 */}
            <div className={`base_frame base_backColor text-center ${pageMinWidth} ${pageMaxWidth} my-2`}>町丁目を<span className={`${!processing ? "active_btn" :"non_active_btn"}`} onClick={onTwonChoiceModeChangeClick}>{townChoiceMode=="select" ? "一覧を貼り付ける" :"一覧から選択"}</span>に変更</div>
      </>
        )
}
