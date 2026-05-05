// 併配の数によってwidthの長さが決まる
export default function setTdWidthByProjectCounts(projectCounts){
    switch(projectCounts){
        case 0:
        return ["w-[50%]","w-[25%]","w-[25%]"];
        case 1:
        return ["w-[45%]","w-[15%]","w-[20%]","w-[20%]"];
        case 2:
        return ["w-[40%]","w-[15%]","w-[15%]","w-[15%]","w-[15%]"];
        case 3:
        return ["w-[40%]","w-[10%]","w-[12.5%]","w-[12.5%]","w-[12.5%]","w-[12.5%]"];
        case 4:
        return ["w-[40%]","w-[10%]","w-[10%]","w-[10%]","w-[10%]","w-[10%]","w-[10%]"];
        case 5:
        return ["w-[40%]","w-[6%]","w-[9%]","w-[9%]","w-[9%]","w-[9%]","w-[9%]","w-[9%]"];
        default:
        return "ひとまず後でやるで";
        // case 6:
        // return ["w-[50%]","w-[25%]","w-[25%]"];
        // case 7:
        // return ["w-[50%]","w-[25%]","w-[25%]"];
        // case 8:
        // return ["w-[50%]","w-[25%]","w-[25%]"];
        // case 9:
        // return ["w-[50%]","w-[25%]","w-[25%]"];
        // case 10:
        // return ["w-[50%]","w-[25%]","w-[25%]"];
    }
}
