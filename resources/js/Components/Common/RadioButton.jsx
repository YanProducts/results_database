// ラジオボタン(横に並べる)
export default function RadioButton({minWidth="min-w-72.5",maxWitdh="max-w-80",contentsSets,innerWidth="w-[40%]",radioName,onChange,stateForSelected}){
    return(
        <div className={`flex justify-center base_frame mt-3 mb-4 ${minWidth} ${maxWitdh} text-center`}>
        {
            contentsSets.map((contents,index)=>
              <div key={index} className={`flex justify-around ${innerWidth} mx-auto`}>
                <input className={`bg-white inline-block border-black border rounded-sm pl-1  whitespace-nowrap`} type="radio" name={radioName} value={contents.value} onChange={onChange} checked={contents.value===stateForSelected}/>
                <span className={`inline-block text-right`}>{contents.prefix}</span>
             </div>
            )
        }
        </div>
    );
}
