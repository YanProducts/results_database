// 入力系統のinput
export default function InputParts({type,name,value,onChange,prefix}){
  return(
    <div className="flex items-center base_frame min-w-72.5 max-w-80 mx-auto my-3">
      <span className="inline-block w-[30%] min-w-32 text-right">{prefix}</span>
      <input className="bg-white inline-block w-[60%] min-w-35 border-black border  rounded-sm pl-1" type={type} name={name} value={value} onChange={onChange}/>
    </div>
  )
}
