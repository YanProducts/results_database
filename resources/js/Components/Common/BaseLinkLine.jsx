import { Link } from "@inertiajs/react"
export default function BaseLinkLine({href,what}){
    return(
            <div className="base_frame min-w-75 max-w-100 mt-3"><p className="text-center font-bold text-lg"><Link className="cursor-pointer  text-blue-500 border-blue-500 border-b-2" href={href}>{what}</Link></p></div>
    )
}
