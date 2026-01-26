// 入力系統のinput
export default function AuthInput({type,name,value,onChange,suffix}){
  return(
    <div className="flex items-center">
      <input type={type} name={name} value={value} onChange={onChange}/>
      <span className="">{suffix}</span>
    </div>
  )
}