// ポップアップの位置の設定
// 座標軸の値は変わるのでtailwindでの設定は難しい。jsで設定(すでにposition:absoluteは設定している前提)
export default function popUpPositionSeeting(e,domId,xOffset,yOffset){

    // 元の要素の座標軸
    const rect = e.currentTarget.getBoundingClientRect();

    // popUpのdomのid取得
    const popupRef=document.getElementById(domId);

    // 座標軸の設定
    popupRef.style.top =
        `${rect.bottom + window.scrollY + yOffset}px`;

    popupRef.style.left =
        `${rect.left + window.scrollX + xOffset}px`;
}
