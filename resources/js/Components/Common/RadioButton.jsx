// ラジオボタン(横に並べる)
export default function RadioButton({minWidth="min-w-72.5",maxWitdh="max-w-80",contentsSets,prefixPercent="w-[30%]",radioName,onChange,stateForSelected}){
    return(
        <div className={`flex items-center base_frame mx-auto my-3 ${minWidth} ${maxWitdh}`}>
        {
            contentsSets.map((contents,index)=>
              <div key={index}>
                <span className={`inline-block w-[30%] min-w-32 text-right ${prefixPercent[index]}`}>{contents.prefix}</span>
                <input className={`bg-white inline-block min-w-35 border-black border rounded-sm pl-1  ${inputPercent}`} type="radio" name={radioName} value={contents.value} onChange={onChange} checked={contents.value===stateForSelected}/>
             </div>
            )
        }
        </div>
    );
}
