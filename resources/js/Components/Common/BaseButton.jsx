// ボタン要素
export default function BaseButton({processing,minWidth="",maxWidth=""}){
  return(
    <div className={`base_btn_div ${minWidth} ${maxWidth}`}>
    <button type="submit" className={`base_btn ${processing ? "active_btn" :"non_active_btn"}`}>決定！</button>
  </div>
  )
}
