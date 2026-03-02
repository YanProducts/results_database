import { setBackColor } from "../Support/Role/setBackColor";

// roleごとのLayout(Authページは全role共通に使用しているので、ここでは適応外)
export function RoleLayout({children,prefix}){
    return(
         <div className={`h-full min-h-screen ${setBackColor(prefix)}`}>
            {children}
        </div>
    )
}
