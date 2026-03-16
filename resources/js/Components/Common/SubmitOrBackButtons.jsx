// エンター送信させず、決定とやり直すで送信させる場合
export default function SubmitOrBackButtons({processing,onSubmitBtnClick,onCancelBtnClick}){
  return(
    <div className="base_two_btn_div">
    <button type="button" onClick={onSubmitBtnClick} className={`base_btn ${processing ? "active_btn" :"non_active_btn"} inline-block`}>決定！</button>
    <button type="button" onClick={onCancelBtnClick} className={`base_btn ${processing ? "active_btn" :"non_active_btn"} inline-block`}>やり直す</button>
  </div>
  )
}
