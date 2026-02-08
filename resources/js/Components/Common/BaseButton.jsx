// ボタン要素
export default function BaseButton({onSubmitBtnClick,processing}){
  return(
    <div className="base_btn_div">
    <button onClick={onSubmitBtnClick} className={`base_btn ${processing ? "active_btn" :"non_active_btn"}`}>決定！</button>
  </div>
  )
}