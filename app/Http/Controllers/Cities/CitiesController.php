<?php
/**
 * Created by PhpStorm.
 * User: Nathan Senn
 * Date: 10/27/2015
 * Time: 4:45 PM
 */
namespace App\Http\Controllers\Cities;

use App\City;
use App\MonthlySharePrice;
use App\User;
use App\Interest;
use App\Item;
use App\CityInfo;
use App\Http\Requests\PostRequest;
use Illuminate\Auth\Guard;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class CitiesController extends Controller
{

    public function __construct(Guard $auth)
    {
        parent::__construct($auth);
        $this->middleware('auth', ['except' => ['index', 'city']]);
    }
    public function index(){
        $cities = City::orderBy('name','asc')->paginate(10);
        return $this->createView('cities.index', compact('cities'));
    }
    public function city($id){
       // $users = User::where('is_email','=',true)->where('city', '=', $id)->paginate(10);
        $city = City::where('name', $id)->first();
//        $interests = Interest::all();
//        foreach($interests as $interest) {
//            $cityinterest[$interest->name] = User::where('interest', '=', $interest->name)->where('city', '=', $id)->get();
//        }
//        foreach($cityinterest as $interest => $users){
//            $shareprices = null;
//            foreach($users as $user){
//                $price = Item::where('value','=',$interest)->where('user_id','=',$user->id)->first();
//                $shareprices[$user->username] = $price->share_price;
//            }
//            $items[$interest] = $shareprices;
//        }

       // return json_encode($cityinterest);

        $userIds = User::where('city','=',$id)->lists('id');
        $stocks =  MonthlySharePrice::with(array('user'))
            ->whereIn('user_id', $userIds)
            ->groupBy('user_id')
            ->select( '*', DB::raw( 'AVG( main_price ) as average' ) )
            ->orderBy('average','desc')
            ->take(10)
            ->get();

        $user = $this->auth->user();
        if(!$city){
            return redirect()->route('cities::home');
        }
        return $this->createView('cities.show', compact('city','user','stocks','items'));
    }
    public function getEdit($id){

        $city = City::where('name', '=', $id)->first();
        if(!$city){
            return redirect()->route('cities::home');
        }
        $action = 'Update';
        return $this->createView('cities.edit', compact('city', 'action'));
    }
    public function postEdit(PostRequest $request, $id){
        $city = City::where('name', '=', $id)->first();
        $data = $request->only(array('name','description','city_photo'));
        $city->update($data);
        return redirect()->route('cities::edit', ['id' => $id])->with('success','Successfully updated');
    }
    public function getPic(){
        $cities = CityInfo::all();
        foreach($cities as $city) {
            $citytitle = $city->title;
            $cityurl = urlencode($citytitle);
            $url = 'https://en.wikipedia.org/w/api.php?action=query&titles='.$cityurl.'&prop=pageimages&format=json&pithumbsize=400&redirects';
            $pics = file_get_contents($url);
            $pics = json_decode($pics);
            $pics = $pics->query->pages;
            if($pics) {
                foreach ($pics as $pic) {
                    if (isset($pic->thumbnail)) {
                        $pic = $pic->thumbnail->source;
                        $citydb = City::where('name', '=', $city->city)->first();
                        $citydb->city_photo = $pic;
                        $citydb->save();
                    }
                    else {
                        echo $city->title;
                    }
                }

            }
        }
    }

    public function getLeaderBoard($name){
        die();
        $userIds = User::where('city','=',$name)->lists('id');
        $leaderBoard =  MonthlySharePrice::with(array('user'))
            ->whereIn('user_id', $userIds)
            ->groupBy('user_id')
            ->select( '*', DB::raw( 'AVG( main_price ) as average' ) )
            ->orderBy('average','asc')
            ->get();
        return $leaderBoard;
    }


}