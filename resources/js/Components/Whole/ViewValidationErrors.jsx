// バリデーションエラーを一元化表示
export default function ViewValidationErrors({errors}){
  if(!errors || Object.keys(errors).length==0){
    return null;
  }
  return(
     Object.values(errors).map((error,index)=><div className="base_error" key={index}><p>{error}</p></div>)
  )
}