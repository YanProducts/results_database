// バリデーションエラーを一元化表示
export default function ViewValidationErrors({errors,mt="",minWidth="",maxWidth=""}){

  if(!errors || Object.keys(errors).length==0){
    return null;
  }

    // 1つのパラメータにつき、1つの1エラー条件が捕捉
    // 文面が同じエラーは1つにまとめる
    const uniqueErrorSet=[...new Set(Object.values(errors).map((error,index)=>error))];

  return(
    <div className={`mb-6 ${mt} ${minWidth} ${maxWidth}`}>
        {uniqueErrorSet.map((eachError,index)=>
            <div className="base_error whitespace-pre-line" key={index}><p>{eachError}</p></div>
        )}
    </div>
  )
}
