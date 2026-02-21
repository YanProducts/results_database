// 入力系統のinput
export default function SelectParts({name,value,onChange,prefix,keyValueSets,allowEmptyOption}){
  return(
    <div className="flex items-center base_frame min-w-72.5 max-w-80 mx-auto my-3">
      <span className="inline-block w-[30%] min-w-32 text-right">{prefix}</span>
      <select className="inline-block w-[60%] min-w-35 " name={name} value={value} onChange={onChange}>
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
