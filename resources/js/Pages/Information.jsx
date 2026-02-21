import Layout from "../Layout/Layout";
import { Link } from "@inertiajs/react";
import { route } from "ziggy-js";

// エラー
export default function Information({message,linkRouteName="top_page",linkPageInJpn="トップ"}){
  return(
    <Layout title="お知らせ">
    <div className="custom_body bg-green-600">
      <p>　</p>
      <div className="base_h"><h1 className="base_h1">お知らせ</h1></div>
      <div className="base_frame"><p className="base_information">{message}</p></div>
      <div className="base_link"><p className="base_link_p"><Link href={route(linkRouteName)}>{linkPageInJpn}へ</Link></p></div>
    </div>
    </Layout>
  )

}
