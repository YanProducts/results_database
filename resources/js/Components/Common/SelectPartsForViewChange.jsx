// 表示変更に繋げるselect(formとは直接は連動せず/非アクティブ化やselectがない場合との切り替えも可能)
export default function SelectPartsForViewChange({value,onChange,prefix,prefixPercent="w-[30%]",maxWidth="max-w-80", minWidth="min-w-72.5", keyValueSets, disabled=false, opacity="opacity-100" ,fixed=false, fixContents=""}){
  return(
    <div className={`flex items-center base_frame ${minWidth} ${maxWidth} mx-auto my-3`}>
      <span className={`inline-block ${prefixPercent} min-w-32 text-right`}>{prefix}</span>

    {!fixed ?
      <select disabled={disabled} className={`inline-block w-[60%] min-w-35 bg-white border-black border rounded-b-sm ${opacity}`} value={value} onChange={onChange}>
        {/* 未登録の場合 */}
        {(!keyValueSets || Object.keys(keyValueSets).length==0) ? <option disabled value="">登録されていません</option> :
            <>
            <option disabled value="">選択してください</option>
            {Object.entries(keyValueSets).map((keyValueSet)=>
                <option key={keyValueSet[0]} value={keyValueSet[0]}>{keyValueSet[1]}</option>)}
         </>
         }
      </select>
    :
      <span className="w-full text-left font-bold">{fixContents}</span>
    }
    </div>
  )
}
