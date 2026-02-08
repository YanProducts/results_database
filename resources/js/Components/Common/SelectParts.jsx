// 入力系統のinput
export default function SelectParts({name,value,onChange,prefix,keyValueSets}){
  return(
    <div className="flex items-center base_frame min-w-72.5 max-w-80 mx-auto my-3">
      <span className="inline-block w-[30%] min-w-32 text-right">{prefix}</span>
      <select className="inline-block w-[60%] min-w-35 " name={name} value={value} onChange={onChange}>
         {Object.entries(keyValueSets).map((keyValueSet,index)=>
            <option key={index} value={keyValueSet[0]}>{keyValueSet[1]}</option>
         )}
      </select>
    </div>
  )
}
