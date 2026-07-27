import SelectParts from "../Common/SelectParts"

// 県→市→町目の住所の選択
export default function AdreessSelects({allTownSets,onPrefChange,onCityChange,onTownChange,selectedPref,selectedCity,selectedTown,pageMaxWidth,pageMinWidth}){

    return(
        <>
            {/* 県名 */}
            <SelectParts prefix={"県　"} minWidth={pageMinWidth} maxWidth={pageMaxWidth} prefixPercent={"w-[35%]"} selectPercent="w-[45%]" needWhiteSpace={true} withOpt={false} allowEmptyOption={false} onChange={onPrefChange} name={"pref"} value={selectedPref} keyValueSets={Object.fromEntries(Object.keys(allTownSets).map((eachPref,index)=>([index,eachPref])))} isMulti={false}/>

             {/* 市名(県の名前が記入済の場合) */}
            {selectedPref &&
            <SelectParts prefix={"市　"} minWidth={pageMinWidth} maxWidth={pageMaxWidth} prefixPercent={"w-[35%]"} selectPercent="w-[45%]" needWhiteSpace={true} withOpt={false} allowEmptyOption={false} onChange={onCityChange} name={"city"} value={selectedCity} keyValueSets={Object.fromEntries(Object.keys(allTownSets?.[selectedPref]).map((eachCity,index)=>([index,eachCity])))} isMulti={false}/>}

             {/* 町名(県と市が記入済の場合) */}
            {selectedPref && selectedCity &&
            <SelectParts prefix={"町　"} minWidth={pageMinWidth} maxWidth={pageMaxWidth} prefixPercent={"w-[35%]"} selectPercent="w-[45%]" needWhiteSpace={true} withOpt={false} allowEmptyOption={false} onChange={onTownChange} name={"pref"} value={selectedTown} keyValueSets={Object.fromEntries(Object.keys(allTownSets?.[selectedPref]?.[selectedCity]).map((eachTown,index)=>([index,eachTown])))} isMulti={false}/>}
        </>
    )

}
