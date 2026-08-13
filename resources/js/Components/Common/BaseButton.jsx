// ボタン要素
export default function BaseButton({processing,minWidth="",maxWidth="",disabled=false, mb="" }){
  return(
    <div className={`base_frame ${minWidth} ${maxWidth} ${mb}`}>
    <button type="submit" disabled={disabled} className={`base_btn ${(processing || disabled) ? "non_active_btn" :"active_btn"}`}>決定！</button>
  </div>
  )
}
