// 表示変更に繋げるselect
export default function SelectPartsForViewChange({value,onChange,prefix,prefixPercent="w-[30%]",maxWidth="max-w-80", minWidth="min-w-72.5", keyValueSets}){
  return(
    <div className={`flex items-center base_frame ${minWidth} ${maxWidth} mx-auto my-3`}>
      <span className={`inline-block ${prefixPercent} min-w-32 text-right`}>{prefix}</span>
      <select className="inline-block w-[60%] min-w-35 bg-white border-black border rounded-b-sm" value={value} onChange={onChange}>
        {/* 未登録の場合 */}
        {(!keyValueSets || Object.keys(keyValueSets).length==0) ? <option disabled value="">登録されていません</option> :
            <>
               <option disabled value="">選択してください</option>
            {Object.entries(keyValueSets).map((keyValueSet)=>
                <option key={keyValueSet[0]} value={keyValueSet[0]}>{keyValueSet[1]}</option>)}
         </>
         }
      </select>
    </div>
  )
}
