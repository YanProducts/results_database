// バリデーションエラーを一元化表示
export default function ViewValidationErrors({errors,minWidth="",maxWidth=""}){
  if(!errors || Object.keys(errors).length==0){
    return null;
  }
  return(
    <div className={`mb-6 ${minWidth} ${maxWidth}`}>
        {Object.values(errors).map((error,index)=>error && <div className="base_error whitespace-pre-line" key={index}><p>{error}</p></div>)}
    </div>
  )
}
