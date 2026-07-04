export default function ViewUserName({pageMinWidth="",pageMaxWidth="",width="w-[80%]",userName,nameBackColor="bg-white", nameColor="black"}){
    return(
        <div className={`${pageMinWidth} ${pageMaxWidth} ${width} mx-auto flex justify-end align-middle base_backColor my-2 h-8`}>
            <p className={`w-[30%] text-center font-bold underline underline-offset-4 text-lg ${nameBackColor} ${nameColor}`}>{userName}</p>
        </div>
    )
}
