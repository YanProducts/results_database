// シンプル版にて、表示用のプロジェクト名の取得(roundNumberが入れ子になっているため。また表示ようなので、roundNumberはトータルの数ではなく、その日ごとなど表示されるものの中で重なっている場合のみ表示)
export default function simpleGetProjectNameForView({valueInProjectName,projectName,roundNumberIndex}){
    return valueInProjectName.length==1 ? projectName : projectName + ":" + (roundNumberIndex + 1);
}
