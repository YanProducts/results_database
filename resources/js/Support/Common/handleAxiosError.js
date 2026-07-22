// axiosのエラー処理
export default function handleAxiosError(e){
    // 仮に非同期でのバリデーションであっても、axiosは自動的にacceptヘッダーで「jsonで返して欲しい」という要求があるためLaravelがJsonで返すので、ここに入る
    if(e.response?.status==422){
        // バリデーション
        console.log(e.response.data?.errors)
    }else if(e.response?.status==419){
        console.log("通信エラーまたは時間切れです")
    }else{
        // その他
        console.log(e.message)
        alert("何らかのエラーが発生し、エクスポートができませんでした")
    }
}
