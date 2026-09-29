import { router } from "@inertiajs/react";
//営業所の変種の際のpropsの確認
export default function checkEditPlaceProps({placeInformation}){
    if(!placeInformation || !["is_active","place_name","red","green","blue"].every(key=>key in placeInformation)){
        // エラーページに飛ばす
        router.get(route("error"))
    }
}
