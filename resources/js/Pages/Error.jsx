import Layout from "../Layout/Layout";
import { Link } from "@inertiajs/react";
import { route } from "ziggy-js";

// エラー
export default function Error({message,backRoute}){
  return(
    <Layout title="エラーのお知らせ">
     <div className="custom_body bg-green-600">
        <p>　</p>
        <div className="base_h"><h1 className="base_h1">エラーのお知らせ</h1></div>
        <div className="base_frame"><p className="base_error">{message}</p></div>
        <div className="base_link"><p className="base_link_p"><Link href={route(backRoute)}>{backRoute==="top_page" ? "トップへ":"戻る"}</Link></p></div>
      </div>
    </Layout>
  )

}
