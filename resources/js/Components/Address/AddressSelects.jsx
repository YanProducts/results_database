import SelectParts from "../Common/SelectParts"

// 県→市→町目の住所の選択
export default function AddressSelects({allTownSets,onPrefChange,onCityChange,onTownChange,selectedPref,selectedCity,selectedTown,pageMaxWidth,pageMinWidth}){
    return(
        <>
            {/* 県名(prefをtown検索の配列に使うためにkeyも日本語名) */}
            <SelectParts prefix={"県　"} minWidth={pageMinWidth} maxWidth={pageMaxWidth} prefixPercent={"w-[35%]"} selectPercent="w-[45%]" needWhiteSpace={true} withOpt={false} allowEmptyOption={false} onChange={onPrefChange} name={"pref"} value={selectedPref} keyValueSets={Object.fromEntries(Object.keys(allTownSets).map((eachPref,index)=>([eachPref,eachPref])))} isMulti={false}/>

             {/* 市名(県の名前が記入済の場合)(cityをtown検索の配列に使うためにkeyも日本語名) */}
            {selectedPref &&
            <SelectParts prefix={"市　"} minWidth={pageMinWidth} maxWidth={pageMaxWidth} prefixPercent={"w-[35%]"} selectPercent="w-[45%]" needWhiteSpace={true} withOpt={false} allowEmptyOption={false} onChange={onCityChange} name={"city"} value={selectedCity} keyValueSets={Object.fromEntries(Object.keys(allTownSets?.[selectedPref]).map((eachCity,index)=>([eachCity,eachCity])))} isMulti={false}/>}

             {/* 町名(県と市が記入済の場合)(Laravel投稿を円滑に進めるため、idをキー=valueにする、すべてはkeyを0にして設定(sqlのidは1からスタート)) */}
            {selectedPref && selectedCity &&
            <SelectParts prefix={"町　"} minWidth={pageMinWidth} maxWidth={pageMaxWidth} prefixPercent={"w-[35%]"} selectPercent="w-[45%]" needWhiteSpace={true} withOpt={false} allowEmptyOption={false} onChange={onTownChange} name={"pref"} value={selectedTown} keyValueSets={{0:"すべて",...Object.fromEntries(Object.values(allTownSets?.[selectedPref]?.[selectedCity]).map((eachTown,index)=>([eachTown.id,eachTown.town])))}} isMulti={false}/>}
        </>
    )

}
