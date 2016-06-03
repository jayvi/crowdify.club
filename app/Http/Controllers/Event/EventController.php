<?php

namespace App\Http\Controllers\Event;

use App\Category;
use App\Event;
use App\EventRegistrant;
use App\EventTicket;
use App\Http\Controllers\Subscriptions\PaypalListener;
use App\Http\Requests\EventRequest;
use App\Services\EmailService;
use App\Services\Paypal\PaypalPaymentService;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use BlockIo;
use App\Wallet;

use App\Http\Requests;
use Illuminate\Http\Response;
use Validator;

class EventController extends BaseController implements PaypalListener
{


    private $mailerService;

    public function __construct( EmailService $mailerService, Guard $auth)
    {
        parent::__construct($auth);
        $this->mailerService = $mailerService;
        $this->middleware('auth', ['except' => ['index', 'show', 'register']]);
    }
    public function index(Request $request){

        if($request->has('category')){
            $category = Category::findOrNew($request->get('category'));
            $eventQuery = $category->events();
        }else{
            $eventQuery = Event::with(array('categories','registrants'));
            if($request->has('location')){
                $location = $request->get('location');
                $eventQuery->where('location', '=', $location);
            }
            if($request->has('title')){
                $eventQuery->where('title', 'like', '%'.$request->get('title').'%');
            }
            if($request->has('date')){
                $dates = $this->getDateInterval($request->get('date'));

                if($dates['start_date']){
                    $eventQuery->where('start_date', '>=', $dates['start_date'])->where('end_date','<=', $dates['end_date']);
                }
            }
        }
        if(!isset($location)){
            $location = null;
        }
        $events = $eventQuery->where('status','=','Published')->orderBy('created_at','desc')->get();


        return $this->createView('event.index', compact('events', 'location'));
    }

    private function getDateInterval($value){
        $data = [];
        switch($value){
            case "all":
                $data['start_date'] = null;
                $data['end_date'] = null;
                break;
            case "today":
                $data['start_date'] = Carbon::now()->startOfDay();
                $data['end_date'] = $data['start_date'];
                break;
            case "tomorrow":
                $data['start_date'] = Carbon::now()->addDay()->startOfDay();
                $data['end_date'] = Carbon::now()->addDay()->endOfDay();
                break;
            case "this_week":
                $data['start_date'] = Carbon::now()->startOfWeek();
                $data['end_date'] = Carbon::now()->startOfWeek()->endOfWeek();
                break;
            case "this_weekend":
                $data['start_date'] = Carbon::now()->endOfWeek()->addDay()->startOfDay();
                $data['end_date'] = Carbon::now()->endOfWeek()->addDay()->endOfDay();
                break;
            case "next_week":
                $data['start_date'] = Carbon::now()->endOfWeek()->addDay()->startOfDay();
                $data['end_date'] = Carbon::now()->endOfWeek()->addDay()->endOfWeek();
                break;
            case "next_month":
                $data['start_date'] = Carbon::now()->endOfMonth()->addDay()->startOfDay();
                $data['end_date'] = Carbon::now()->endOfMonth()->addDay()->endOfMonth();
                break;
        }

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        $event = new Event();
        $action = 'Create';
        $categories = Category::lists('name','id')->toArray();
        $ids = array();
        $types = [
            'Free' => 'Free',
            'Paid' => 'Paid'
        ];

        return $this->createView('event.create', compact('event', 'action','categories','ids', 'types'));
    }
    

    public function postCreate(EventRequest $request){

        $data = $request->getFieldsToSave();
        $data ['user_id'] = $this->auth->id();
        $event =  Event::create($data);

        $categoryIds = $request->get('categories');
        if($categoryIds && count($categoryIds) > 0){
            $event->categories()->attach($categoryIds);
        }

        return redirect()->route('event::show', ['id' => $event->id])->with('success','Successfully created');
        //return redirect()->route('event::my-events')->with('success','Successfully created');
    }


    public function upload(){
        return Response::make(array('success'=> 'success'), 200);
    }

    public function myEvents(Request $request)
    {
        $myEvents = $request->user()->events;
        return $this->createView('event.myEvents',compact('myEvents'));
    }

    public function myEventSearch(Request $request)
    {
        $search_text = $request->get('search_text');
        $events = Event::where('title', 'like', '%'.$search_text.'%')->get();
        $view = view('event.includes.events', array('events' => $events, 'auth' => $this->auth))->render();
        return response()->json(array('status' => 200, 'view'=> $view), 200);
    }

    public function getEdit($event_id)
    {
        $event = Event::find($event_id);
        $action = 'Update';
        $categories = Category::lists('name','id')->toArray();
        $ids = $event->categories->lists('id')->toArray();
        $types = [
            'Free' => 'Free',
            'Paid' => 'Paid'
        ];

        return $this->createView('event.create', compact('event', 'action','categories','ids','types'));
    }

    public function postEdit($event_id, EventRequest $request)
    {
        $event = Event::find($event_id);

        $data = $request->getFieldsToSave(true);
        $categoryIds = $request->get('categories');
        if($categoryIds && count($categoryIds) > 0){
            $event->categories()->sync($categoryIds);
        }
        $event->update($data);
        return redirect()->route('event::show', ['id' => $event->id])->with('success','Successfully Updated');
    }

    public function delete(Request $request)
    {
        $current_user_id = $this->auth->user()->id;
        $event_id = $request->get('event_id');
        $event = Event::where('id', $event_id)->where('user_id', $current_user_id)->first();

        if($event)
        {
            $event->delete();
            return redirect()->route('event::my-events')->with('success','Successfully deleted');
        }
        else
        {
            return redirect()->route('event::my-events')->with('error','You don\'t have permission to delete this event');
        }
    }

    public function show($event_id)
    {

        if($this->auth->user())
        {
            $current_user_id = $this->auth->user()->id;
        }
        else
        {
            $current_user_id = 0;
        }
        $event = Event::with(['registrants.ticket','tickets'])->findOrFail($event_id);

        $isRegistered = EventRegistrant::where('user_id', $current_user_id)->where('event_id','=',$event_id)->exists();
        $ticketQuantities = [];
        for ($i = 1; $i<=10; $i++){
            $ticketQuantities["$i"] = $i;
        }
        return $this->createView('event.show', compact('event', 'current_user_id','isRegistered','ticketQuantities'));
    }

    public function register(Request $request, $event_id)
    {
        if($this->auth->check())
        {
            $register = EventRegistrant::create([
                'user_id'       => $this->auth->id(),
                'first_name'    => $this->auth->user()->first_name,
                'last_name'     => $this->auth->user()->last_name,
                'email'         => $this->auth->user()->email,
                'event_id'      => $event_id
            ]);
        }
        else
        {
            $rules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'contact' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails())
            {
                return redirect()->back()->withInput()->withErrors($validator->messages());
            }
            else
            {
                $data = $request->all();
                $user = User::where('email', $request->get('email'))->first();
                if($user)
                {
                    $data['user_id'] = $user->id;
                }
                $data['event_id'] = $event_id;
                $register = EventRegistrant::create($data);
            }
        }
        if($register)
        {

            $event = Event::find($event_id);
            if($event){
                $this->mailerService->sendWithView(
                    'event.mail.event_registration',
                    array('register' => $register, 'event' => $event),
                    $from = null,
                    $register->email,
                    'Crowdify Event Registration'
                );
            }


            return redirect()->back()->with('success', 'Joined');
        }
        else
        {
            return redirect()->back()->with('error', 'Failed');
        }
    }

    public function publishEvent($event_id){
        $event = Event::find($event_id);
        if($event){
            if($this->auth->id() == $event->user_id){
                $event->status = 'Published';
                $event->update();
            }
        }
        return redirect()->back();
    }

    public function unPublishEvent($event_id){
        $event = Event::find($event_id);
        if($event){
            if($this->auth->id() == $event->user_id){
                $event->status = 'Draft';
                $event->update();
            }
        }
        return redirect()->back();
    }

    public function createTicket(Request $request, $event_id)
    {
        $this->validate($request,[
            'name' => 'required',
            'price' => 'required'
        ]);
        $data = $request->only(['name','price']);
        $data['event_id'] = $event_id;
        $ticket = new EventTicket($data);
        $ticket->save();
        return response()->json(['ticket' => $ticket],200);
    }

    public function deleteTicket(Request $request, $event_id, $ticketId)
    {
        $ticket = EventTicket::with('registrants')->find($ticketId);
        if($ticket->event_id == $event_id){
            if(count($ticket->registrants)){
                return response()->json(['message' => 'Sorry, but this tickets is already bought by '.count($ticket->registrants).' People'],400);
            }
            $ticket->delete();
            return response()->json([],200);
        }
    }

    public function buyTicket(PaypalPaymentService $paypalPaymentService, Request $request, $event_id, $ticketId)
    {
        if($request->payment == 'BitCoin'){
            $eventwallet = Wallet::where('user_id', '=', $request->euser)->first();
            $sendwallet = $eventwallet->address;
            $bitcoin = $request->bitcoin;
            $block_io = new BlockIo(env('BLOCKIO_APIKEY'), env('BLOCKIO_PIN'), env('BLOCKIO_VERSION'));
            $wallet = $this->auth->user()->wallet;
            $addressBalance = $block_io->get_address_balance(array('addresses' => $wallet->address));
            $addressBalance = floatval($addressBalance->data->available_balance);
            $bitcoin = $bitcoin*$request->ticket_amount;
            if ($addressBalance >=$bitcoin ) {
                try {
                    $transferInfo = $block_io->withdraw_from_addresses(array('amounts' => strval($bitcoin), 'from_addresses' => $wallet->address, 'to_addresses' => $sendwallet, 'pin' => env('BLOCKIO_PIN'), 'priority' => 'low'));
                } catch (\Exception $e) {
                    return response()->json(array('status' => 400, 'message' => $e->getMessage()), 400);
                }
                $registrant = new EventRegistrant([
                    'user_id' => $this->auth->user()->id,
                    'event_id' => $event_id,
                    'event_ticket_id' => $ticketId,
                    'number_of_tickets' => $request->session()->get('quantity'),
                    'email' => $this->auth->user()->email
                ]);
                $registrant->save();
                $ticket = EventTicket::find($ticketId);
                $ticket->number_of_ticket_sold = $ticket->number_of_ticket_sold + ($request->session()->has('quantity') ? $request->session()->get('quantity') : 0);
                $ticket->save();
                $request->session()->forget('quantity');
                return redirect(route('event::show',['event_id' => $event_id]))->with('success',"Thank's for Joining with us. You will be receive an email with the Ticket Information");
            } else {
                $message = 'You must have ' . $bitcoin . 'BTC to buy this Ticket';
                return redirect()->route('profile::bank')->with('error', $message);
            }
        }
        else {
            $paymentStatusUrl = route('event::tickets::payment-status', ['event_id' => $event_id, 'ticket_id' => $ticketId]);
            $paypalPaymentService->setRedirectUrls($paymentStatusUrl.'?success=true', $paymentStatusUrl.'?success=false');
            $ticket = EventTicket::find($ticketId);
            $quantity = $request->has('ticket_amount') ? $request->get('ticket_amount') : 1;
            $request->session()->put('quantity', $quantity);
            return $paypalPaymentService->processEventTicketSell($ticket, $quantity, $this);
        }
    }

    public function getPaymentStatus(PaypalPaymentService $paypalPaymentService,Request $request, $event_id, $ticketId)
    {
        if($request->has('success') && $request->get('success') == 'true'){
            if($paypalPaymentService->processApproval($request)){
                $registrant = new EventRegistrant([
                    'user_id' => $this->auth->user()->id,
                    'event_id' => $event_id,
                    'event_ticket_id' => $ticketId,
                    'number_of_tickets' => $request->session()->get('quantity'),
                    'email' => $this->auth->user()->email
                ]);
                $registrant->save();
                $ticket = EventTicket::find($ticketId);
                $ticket->number_of_ticket_sold = $ticket->number_of_ticket_sold + ($request->session()->has('quantity') ? $request->session()->get('quantity') : 0);
                $ticket->save();
                $request->session()->forget('quantity');
                return redirect(route('event::show',['event_id' => $event_id]))->with('success',"Thank's for Joining with us. You will be receive an email with the Ticket Information");
            }else{
                return redirect(route('event::show',['event_id' => $event_id]))->with('error', 'Failed to complete transaction');
            }
        }
        return redirect(route('event::show',['event_id' => $event_id]))->with('error', 'Failed to complete transaction');
    }

    public function redirectForApproval($approvalUrl)
    {
        return redirect($approvalUrl);
    }

    public function success($message)
    {
        return redirect()->back()->with('success',$message);
    }

    public function failed($error)
    {
        return redirect()->back()->with('error',$error);
    }
}
