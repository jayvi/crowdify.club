<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $dates = ['last_payment_date'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
//    protected $fillable = ['username', 'email', 'avatar'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['remember_token'];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->first_name." ".$this->last_name;
    }

    public function usertype(){
        return $this->belongsTo(UserType::class, 'usertype_id');
    }

    public function profiles(){
        return $this->hasMany(Profile::class, 'user_id');
    }

    public function events(){
        return $this->hasMany(Event::class, 'user_id');
    }

    public function bank(){
        return $this->hasOne(Bank::class, 'user_id');
    }

    public function wallet(){
        return $this->hasOne(Wallet::class, 'user_id');
    }

    public function achievements(){
        return $this->hasMany(Achievement::class, 'user_id');
    }

    public function hugs(){
        return $this->hasMany(Hug::class, 'user_id');
    }

    public function completedHugs(){
        return $this->belongsToMany(Hug::class, 'hug_completer', 'completer_id', 'hug_id')->withPivot('approved');;
    }

    public function notifications(){
        return $this->hasMany(Notification::class, 'recipient_id');
    }

    public function unseenNotifications(){
        return $this->notifications()->where('seen','=',false)->orderBy('created_at','desc')->get();
    }

    public function items(){
        return $this->hasMany(Item::class, 'user_id');
    }

    public function boughtShares(){
        return $this->hasMany(Share::class, 'investor_id');
    }

    public function perks(){
        return $this->hasMany(Perk::class, 'user_id');
    }

    public function isSponsor(){
        if($this->usertype->role == 'Sponsor'){
            return true;
        }
        return false;
	}

    public function isFounder()
    {
        if ($this->usertype->role == 'Founding Member') {
            return true;
        }
        return false;
    }
    public function isAdmin(){
        if($this->usertype->role == 'Admin'){
            return true;
        }
        return false;
    }

    public function isPaidMember(){
        if($this->usertype->role == 'Sponsor' || $this->usertype->role == 'Paid' || $this->usertype->role == 'Founding Member'){
            return true;
        }
        return false;
    }

    public function isFreeUser(){
        if($this->usertype->role == 'Free'){
            return true;
        }
        return false;
    }
    public function isDriver(){
        if($this->usertype->role == 'driver'){
            return true;
        }
        return false;
    }
    public function isCyclist(){
        if($this->usertype->role == 'cyclist'){
            return true;
        }
        return false;
    }
    public function isPilot(){
        if($this->usertype->role == 'pilot'){
            return true;
        }
        return false;
    }
    public function isAstronaut(){
        if($this->usertype->role == 'astronaut'){
            return true;
        }
        return false;
    }

    public function blogs(){
        return $this->hasMany(BlogPost::class, 'user_id');
    }

    public function twitterProfile(){
        return $this->profiles()->where('provider','=','twitter')->first();
    }

    public function places(){
        return $this->hasMany(Place::class, 'user_id');
    }

    public function subscription(){
        return $this->hasOne(PaypalAgreement::class,'user_id');
    }

    public function followers(){
        return $this->belongsToMany(User::class, 'followers_following','user_id','follower_id');
    }

    public function following(){
        return $this->belongsToMany(User::class, 'followers_following','follower_id','user_id');
    }

    public function isFollowing($user_id){
        //return true;
        return $this->following()->where('user_id','=',$user_id)->exists();
    }

    public function affiliateReference(){
        return $this->hasOne(AffiliateReference::class, 'user_id');
    }

    public function isAffiliate(){
        return Affiliate::where('username','=',$this->username)->exists();
    }

    public function statusComments()
    {
        return $this->hasMany(StatusComments::class, 'commenter_id');
    }

    public function communityPosts()
    {
        return $this->hasMany(CommunityPost::class, 'owner_id');
    }

    public function communityPostComment()
    {
        return $this->hasMany(CommunityPostComment::class, 'commenter_id');
    }

    public function scopeAdmin($query)
    {
        return $query->where('usertype_id', '=', 8);
    }
}
