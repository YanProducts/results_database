// データの変換place編集版(デフォルトとの違いを比較)
export default function formatStateData({placeInformation,placeName,colors,isInWorkChange}){
    return{
        place_name:placeName,
        is_active:isInWorkChange ? !placeInformation.is_active : placeInformation?.is_active,
        red:colors?.red || 255,
        green:colors?.green || 255,
        blue:colors?.blue || 255,
    }
}
