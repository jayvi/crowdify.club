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
                                    <h2>Privacy</h2>
                                    <p>Crowdify will collect information of two types about you: (1) Personal Information and (2) Web Site Information</p>
                                    <ol>
                                        <li>
                                            Personal Information refers to information that you provide us directly (i.e. through the forms on the site). This includes but is not limited to your name, photo, your date of birth, your e-mail address, your country, your interests, your profile information etc.
                                        </li>
                                        <li>
                                            Web Site Information is information that Crowdify collects about you during use of the Website. This can be information obtained from posts on your profile pages, your private messaging, and other account activity. It may also be collected about you from the messages and posts and account activity of others regarding you. It also includes information like your browser type and IP address. We may use "cookies" to help us store and collect other information based on your browsing.
                                        </li>
                                        <li>
                                            We may use your name and contact information to send you notifications with valuable information
                                        </li>
                                    </ol>
                                    <p>We reserve the right to change our Privacy Policy at any time but will give at least a week`s notice by email if we ever make any material changes.</p>
                                    <br>
                                    <h2>Terms and Conditions of Use</h2>
                                    <p><small><i>This document updated: 14/05/2014</i></small></p>
                                    <p><strong>PLEASE READ THE FOLLOWING TERMS AND CONDITIONS CAREFULLY</strong></p>
                                    <p>The Crowdify.tech site (the "Web Site") is provided by Abundance Marketing KK ("Crowdify") and/or its affiliates and subsidiaries for the use of registered Crowdify users subject to these terms and conditions of use as amended from time to time ("Terms and Conditions"), any rules that may be published from time to time by Crowdify ("Rules"). There are 2 types of user roles to which these Terms and Conditions apply. These roles are</p>
                                    <ol>
                                        <li>
                                            Registered Users (those who register so as to participate on the Web Site and create an account) and any user who chooses to browse the Web Site and access its contents, and
                                        </li>
                                        <li>
                                            Developers (those who register on the site and make use of Crowdify application program interfaces (APIs) and our developer tools or platform)
                                        </li>
                                    </ol>
                                    <p>By using this site, you are deemed to have agreed to these Terms and Conditions and any Rules as they apply to you in any and all user categories to which you may belong. Any other access to or use of the Web Site shall constitute a trespass on Crowdify computer systems, shall constitute an infringement of Crowdify intellectual property and an unlawful use of Crowdify assets unless agreed to in writing by Crowdify. Crowdify may amend this Agreement and/or Rules at any time by posting a new or amended Agreement and/or Rules as the case may be as a replacement of the previous Agreement and/or Rules Crowdify will post notice that the Agreement and/or Rules and/or Policy(ies) as applicable has been amended on this website for a period of 7 days. The amended Agreement and/or Rules, as the case may be, shall automatically be effective when posted. Your continued use of this website following the posting of any amendment notice referred to herein shall mean that you accept the terms of this Agreement and/or the Rules and/or Policy(ies) as applicable as amended. If you do not agree with any of the terms of the amended Agreement and/or Rules and/or Policy(ies) then you must terminate all use of the Web Site by deleting your account. This Agreement may not otherwise be amended.</p>
                                    <h3>1) WEB SITE USE</h3>
                                    <p>This Web Site and all of its services, including but not limited to any trading service, communications service, chat room, message board, newsgroup, advertising, game content or other interactive service that may be available to you on or through this Web Site, (the "Services") are provided to you subject to the Terms and Conditions set out herein and any Rules and Policies. By using this Web Site and Services you acknowledge and agree that you are at least 13 years old and you agree:</p>
                                    <ul>
                                        <li>
                                            (a) to provide true, accurate, current and complete information about yourself as requested
                                        </li>
                                        <li>
                                            (b) to promptly update this information as required to keep it true, accurate, current and complete and
                                        </li>
                                        <li>
                                            (c) Crowdify reserves the right to remove any such information that you provide at its discretion. If any information provided by you is untrue, inaccurate, not current or incomplete, and/or you fail to comply with the Terms and Conditions and/or any Rules, Crowdify reserves the right to terminate your account and refuse any and all current or future use of the Services.
                                        </li>
                                        <li>
                                            (d) you agree that any ideas, feedback and suggestions for enhancements and/or improvements to this website provided by you to Crowdify are assigned by you absolutely to Crowdify without compensation or any duty to account to you for such use.
                                        </li>
                                        <li>
                                            (e) No Crowdify staff will be acting as moderators of Facebook or any other communities, or any other group gathering of users unless so designated and specifically stated.
                                        </li>
                                    </ul>
                                    <h4>You shall not:</h4>
                                    <ol>
                                        <li>Resell in any shape or form any perk or discount received by you or any other member of your team or organization while using the website;</li>
                                        <li>submit or post any content, information or link to any content, information, ware or service which is obscene, illegal, sexually explicit, violent, discriminatory or contains pornographic material of any kind;</li>
                                        <li>provide any false information on the website or create an account for anyone other than yourself without permission;</li>
                                        <li>breach any of the terms of service of Twitter while using this website;</li>
                                        <li>use this website to commit any dishonest act, act of fraud or any act which infringes on the rights of any third party;</li>
                                        <li> submit any information or link to any material which infringes or potentially infringes any third party intellectual property or proprietary rights including without limitation, copyright, patent, trademark, trade secret, right of publicity or privacy;</li>
                                        <li>submit or post any information or link to any material which is defamatory, libelous, harassing or threatening of or to any person or organization;</li>
                                        <li> submit or post any information or link to any material which is harmful or potentially harmful, without limitation, that which places any person`s health or safety at risk, places the integrity of any computer system at risk or distribute any virus, Trojan horse, worm, time bomb or invasive computer program;</li>
                                        <li>submit or post any information or link to any material which in any way violates any federal, state or provincial law, statute, ordinance or regulation in any jurisdiction including without limitation those relating to export control, consumer protection, unfair competition, discrimination, false advertising or copyright:</li>
                                        <li>undertake any activity which creates liability or damage to Crowdify or its computer systems or its service providers;</li>
                                        <li>act in a manner that negatively affects other users`ability to engage in the website or services.</li>
                                        <li>manipulate or attempt to manipulate, corrupt or otherwise affect the outcome of the services, in whole or part, by among other methods, registering multiple accounts under the same or other names on Crowdify or other social networks.</li>
                                        <li>impersonate any person or entity in any way, or falsely state or otherwise misrepresent your affiliation with a person or entity;</li>
                                        <li>create repetitive tasks or blog posts</li>
                                    </ol>
                                    <p>You agree not to use any spider, robot or other automatic means to search, monitor, copy or manipulate the content of this website. You agree that you shall not use any automatic means, including software, to interfere with or attempt to interfere with Crowdify software or websites or their use by any other user. You agree not to knowingly cause any unreasonable burden or load on Crowdify software or its computer systems.</p>
                                    <p>You also agree that you will not, other than by using the Crowdify interface harvest, collect or store information about the users of this Web Site or the content posted by others on this Web Site or use such information for any purpose inconsistent with the purpose of this Web Site or for the purpose of transmitting or facilitating transmission of unsolicited bulk electronic mail or communications.</p>
                                    <p>You acknowledge that Crowdify will attempt to but may not always pre-screen, monitor, review or edit the content posted by users of the Web Site. However, Crowdify and its designees have the right (but not the obligation) at their sole discretion to remove or to request the removal of any content or advertising, in whole or part, that, in Crowdify`s judgment, does not comply with these Terms and Conditions and/or Rules or is otherwise undesirable, inappropriate or inaccurate. Crowdify is not responsible for any failure, non-failure or delay in removing such content or advertising.</p>
                                    <p>You acknowledge and agree that Crowdify may disclose any content or data on the Web Site or gathered about its users if required to do so by law or in the good faith belief that such preservation or disclosure is reasonably necessary to: (a) comply with legal process; (b) enforce these Terms and Conditions; (c) respond to claims that any content violates the rights of third parties; or (d) protect the rights, property, or personal safety of Crowdify, its users and the public.</p>
                                    <p>You understand that the technical processing and transmission of the Web site, including your content, may involve (a) transmissions over various networks and (b) changes to conform and adapt to technical requirements of connecting networks or devices. Crowdify assumes no responsibility for the deletion, loss, or modification of postings or other information submitted by you or other users to the website.</p>
                                    <p>Any or all content on the website may be purged periodically at Crowdify's sole discretion, as permitted by applicable laws. You acknowledge and agree that content you view or post is at your own discretion and risk, including any reliance on the accuracy, completeness, or usefulness of such content. In this regard, you acknowledge that you may not rely on any content and/or data created by Crowdify or submitted to or by Crowdify. You further acknowledge and agree that the views expressed on the site do not necessarily reflect the views of Crowdify, and Crowdify does not necessarily support or endorse any content posted by you or any user. You may terminate your account at any time by selecting the "Delete" option in your Account Settings. Crowdify may, in its sole discretion, terminate or suspend your access to all or part of the Web Site for any reason, including, without limitation, breach of these Terms of Service.</p>
                                    <p><strong>Use of the Crowdify Shop: Payments, Tax and Refund Policy</strong></p>
                                    <p>You agree that you will pay for all products/service you purchase through the website, and that Crowdify may charge your payment method for any products/services purchased and for any additional amounts (including any taxes, prorated amounts for upgrading or downgrading subscription purchases, and late fees, as applicable) that may be accrued by or in connection with your account. </p>
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

@stop
@section('styles')
    <style>
        .terms-of-services ul,ol{
            padding-left: 40px;
        }
        .terms-of-services p{
            /*font-family: "Open Sans",Helvetica,Arial,sans-serif;*/
            font-size: 14px;
            font-weight: 400;
            line-height: 1.8;
            color: #59626a;
        }
        .terms-of-services ul,ol li{
            /*font-family: "Open Sans",Helvetica,Arial,sans-serif;*/
            font-size: 14px;
            font-weight: 400;
            line-height: 1.8;
            color: #59626a;
        }
        .terms-of-services ul li:before{
            /*font-family: "Open Sans",Helvetica,Arial,sans-serif;*/
            content:"·";
            font-size:65px;
            vertical-align:middle;
            line-height:20px;
        }
    </style>
@stop
