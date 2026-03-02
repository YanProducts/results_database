// 入力系統のinput
export default function InputParts({type,name,minWidth="min-w-72.5", maxWitdh="max-w-80",value,onChange,prefix,prefixPercent="w-[30%]", inputPercent="w-[60%]", attributes={}}){
  return(
    <div className={`flex items-center base_frame mx-auto my-3 ${minWidth} ${maxWitdh}`}>
      <span className={`inline-block w-[30%] min-w-32 text-right ${prefixPercent}`}>{prefix}</span>
      <input className={`bg-white inline-block min-w-35 border-black border rounded-sm pl-1  ${inputPercent}`} type={type} name={name} value={value} onChange={onChange} {...attributes}/>
    </div>
  )
}
