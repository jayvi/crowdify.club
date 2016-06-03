<?php

namespace App\Http\Controllers\Talent;

use App\Category;
use App\Category2;
use App\Interest;
use App\Wallet;
use App\Talentreq;
use Illuminate\Http\Request;
use App\Repositories\DataRepository;
use Validator;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Requests;
use App\Talent;
use App\TalentOrder;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use BlockIo;
use Response;
use App\Date;
use Intervention\Image\Facades\Image;
use DateTime;
use DateTimeZone;
use App\Bidtalent;

date_default_timezone_set('UTC');

class TalentController extends Controller
{
    public function __construct(Guard $auth, DataRepository $repository)
    {
        parent::__construct($auth);
        $this->middleware('auth', ['except' => ['index', 'show']]);
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->createView('talent.index');
    }

    public function viewTalent($id)
    {
        $talent = Talent::where('id', '=', $id)->first();
        $ratings = TalentOrder::where('talent_id', '=', $id)->where('star_rating', '>=', 1)->get();
        $user = User::where('id', '=', $talent->user_id)->first();
        return $this->createView('talent.talent', compact('talent', 'user', 'ratings'));
    }

    public function bidRequest(Request $request, $id)
    {
        $data = $request->all();
        $data['request_id'] = $id;
        $data['bidder_id'] = $this->auth->id();
        if (Bidtalent::create($data)) {
            return redirect()->route('talent::request', array('id' => $id))->with('success', 'Bid Successfull');
        } else {
            return redirect()->route('talent::request', array('id' => $id))->with('error', 'Error');
        }
    }

    public function viewRequest($id)
    {
        $talent = Talentreq::where('id', '=', $id)->first();
        $ratings = TalentOrder::where('talent_id', '=', $id)->where('star_rating', '>=', 1)->get();
        $bids = Bidtalent::where('request_id', '=', $id)->get();
        $user = User::where('id', '=', $talent->user_id)->first();
        $bid = null;
        return $this->createView('talent.talentreq', compact('talent', 'user', 'ratings', 'bids', 'bid'));
    }

    public function requestTalent()
    {
        $categories = $this->repository->getTalents(true);
        $action = 'Create';
        return $this->createView('talent.request', compact('categories', 'action'));
    }

    public function acceptBid(Request $request)
    {
        $req = $request->req;
        $treq = Bidtalent::where('request_id', '=', $req)->get();
        $gbc = 0;
        foreach ($treq as $creq) {
            if ($creq->status == 1) {
                $gbc = 1;
                break;
            }
        }
        if ($gbc) {
            $amount = Talentreq::where('id', '=', $req)->first();
            $amount = $amount->bitcoins;
            $block_io = new BlockIo(env('BLOCKIO_APIKEY'), env('BLOCKIO_PIN'), env('BLOCKIO_VERSION'));
            $wallet = $this->auth->user()->wallet;
            try {
                $transferInfo = $block_io->withdraw_from_addresses(array('amounts' => strval($amount), 'from_addresses' => $wallet->address, 'to_addresses' => '34z1LXGG4j7ieYNpzUM3iYWwuykLDT46Qa', 'pin' => env('BLOCKIO_PIN'), 'priority' => 'low'));
            } catch (\Exception $e) {
                return response()->json(array('status' => 400, 'message' => $e->getMessage()), 400);
            }
            $addressBalance = $block_io->get_address_balance(array('addresses' => $wallet->address));
//
//                    $log = print_r($addressBalance);
//                    Log::info(' auth user balance after transfer');
//                  Log::info($log);

            // Update our current wallets info
            $wallet->balance = floatval($addressBalance->data->available_balance);
            $wallet->pending_balance = floatval($addressBalance->data->pending_received_balance);
            $wallet->save();
            $this->auth->user()->bank->bit_coins = $wallet->balance;
            $this->auth->user()->bank->update();
        }
        $id = $request->id;
        $bid = Bidtalent::where('id', '=', $id)->first();
        $bid->status = 1;
        $bid->save();
        return redirect()->route('talent::home')->with('success', 'Bid Accepted');
    }

    public function cancelJob(Request $request)
    {
        $id = $request->id;
        $req = $request->req;
        $bidder = $request->bidder;
        $treq = Bidtalent::where('request_id', '=', $req)->get();
        $gbc = 0;
        $i = 0;
        foreach ($treq as $creq) {
            if ($creq->status == 1) {
                $i = $i + 1;
            }
            if ($i = 2) {
                $gbc = 1;
                break;
            }
        }
        if ($gbc > 0) {
            $amount = Talentreq::where('id', '=', $req)->first();
            $amount = $amount->bitcoins;
            $block_io = new BlockIo(env('BLOCKIO_APIKEY'), env('BLOCKIO_PIN'), env('BLOCKIO_VERSION'));
            $wallet = $this->auth->user()->wallet;
            try {
                $transferInfo = $block_io->withdraw_from_addresses(array('amounts' => strval($amount), 'from_addresses' => '34z1LXGG4j7ieYNpzUM3iYWwuykLDT46Qa', 'to_addresses' => $wallet->address, 'pin' => env('BLOCKIO_PIN'), 'priority' => 'low'));
            } catch (\Exception $e) {
                return response()->json(array('status' => 400, 'message' => $e->getMessage()), 400);
            }
            $addressBalance = $block_io->get_address_balance(array('addresses' => $wallet->address));
//
//                    $log = print_r($addressBalance);
//                    Log::info(' auth user balance after transfer');
//                  Log::info($log);

            // Update our current wallets info
            $wallet->balance = floatval($addressBalance->data->available_balance);
            $wallet->pending_balance = floatval($addressBalance->data->pending_received_balance);
            $wallet->save();
            $this->auth->user()->bank->bit_coins = $wallet->balance;
            $this->auth->user()->bank->update();
        }
        $bid = Bidtalent::where('id', '=', $id)->first();
        $bid->status = 1;
        $bid->save();
        return redirect()->route('talent::home')->with('success', 'Job Canceled');

    }

    public function releaseBtc(Request $request)
    {
        $block_io = new BlockIo(env('BLOCKIO_APIKEY'), env('BLOCKIO_PIN'), env('BLOCKIO_VERSION'));
        $id = $request->id;
        $bid = Bidtalent::where('id', '=', $id)->first();
        $req = $request->req;
        $amount = Talentreq::where('id', '=', $req)->first();
        $amount = $amount->bitcoins;
        $bidder = $request->bidder;
        $wallet = Wallet::where('user_id', '=', $bidder)->first();
        $authUserWallet = $this->auth->user()->wallet;
        if(!$authUserWallet){
            return redirect()->back()->with('error',"You don't have any bitcoin wallet. Please go to bank and create an wallet");
        }
        if(!$wallet){
            return redirect()->back()->with('error',"The bidder doesn't have any wallet. Please tell him to create an wallet");
        }
        try {
            //$transferInfo = $block_io->withdraw_from_addresses(array('amounts' => strval($amount), 'from_addresses' => '34z1LXGG4j7ieYNpzUM3iYWwuykLDT46Qa', 'to_addresses' => $wallet->address, 'pin' => env('BLOCKIO_PIN'), 'priority' => 'low'));
            $transferInfo = $block_io->withdraw_from_addresses(array('amounts' => strval($amount), 'from_addresses' => $authUserWallet->address, 'to_addresses' => $wallet->address, 'pin' => env('BLOCKIO_PIN'), 'priority' => 'low'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
            //return response()->json(array('status' => 400, 'message' => $e->getMessage()), 400);
        }
        try{
            $addressBalance = $block_io->get_address_balance(array('addresses' => $wallet->address));
            // Update our current wallets info
            $wallet->balance = floatval($addressBalance->data->available_balance);
            $wallet->pending_balance = floatval($addressBalance->data->pending_received_balance);
            $wallet->save();
            $this->auth->user()->bank->bit_coins = $wallet->balance;
            $this->auth->user()->bank->update();
            $bid->status = 2;
            $bid->save();
        }catch(\Exception $e){

        }
        return redirect()->route('talent::home')->with('success', 'Bitcoins Released');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate()
    {
        $categories = $this->repository->getTalents(true);
        $action = 'Create';
        return $this->createView('talent.create', compact('action', 'categories'));
    }

    public function getTalents($id)
    {
        $talents = Talent::where('category_1', '=', $id)->orderBy('created_at', 'desc')->get();
        $talentsreq = Talentreq::where('category_1', '=', $id)->orderBy('created_at', 'desc')->get();
        if (!$talents->count()) {
            $talents = Talent::where('category_2', '=', $id)->orderBy('created_at', 'desc')->get();
            $talentsreq = Talentreq::where('category_2', '=', $id)->orderBy('created_at', 'desc')->get();
        }
        return $this->createView('talent.talents', compact('talents', 'talentsreq'));
    }

    public function allTalent()
    {
        $talents = Talent::orderBy('created_at', 'desc')->get();
        $talentsreq = null;
        return $this->createView('talent.talents', compact('talents', 'talentsreq'));
    }

    public function allRequest()
    {
        $talentsreq = Talentreq::orderBy('created_at', 'desc')->get();
        $talents = null;
        return $this->createView('talent.talents', compact('talentsreq', 'talents'));
    }

    public function addTime(Request $request)
    {
        $data = $request->all();
        $entity = Date::create($data);
    }

    public function postCreate(Request $request)
    {
        $this->validate($request, [
            'category_1' => 'required',
            'title'  => 'required',
            'metatag' => 'required',
            'description' => 'required|min:50',
            'days' => 'required|numeric|min:0',
            'bitcoins' => 'numeric|min:0',
            'crowdcoins' => 'numeric|min:0',
            'talent_photo' => 'required|image'
        ],[
            'metatag.required' => 'The short description field is required',
            'days.min' => 'Days on average must be a positive number',
            'bitcoins.min' => 'Bitcoins must be a positive number',
            'crowdcoins.min' => 'Crowdcoins must be a positive number',
        ]);
        
        $data['user_id'] = $this->auth->id();
        $data['category_1'] = $request->category_1;
        $data['category_2'] = $request->category_2;
        $data['title'] = $request->title;
        $data['bitcoins'] = $request->bitcoins;
        $data['crowdcoins'] = $request->crowdcoins;
        $data['metatag'] = $request->metatag;
        $data['description'] = $request->description;
        $data['talent_photo'] = $this->savePhoto($request);
        $data['days'] = $request->days;
        if (Talent::create($data)) {
            return redirect()->route('talent::home')->with('success', 'Successfully added');
        } else {
            return redirect()->route('talent::home')->with('error', 'Error in post');
        }

    }

    public function postRequest(Request $request)
    {

        $data['user_id'] = $this->auth->id();
        $data['category_1'] = $request->category_1;
        $data['category_2'] = $request->category_2;
        $data['title'] = $request->title;
        $data['bitcoins'] = $request->bitcoins;
        $data['crowdcoins'] = $request->crowdcoins;
        $data['metatag'] = $request->metatag;
        $data['description'] = $request->description;
        $data['talent_photo'] = $this->savePhoto($request);
        if (Talentreq::create($data)) {
            return redirect()->route('talent::home')->with('success', 'Successfully added');
        } else {
            return redirect()->route('talent::home')->with('error', 'Error in post');
        }

    }

    public function postEdit(Request $request, $id)
    {
        $talent = Talent::where('id', '=', $id)->first();
        $talent->title = $request->title;
        $talent->bitcoins = $request->bitcoins;
        $talent->crowdcoins = $request->crowdcoins;
        $talent->metatag = $request->metatag;
        $talent->description = $request->description;
        if ($this->savePhoto($request)) {
            $talent->talent_photo = $this->savePhoto($request);
        }
        $talent->days = $request->days;
        if ($talent->update()) {
            return redirect()->route('talent::home')->with('success', 'Successfully Edited');
        } else {
            return redirect()->route('talent::home')->with('error', 'Error in post');
        }

    }

    public function getRatings($id)
    {
        $talent = Talent::where('id', '=', $id)->first();
        $user = $talent->user_id;
        $ratings = TalentOrder::where('seller_id', '=', $user)->where('star_rating', '>=', 1)->get();
        $stars = 0;
        foreach ($ratings as $rating) {
            $stars = $rating->star_rating + $stars;
        }
        $stars = $stars / $ratings->count();
        $data['number_votes'] = $ratings->count();
        $data['dec_avg'] = $stars;
        $data['whole_avg'] = round($stars);
        $data = json_encode($data);
        return $data;
    }

    public function getDates($id)
    {
        $timezone = null;
        $date = Date::where('job_id', '=', $id)->get();
        if ($date->count()) {
            foreach ($date as $dates) {
                $data['title'] = $dates->title;
                $data['start'] = $dates->date;
                $event = new Bookdate($data, $timezone);
                $output_arrays[] = $event->toArray();
            }
            echo json_encode($output_arrays);
        }

    }

    public function setRating(Request $request)
    {
        preg_match('/star_([1-5]{1})/', $request->clicked_on, $match);
        $vote = $match[1];
        $data['whole_avg'] = $vote;
        return json_encode($data);
    }

    public function viewOrder($id)
    {
        $order = TalentOrder::where('id', '=', $id)->first();
        $talent = Talent::where('id', '=', $order->talent_id)->first();
        $user = User::where('id', '=', $order->user_id)->first();
        return $this->createView('talent.order', compact('talent', 'order', 'user'));
    }

    public function viewPurchase($id)
    {
        $order = TalentOrder::where('id', '=', $id)->first();
        $talent = Talent::where('id', '=', $order->talent_id)->first();
        $user = User::where('id', '=', $talent->user_id)->first();
        return $this->createView('talent.purchase', compact('talent', 'order', 'user'));
    }

    public function editTalent($id)
    {
        $talent = Talent::where('id', '=', $id)->first();
        $ratings = TalentOrder::where('talent_id', '=', $id)->where('star_rating', '>=', 1)->get();
        $user = User::where('id', '=', $talent->user_id)->first();
        if ($user->id == $this->auth->id()) {
            return $this->createView('talent.manage_talent', compact('talent', 'user', 'ratings'));
        } else {
            return redirect()->route('talent::home')->with('error', 'Not your talent!');
        }
    }

    public function finishOrder(Request $request, $id)
    {
        $order = TalentOrder::where('id', '=', $id)->first();
        $order->status = $request->status;;
        $order->update();
        return redirect()->route('talent::home')->with('success', 'Successfully marked finished');
    }

    public function finishPurchase(Request $request, $id)
    {
        $order = TalentOrder::where('id', '=', $id)->first();
        $seller = $order->seller_id;
        $bitcoins = $order->bitcoins - .0002;
        $wallet = Wallet::where('id', '=', $seller)->first();
        $order->status = $request->status;
        $order->feedback = $request->feedback;
        $order->star_rating = $request->stars;
        if ($order->update()) {
            $block_io = new BlockIo(env('BLOCKIO_APIKEY'), env('BLOCKIO_PIN'), env('BLOCKIO_VERSION'));
            $wallet = $this->auth->user()->wallet;
            try {
                $transferInfo = $block_io->withdraw_from_addresses(array('amounts' => strval($bitcoins), 'from_addresses' => '34z1LXGG4j7ieYNpzUM3iYWwuykLDT46Qa', 'to_addresses' => $wallet->address, 'pin' => env('BLOCKIO_PIN'), 'priority' => 'low'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error',$e->getMessage());
               // return response()->json(array('status' => 400, 'message' => $e->getMessage()), 400);
            }
            return redirect()->route('talent::home')->with('success', 'Successfull');
        } else {
            return redirect()->route('talent::home')->with('error', 'Error please contact support');
        }
    }

    public function It()
    {

    }

    public function manageTalent()
    {
        $talents = Talent::where('user_id', '=', $this->auth->id())->get();
        return $this->createView('talent.manage', compact('talents'));
    }

    public function reqMod()
    {
        $talents = Talentreq::where('user_id', '=', $this->auth->id())->get();
        return $this->createView('talent.reqmod', compact('talents'));
    }

    public function manageOrders()
    {
        $purchases = TalentOrder::where('user_id', '=', $this->auth->id())->get();
        $orders = TalentOrder::where('seller_id', '=', $this->auth->id())->get();
        $talents = Talent::where('user_id', '=', $this->auth->id())->get();
        $talentreq = Talentreq::where('user_id', '=', $this->auth->id())->get();
        foreach ($orders as $order) {
            $talent[$order->talent_id] = Talent::where('id', '=', $order->talent_id)->first();
        }
        foreach ($purchases as $purchase) {
            $ptalent[$purchase->talent_id] = Talent::where('id', '=', $purchase->talent_id)->first();
        }
        return $this->createView('talent.orders', compact('purchases', 'talentreq', 'orders', 'talents', 'talent', 'ptalent'));
    }

    public function getCategory($id)
    {
        $categorys = Category2::where('category_id', '=', $id)->get();
        $options = array();
        foreach ($categorys as $category) {
            $options += array($category->name => $category->name);
        }
        return Response::json($options, 200);
    }

    public function buyTalent(Request $request, $id)
    {
        $data['user_id'] = $this->auth->id();
        $data['talent_id'] = $id;
        $data['status'] = 0;
        $data['bitcoins'] = $request->bitcoins;
        $data['seller_id'] = $request->seller;
        if (TalentOrder::create($data)) {
            $block_io = new BlockIo(env('BLOCKIO_APIKEY'), env('BLOCKIO_PIN'), env('BLOCKIO_VERSION'));
            $wallet = $this->auth->user()->wallet;
            try {
                $transferInfo = $block_io->withdraw_from_addresses(array('amounts' => strval($request->get('bitcoins')), 'from_addresses' => $wallet->address, 'to_addresses' => '34z1LXGG4j7ieYNpzUM3iYWwuykLDT46Qa', 'pin' => env('BLOCKIO_PIN'), 'priority' => 'low'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error',$e->getMessage());
                //return response()->json(array('status' => 400, 'message' => $e->getMessage()), 400);
            }
            $addressBalance = $block_io->get_address_balance(array('addresses' => $wallet->address));
//
//                    $log = print_r($addressBalance);
//                    Log::info(' auth user balance after transfer');
//                  Log::info($log);

            // Update our current wallets info
            $wallet->balance = floatval($addressBalance->data->available_balance);
            $wallet->pending_balance = floatval($addressBalance->data->pending_received_balance);
            $wallet->save();
            $this->auth->user()->bank->bit_coins = $wallet->balance;
            $this->auth->user()->bank->update();
            return redirect()->route('talent::home')->with('success', 'Successfully Booked');
        } else {
            return redirect()->route('talent::home')->with('error', 'Error please contact support');
        }
    }

    private function savePhoto(Request $request)
    {
        if ($request->hasFile('talent_photo')) {
            $file = $request->file('talent_photo');
            $destinationPath = public_path() . "/uploads/talent/images/original/";
            $fileName = rand(1, 100000) . strtotime(date('Y-m-d H:i:s')) . $request->user()->id . "." . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);

            //$fileName = $destinationPath.$fileName;
            $savedFileName = "/uploads/talent/images/original/" . $fileName;
            return $savedFileName;
        } else if ($request->has('photo_url')) {
            return $request->get('photo_url');
        }
        return null;
    }

}

//--------------------------------------------------------------------------------------------------
// Utilities for our event-fetching scripts.
//
// Requires PHP 5.2.0 or higher.
//--------------------------------------------------------------------------------------------------

// PHP will fatal error if we attempt to use the DateTime class without this being set.


class Bookdate
{

    // Tests whether the given ISO8601 string has a time-of-day or not
    const ALL_DAY_REGEX = '/^\d{4}-\d\d-\d\d$/'; // matches strings like "2013-12-29"

    public $title;
    public $allDay; // a boolean
    public $start; // a DateTime
    public $end; // a DateTime, or null
    public $properties = array(); // an array of other misc properties


    // Constructs an Event object from the given array of key=>values.
    // You can optionally force the timezone of the parsed dates.
    public function __construct($array, $timezone = null)
    {

        $this->title = $array['title'];

        if (isset($array['allDay'])) {
            // allDay has been explicitly specified
            $this->allDay = (bool)$array['allDay'];
        } else {
            // Guess allDay based off of ISO8601 date strings
            $this->allDay = preg_match(self::ALL_DAY_REGEX, $array['start']) &&
                (!isset($array['end']) || preg_match(self::ALL_DAY_REGEX, $array['end']));
        }

        if ($this->allDay) {
            // If dates are allDay, we want to parse them in UTC to avoid DST issues.
            $timezone = null;
        }

        // Parse dates
        $this->start = parseDateTime($array['start'], $timezone);
        $this->end = isset($array['end']) ? parseDateTime($array['end'], $timezone) : null;

        // Record misc properties
        foreach ($array as $name => $value) {
            if (!in_array($name, array('title', 'allDay', 'start', 'end'))) {
                $this->properties[$name] = $value;
            }
        }
    }


    // Returns whether the date range of our event intersects with the given all-day range.
    // $rangeStart and $rangeEnd are assumed to be dates in UTC with 00:00:00 time.
    public function isWithinDayRange($rangeStart, $rangeEnd)
    {

        // Normalize our event's dates for comparison with the all-day range.
        $eventStart = stripTime($this->start);
        $eventEnd = isset($this->end) ? stripTime($this->end) : null;

        if (!$eventEnd) {
            // No end time? Only check if the start is within range.
            return $eventStart < $rangeEnd && $eventStart >= $rangeStart;
        } else {
            // Check if the two ranges intersect.
            return $eventStart < $rangeEnd && $eventEnd > $rangeStart;
        }
    }


    // Converts this Event object back to a plain data array, to be used for generating JSON
    public function toArray()
    {

        // Start with the misc properties (don't worry, PHP won't affect the original array)
        $array = $this->properties;

        $array['title'] = $this->title;

        // Figure out the date format. This essentially encodes allDay into the date string.
        if ($this->allDay) {
            $format = 'Y-m-d'; // output like "2013-12-29"
        } else {
            $format = 'c'; // full ISO8601 output, like "2013-12-29T09:00:00+08:00"
        }

        // Serialize dates into strings
        $array['start'] = $this->start->format($format);
        if (isset($this->end)) {
            $array['end'] = $this->end->format($format);
        }

        return $array;
    }

}


// Date Utilities
//----------------------------------------------------------------------------------------------


// Parses a string into a DateTime object, optionally forced into the given timezone.
function parseDateTime($string, $timezone = null)
{
    $date = new DateTime(
        $string,
        $timezone ? $timezone : new DateTimeZone('UTC')
    // Used only when the string is ambiguous.
    // Ignored if string has a timezone offset in it.
    );
    if ($timezone) {
        // If our timezone was ignored above, force it.
        $date->setTimezone($timezone);
    }
    return $date;
}


// Takes the year/month/date values of the given DateTime and converts them to a new DateTime,
// but in UTC.
function stripTime($datetime)
{
    return new DateTime($datetime->format('Y-m-d'));
}

