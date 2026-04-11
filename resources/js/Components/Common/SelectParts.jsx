// 入力系統のinput(タイトルをつける)
export default function SelectParts({name,value,onChange,prefix,prefixPercent="w-[30%]",selectPercent="w-[60%]",maxWidth="max-w-80", minWidth="min-w-72.5", prefixMinWidth="min-w-32", selectMinWidth="min-w-35",  keyValueSets,allowEmptyOption}){

  return(
    <div className={`flex items-center base_frame ${minWidth} ${maxWidth} mx-auto my-3`}>
      <span className={`inline-block ${prefixPercent} ${prefixMinWidth} text-right`}>{prefix}</span>
      <select className={`inline-block ${selectPercent} ${selectMinWidth} bg-white border-black border rounded-b-sm`} name={name} value={value} onChange={onChange}>
        {/* 未登録の場合 */}
        {(!keyValueSets || Object.keys(keyValueSets).length==0) ? <option disabled value="">登録されていません</option> :
        <>
            {/* 「登録しない」を許可するかどうか */}
            {allowEmptyOption ? <option value="">登録しない</option> : <option disabled value="">選択してください</option> }
            {Object.entries(keyValueSets).map((keyValueSet)=>
                <option key={keyValueSet[0]} value={keyValueSet[0]}>{keyValueSet[1]}</option>
            )}
         </>
         }
      </select>
    </div>
  )
}
