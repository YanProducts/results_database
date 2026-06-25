// オブジェクトかどうかの判定
// やや煩雑なので外注化
export default function isObject(value){
    return value !== null && typeof value === "object" && !Array.isArray(value)
}
