// エンター送信させず、決定とやり直すで送信させる場合
export default function SubmitOrBackButtons({minWidth="min-w-75", maxWidth="max-w-250",processing,errors={},onSubmitBtnClick,onCancelBtnClick}){
  return(
    <div className={`base_two_btn_div ${maxWidth} ${minWidth} `}>
    <button type="button" onClick={onSubmitBtnClick} className={`base_btn ${processing || Object.keys(errors).length>0  ? "non_active_btn" :"active_btn"} inline-block`}>決定！</button>
    <button type="button" onClick={onCancelBtnClick} className={`base_btn ${processing ? "non_active_btn" :"active_btn"} inline-block`}>やり直す</button>
  </div>
  )
}
