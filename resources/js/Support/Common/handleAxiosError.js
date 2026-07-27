// axiosのエラー処理
export default function handleAxiosError(e){
    // 仮に非同期でのバリデーションであっても、axiosは自動的にacceptヘッダーで「jsonで返して欲しい」という要求があるためLaravelがJsonで返すので、ここに入る
    if(e.response?.status==422){
        // バリデーション
        console.log(e.response.data?.errors)
        alert("値の取得時にエラーが発生しました")
    }else if(e.response?.status==419){
        alert("通信エラー、もしくは時間切れです")
    }else{
        // その他
        // 理由は内部で補足
        console.log(e.message)
        alert("何らかのエラーが発生し、エクスポートができませんでした")
    }
}
