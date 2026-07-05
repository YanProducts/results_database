import { Link } from "@inertiajs/react";
import { route } from "ziggy-js";
export default function BaseLinkLine({minWidth="min-w-75", maxWidth="max-w-250",routeName,what,needAlert=false,needAlertFunc=()=>{}}){

    return(
    <>
        <div className={`base_frame base_backColor mt-2 ${minWidth} ${maxWidth}`}><p className="text-center font-bold">{what}は

            {/* ダイレクトにリンクさせるか、確認表示からのlocation.hrefか */}
            {!needAlert ?
            <Link className="cursor-pointer  text-blue-500 border-blue-500 border-b-2" href={route(`${routeName}`)}>こちら</Link>
            :
            <span onClick={()=>needAlertFunc(routeName)}  className="cursor-pointer  text-blue-500 border-blue-500 border-b-2">こちら</span>
            }

            から</p></div>
    </>
    )
}
