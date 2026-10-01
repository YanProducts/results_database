import {route} from 'ziggy-js';

export default function useSendProjectActions({prefix,post,data,setData}){

  // 営業所変化(営業所担当の場合は不要だが分岐を作るまでもない)
  const onPlaceChange=(e)=>{
    setData("place",e.currentTarget.value)
  }

//   ファイルの選択変更
  const onFileChange=(e)=>{
      const files=Array.from(e.currentTarget.files);
      setData("fileSets",[...data.fileSets,...files]);
  }


//   アップしようとしたファイルの削除
  const onFileDeleteClick=(index)=>{
    setData("fileSets",data.fileSets.filter((file,fileIndex)=>fileIndex!==index));
  }


  // 決定ボタンを押した時
  const onSubmitBtnClick=(e)=>{
        e.preventDefault();

        const postRoute=prefix=="project_operator" ? "project_operator.dispatch_project_post" : ( prefix=="branch_manager" ? "branch_manager.handing_assignment_post" : undefined);

       // バリデーションはlaravelに任せる(遷移しないため)
       post(route(postRoute));

}

  return{onPlaceChange,onFileChange,onFileDeleteClick,onSubmitBtnClick}
}
