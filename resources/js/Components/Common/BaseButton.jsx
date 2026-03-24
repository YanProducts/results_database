// ボタン要素
export default function BaseButton({processing,minWidth="",maxWidth=""}){
  return(
    <div className={`base_frame ${minWidth} ${maxWidth}`}>
    <button type="submit" className={`base_btn ${processing ? "non_active_btn" :"active_btn"}`}>決定！</button>
  </div>
  )
}
