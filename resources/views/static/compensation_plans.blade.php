@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('header')
    @include('perk.includes.header')
@stop

@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="row" >
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                            @include('perk.includes.home_nav')
                        </div>
                        <div class="col-md-7">
                            <div class="panel z1">
                                <div class="panel-body terms-of-services">
                                    <h2>The Crowdify Compensation Plan</h2>
                                    <p>Hi everyone here is how the money works here. This is an initial draft and may be subject to change after input from the community</p>
                                    <p>The general goal of this plan is to reward you for finding new members for Crowdify and then for you to benefit from their activity both on and off the site. Crowdify has several different economies and business opportunities so far and we will continue to add new ways for you and the people you introduce to benefit. The ultimate goal is for you to meet like minded people with a passion for similar things to you in cities all over the world. On the way to there you will benefit from being part of a large crowd of people.</p>
                                    <p>We are determined to reward most those who show faith and trust in Crowdify at an early stage and those who promote and use Crowdify in an effective and ethical manner.</p>
                                    <p>Initially we will pay out commissions to you all on a monthly basis beginning with a first payment on 1 June. This will coincide with the proposed release of Crowdify Coins on to the market. We hope that by August after extensive programming that we will be able to pay you on a weekly and then a daily basis. Payment will be made in Bitcoin to the Bitcoin wallet that you allocate to your account. All payments will be a month late to allow for calculations of money back guarantees. For example if somebody joins your tree on May 3 you will be paid for that sign up on July 1. July 1 will be a big day for many of us and we hope to hold a big party somewhere on the West Coast of the United States on that day to officially launch Crowdify.</p>
                                    <p>Monthly subscription payments will have to be made to your account at least once every calendar month.</p>
                                    <p>To qualify to be paid you need to have a current up to date monthly subscription. You will also have to have sponsored at least 2 people who have a current up to date subscription.</p>
                                    <p>Crowdify has 5 membership levels ~ Hiker (Free), Cyclist, Driver, Pilot and Astronaut. The benefits of membership at each of these levels can be found at <a href="http://crowdify.club/perk/membership">http://crowdify.club/perk/membership</a> Initially we will be highly circumspect about who we give a sponsor link to. Probably around 200 -300 people in the next 6 months</p>
                                    <p>There are 4 major types of bonus.</p>
                                    <h3>10% SPONSOR BONUS</h3>
                                    <p>You will be paid a one off 10% commission on everybody who joins Crowdify at your level or below under your sponsor link. If say for example somebody joins as a Driver and you are also a Driver then you will be paid $40. If they join as a Pilot you will also receive $20. If somebody joins as a Cyclist you will be paid $4.50.</p>
                                    <h3>ONGOING SPONSOR BONUS</h3>
                                    <p>You will be paid 2% of all the revenue generating activity from everybody you sponsor.</p>
                                    <h3>MONEY TREE BONUS</h3>
                                    <p>You will be paid according to who is below you in your tree and paying a monthly subscription. This will be in the form of points. For every Cyclist who pays a $9 monthly subscription under you, whether you sponsored them or not you will receive 1 point. For every Driver who pays a $40 subscription  in your tree whether you sponsored them or not you will receive 5 points. You will only get points credited for a Driver below you if you are at driver level yourself. To activate the tree Bonus you will need to reach the level of 50 points on both of your two legs.</p>
                                    <p>This bonus will pay you $25 every time you activate it. There is a maximum of 10 times a day you can do this.</p>
                                    <h3>BINARY BONUS</h3>
                                    <p>The Binary Bonus will be calculated from the level of “activity” from people in your money tree.The Binary Bonus will not take into account whether you have sponsored people or not. For example if somebody is your downline sponsors someone who creates an event that people pay to attend via the Crowdify Mingle App then you will be paid. Ditto if for example that person makes a sale of the Crowdify Online Marketing Course. There are many ways in our site to earn and you will be rewarded something every time people below you get paid. The binary bonus will be paid out every month and will depend on total volume from the whole of the Crowdify community.The initial 3 months will have to be monitiored but basically we will pay out an amount up to 45% in total by including this in the $5%. It sould be around 5-10%</p>
                                    <h3>PILOT AND ASTRONAUT BONUSES</h3>
                                    <p>All Pilots will be paid an extra 1% on all the activity of other pilots in their personal money tree.All Astronauts will be paid an extra 3% on all the activity of all Astronauts and Pilots below them in their personal money tree.</p>
                                    <h3>INACTIVITY</h3>
                                    <p>Our compensation plan is not designed to reward those who simply open an account and invite a few people and then rush off to the next opportunity. You will have to log into your Crowdify account at least once during the previous month to qualify for a payment. If you miss a monthly payment you will be automatically relegated to a position in the money tree below the lowest person in your tree who has made a monthly payment.</p>
                                    <p>There will be a growing Compensation Plan FAQ developed over the next few days and weeks.</p>
                                    <p>Thank you for being patient as we iron out an ultimate plan that is both fair and generous to everybody involved.</p>
                                    <p>We look forward to any questions that you may have about the plan on this thread in our main <a href="https://www.facebook.com/groups/crowdifyconnecting/permalink/1129785430396245/">Facebook group.</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            @include('includes.right_block')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection