import {route} from 'ziggy-js';

export default function useSendProjectActions(post,data,setData){

  // 営業所変化
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
    // バリデーションはlaravelに任せる(遷移しないため)
       post(route("project_operator.dispatch_project_post"));
  }

  return{onPlaceChange,onFileChange,onFileDeleteClick,onSubmitBtnClick}
}
