// 併配の数によってwidthの長さが決まる
export default function setTdWidthByProjectCounts(projectCounts,isBigMedia){


    if(isBigMedia){
        // PC版
        switch(projectCounts){
            case 0:
            return ["w-[50%]","w-[15%]","w-[25%]","w-[10%]"];
            case 1:
            return ["w-[40%]","w-[10%]","w-[20%]","w-[20%]","w-[10%]"];
            case 2:
            return ["w-[40%]","w-[9%]","w-[14%]","w-[14%]","w-[14%]","w-[9%]"];
            case 3:
            return ["w-[40%]","w-[7%]","w-[11%]","w-[11%]","w-[11%]","w-[11%]","w-[9%]"];
            case 4:
            return ["w-[40%]","w-[7%]","w-[9%]","w-[9%]","w-[9%]","w-[9%]","w-[9%]","w-[8%]"];
            case 5:
            return ["w-[33%]","w-[6%]","w-[9%]","w-[9%]","w-[9%]","w-[9%]","w-[9%]","w-[9%]","w-[7%]"];
            case 6:
            return ["w-[32%]","w-[5%]","w-[8%]","w-[8%]","w-[8%]","w-[8%]","w-[8%]","w-[8%]","w-[8%]","w-[7%]"];
            case 7:
            return ["w-[26%]","w-[5%]","w-[8%]","w-[8%]","w-[8%]","w-[8%]","w-[8%]","w-[8%]","w-[8%]","w-[8%]","w-[5%]"];

            default:
                // これはインラインのstyleで設定する必要あり(動的な数値はtailwindが通らない)
             const writeResultWidthSets=Array.from({length:projectCounts},(_,i)=>(70/projectCounts + "%"))
            return ["20%","5%",  ...writeResultWidthSets,   "5%"];
        }
    }else{
        // スマホ版
        switch(projectCounts){
            case 0:
            return ["w-[30%]","w-[30%]","w-[35%]","w-[10%]"];
            case 1:
            return ["w-[15%]","w-[25%]","w-[25%]","w-[25%]","w-[10%]"];
            case 2:
            return ["w-[17%]","w-[15%]","w-[20%]","w-[20%]","w-[20%]","w-[8%]"];
            case 3:
            return ["w-[14%]","w-[14%]","w-[16%]","w-[16%]","w-[16%]","w-[16%]","w-[8%]"];
            case 4:
            return ["w-[11%]","w-[12%]","w-[14%]","w-[14%]","w-[14%]","w-[14%]","w-[14%]","w-[7%]"];
            case 5:
            return ["w-[11%]","w-[10%]","w-[12%]","w-[12%]","w-[12%]","w-[12%]","w-[12%]","w-[12%]","w-[7%]"];
            case 6:
            return ["w-[9%]","w-[7%]","w-[11%]","w-[11%]","w-[11%]","w-[11%]","w-[11%]","w-[11%]","w-[11%]","w-[7%]"];
            case 7:
            return ["w-[8%]","w-[6%]","w-[10%]","w-[10%]","w-[10%]","w-[10%]","w-[10%]","w-[10%]","w-[10%]","w-[10%]","w-[6%]"];
            default:
            // これはインラインのstyleで設定する必要あり(動的な数値はtailwindが通らない)
            const writeResultWidthSets=Array.from({length:projectCounts},(_,i)=>(78/projectCounts + "%"))
            return ["7%","5%",  ...writeResultWidthSets,   "5%"];
     }
    }


}
