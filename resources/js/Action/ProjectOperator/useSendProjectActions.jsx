import {route} from 'ziggy-js';

export default function useSendProjectActions(data,setData){

//   期日変化
const onStartDateChange=(e)=>{
    setData("startDate",e.currentTarget.value)
}
const onEndDateChange=(e)=>{
    setData("endDate",e.currentTarget.value)
}

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

  return{onStartDateChange,onEndDateChange,onPlaceChange,onFileChange,onFileDeleteClick,onSubmitBtnClick}
}
