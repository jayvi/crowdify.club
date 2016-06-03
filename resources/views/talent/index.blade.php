<?php
/**
 * Created by PhpStorm.
 * User: Nathan Senn
 * Date: 11/20/2015
 * Time: 7:51 PM
 */
?>
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
            <div class="row">
                <div class="col-md-2">
                    <?php
                    use Jenssegers\Agent\Agent as Agent;
                    $Agent = new Agent();
                    ?>
                    <?php
                    if ($Agent->isMobile()) {
                    ?>
                    <ul class="nav nav-tabs">
                        <li role="presentation" class="active"><a href="{{ route('talent::home') }}">Home</a></li>
                        <li role="presentation"><a href="{{ route('talent::post') }}">Create</a></li>
                        <li role="presentation"><a href="{{ route('talent::manage') }}">Manage</a></li>
                    </ul>
                    <?php
                    }
                    ?>
                    @include('perk.includes.talent_nav')
                </div>
                <div class="col-md-10">
                    <div class="col-md-12 talent">
                        <h1>Talent</h1>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-6  talent">
                            <p>Welcome to the talent exchange on Crowdify. Have a project and need work done? Trade your Bitcoin or CrowdCoins to get the
                            job done. Use your talent to earn bitcoins and crowdcoins</p>
                        </div>
                        <div class="col-md-3">
                        </div>
                    </div>
                    <div class="col-md-3 talent">
                        <img class="talent" src="assets/images/talent_code.png">
                        <h4><a href="http://talent.crowdify.tech/talents/Websites, IT & Software">Websites, IT & Software</a></h4>
                        <p><a href="http://talent.crowdify.tech/talents/PHP">PHP,</a> <a href="http://talent.crowdify.tech/talents/HTML">HTML,</a> <a href="http://talent.crowdify.tech/talents/Software Architecture">Software Architecture,</a> <a href="http://talent.crowdify.tech/talents/Wordpress">Wordpress,</a> <a href="http://talent.crowdify.tech/talents/MySQL">MySQL</a></p>
                </div>
                    <div class="col-md-3 talent">
                        <img class="talent" src="assets/images/talent_writing.png">
                        <h4><a href="http://talent.crowdify.tech/talents/Writing & Content">Writing & Content</a></h4>
                        <p><a href="http://talent.crowdify.tech/talents/Articals">Articals,</a> <a href="http://talent.crowdify.tech/talents/Copywriting">Copywriting,</a> <a href="http://talent.crowdify.tech/talents/Content Writing">Content Writing,</a> <a href="http://talent.crowdify.tech/talents/Ghostwriting">Ghostwriting,</a> <a href="http://talent.crowdify.tech/talents/Translation">Translation</a></p>
                    </div>
                    <div class="col-md-3 talent">
                        <img class="talent" src="assets/images/talent_design.png">
                        <h4><a href="http://talent.crowdify.tech/talents/Design & Media">Design & Media</a></h4>
                        <p><a href="http://talent.crowdify.tech/talents/Graphic Design">Graphic Design,</a> <a href="http://talent.crowdify.tech/talents/Website Design">Website Design,</a> <a href="http://talent.crowdify.tech/talents/Logos">Logos,</a> <a href="http://talent.crowdify.tech/talents/Photoshop">Photoshop,</a> <a href="http://talent.crowdify.tech/talents/CSS">CSS</a></p>
                    </div>
                    <div class="col-md-3 talent">
                        <img class="talent" src="assets/images/talent_sales.png">
                        <h4><a href="http://talent.crowdify.tech/talents/Sales & Marketing">Sales & Marketing</a></h4>
                        <p><a href="http://talent.crowdify.tech/talents/Internet Marketing">Internet Marketing, </a><a href="http://talent.crowdify.tech/talents/Sales">Sales, </a><a href="http://talent.crowdify.tech/talents/Facebook">Facebook, </a><a href="http://talent.crowdify.tech/talents/Social Media">Social Media</a></p>
                    </div>
                    <div class="col-md-3 talent">
                        <img class="talent" src="assets/images/talent_post.png">
                        <h4><a href="{{ route('talent::post') }}">Post your Talent</a></h4>
                    </div>
                    <div class="col-md-3 talent">
                        <img class="talent" src="assets/images/talent_request.png">
                        <h4><a href="{{ route('talent::request') }}">Request Talents</a></h4>
                    </div>
                    <div class="col-md-3 talent">
                        <img class="talent" src="assets/images/talent_manage.png">
                        <h4><a href="{{ route('talent::orders') }}">Manage talents and orders</a></h4>
                    </div>
                </div>
            </div>
        </div>
    </main>


@stop