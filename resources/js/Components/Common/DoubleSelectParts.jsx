// 入力系統のinput
export default function DoubleSelectParts({name1,name2,value1,value2,onChange1,onChange2,prefix,keyValueSets1,keyValueSets2}){
  return(
    <div className="flex items-center base_frame min-w-80 max-w-100 mx-auto my-3">
      <span className="inline-block w-[20%]  text-right">{prefix}</span>
      <select className="inline-block w-[35%] bg-white border-black border rounded-sm" name={name1} value={value1} onChange={onChange1}>
        {/* 未登録の場合 */}
        {(!keyValueSets1 || Object.keys(keyValueSets1).length==0) ? <option disabled value="">登録されていません</option> :
        <>
            {Object.entries(keyValueSets1).map((keyValueSet)=>
                <option key={keyValueSet[0]} value={keyValueSet[0]}>{keyValueSet[1]}</option>
            )}
         </>
         }
      </select>
      <span className="inline-block w-[5%]  text-center">〜</span>
      <select className="inline-block w-[35%] bg-white  border-black border rounded-sm" name={name2} value={value2} onChange={onChange2}>
        {/* 未登録の場合 */}
        {(!keyValueSets2 || Object.keys(keyValueSets2).length==0) ? <option disabled value="">登録されていません</option> :
        <>
            {Object.entries(keyValueSets2).map((keyValueSet)=>
                <option key={keyValueSet[0]} value={keyValueSet[0]}>{keyValueSet[1]}</option>
            )}
         </>
         }
      </select>
    </div>
  )
}
