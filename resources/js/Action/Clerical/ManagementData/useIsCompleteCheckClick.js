// 案件完成フラグが押された時
export const useIsCompleteCheckClick=async(e,projectName,projectId,setIsComplete)=>{
        // fetchで取得する
        const response= await fetch(route("clerical.toggle_complete"),{
            method:"POST",
            headers:{
              "Content-Type":"application/json",
              "X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').content,
            },
            body:JSON.stringify({
                // 同案件フラグもあるのでidも出す
                "projectName":projectName,
                "projectId":projectId
            })
        });
        if(!response.ok){
            alert("処理に失敗しました\n再度読み込んで失敗が続く場合は作成者に連絡してください")
            return;
        }
        const responseData=await response.json()

        if(responseData.fetchError || !responseData?.isOK){
            if(responseData.fetchError?.projectChange){
                alert("途中で案件データが操作されているので、１度リロードします");
                location.reload();
                return;
            }

            alert("予期せぬエラーです\n再度読み込んで失敗が続く場合は作成者に連絡してください");
            return;
        }

        // エラーがなければ、完成フラグのみ反転
        setIsComplete(prev=>({...prev,
            [projectName]:{
                id:projectId,
                completeFlag:!prev[projectName].completeFlag
        }}));

    }
