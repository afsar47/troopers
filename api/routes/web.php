<?php
use Illuminate\Support\Facades\Route;
/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It is a breeze. Simply tell Lumen the URIs it should respond to
  | and give it the Closure to call when that URI is requested.
  |
 */
Route::get('/greeting','MemberController@getAllOffer' );
Route::get('/greeting_details/{offer_id}','MemberController@singleOffer' );
$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(['prefix' => '','middleware' => 'lang'], function () use ($router) {
    $router->post('demo_image_lib', 'MemberController@demo_image_lib');   
    $router->get('all_country', 'MemberController@getAllCountry');
    $router->get('all_language', 'MemberController@getAllLanguage');
    $router->post('login', 'MemberController@authenticate');
    $router->post('registrationAcc', 'MemberController@createMember');
    $router->post('registerFB', 'MemberController@createMember_fb');
    $router->post('registerGoogle', 'MemberController@createMember_google');
    $router->post('update_mobile_no', 'MemberController@UpdateMobileNo');
     $router->post('update_user_coin', 'MemberController@UpdateCoin');
    $router->post('checkMember', 'MemberController@checkMember');
    $router->post('checkMobileNumber', 'MemberController@checkMobileNumber');
    $router->post('sendOTP', 'MemberController@sendOTP');
    $router->post('forgotpassword', 'MemberController@forgotpassword');
    $router->get('version[/{versionfor}]', 'MemberController@version');
    $router->get('one_signal_app', 'MemberController@oneSignalApp');
    $router->post('verifyChecksum', 'MemberController@verifyChecksum');
    $router->post('paytm_response', 'MemberController@paytmResponse');
    $router->post('paypal_response', 'MemberController@paypalResponse');
    $router->post('instamojo_response', 'MemberController@instamojoResponse');
    $router->post('razorpay_response', 'MemberController@razorpayResponse');
    $router->post('googlepay_response', 'MemberController@googlePayResponse');
    $router->post('cashfree_response', 'MemberController@cashFreeResponse');
    $router->post('add_money', 'MemberController@addMoney');
    $router->post('payu_response', 'MemberController@payuResponse');    
    $router->post('payu_succ_fail', 'MemberController@payuSuccFail');  
});

$router->group(['middleware' => ['auth','lang']], function () use ($router) {
    $router->post('demo', 'MemberController@demo');
    $router->get('announcement', 'MemberController@getAnnouncement');
    $router->get('pin_match/{member_id}/{match_id}', 'MemberController@pinMatch');
    $router->get('all_game', 'MemberController@getAllGame');
    $router->get('lottery/{member_id}/{status}', 'MemberController@getAllLottery');
    $router->get('single_lottery/{lottery_id}/{member_id}/', 'MemberController@singleLottery');
    $router->get('product', 'MemberController@getAllProduct');
    $router->get('single_product/{product_id}', 'MemberController@singleProduct');
    
     $router->get('search_member/{name}', 'MemberController@smember');
    
    
     $router->get('offer', 'MemberController@getAllOffer');
    $router->get('single_offer/{offer_id}', 'MemberController@singleOffer');
    
    
    $router->get('all_ongoing_match/{game_id}/{member_id}', 'MemberController@getAllOngoingMatch');
    $router->get('all_game_result/{game_id}/{member_id}', 'MemberController@getAllGameResult');
    $router->get('all_play_match/{game_id}/{member_id}', 'MemberController@getAllPlayMatch');
    $router->get('my_match/{member_id}', 'MemberController@getMyMatches');
    $router->get('dashboard[/{member_id}]', 'MemberController@getDashboardDetails');
    $router->get('payment', 'MemberController@getPayment');
    $router->get('about_us', 'MemberController@aboutUs');
    $router->get('customer_support', 'MemberController@customerSupport');
    $router->get('leader_board', 'MemberController@leadeBoard');
    $router->get('match_participate[/{match_id}]', 'MemberController@matchParticipate');
    $router->get('my_profile[/{member_id}]', 'MemberController@myProfile');
    $router->get('my_refrrrals[/{member_id}]', 'MemberController@myRefrrrals');
    $router->get('my_statistics[/{member_id}]', 'MemberController@myStatistics');
    $router->get('single_game_result[/{match_id}]', 'MemberController@singleGameResult');
    $router->get('single_match/{match_id}/{member_id}', 'MemberController@singleMatch');
    $router->get('terms_conditions', 'MemberController@termsConditions');
    $router->get('top_players', 'MemberController@topPlayers');
    $router->get('transaction', 'MemberController@transaction');
    $router->get('join_match_single[/{match_id}]', 'MemberController@joinMatchSingle');
    $router->get('youtube_link', 'MemberController@youTubeLink');
    $router->get('withdraw_method', 'MemberController@withdrawMethod');
    $router->get('slider', 'MemberController@getSlider');
    $router->get('banner', 'MemberController@getBanner');
    $router->get('my_order/{member_id}', 'MemberController@MyOrder');

    $router->get('watch_earn/{member_id}', 'MemberController@getWatchAndEarn');
    $router->get('watch_earn2/{member_id}', 'MemberController@getWatchAndEarn2');
    $router->get('watch_earn_detail/{member_id}', 'MemberController@getWatchAndEarnDetail');

    $router->post('update_myprofile', 'MemberController@updateMyprofile');
    $router->post('withdraw', 'MemberController@withdraw');
    $router->post('join_match_process', 'MemberController@joinMatchProcess');
    $router->post('change_player_name', 'MemberController@changePlayerName');
    $router->post('lottery_join', 'MemberController@joinLottery');
    $router->post('product_order', 'MemberController@ProductOrder');
    $router->post('paystack_response', 'MemberController@paystackResponse');
    $router->post('add_challenge', 'MemberController@addChallenge');
    $router->get('live_challenge_list/{game_id}', 'MemberController@liveChallengeList');
    $router->get('my_challenge_list/{game_id}', 'MemberController@myChallengeList');
    $router->get('challenge_result_list/{game_id}', 'MemberController@challengeResultList');
    $router->post('accept_challenge', 'MemberController@acceptChallenge');
    $router->post('update_challenge_room', 'MemberController@updataChallengeRoom');
    $router->post('cancel_challenge', 'MemberController@cancelChallenge');
    $router->post('challenge_result_upload', 'MemberController@challengeResultUpload');
    $router->get('notification_list/{game_id}', 'MemberController@notificationList');
    $router->get('budy_list/{game_id}', 'MemberController@budyList');
    $router->get('budy_play_request/{to_member_id}/{game_id}', 'MemberController@budyPlayRequest');
    $router->get('ludo_leader_board/{game_id}', 'MemberController@ludoLeaderBoard');    
    $router->post('follow_unfollow_game', 'MemberController@followUnfollowGame');    
    $router->get('get_game_follow_status/{game_id}/{member_id}', 'MemberController@getGameFollowStatus');
});
