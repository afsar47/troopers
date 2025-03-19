<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Illuminate\Validation\Rule;
use Razorpay\Api\Api;
use Jenssegers\Agent\Agent;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;
use IEXBase\TronAPI\Tron;
use App\lib\Image as OC_Image;

class MemberController extends Controller {

    public function __construct() {
        Cache::flush();
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        $this->base_url = env('APP_URL');
        $this->profile_img_size_array = array(100 => 100);

        $data = DB::table('web_config')
                ->get();

        foreach ($data as $row) {
            $this->system_config[$row->web_config_name] = $row->web_config_value;
        }

        $this->timezone = $this->system_config['timezone'];       

        if ($this->system_config['under_maintenance'] == '1') {
            $array['message'] = '<h1>Under Maintenance</h1>';
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function demo(Request $request) {        
        $file = $request->file('image');
        // Define our destinations
        
        $destinationPath = substr(base_path(), 0, strrpos(base_path(), '/')) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR ;
        $destinationPathThumb = $destinationPath . 'thumb/';
        // What's the original filename
        if($file){
            
            $filename = $file->getClientOriginalName();
            // Upload the original
            $original = $file->move($destinationPath, $filename);
            // Create a thumb sized from the original and save to the thumb path
            foreach ($this->profile_img_size_array as $key => $val) {
                $thumb = Image::make($original->getRealPath())
                            ->resize($key, $val)
                            ->save($destinationPathThumb . $filename); 
            } 
        }  
        
        $array = array(
            "status" => true,
            "message" => " image uploaded",
        );
        return $array;
           
    }

    public function demo_image_lib(Request $request) {
                       
        $file = $request->file('image');

        // Define our destinations        
        $destinationPath = substr(base_path(), 0, strrpos(base_path(), '/')) . '/uploads/';
        $destinationPathThumb = $destinationPath . 'thumb/';

        // What's the original filename
        if($file){            
                
            $filename = $file->getClientOriginalName();
            
            // Upload the original
            $original = $file->move($destinationPath, $filename);           
            
            // Create a thumb sized from the original and save to the thumb path
            foreach ($this->profile_img_size_array as $key => $val) {
                $real_path = $original->getRealPath();                
                list($width_orig, $height_orig, $image_type) = getimagesize($real_path);				                                                
                                                            
                if ($width_orig != $key || $height_orig != $val) {  
                    $oc_image = new OC_Image;                                                                                                                                                                  
                    $oc_image::initialize($real_path);                                                       
                    $oc_image::resize($key, $val);
                    $oc_image::save($destinationPathThumb . $key . "x" . $val . "_" . $filename);
                } else {
                    copy($real_path, $destinationPathThumb . $key . "x" . $val . "_" . $filename);
                }                 
            } 
        }        
        
        $array = array(
            "status" => true,
            "message" => " image uploaded",
        );
        
        return $array;
    }   

    public function GetRefrralNo($promo_code) {
        $data = DB::table('member')
                ->where('user_name', trim($promo_code))
                ->first();
        if ($data) {
            return $data->member_id;
        }
    }

    function generate_otp($len) {
        $r_str = "";
        $chars = "0123456789";
        do {
            $r_str = "";
            for ($i = 0; $i < $len; $i++) {
                $r_str .= substr($chars, rand(0, strlen($chars)), 1);
            }
        } while (strlen($r_str) != $len);
        return $r_str;
    }

    function generateUsername($user_name) {
        $chars = "0123456789";
        $r_str = '';
        for ($i = 0; $i < 6; $i++) {
            $r_str .= substr($chars, rand(0, strlen($chars)), 1);
        }
        $new_user_name = $user_name . $r_str;
        $data = DB::table('member')
                ->where('user_name', $new_user_name)
                ->first();
        if ($data) {
            $this->generateUsername($user_name);
        } else {
            return $new_user_name;
        }
    }

    public function checkMobileNumber(Request $request) {
        $array = array();
        $validator = Validator::make($request->all(), [
                    'mobile_no' => 'required|unique:member|numeric|digits_between:7,15',], [
                    'mobile_no.required' => trans('message.err_mobile_no_req'),
                    'mobile_no.numeric' => trans('message.err_mobile_no_num'),
                    'mobile_no.unique' => trans('message.err_mobile_no_exist'),
                    'mobile_no.digits_between' => trans('message.err_mobile_no_7to15'),
        ]);
        if ($validator->fails()) {
            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
        } else {
            $referral_id = 0;
            if ($request->input('promo_code') && $request->input('promo_code') != '') {
                $referral_id = $this->GetRefrralNo($request->input('promo_code'));
                if (!$referral_id) {
                    $array['status'] = false;
                    $array['title'] = 'Error!';
                    $array['message'] = trans('message.err_referral_code_valid');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
            $array['status'] = true;
            $array['title'] = 'Success!';
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function checkMember(Request $request) {
        if (($request->input('submit')) && $request->input('submit') == 'register') {
            $array = array();
            $validator = Validator::make($request->all(), [
                        'promo_code' => 'exists:member,user_name',
                        'mobile_no' => 'required|unique:member|numeric|digits_between:7,15',
                        'user_name' => 'required|unique:member',
//                        'country_id' => 'required',
                        'country_code' => 'required',
                        'email_id' => [
                            'required',
                            Rule::unique('member')->where(function ($query) {
                                        return $query->where('login_via', '0');
                                    }),
                        ],
                        'password' => 'required|min:6',
                        'cpassword' => 'required|same:password|min:6',
                            ], [
                        'promo_code.exists' => trans('message.err_referral_code_valid'),
                        'user_name.required' => trans('message.err_username_req'),
                        'user_name.unique' => trans('message.err_username_exist'),
                        'mobile_no.required' => trans('message.err_mobile_no_req'),
                        'mobile_no.numeric' => trans('message.err_mobile_no_num'),
                        'mobile_no.unique' => trans('message.err_mobile_no_exist'),
                        'mobile_no.digits_between' => trans('message.err_mobile_no_7to15'),
                        'email_id.required' => trans('message.err_email_req'),
                        'email_id.email' => trans('message.err_email_valid'),
                        'email_id.unique' => trans('message.err_email_exist'),
                        'password.required' => trans('message.err_password_req'),
                        'password.min' => trans('message.err_password_min'),
                        'cpassword.required' => trans('message.err_cpassword_req'),
                        'cpassword.same' => trans('message.err_pass_cpass_not_same'),
                        'cpassword.min' => trans('message.err_cpassword_min'),
            ]);
            if ($validator->fails()) {
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            } else {
                $array['status'] = true;
                $array['title'] = 'Success!';
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }

    public function createMember_fb(Request $request) {
        if (($request->input('submit')) && $request->input('submit') == 'fb_login') {
            $validator = Validator::make($request->all(), [
                        'fb_id' => 'required',
                    ]);
            if ($validator->fails()) {
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;                            
            }
            $user = DB::table('member')->where('fb_id', $request->input('fb_id'))->where('login_via', '1')->get();
            if ($user->count() <= 0) {
                $api_token = uniqid() . base64_encode(str_random(40));
                $user_name = $this->generateUsername($request->input('user_name'));
                $email_id = '';
                if ($request->input('email_id') != NULL)
                    $email_id = $request->input('email_id');
                $member_data = [
                    'user_name' => $user_name,
                    'mobile_no' => '',
                    'email_id' => $email_id,
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'player_id' => $request->input('player_id'),
                    'password' => md5($request->input('fb_id')),
                    'fb_id' => $request->input('fb_id'),
                    'login_via' => '1',
                    'wallet_balance'=> 500,
                    'api_token' => $api_token,
                    'entry_from' => '1',
                    'new_user' => 'Yes',
                    'created_date' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')];
                $member_id = DB::table('member')->insertGetId($member_data);
                $member_data['member_id'] = $member_id;
                $array['status'] = true;
                $array['title'] = trans('message.text_succ_register');
                $array['message'] = (object) $member_data;
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                if ($user[0]->member_status == '1') {
                    
                    $player_id_data = [
                            'player_id' => $request->input('player_id')];
                    
                    DB::table('member')->where('member_id', $user[0]->member_id)->update($player_id_data);
                    
                    $user_data = DB::table('member')->where('member_id', $user[0]->member_id)->get();
                    
                    $array['status'] = true;
                    $array['title'] = trans('message.text_succ_login');
                    $array['message'] = $user_data[0];
                    $array['member_id'] = $user_data[0]->member_id;
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                } else {
                    $array['status'] = false;
                    $array['title'] = trans('message.text_fail_login');
                    $array['message'] = trans('message.text_block_acc');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
        }
    }

    public function createMember_google(Request $request) {
        if (($request->input('submit')) && $request->input('submit') == 'google_login') {
            $validator = Validator::make($request->all(), [
                        'email_id' => 'required',
                        'g_id' => 'required',
            ]);
            if ($validator->fails()) {
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;                            
            }
            $user = DB::table('member')->where('fb_id', $request->input('g_id'))->where('login_via', '2')->get();
            if ($user->count() <= 0) {
                $api_token = uniqid() . base64_encode(str_random(40));
                $user_name = $this->generateUsername($request->input('user_name'));
                $member_data = [
                    'user_name' => $user_name,
                    'mobile_no' => '',
                    'email_id' => $request->input('email_id'),
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'player_id' => $request->input('player_id'),
                    'password' => md5($request->input('g_id')),
                    'fb_id' => $request->input('g_id'),
                    'login_via' => '2',
                    'wallet_balance'=> 500,
                    'api_token' => $api_token,
                    'entry_from' => '1',
                    'new_user' => 'Yes',
                    'created_date' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')];
                $member_id = DB::table('member')->insertGetId($member_data);
                $member_data['member_id'] = $member_id;
                $array['status'] = true;
                $array['title'] = trans('message.text_succ_register');
                $array['message'] = (object) $member_data;
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                if ($user[0]->member_status == '1') {
                    
                    $player_id_data = [
                            'player_id' => $request->input('player_id')];
                    
                    DB::table('member')->where('member_id', $user[0]->member_id)->update($player_id_data);
                    
                    $user_data = DB::table('member')->where('member_id', $user[0]->member_id)->get();
                    
                    $array['status'] = true;
                    $array['title'] = trans('message.text_succ_login');
                    $array['message'] = $user_data[0];
                    $array['member_id'] = $user_data[0]->member_id;
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                } else {
                    $array['status'] = false;
                    $array['title'] = trans('message.text_fail_login');
                    $array['message'] = trans('message.text_block_acc');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
        }
    }

    function GetCountryId($country_code) {
        $country = DB::table('country')->where('p_code', $country_code)->select('country_id')->first();
        if ($country)
            return $country->country_id;
        else
            return 0;
    }

    public function UpdateMobileNo(Request $request) {
        $validator = Validator::make($request->all(), ['member_id' => 'required', 'country_code' => 'required', 'mobile_no' => 'required|unique:member|numeric|digits_between:7,15',
                        ], ['member_id.required' => trans('message.err_member_id'),
                    'country_code.required' => trans('message.err_country_code_req'),
                    'mobile_no.required' => trans('message.err_mobile_no_req'),
                    'mobile_no.numeric' => trans('message.err_mobile_no_num'),
                    'mobile_no.unique' => trans('message.err_mobile_no_exist'),
                    'mobile_no.digits_between' => trans('message.err_mobile_no_7to15')]);
        if ($validator->fails()) {
            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
        } else {
//            $country_id = $this->GetCountryId($request->input('country_code'));
            $member_data = array(
                'mobile_no' => $request->input('mobile_no'),
//                'country_id' => $country_id, 
                'country_code' => $request->input('country_code'),
                'new_user' => 'No');

            $referral_id = 0;
            if ($request->input('promo_code') != '') {
                $referral_id = $this->GetRefrralNo($request->input('promo_code'));
                $member_data['referral_id'] = $referral_id;
            }
            DB::table('member')->where('member_id', $request->input('member_id'))->update($member_data);
            $array['status'] = true;
            $array['title'] = 'Success!';
            $array['message'] = trans('message.text_succ_mobile_no_ins');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    
    public function UpdateCoin(Request $request) {
        $validator = Validator::make($request->all(), ['member_id' => 'required',
                        ], ['member_id.required' => trans('message.err_member_id')]);
        if ($validator->fails()) {
            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
        } else {
            
            
             $member_data = DB::table('member')
                    ->where('member_id', $request->input('member_id'))
                    ->first();
            
            
//            $country_id = $this->GetCountryId($request->input('country_code'));
            $member_data = array(
                'wallet_balance' => $member_data->wallet_balance+$request->input('coin'));

           
            DB::table('member')->where('member_id', $request->input('member_id'))->update($member_data);
            $array['status'] = true;
            $array['title'] = 'Success!';
            $array['message'] = trans('message.text_succ_mobile_no_ins');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function createMember(Request $request) {
        if (($request->input('submit')) && $request->input('submit') == 'register') {
            $array = array();
            $validator = Validator::make($request->all(), [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'promo_code' => 'exists:member,user_name',
                        'mobile_no' => 'required|unique:member|numeric|digits_between:7,15',
                        'user_name' => 'required|unique:member',
//                        'country_id' => 'required',
                        'country_code' => 'required',
                        'email_id' => [
                            'required',
                            Rule::unique('member')->where(function ($query) {
                                        return $query->where('login_via', '0');
                                    }),
                        ],
                        'password' => 'required|min:6',
                        'cpassword' => 'required|same:password|min:6',
                            ], [
                        'first_name.required' => trans('message.err_fname_req'),
                        'last_name.required' => trans('message.err_lname_req'),
                        'promo_code.exists' => trans('message.err_referral_code_valid'),
                        'user_name.required' => trans('message.err_username_req'),
                        'user_name.unique' => trans('message.err_username_exist'),
                        'mobile_no.required' => trans('message.err_mobile_no_req'),
                        'mobile_no.numeric' => trans('message.err_mobile_no_num'),
                        'mobile_no.unique' => trans('message.err_mobile_no_exist'),
                        'mobile_no.digits_between' => trans('message.err_mobile_no_7to15'),
                        'email_id.required' => trans('message.err_email_req'),
                        'email_id.email' => trans('message.err_email_valid'),
                        'email_id.unique' => trans('message.err_email_exist'),
                        'password.required' => trans('message.err_password_req'),
                        'cpassword.required' => trans('message.err_cpassword_req'),
                        'cpassword.same' => trans('message.err_pass_cpass_not_same'),
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'title' => 'Error!', 'message' => $validator->errors()->first()]);
            }
            $referral_id = 0;
            if ($request->input('promo_code') != '') {
                $referral_id = $this->GetRefrralNo($request->input('promo_code'));
            }
            $api_token = uniqid() . base64_encode(str_random(40));
            $member_data = [
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'player_id' => $request->input('player_id'),
                'user_name' => $request->input('user_name'),
                'email_id' => $request->input('email_id'),
                'mobile_no' => $request->input('mobile_no'),
                'password' => md5($request->input('password')),
//                'country_id' => $request->input('country_id'),
                'country_code' => $request->input('country_code'),
                'referral_id' => $referral_id,
                'api_token' => $api_token,
                'entry_from' => '1',
                'created_date' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')];

            $member_id = DB::table('member')->insertGetId($member_data);
            if ($request->input('promo_code') != '') {
                if ($this->system_config['active_referral'] == '1') {
                    $wallet_balance = $this->system_config['referral']; //$SYSTEM_CONFIG['referral'];
                    $data = [
                        'join_money' => $wallet_balance];
                    DB::table('member')->where('member_id', $member_id)->update($data);

                    $referral_data = [
                        'member_id' => $member_id,
                        'from_mem_id' => $referral_id,
                        'referral_amount' => $this->system_config['referral'], //$SYSTEM_CONFIG['referral']
                        'referral_status' => '1',
                        'entry_from' => '1',
                        ];
                    DB::table('referral')->insert($referral_data);
                    $browser = '';
                    $agent = new Agent();
                    if ($agent->isMobile()) {
                        $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                    } elseif ($agent->isDesktop()) {
                        $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                    } elseif ($agent->isRobot()) {
                        $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                    }
                    $ip = $this->getIp();
                    $acc_data = [
                        'member_id' => $member_id,
                        'from_mem_id' => $referral_id,
                        'deposit' => $this->system_config['referral'], //$SYSTEM_CONFIG['referral']
                        'withdraw' => 0,
                        'join_money' => $wallet_balance,
                        'win_money' => 0,
                        'note' => 'Register Referral',
                        'note_id' => '3',
                        'entry_from' => '1',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                    ];
                    DB::table('accountstatement')->insert($acc_data);
                }
            }
            $array['status'] = true;
            $array['title'] = trans('message.text_succ_register');
            $array['message'] = trans('message.text_succ_register_login');
            $array['member_id'] = $member_id;
            $array['api_token'] = $api_token;
            echo json_encode($array);
            exit;
        }
    }

    public function getAnnouncement() {
        $data['announcement'] = DB::table('announcement')
                ->get();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getSlider() {
        $data['slider'] = DB::table('slider')
                ->where('slider.status', '1')
                ->leftJoin('game as g', 'g.game_id', '=', 'slider.link_id')
                ->select('slider.*', DB::raw('(CASE 
                        WHEN slider_image = "" THEN ""
                        ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/slider_image/thumb/1000x500_", slider_image) 
                        END) AS slider_image'), 'g.game_name')
                ->orderBy('slider_id', 'ASC')
                ->get();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getBanner() {
        $data['banner'] = DB::table('banner')
                ->where('banner.status', '1')
                ->leftJoin('game as g', 'g.game_id', '=', 'banner.link_id')
                ->select('banner.*', DB::raw('(CASE 
                        WHEN banner_image = "" THEN "" 
                        ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/banner_image/thumb/1000x500_", banner_image) 
                        END) AS banner_image'), 'g.game_name')
                ->orderBy('banner_id', 'ASC')
                ->get();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getWatchAndEarn($member_id) {
        $data['watch_earn']['watch_ads_per_day'] = $this->system_config['watch_ads_per_day'];
        $data['watch_earn']['point_on_watch_ads'] = $this->system_config['point_on_watch_ads'];
        $data['watch_earn']['watch_earn_description'] = $this->system_config['watch_earn_description'];
        $data['watch_earn']['watch_earn_note'] = $this->system_config['watch_earn_note'];

        $total_watch_ads = DB::table('watch_earn')
                ->where("member_id", $member_id)
                ->where("watch_earn_date", date('Y-m-d'))
                ->select("rewards")
                ->first();
        if ($total_watch_ads)
            $data['watch_earn']['total_watch_ads'] = $total_watch_ads->rewards;
        else
            $data['watch_earn']['total_watch_ads'] = 0;
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getWatchAndEarn2($member_id) {
        $total_watch_ads = DB::table('watch_earn')
                ->where("member_id", $member_id)
                ->where("watch_earn_date", date('Y-m-d'))
                ->select("rewards", "watch_earn_id")
                ->first();

        if ($total_watch_ads) {
            $rewards = $total_watch_ads->rewards + 1;

            if ($rewards >= $this->system_config['watch_ads_per_day']) {
                $member = DB::table('member')
                        ->where("member_id", $member_id)
                        ->select("join_money", "wallet_balance")
                        ->first();
                $wallet_balance = $member->wallet_balance + $this->system_config['point_on_watch_ads'];
                $join_money = $member->join_money;
                $browser = '';
                $agent = new Agent();
                if ($agent->isMobile()) {
                    $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                } elseif ($agent->isDesktop()) {
                    $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                } elseif ($agent->isRobot()) {
                    $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                }
                $ip = $this->getIp();
                $acc_data = [
                    'member_id' => $member_id,
                    'deposit' => $this->system_config['point_on_watch_ads'],
                    'withdraw' => 0,
                    'join_money' => $join_money,
                    'win_money' => $wallet_balance,
                    'note' => 'Watch And Earn',
                    'note_id' => '13',
                    'entry_from' => '1',
                    'ip_detail' => $ip,
                    'browser' => $browser,
                    'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                ];
                DB::table('accountstatement')->insert($acc_data);

                $upd_data = [
                    'wallet_balance' => $wallet_balance];
                DB::table('member')->where('member_id', $member_id)->update($upd_data);

                $upd_watch_earn_data = [
                    'rewards' => $rewards,
                    'earning' => $this->system_config['point_on_watch_ads'],];
                DB::table('watch_earn')->where('watch_earn_id', $total_watch_ads->watch_earn_id)->update($upd_watch_earn_data);

                $array['status'] = true;
                $array['title'] = 'Success!';
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $upd_watch_earn_data = [
                    'rewards' => $rewards,];
                DB::table('watch_earn')->where('watch_earn_id', $total_watch_ads->watch_earn_id)->update($upd_watch_earn_data);
                $array['status'] = true;
                $array['title'] = 'Success!';
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
        } else {
            $watch_earn_data = [
                'member_id' => $member_id,
                'rewards' => 1,
                'earning' => 0,
                'watch_earn_date' => date('Y-m-d'),
                'date_created' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
            ];
            DB::table('watch_earn')->insert($watch_earn_data);
            $array['status'] = true;
            $array['title'] = 'Success!';
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function getWatchAndEarnDetail($member_id) {
        $total = DB::table('watch_earn')
                ->where("member_id", $member_id)
                ->select(DB::raw("SUM(rewards) as total_rewards"), DB::raw("SUM(earning) as total_earning"))
                ->first();
        $data['total_rewards'] = $total->total_rewards;
        $data['total_earning'] = $total->total_earning;
        $data['watch_earn_data'] = DB::table('watch_earn')->orderBy('watch_earn_date', 'DESC')
                        ->where("member_id", $member_id)->get();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getIp() {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    return $ip;
                }
            }
        }
    }

    public function getAllCountry() {
        $data['all_country'] = DB::table('country')
                ->select('*')
                ->where("country_status", '1')
                ->orderBy('country_id', 'ASC')
                ->get();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getAllLanguage() {
        $data['supported_language'] = json_decode($this->system_config['supported_language']);
        $data['rtl_supported_language'] = json_decode($this->system_config['rtl_supported_language']);
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getAllGame() {				
		
		$member_id = Auth::user()->member_id;
		
        $data['all_game'] = DB::table('game as g')
                ->select('*', \DB::raw('(select count(*) from matches as m where m.game_id = g.game_id and m.match_status = "1") as total_upcoming_match'),\DB::raw('(select count(*) from ludo_challenge as l where l.game_id = g.game_id and l.accept_status = "0" and l.challenge_status = "1" and l.member_id != "'. $member_id .'" and l.accepted_member_id != "'. $member_id .'") as total_upcoming_challenge'), \DB::raw('(CASE 
                        WHEN g.game_image = "" THEN "" 
                        ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/game_image/thumb/1000x500_", g.game_image) 
                        END) AS game_image'), \DB::raw('(CASE 
                        WHEN g.game_logo = "" THEN "" 
                        ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/game_logo_image/thumb/100x100_", g.game_logo) 
                        END) AS game_logo'))
                ->where("status", '1')
                
                ->orderBy('game_id', 'ASC')
                ->get();
                                     		
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getAllLottery($member_id, $status) {
        $member_id = Auth::user()->member_id;
        $query = DB::table('lottery')
                ->leftJoin('image as i', 'i.image_id', '=', 'lottery.image_id')
                ->select('lottery.*', \DB::raw('(CASE 
                        WHEN lottery.image_id != 0 THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/select_image/thumb/1000x500_", i.image_name)
                        WHEN lottery.lottery_image != "" THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/lottery_image/thumb/1000x500_", lottery.lottery_image) 
                        ELSE ""
                        END) AS lottery_image'))
                ->orderBy('lottery_id', 'ASC');
        if ($status == 'ongoing') {
            $array_name = 'ongoing';
            $query = $query->where("lottery_status", '1');
        } else if ($status == 'result') {
            $array_name = 'result';
            $query = $query->where("lottery_status", '2');
        }
        $data[$array_name] = $query->get();
        $lottery_id = array();
        foreach ($data[$array_name] as $row) {
            $lottery_id[] = $row->lottery_id;
        }
        $lottery_join = DB::table('lottery_member')
                ->where("member_id", $member_id)
                ->whereIn("lottery_id", $lottery_id)
                ->get();
        $i = 0;
        foreach ($data[$array_name] as $row) {
            $data[$array_name][$i]->member_id = "";
            $data[$array_name][$i]->join_status = false;
            foreach ($lottery_join as $lottery_join_id) {
                if ($row->lottery_id == $lottery_join_id->lottery_id) {
                    $data[$array_name][$i]->join_status = true;
                    $data[$array_name][$i]->member_id = $member_id;
                }
            }
            $data[$array_name][$i]->won_by = '';

            $data[$array_name][$i]->join_member = DB::table('lottery_member as l')
                    ->leftJoin('member as m', 'm.member_id', '=', 'l.member_id')
                    ->where("lottery_id", $row->lottery_id)
                    ->select('l.*', 'm.user_name')
                    ->get();
            foreach ($data[$array_name][$i]->join_member as $lottery_join_member) {
                if ($lottery_join_member->status == '1') {
                    $data[$array_name][$i]->won_by = $lottery_join_member->user_name;
                }
            }
            $i++;
        }


        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function singleLottery($lottery_id, $member_id) {
        $member_id = Auth::user()->member_id;
        $data['lottery'] = DB::table('lottery as l')
                ->leftJoin('image as i', 'i.image_id', '=', 'l.image_id')
                ->leftJoin(DB::raw("(select * from lottery_member where member_id='$member_id') as lm"), 'lm.lottery_id', '=', 'l.lottery_id')
                ->select('l.*', 'lm.member_id', \DB::raw('(CASE 
                        WHEN l.lottery_image = "" THEN "" 
                        ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/lottery_image/thumb/1000x500_", l.lottery_image) 
                        END) AS lottery_image'), \DB::raw('(CASE 
                        WHEN l.image_id != 0 THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/select_image/thumb/1000x500_", i.image_name)                          
                        END) AS lottery_image'))
                ->where("l.lottery_id", $lottery_id)
                ->first();
        if ($data['lottery']->member_id == $member_id) {
            $data['lottery']->join_status = true;
        } else {
            $data['lottery']->join_status = false;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getAllOngoingMatch($game_id, $member_id) {
        $member_id = Auth::user()->member_id;
        DB::statement("SET sql_mode = '' ");
        $data['all_ongoing_match'] = DB::table('matches')
                ->leftJoin('image as i', 'i.image_id', '=', 'matches.image_id')
                ->where("match_status", '3')
                ->where("game_id", $game_id)
                ->select('m_id', 'match_name', 'match_url', 'matches.room_description', 'match_time', 'matches.win_prize', 'prize_description', 'per_kill', 'entry_fee', 'type', 'MAP', 'match_type', 'match_desc','match_private_desc', 'no_of_player', 'number_of_position', \DB::raw('(CASE 
                        WHEN matches.image_id != 0 THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/select_image/thumb/1000x500_", i.image_name)                         
                        WHEN matches.match_banner != "" THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/match_banner_image/thumb/1000x500_", matches.match_banner) 
                        ELSE ""
                        END) AS match_banner'), 'match_sponsor')
                ->groupBy('m_id')
                ->orderBy('match_time', 'ASC')
                ->get();
        $match_id = array();
        foreach ($data['all_ongoing_match'] as $row) {
            $match_id[] = $row->m_id;
        }
        $match_join = DB::table('match_join_member')
                ->where("member_id", $member_id)
                ->whereIn("match_id", $match_id)
                ->get();
        $i = 0;
        foreach ($data['all_ongoing_match'] as $row) {
            $room_description = $data['all_ongoing_match'][$i]->room_description;            
            $data['all_ongoing_match'][$i]->member_id = "";
            $data['all_ongoing_match'][$i]->join_status = false;
            $data['all_ongoing_match'][$i]->room_description = "";            
            foreach ($match_join as $match_join_id) {
                if ($row->m_id == $match_join_id->match_id) {
                    if ($room_description != '')
                        $data['all_ongoing_match'][$i]->room_description = $room_description;                   

                    $data['all_ongoing_match'][$i]->join_status = true;
                    $data['all_ongoing_match'][$i]->member_id = $member_id;
                }
            }
            $i++;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getAllGameResult($game_id, $member_id) {
        $member_id = Auth::user()->member_id;
        DB::statement("SET sql_mode = '' ");
        $data['all_game_result'] = DB::table('matches as m')
                ->leftJoin('image as i', 'i.image_id', '=', 'm.image_id')
                ->where("match_status", '2')
                ->where("game_id", $game_id)
                ->select('m_id', 'match_name', 'match_url', 'm.room_description', DB::raw("STR_TO_DATE(match_time, '%d/%m/%Y %h:%i %p') as m_time"), 'match_time', 'm.win_prize', 'prize_description', 'per_kill', 'entry_fee', 'type', 'MAP', 'match_type', 'match_desc','match_private_desc', 'no_of_player', 'number_of_position', \DB::raw('(CASE 
                        WHEN m.image_id != 0 THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/select_image/thumb/1000x500_", i.image_name) 
                        WHEN m.match_banner != "" THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/match_banner_image/thumb/1000x500_", m.match_banner) 
                        ELSE ""
                        END) AS match_banner'), 'match_sponsor')
                ->groupBy('m_id')
                ->orderBy('m_time', 'DESC')
                ->limit(10)
                ->get();
        $match_id = array();
        foreach ($data['all_game_result'] as $row) {
            $match_id[] = $row->m_id;
        }
        $match_join = DB::table('match_join_member')
                ->where("member_id", $member_id)
                ->whereIn("match_id", $match_id)
                ->get();
        $i = 0;
        foreach ($data['all_game_result'] as $row) {
            $room_description = $data['all_game_result'][$i]->room_description;            
            $data['all_game_result'][$i]->member_id = "";
            $data['all_game_result'][$i]->join_status = false;
            $data['all_game_result'][$i]->room_description = "";            
            foreach ($match_join as $match_join_id) {
                if ($row->m_id == $match_join_id->match_id) {
                    if ($room_description != '')
                        $data['all_game_result'][$i]->room_description = $room_description;
                   
                    $data['all_game_result'][$i]->join_status = true;
                    $data['all_game_result'][$i]->member_id = $member_id;
                }
            }
            $i++;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getAllPlayMatch($game_id, $member_id) {
        $member_id = Auth::user()->member_id;
        DB::statement("SET sql_mode = '' ");
        $data['allplay_match'] = DB::table('matches as m')
                ->leftJoin('image as i', 'i.image_id', '=', 'm.image_id')
                ->select('pin_match', 'm_id', 'match_name', 'match_url', 'm.room_description', DB::raw("STR_TO_DATE(match_time, '%d/%m/%Y %h:%i %p') as m_time"), 'match_time', 'm.win_prize', 'prize_description', 'per_kill', 'entry_fee', 'type', 'MAP', 'match_type', 'match_desc','match_private_desc', 'no_of_player', 'number_of_position', \DB::raw('(CASE 
                        WHEN m.image_id != 0 THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/select_image/thumb/1000x500_", i.image_name) 
                        WHEN m.match_banner != "" THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/match_banner_image/thumb/1000x500_", m.match_banner) 
                        ELSE ""
                        END) AS match_banner'), 'match_sponsor')
                ->where("match_status", '1')
                ->where("game_id", $game_id)
                ->groupBy('m_id')
                ->orderBy('pin_match', 'DESC')
                ->orderBy('m_time', 'ASC')
                ->get();
        $match_id = array();
        foreach ($data['allplay_match'] as $row) {
            $match_id[] = $row->m_id;
        }
        $match_join = DB::table('match_join_member')
                ->where("member_id", $member_id)
                ->whereIn("match_id", $match_id)
                ->groupBy('match_id')
                ->get();
        $i = 0;
        foreach ($data['allplay_match'] as $row) {
            $room_description = $data['allplay_match'][$i]->room_description;            
            $data['allplay_match'][$i]->room_description = "";            
            $data['allplay_match'][$i]->member_id = "";
            $data['allplay_match'][$i]->join_status = false;
            foreach ($match_join as $match_join_id) {
                if ($row->m_id == $match_join_id->match_id) {
                    $data['allplay_match'][$i]->join_status = true;
                    $data['allplay_match'][$i]->member_id = $member_id;
                    if ($room_description != '')
                        $data['allplay_match'][$i]->room_description = $room_description;                    
                }
            }
            $i++;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getMyMatches($member_id) {
        $member_id = Auth::user()->member_id;
        DB::statement("SET sql_mode = '' ");
        $data['my_match'] = DB::table('match_join_member as mj')
                ->leftJoin('matches as m', 'mj.match_id', '=', 'm.m_id')
                ->leftJoin('image as i', 'i.image_id', '=', 'm.image_id')
                ->leftJoin('game as g', 'g.game_id', '=', 'm.game_id')
                ->select('g.game_name', 'm_id', 'match_name', 'match_url', 'm.room_description', DB::raw("STR_TO_DATE(match_time, '%d/%m/%Y %h:%i %p') as m_time"), 'match_time', 'm.win_prize', 'prize_description', 'per_kill', 'entry_fee', 'type', 'MAP', 'match_type', 'match_desc','match_private_desc', 'no_of_player', 'number_of_position', \DB::raw('(CASE 
                        WHEN m.image_id != 0 THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/select_image/thumb/1000x500_", i.image_name) 
                        WHEN m.match_banner != "" THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/match_banner_image/thumb/1000x500_", m.match_banner) 
                        ELSE ""
                        END) AS match_banner'), 'match_sponsor', 'match_status', 'mj.member_id')
                ->where("match_status", '!=', '0')
                ->where("match_status", '!=', '4')
                ->where("mj.member_id", $member_id)
                ->groupBy('m_id')
                ->orderBy('m_time', 'ASC')
                ->get();
        $i = 0;
        foreach ($data['my_match'] as $my_match) {
            $data['my_match'][$i]->join_status = true;
            if ($data['my_match'][$i]->room_description != '')
                $data['my_match'][$i]->room_description = $data['my_match'][$i]->room_description;            
            $i++;
        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getDashboardDetails($member_id) {
        $member_id = Auth::user()->member_id;
        $data['member'] = DB::table('member')
                ->where("member_id", $member_id)
                ->select('password', 'first_name', 'last_name', 'user_name', 'wallet_balance', 'join_money', 'pubg_id', 'member_status')
                ->first();

        $total = DB::table('match_join_member')
                ->where("member_id", $member_id)
                ->select(DB::raw("COUNT(match_join_member_id) as total_match"), DB::raw("SUM(killed) as total_kill"), DB::raw("SUM(total_win) as total_win"))
                ->first();

        $data['tot_match_play']['total_match'] = $total->total_match;
        $data['tot_kill']['total_kill'] = $total->total_kill;
        $data['tot_win']['total_win'] = $total->total_win;

        $data['tot_withdraw'] = DB::table('accountstatement')
                ->where("member_id", $member_id)
                ->where("note_id", '8')                
                ->select(DB::raw("SUM(withdraw) as tot_withdraw"))
                ->first();

//        $data['web_config']['point'] = $this->system_config['point'];
        $data['web_config']['share_description'] = $this->system_config['share_description'];
        $data['web_config']['referandearn_description'] = $this->system_config['referandearn_description'];
        $data['web_config']['active_referral'] = $this->system_config['active_referral'];

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function aboutUs() {
        $data['about_us'] = DB::table('page')
                ->where("page_slug", 'about-us')
                ->select('page_content as aboutus')
                ->first();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function customerSupport() {
        $data['customer_support']['company_street'] = $this->system_config['company_street'];
        $data['customer_support']['company_address'] = $this->system_config['company_address'];
        $data['customer_support']['comapny_phone'] = $this->system_config['comapny_phone'];
        $data['customer_support']['comapny_country_code'] = $this->system_config['comapny_country_code'];
        $data['customer_support']['company_time'] = $this->system_config['company_time'];
        $data['customer_support']['company_email'] = $this->system_config['company_email'];
        $data['customer_support']['insta_link'] = $this->system_config['insta_link'];
        if ($this->system_config['insta_link'] == '' || $this->system_config['insta_link'] == '#')
            $data['customer_support']['insta_link'] = '';
        else
            $data['customer_support']['insta_link'] = substr(rtrim($this->system_config['insta_link'], '/'), strrpos(rtrim($this->system_config['insta_link'], '/'), '/') + 1);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function leadeBoard() {
        DB::statement("SET sql_mode = '' ");
        $data['leader_board'] = DB::table('member as m')
                ->leftJoin('member as m2', 'm.referral_id', '=', 'm2.member_id')
                ->select('m2.user_name', 'm.referral_id', DB::raw("COUNT(m.referral_id) as tot_referral"))
                ->whereNotIn('m.referral_id', array(0))
                ->groupBy('m.referral_id')
                ->orderBy('tot_referral', 'DESC')
                ->limit(10)
                ->get();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function matchParticipate($match_id) {
        $data['match_participate'] = DB::table('match_join_member as mj')
                ->leftJoin('member as m', 'mj.member_id', '=', 'm.member_id')
                ->where("match_id", $match_id)
                ->select('mj.pubg_id')
                ->get();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function myProfile($member_id) {
        $member_id = Auth::user()->member_id;
        $data['my_profile'] = DB::table('member')
                ->where("member_id", $member_id)
                ->select("password", "member_id", "first_name", "last_name", "user_name", "email_id", "country_id", "country_code", "mobile_no", "join_money", "wallet_balance", "pubg_id", "dob", "gender", DB::raw('(CASE 
                WHEN profile_image = "" THEN ""
                ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/profile_image/thumb/100x100_", profile_image) 
                END) AS profile_image'))
                ->first();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function myRefrrrals($member_id) {
        $member_id = Auth::user()->member_id;
        $data['tot_referrals'] = DB::table('member')
                ->where("referral_id", $member_id)
                ->select(DB::raw("count(member_id) as total_ref"))
                ->first();

        $data['tot_earnings'] = DB::table('referral')
                ->where("member_id", $member_id)
                ->where("referral_status", '0')
                ->select(DB::raw("sum(referral_amount) as total_earning"))
                ->first();

        $data['my_referrals'] = DB::table('member')
                ->where("referral_id", $member_id)
                ->select(DB::raw("date(created_date) as date"), "user_name", "member_status", "member_package_upgraded")
                ->get();
        $i = 0;
        foreach ($data['my_referrals'] as $row) {
            if ($row->member_status == '1' && $row->member_package_upgraded == '1') {
                $data['my_referrals'][$i]->status = trans('message.text_rewarded');
            } else if ($row->member_status == '1') {
                $data['my_referrals'][$i]->status = trans('message.text_registered');
            } else {
                $data['my_referrals'][$i]->status = trans('message.text_inactive');
            }
            $i++;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function myStatistics($member_id) {
        $member_id = Auth::user()->member_id;
        $data['my_statistics'] = DB::table('match_join_member as mj')
                ->Join('matches as m', 'm.m_id', '=', 'mj.match_id')
                ->where("member_id", $member_id)
                ->select('m.match_name', 'm.m_id', 'match_time', 'entry_fee as paid', 'total_win as won')
                ->get();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function singleGameResult($match_id) {
        $data['match_deatils'] = DB::table('matches')
                ->where("m_id", $match_id)
                ->select('m_id', 'match_name', 'match_time', 'win_prize', 'per_kill', 'entry_fee', 'match_url', 'type', 'match_type', 'result_notification', 'match_sponsor')
                ->first();

        if ($data['match_deatils']->type == 'Solo') {
            $limit = 1;
        } elseif ($data['match_deatils']->type == 'Duo') {
            $limit = 2;
        } elseif ($data['match_deatils']->type == 'Squad') {
            $limit = 4;
        } elseif ($data['match_deatils']->type == 'Squad5') {
            $limit = 5;
        }

        $data['full_result'] = DB::table('match_join_member as mj')
                        ->where("match_id", $match_id)
                        ->leftJoin('member as m', 'mj.member_id', '=', 'm.member_id')
                        ->select('user_name', 'mj.pubg_id', 'killed', 'total_win')
                        ->orderBy('win_prize', 'DESC')
                        ->orderBy('total_win', 'DESC')
                        ->get()->toArray();
        $data['match_winner'] = array_slice($data['full_result'], 0, $limit);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function singleMatch($match_id, $member_id) {
        $member_id = Auth::user()->member_id;
        $data['match'] = DB::table('matches as m')
                ->leftJoin(DB::raw("(select * from match_join_member where member_id='$member_id') as mj"), 'mj.match_id', '=', 'm.m_id')
                ->leftJoin('game as g', 'g.game_id', '=', 'm.game_id')
                ->where("m_id", $match_id)
                ->select('m_id', 'match_name', 'match_time', 'm.win_prize', 'per_kill', 'entry_fee', 'type', 'MAP', 'match_type', 'match_desc','match_private_desc', 'no_of_player', 'number_of_position', 'mj.member_id', 'match_url', 'm.room_description', 'match_sponsor', 'g.package_name')
                ->orderBy('match_time', 'ASC')
                ->first();
        $data['join_position'] = DB::table('match_join_member')
                ->where("member_id", $member_id)
                ->where("match_id", $match_id)
                ->select('pubg_id', 'team', 'position', 'match_join_member_id')
                ->get();


        if ($data['match']->member_id == $member_id) {
            $data['match']->join_status = true;
            if ($data['match']->room_description != '')
                $data['match']->room_description = $data['match']->room_description;           
        } else {
            $data['match']->room_description = "";           
            $data['match']->join_status = false;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function termsConditions() {
        $data['terms_conditions'] = DB::table('page')
                ->where("page_slug", 'terms_conditions')
                ->select('page_content as terms_conditions')
                ->first();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function topPlayers() {
        DB::statement("SET sql_mode = '' ");
        $game = DB::table('game')
                ->select('*', \DB::raw('(CASE 
                        WHEN game_logo = "" THEN "" 
                        ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/game_logo_image/thumb/100x100_", game_logo) 
                        END) AS game_logo'))
                ->where("status", '1')
                ->get();
        $data['game'] = array();
        $data['top_players'] = array();
        foreach ($game as $row) {
            $data['top_players'][$row->game_name] = DB::table('match_join_member as mj')
                    ->join('member as m', function ($join) {
                        $join->on('m.member_id', '=', 'mj.member_id');
                    })
                    ->join('matches as m1', function ($join) {
                        $join->on('m1.m_id', '=', 'mj.match_id');
                    })
                    ->where("m1.game_id", $row->game_id)
                    ->select(DB::raw("sum(total_win) as winning"), 'm.user_name', 'm.member_id', 'm.pubg_id')
                    ->groupBy('mj.member_id')
                    ->orderBy('winning', 'DESC')
                    ->take(10)
                    ->get();
            if ($data['top_players'][$row->game_name]->count() > 0) {
                $data['game'][] = $row;
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function transaction() {
        $data['transaction'] = DB::table('accountstatement as a')
                ->where('a.member_id', Auth::user()->member_id)
                ->select('a.account_statement_id as transaction_id', 'a.note', 'a.join_money', 'a.win_money', 'a.match_id', 'a.note_id', 'a.accountstatement_dateCreated as date', 'a.deposit', 'a.withdraw')
                ->orderBy('account_statement_id', 'DESC')
                ->get();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    
     public function getAllOffer() {
        $query = DB::table('offer')
                ->select('*', \DB::raw('(CASE 
                        WHEN offer_image != "" THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/product_image/thumb/1000x500_", offer_image) 
                        ELSE ""
                        END) AS offer_image'))
                ->where("offer_status", '1')
                ->orderBy('offer_id', 'DESC');
        $data['offer'] = $query->get();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    
      public function singleOffer($offer_id) {
        $data['offer'] = DB::table('offer')
                ->select('*', \DB::raw('(CASE 
                        WHEN offer_image != "" THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/product_image/thumb/1000x500_", offer_image) 
                        ELSE ""
                        END) AS offer_image'))
                ->where("offer_id", $offer_id)
                ->first();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    

    public function getAllProduct() {
        $query = DB::table('product')
                ->select('*', \DB::raw('(CASE 
                        WHEN product_image != "" THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/product_image/thumb/1000x500_", product_image) 
                        ELSE ""
                        END) AS product_image'))
                ->where("product_status", '1')
                ->orderBy('product_id', 'DESC');
        $data['product'] = $query->get();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
       public function smember($name) {
        $data['memberlist'] = DB::table('member')
                ->orWhere("user_name", 'like', '%' . $name . '%')
                ->first();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function singleProduct($product_id) {
        $data['product'] = DB::table('product')
                ->select('*', \DB::raw('(CASE 
                        WHEN product_image != "" THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/product_image/thumb/1000x500_", product_image) 
                        ELSE ""
                        END) AS product_image'))
                ->where("product_id", $product_id)
                ->first();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function MyOrder($member_id) {
        $data['my_orders'] = DB::table('orders as o')
                ->where("member_id", $member_id)
                ->leftjoin('courier as c', 'o.courier_id', '=', 'c.courier_id')
                ->select('o.*', DB::raw('(CASE 
                        WHEN c.courier_link != "" THEN CONCAT (c.courier_link,o.tracking_id) 
                        ELSE ""
                        END) AS courier_link'), DB::raw('(CASE 
                        WHEN product_image != "" THEN CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/product_image/thumb/1000x500_", product_image) 
                        ELSE ""
                        END) AS product_image'), DB::raw('DATE_FORMAT(created_date, "%M %d %Y") as created_date'))
                ->orderBy('orders_id', 'DESC')
                ->get();
        $i = 0;
        foreach ($data['my_orders'] as $row) {
            $shipping_address = @unserialize($row->shipping_address);
            $data['my_orders'][$i]->name = $shipping_address['name'];
            $data['my_orders'][$i]->address = $shipping_address['address'];
            $data['my_orders'][$i]->add_info = '';
            if (isset($shipping_address['add_info']))
                $data['my_orders'][$i]->add_info = $shipping_address['add_info'];
            $i++;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function ProductOrder(Request $request) {
        if (($request->input('submit')) && $request->input('submit') == 'order') {
            $validator = Validator::make($request->all(), [
                        'product_id' => 'required',
                        'member_id' => 'required',
                        'shipping_address' => 'required',
                            ], [
                        'product_id.required' => trans('message.err_product_id'),
                        'member_id.required' => trans('message.err_member_id'),
                        'shipping_address' => trans('message.err_sho_address_req'),
            ]);
            if ($validator->fails()) {
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }
            $product = DB::table('product')
                    ->where('product_id', $request->input('product_id'))
                    ->first();
            if ($product->product_status != 1) {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = 'Product not available';
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
            $member = DB::table('member')
                    ->where('member_id', $request->input('member_id'))
                    ->first();
            if ($member->wallet_balance + $member->join_money >= $product->product_selling_price) {
                $invoice = DB::table('orders')
                        ->orderBy('orders_id', 'DESC')
                        ->limit(1)
                        ->first();
                if ($invoice) {
                    $invoice_no = $invoice->no + 1;
                    $no = $invoice->no + 1;
                } else {
                    $invoice_no = $no = 1;
                }
                $order_no = str_pad($invoice_no, 8, 'ORD0000', STR_PAD_LEFT);
                $order_data = [
                    'member_id' => $request->input('member_id'),
                    'no' => $no,
                    'order_no' => $order_no,
                    'product_name' => $product->product_name,
                    'product_image' => $product->product_image,
                    'product_price' => $product->product_selling_price,
                    'shipping_address' => serialize($request->input('shipping_address')),
                    'order_status' => trans('message.text_hold'),
                    'entry_from' => '1',
                    'created_date' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                ];
                $order_id = DB::table('orders')->insertGetId($order_data);
                if ($member->join_money > $product->product_selling_price) {
                    $join_money = $member->join_money - $product->product_selling_price;
                    $wallet_balance = $member->wallet_balance;
                } elseif ($member->join_money < $product->product_selling_price) {
                    $join_money = 0;
                    $amount1 = $product->product_selling_price - $member->join_money;
                    $wallet_balance = $member->wallet_balance - $amount1;
                } elseif ($member->join_money == $product->product_selling_price) {
                    $join_money = 0;
                    $wallet_balance = $member->wallet_balance;
                }
                $data = [
                    'join_money' => $join_money,
                    'wallet_balance' => $wallet_balance,
                ];
                $browser = '';
                $agent = new Agent();
                if ($agent->isMobile()) {
                    $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                } elseif ($agent->isDesktop()) {
                    $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                } elseif ($agent->isRobot()) {
                    $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                }
                $ip = $this->getIp();
                $acc_data = [
                    'member_id' => $request->input('member_id'),
                    'order_id' => $order_id,
                    'deposit' => 0,
                    'withdraw' => $product->product_selling_price,
                    'join_money' => $join_money,
                    'win_money' => $wallet_balance,
                    'note' => 'Product Order',
                    'note_id' => '12',
                    'entry_from' => '1',
                    'ip_detail' => $ip,
                    'browser' => $browser,
                    'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                ];
                DB::table('accountstatement')->insert($acc_data);

                DB::table('member')->where('member_id', $request->input('member_id'))->update($data);

                $array['status'] = true;
                $array['title'] = 'Success!';
                $array['message'] = trans('message.text_succ_order');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.err_balance_low');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }

    public function joinLottery(Request $request) {
        if (($request->input('submit')) && $request->input('submit') == 'joinnow') {
            $validator = Validator::make($request->all(), [
                        'lottery_id' => 'required',
                        'member_id' => 'required',
                            ], [
                        'lottery_id.required' => trans('message.err_lottery_id'),
                        'member_id.required' => trans('message.err_member_id'),
            ]);
            if ($validator->fails()) {
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }
            $lottery = DB::table('lottery')
                    ->where('lottery_id', $request->input('lottery_id'))
                    ->first();
            if ($lottery->lottery_size <= $lottery->total_joined) {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.err_no_spot');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
            $lottery_member = DB::table('lottery_member')
                    ->where('lottery_id', $request->input('lottery_id'))
                    ->where('member_id', $request->input('member_id'))
                    ->count();
            if ($lottery_member > 0) {                
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.err_already_join_lottery');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
            $member = DB::table('member')
                    ->where('member_id', $request->input('member_id'))
                    ->first();
            if ($member->wallet_balance + $member->join_money >= $lottery->lottery_fees) {
                $lottery_member = [
                    'lottery_id' => $request->input('lottery_id'),
                    'member_id' => $request->input('member_id'),
                    'entry_from' => '1',
                    'date_created' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                ];
                DB::table('lottery_member')->insert($lottery_member);
                if ($lottery->lottery_fees > 0) {
                    if ($member->join_money > $lottery->lottery_fees) {
                        $join_money = $member->join_money - $lottery->lottery_fees;
                        $wallet_balance = $member->wallet_balance;
                    } elseif ($member->join_money < $lottery->lottery_fees) {
                        $join_money = 0;
                        $amount1 = $lottery->lottery_fees - $member->join_money;
                        $wallet_balance = $member->wallet_balance - $amount1;
                    } elseif ($member->join_money == $lottery->lottery_fees) {
                        $join_money = 0;
                        $wallet_balance = $member->wallet_balance;
                    }
                    $data = [
                        'join_money' => $join_money,
                        'wallet_balance' => $wallet_balance,
                    ];
                    $browser = '';
                    $agent = new Agent();
                    if ($agent->isMobile()) {
                        $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                    } elseif ($agent->isDesktop()) {
                        $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                    } elseif ($agent->isRobot()) {
                        $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                    }
                    $ip = $this->getIp();
                    $acc_data = [
                        'member_id' => $request->input('member_id'),
                        'lottery_id' => $request->input('lottery_id'),
                        'deposit' => 0,
                        'withdraw' => $lottery->lottery_fees,
                        'join_money' => $join_money,
                        'win_money' => $wallet_balance,
                        'note' => 'Lottery Joined',
                        'note_id' => '10',
                        'entry_from' => '1',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                    ];
                    DB::table('accountstatement')->insert($acc_data);

                    DB::table('member')->where('member_id', $request->input('member_id'))->update($data);

                    $total_joined = DB::table('lottery_member')
                                    ->where('lottery_id', $request->input('lottery_id'))
                                    ->select(DB::raw("COUNT(*) as total_joined"))
                                    ->first()->total_joined;
                    $data = [
                        'total_joined' => $total_joined];
                    DB::table('lottery')->where('lottery_id', $request->input('lottery_id'))->update($data);
                } else {
                    $total_joined = DB::table('lottery_member')
                                    ->where('lottery_id', $request->input('lottery_id'))
                                    ->select(DB::raw("COUNT(*) as total_joined"))
                                    ->first()->total_joined;
                    $data = [
                        'total_joined' => $total_joined];
                    DB::table('lottery')->where('lottery_id', $request->input('lottery_id'))->update($data);
                }
                $array['status'] = true;
                $array['title'] = 'Success!';
                $array['message'] = trans('message.text_succ_join');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.err_balance_low');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }

    public function joinMatchSingle($match_id) {
        $data = array();
        $match = DB::table('matches')
                ->where('m_id', $match_id)
                ->first();
        $member = DB::table('member')
                ->where('member_id', Auth::user()->member_id)
                ->first();
        
        if($member->pubg_id != ''){
            $pubg_id = unserialize($member->pubg_id);
        } else {
            $pubg_id = $member->pubg_id;
        }

        $data['pubg_id'] = '';
        if (is_array($pubg_id) && array_key_exists($match->game_id, $pubg_id)) {
        // if ($pubg_id->getType() && $pubg_id->getType()->getName() === 'array' && array_key_exists($match->game_id, $pubg_id)) {
            $data['pubg_id'] = $pubg_id[$match->game_id];
        }
        if ($match->no_of_player >= $match->number_of_position) {
            $array['status'] = false;
            $array['title'] = 'Error!';
            $array['message'] = trans('message.err_no_spot');
            echo json_encode($array, JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $match_join_member = DB::table('match_join_member as mj')
                    ->leftjoin('member as m', 'm.member_id', '=', 'mj.member_id')
                    ->leftjoin('matches as ma', 'ma.m_id', '=', 'mj.match_id')
                    ->where('mj.match_id', $match_id)
                    ->select('m.user_name', 'mj.pubg_id', 'mj.team', 'mj.position', 'ma.type')
                    ->orderBy('team', 'ASC')
                    ->orderBy('position', 'ASC')
                    ->get();

            if ($match->type == 'Solo') {
                for ($j = 1; $j <= $match->number_of_position; $j++) {
                    if (count($match_join_member) == 0) {
                        $a = array(
                            'user_name' => '',
                            'pubg_id' => '',
                            'team' => '1',
                            'position' => $j,
                        );
                    } else {
                        foreach ($match_join_member as $res) {
                            if ($res->position == $j) {
                                $a = array(
                                    'user_name' => $res->user_name,
                                    'pubg_id' => $res->pubg_id,
                                    'team' => '1',
                                    'position' => $j,
                                );
                                break;
                            } else {
                                $a = array(
                                    'user_name' => '',
                                    'pubg_id' => '',
                                    'team' => '1',
                                    'position' => $j,
                                );
                            }
                        }
                    }
                    $data['result'][] = $a;
                }
            } elseif ($match->type == 'Duo') {
                $loop = ceil($match->number_of_position / 2);
                for ($j = 1; $j <= $loop; $j++) {
                    for ($i = 1; $i <= 2; $i++) {
                        if (count($match_join_member) == 0) {
                            $a = array(
                                'user_name' => '',
                                'pubg_id' => '',
                                'team' => $j,
                                'position' => $i,
                            );
                        } else {
                            foreach ($match_join_member as $res) {
                                if ($res->team == $j && $res->position == $i) {
                                    $a = array(
                                        'user_name' => $res->user_name,
                                        'pubg_id' => $res->pubg_id,
                                        'team' => $res->team,
                                        'position' => $res->position,
                                    );
                                    break;
                                } else {
                                    $a = array(
                                        'user_name' => '',
                                        'pubg_id' => '',
                                        'team' => $j,
                                        'position' => $i,
                                    );
                                }
                            }
                        }
                        $data['result'][] = $a;
                    }
                }
            } elseif ($match->type == 'Squad') {
                $loop = ceil($match->number_of_position / 4);
                for ($j = 1; $j <= $loop; $j++) {
                    for ($i = 1; $i <= 4; $i++) {
                        if (count($match_join_member) == 0) {
                            $a = array(
                                'user_name' => '',
                                'pubg_id' => '',
                                'team' => $j,
                                'position' => $i,
                            );
                        } else {
                            foreach ($match_join_member as $res) {
                                if ($res->team == $j && $res->position == $i) {
                                    $a = array(
                                        'user_name' => $res->user_name,
                                        'pubg_id' => $res->pubg_id,
                                        'team' => $res->team,
                                        'position' => $res->position,
                                    );
                                    break;
                                } else {
                                    $a = array(
                                        'user_name' => '',
                                        'pubg_id' => '',
                                        'team' => $j,
                                        'position' => $i,
                                    );
                                }
                            }
                        }
                        $data['result'][] = $a;
                    }
                }
            } elseif ($match->type == 'Squad5') {
                $loop = ceil($match->number_of_position / 5);
                for ($j = 1; $j <= $loop; $j++) {
                    for ($i = 1; $i <= 5; $i++) {
                        if (count($match_join_member) == 0) {
                            $a = array(
                                'user_name' => '',
                                'pubg_id' => '',
                                'team' => $j,
                                'position' => $i,
                            );
                        } else {
                            foreach ($match_join_member as $res) {
                                if ($res->team == $j && $res->position == $i) {
                                    $a = array(
                                        'user_name' => $res->user_name,
                                        'pubg_id' => $res->pubg_id,
                                        'team' => $res->team,
                                        'position' => $res->position,
                                    );
                                    break;
                                } else {
                                    $a = array(
                                        'user_name' => '',
                                        'pubg_id' => '',
                                        'team' => $j,
                                        'position' => $i,
                                    );
                                }
                            }
                        }
                        $data['result'][] = $a;
                    }
                }
            }
            $data['match'] = $match;
            $array['status'] = true;
            $array['title'] = 'Success!';
            $array['message'] = $data;

            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function updateMyprofile(Request $request) {
        if (($request->input('submit')) && $request->input('submit') == 'save') {
            $user = DB::table('member')->where('member_id', $request->input('member_id'))->first();
            if ($user->login_via == '0') {
                $member_id = Auth::user()->member_id;

                $validator = Validator::make($request->all(), [
//                            'country_id' => 'required',
                            'first_name' => 'required',
                            'last_name' => 'required',
                            'country_code' => 'required',
                            'user_name' => 'required|unique:member,user_name,' . $request->input('member_id') . ',member_id',
                            'email_id' => [
                                'required',
                                Rule::unique('member')->where(function ($query) {
                                            return $query->where('member_id', '!=', Auth::user()->member_id)->where('login_via', '0');
                                        }),
                            ],
                            'mobile_no' => 'required|unique:member,mobile_no,' . $request->input('member_id') . ',member_id|numeric|digits_between:7,15',
                            // 'member_pass' => 'required',
                                ], [
                            'first_name.required' => trans('message.err_fname_req'),
                            'last_name.required' => trans('message.err_lname_req'),
                            'user_name.required' => trans('message.err_username_req'),
                            'user_name.unique' => trans('message.err_username_exist'),
                            'email_id.required' => trans('message.err_email_req'),
                            'email_id.unique' => trans('message.err_email_exist'),
//                            'country_id.required' => trans('message.err_country_req'),
                            'country_code.required' => trans('message.err_country_code_req'),
                            'mobile_no.required' => trans('message.err_mobile_no_req'),
                            'mobile_no.numeric' => trans('message.err_mobile_no_num'),
                            'mobile_no.unique' => trans('message.err_mobile_no_exist'),
                            'mobile_no.digits_between' => trans('message.err_mobile_no_7to15'),
                            // 'member_pass.required' => trans('message.err_password_req'),
                ]);
            } elseif ($user->login_via == '1') {
                $validator = Validator::make($request->all(), [
                            'first_name' => 'required',
                            'last_name' => 'required',
                            'user_name' => 'required|unique:member,user_name,' . $request->input('member_id') . ',member_id',
                            'mobile_no' => 'required|unique:member,mobile_no,' . $request->input('member_id') . ',member_id|numeric|digits_between:7,15',
                            // 'member_pass' => 'required',
                                ], [
                            'first_name.required' => trans('message.err_fname_req'),
                            'last_name.required' => trans('message.err_lname_req'),
                            'user_name.required' => trans('message.err_username_req'),
                            'user_name.unique' => trans('message.err_username_exist'),
//                            'country_id.required' => trans('message.err_country_req'),
                            'country_code.required' => trans('message.err_country_code_req'),
                            'mobile_no.required' => trans('message.err_mobile_no_req'),
                            'mobile_no.numeric' => trans('message.err_mobile_no_num'),
                            'mobile_no.digits_between' => trans('message.err_mobile_no_7to15'),
                            // 'member_pass.required' => trans('message.err_password_req'),
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                            'first_name' => 'required',
                            'last_name' => 'required',
                            'user_name' => 'required|unique:member,user_name,' . $request->input('member_id') . ',member_id',
                            'email_id' => [
                                'required',
                            ],
                            'mobile_no' => 'required|unique:member,mobile_no,' . $request->input('member_id') . ',member_id|numeric|digits_between:7,15',
                            // 'member_pass' => 'required',
                                ], [
                            'first_name.required' => trans('message.err_fname_req'),
                            'last_name.required' => trans('message.err_lname_req'),
                            'user_name.required' => trans('message.err_username_req'),
                            'user_name.unique' => trans('message.err_username_exist'),
                            'email_id.required' => trans('message.err_email_req'),
//                            'country_id.required' => trans('message.err_country_req'),
                            'country_code.required' => trans('message.err_country_code_req'),
                            'mobile_no.required' => trans('message.err_mobile_no_req'),
                            'mobile_no.numeric' => trans('message.err_mobile_no_num'),
                            'mobile_no.digits_between' => trans('message.err_mobile_no_7to15'),
                            // 'member_pass.required' => trans('message.err_password_req'),
                ]);
            }
            
            if ($validator->fails()) {
                                
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }

            if($request->file('profile_image')) {
                
                $file = $request->file('profile_image');

                $allowed_type_arr = array('jpeg', 'png','jpg');

                if (!in_array($file->getClientOriginalExtension(), $allowed_type_arr)) {
                    $array['status'] = false;
                    $array['title'] = 'Error!';
                    $array['message'] = trans('File type not valid !');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                }

                if ($file->getClientSize() > 2000000) {
                    $array['status'] = false;
                    $array['title'] = 'Error!';
                    $array['message'] = trans('Image size exceeds 2MB !');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                }
                
                if (file_exists(substr(base_path(), 0, strrpos(base_path(), '/')) . '/uploads/profile_image/' . $user->profile_image)) {
                        @unlink(substr(base_path(), 0, strrpos(base_path(), '/')) . '/uploads/profile_image/' . $user->profile_image);
                }
                    
                foreach ($this->profile_img_size_array as $key => $val) {
                    if (file_exists(substr(base_path(), 0, strrpos(base_path(), '/')) . '/uploads/profile_image/thumb/' . $key . 'x' . $val . '_' . $user->profile_image)) {
                        @unlink(substr(base_path(), 0, strrpos(base_path(), '/')) . '/uploads/profile_image/thumb/' . $key . 'x' . $val . '_' . $user->profile_image);
                    }
                }                                
               
                $destinationPath = substr(base_path(), 0, strrpos(base_path(), '/')) . '/uploads/profile_image/';
                $destinationPathThumb = $destinationPath . 'thumb/';
                
                // $filename = $file->getClientOriginalName();                            
                
                $ext = $file->getClientOriginalExtension();

                $filename = 'member_' . rand() . '_' . $request->input('member_id') . '.' . $ext;
                
                $original = $file->move($destinationPath, $filename);
               
                foreach ($this->profile_img_size_array as $key => $val) {                                        
                    $real_path = $original->getRealPath();                
                    list($width_orig, $height_orig, $image_type) = getimagesize($real_path);				                                                
                                                                
                    if ($width_orig != $key || $height_orig != $val) {  
                        $oc_image = new OC_Image;                                                                                                                                                                  
                        $oc_image::initialize($real_path);                                                       
                        $oc_image::resize($key, $val);
                        $oc_image::save($destinationPathThumb . $key . "x" . $val . "_" . $filename);
                    } else {
                        copy($real_path, $destinationPathThumb . $key . "x" . $val . "_" . $filename);
                    }
                }

                $data = [
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'user_name' => $request->input('user_name'),
                    'email_id' => $request->input('email_id'),
                    'mobile_no' => $request->input('mobile_no'),
    //                'country_id' => $request->input('country_id'),
                    'country_code' => $request->input('country_code'),
                    'dob' => $request->input('dob'),
                    'gender' => $request->input('gender'),
                    'profile_image' => $filename
                ];
               
            } else {                
                $data = [
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'user_name' => $request->input('user_name'),
                    'email_id' => $request->input('email_id'),
                    'mobile_no' => $request->input('mobile_no'),
    //                'country_id' => $request->input('country_id'),
                    'country_code' => $request->input('country_code'),
                    'dob' => $request->input('dob'),
                    'gender' => $request->input('gender')];
            }
                        
            $res = DB::table('member')->where('member_id', $request->input('member_id'))->update($data);
            $array['status'] = true;
            $array['title'] = 'Success!';
            $array['message'] = "Profile Updated Successfully";
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
        if (($request->input('submit')) && $request->input('submit') == 'reset') {
            $array = array();
            $validator = Validator::make($request->all(), [
                        'oldpass' => "required",
                        'newpass' => 'required',
                        'confpass' => 'required|same:newpass|different:oldpass',
                            ], [
                        'oldpass.required' => trans('message.err_old_password_req'),
                        'newpass.required' => trans('message.err_new_password_req'),
                        'confpass.required' => trans('message.err_cpassword_req'),
                        'confpass.same' => trans('message.err_pass_cpass_not_same'),
                        'confpass.different' => trans('message.err_npassword_oldpass_same'),
            ]);
            if ($validator->fails()) {
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }
            if (md5($request->oldpass) != Auth::user()->password) {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.err_old_pass_wrong');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $member = DB::table('member')
                        ->where('member_id', $request->input('member_id'))
                        ->where('password', md5($request->input('oldpass')))
                        ->get();
                if ($member->count() > 0) {
                    $data = [
                        'password' => md5($request->input('newpass'))];
                    $res = DB::table('member')->where('member_id', $request->input('member_id'))->where('password', md5($request->input('oldpass')))->update($data);
                    if ($res) {
                        $array['status'] = true;
                        $array['title'] = 'Success!';
                        $array['message'] = trans('message.text_succ_pass_change');
                        echo json_encode($array,JSON_UNESCAPED_UNICODE);
                        exit;
                    } else {
                        $array['status'] = false;
                        $array['title'] = 'Error!';
                        $array['message'] = trans('message.text_err_pass_not_change');
                        echo json_encode($array,JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                } else {
                    $array['status'] = false;
                    $array['title'] = 'Error!';
                    $array['message'] = trans('message.err_check_credentials');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
        }
        if (($request->input('submit')) && $request->input('submit') == 'submit_push_noti') {
            $array = array();
                                                    
            $data = [
                'push_noti' => $request->input('push_noti')];
            $res = DB::table('member')->where('member_id', $request->input('member_id'))->update($data);                    
            if ($res) {
                $member = DB::table('member')
                ->where('member_id', $request->input('member_id'))                        
                ->first();
                $array['status'] = true;
                $array['title'] = 'Success!';                
                $array['member_data'] = $member;
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $array['status'] = false;
                $array['title'] = 'Error!';                
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }                            
        }
    }

    function generate_password($len) {
        $r_str = "";
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
        for ($i = 0; $i < $len; $i++)
            $r_str .= substr($chars, rand(0, strlen($chars)), 1);
        return $r_str;
    }

    public function sendOTP(Request $request) {
        if ($this->system_config['msg91_otp'] == '0' || $this->system_config['msg91_otp'] == 0) {
            $validator = Validator::make($request->all(), [
                        'email_mobile' => 'required|email',], [
                        'email_mobile.required' => trans('message.err_email_req'),
                        'email_mobile.email' => trans('message.err_email_valid'),
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                        'email_mobile' => 'required',
                            ], [
                        'email_mobile.required' => trans('message.err_email_or_mobile_req'),
            ]);
        }
        if ($validator->fails()) {
            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
        }
        $member = DB::table('member as m')
                ->where('login_via', '0')
                ->where('email_id', $request->input('email_mobile'))
                ->orWhere('mobile_no', $request->input('email_mobile'))
                ->select('m.*')
                ->get();
        if ($member->count() > 0) {
            if (strtolower($member[0]->email_id) == strtolower($request->input('email_mobile'))) {
                $otp = $this->generate_OTP(6);
                $smtpUsername = $this->system_config['smtp_user'];
                $smtpPassword = urldecode($this->system_config['smtp_pass']);
                $emailFrom = $this->system_config['company_email'];
                $emailFromName = $this->system_config['company_name'];
                $emailTo = $request->input('email_mobile');
                $mail = new PHPMailer;

                //$mail->isSMTP();
                $mail->Host = $this->system_config['smtp_host'];
                $mail->Port = $this->system_config['smtp_port'];
                $mail->SMTPSecure = $this->system_config['smtp_secure']; //'ssl'
                $mail->SMTPAuth = true;
                $mail->Username = $smtpUsername;
                $mail->Password = $smtpPassword;
                $mail->setFrom($smtpUsername, $emailFromName);
                $mail->addAddress($emailTo);
                $mail->isHTML(true);
                $mail->Subject = "Password Recover";
                $mail->Body = "<html>
                            <head>
                            <title>Password Recover </title>
                            </head>
                            <body>
                            <p>Your verification otp is : $otp</p>                            
                            </body>
                            </html>";
                $mail->send();
                
                
                $array['status'] = true;
                $array['title'] = 'Success!';
                $array['message'] = trans('message.text_succ_send_mail');
                $array['member_id'] = $member[0]->member_id;
                $array['otp'] = $otp;
                header('Access-Control-Allow-Origin: *');
                header('Content-type: application/json');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
//                $otp = $this->generate_OTP(6);
//                $to = $request->input('email_mobile');
//                $subject = "Password Recover";
//                $message = "<html>
//                            <head>
//                            <title>Password Recover </title>
//                            </head>
//                            <body>
//                            <p>Your verification otp is : $otp</p>                            
//                            </body>
//                            </html>";
//                $company_email = $this->system_config['company_email'];
//                $headers = "From: $company_email \r\n";
//                $headers .= "MIME-Version: 1.0\r\n";
//                $headers .= "Content-type: text/html\r\n";
//                if (mail($to, $subject, $message, $headers)) {
//                    $array['status'] = true;
//                    $array['title'] = 'Success!';
//                    $array['message'] = 'OTP send in mail.Please check your email.';
//                    $array['member_id'] = $member[0]->member_id;
//                    $array['otp'] = $otp;
//                    header('Access-Control-Allow-Origin: *');
//                    header('Content-type: application/json');
//                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
//                    exit;
//                } else {
//                    $array['status'] = false;
//                    $array['title'] = 'Error!';
//                    $array['message'] = 'mail not send !';
//                    header('Access-Control-Allow-Origin: *');
//                    header('Content-type: application/json');
//                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
//                    exit;
//                }
            } elseif ($member[0]->mobile_no == $request->input('email_mobile')) {
                $message = "Your verification code is : $otp";
                $m_number = $member[0]->country_code . $member[0]->mobile_no;
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?sender=" . $this->system_config['msg91_sender'] . "&route=" . $this->system_config['msg91_route'] . "&mobiles=" . $m_number . "&authkey=" . $this->system_config['msg91_authkey'] . "&encrypt=0&country=" . $member[0]->country_code . "&message=" . urlencode($message) . "&response=json",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ));
                $response = curl_exec($curl);
                $response = json_decode($response);
                $err = curl_error($curl);
                curl_close($curl);
                if ($response->type == 'success') {
                    $array['status'] = true;
                    $array['title'] = 'Success!';
                    $array['message'] = trans('message.text_succ_send_sms');
                    $array['member_id'] = $member[0]->member_id;
                    $array['otp'] = $otp;
                    header('Access-Control-Allow-Origin: *');
                    header('Content-type: application/json');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                } else {
                    $array['status'] = false;
                    $array['title'] = 'Error!';
                    $array['message'] = trans('message.text_err_sms');
                    header('Access-Control-Allow-Origin: *');
                    header('Content-type: application/json');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
        } else {
            $array['status'] = false;
            $array['title'] = 'Error!';
            $array['message'] = trans('message.err_email_or_mobile_exist');
            header('Access-Control-Allow-Origin: *');
            header('Content-type: application/json');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function forgotpassword(Request $request) {
        if (($request->input('submit')) && $request->input('submit') == 'forgotpass') {
            $validator = Validator::make($request->all(), [
                        'member_id' => 'required',
                        'password' => 'required|min:6',
                        'cpassword' => 'required|same:password|min:6',
                            ], [
                        'member_id.required' => trans('message.err_member_id'),
                        'password.required' => trans('message.err_password_req'),
                        'password.min' => trans('message.err_password_min'),
                        'cpassword.required' => trans('message.err_cpassword_req'),
                        'cpassword.same' => trans('message.err_pass_cpass_not_same'),
                        'cpassword.min' => trans('message.err_cpassword_min'),
            ]);
            if ($validator->fails()) {
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }
            $res = DB::table('member')->where('member_id', $request->input('member_id'))->update(['password' => md5($request->input('password'))]);
            $array['status'] = true;
            $array['title'] = 'Success!';
            $array['message'] = trans('message.text_succ_pass_change');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function withdrawMethod() {
        $data['withdraw_method'] = DB::table('withdraw_method')
                ->leftJoin('currency as c', 'c.currency_id', '=', 'withdraw_method.withdraw_method_currency')
                ->where("withdraw_method_status", '1')
                ->select('withdraw_method.*', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')
                ->get();
        $data['min_withdrawal'] = $this->system_config['min_withdrawal'];
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function withdraw(Request $request) {
        if (($request->input('submit')) && $request->input('submit') == 'withdraw') {
            $array = array();
            $currency = DB::table('currency')
                    ->where("currency_id", $this->system_config['currency'])
                    ->first();
            $withdraw_method = DB::table('withdraw_method')
                    ->leftJoin('currency as c', 'c.currency_id', '=', 'withdraw_method.withdraw_method_currency')
                    ->where("withdraw_method", $request->input('withdraw_method'))
                    ->select('withdraw_method_field')
                    ->first();
            if ($withdraw_method->withdraw_method_field == 'mobile no') {
                $validator = Validator::make($request->all(), [
                            'member_id' => 'required',
                            'pyatmnumber' => 'required|numeric|digits_between:7,15',
                            'amount' => 'required|numeric|min:' . $this->system_config['min_withdrawal'],
                                ], [
                            'member_id.required' => trans('message.err_member_id'),
                            'pyatmnumber.required' => trans('message.err_mobile_no_req'),
                            'pyatmnumber.numeric' => trans('message.err_mobile_no_num'),
                            'pyatmnumber.digits_between' => trans('message.err_mobile_no_7to15'),
                            'amount.required' => trans('message.err_amount_req'),
                            'amount.min' => trans('message.err_amount_min', ['currency' => '', 'amount' => $this->system_config['min_withdrawal']]),
                ]);
            } else if ($withdraw_method->withdraw_method_field == 'email') {
                $validator = Validator::make($request->all(), [
                            'member_id' => 'required',
                            'pyatmnumber' => 'required|email',
                            'amount' => 'required|numeric|min:' . $this->system_config['min_withdrawal'],
                                ], [
                            'member_id.required' => trans('message.err_member_id'),
                            'pyatmnumber.required' => trans('message.err_email_req'),
                            'pyatmnumber.email' => trans('message.err_email_valid'),
                            'amount.required' => trans('message.err_amount_req'),
                            'amount.min' => trans('message.err_amount_min', ['currency' => '', 'amount' => $this->system_config['min_withdrawal']]),
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                            'member_id' => 'required',
                            'pyatmnumber' => 'required',
                            'amount' => 'required|numeric|min:' . $this->system_config['min_withdrawal'],
                                ], [
                            'member_id.required' => trans('message.err_member_id'),
                            'pyatmnumber.required' => trans('message.err_upi_req'),
                            'amount.required' => trans('message.err_amount_req'),
                            'amount.min' => trans('message.err_amount_min', ['currency' => '', 'amount' => $this->system_config['min_withdrawal']]),
                ]);
            }
            if ($validator->fails()) {
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }
            $member = DB::table('member')
                    ->where('member_id', $request->input('member_id'))
                    ->first();
            if ($member) {
                
                if($member->wallet_balance < $this->system_config['min_require_balance_for_withdrawal']) {
                    $array['status'] = false;
                    $array['title'] = 'Error!';
                    $array['message'] = 'Wallet Balance shoulde be greater than '. $this->system_config['min_require_balance_for_withdrawal'] .' for withdraw.';
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;                    
                }

                if ($member->wallet_balance >= $request->input('amount')) {

                    $wallet_balance = $member->wallet_balance - $request->input('amount');
                    $browser = '';
                    $agent = new Agent();
                    if ($agent->isMobile()) {
                        $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                    } elseif ($agent->isDesktop()) {
                        $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                    } elseif ($agent->isRobot()) {
                        $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                    }
                    $ip = $this->getIp();
                    $acc_data = [
                        'member_id' => $request->input('member_id'),
                        'pubg_id' => $member->pubg_id,
                        'from_mem_id' => 0,
                        'deposit' => 0,
                        'withdraw' => $request->input('amount'),
                        'join_money' => $member->join_money,
                        'win_money' => $wallet_balance,
                        'pyatmnumber' => $request->input('pyatmnumber'),
                        'withdraw_method' => $request->input('withdraw_method'),
                        'note' => 'Withdraw Money from Win Wallet',
                        'note_id' => '9',
                        'entry_from' => '1',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                    ];
                    $acc_id = DB::table('accountstatement')->insertGetId($acc_data);
                    $data = [
                        'wallet_balance' => $wallet_balance];
                    DB::table('member')->where('member_id', $request->input('member_id'))->update($data);

                    $array['status'] = true;
                    $array['title'] = 'Success!';
                    $array['message'] = trans('message.text_succ_withdraw', ['method' => $request->input('withdraw_method')]);
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                } else {
                    $array['status'] = false;
                    $array['title'] = 'Error!';
                    $array['message'] = trans('message.err_balance_low');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.err_something_went_wrong');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }

    public function oneSignalApp() {
        $array = ["one_signal_app_id" => $this->system_config['app_id'], "one_signal_notification" => $this->system_config['one_signal_notification']];
        echo json_encode($array,JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function version($versionfor) {
        
        Cache::flush();
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        if ($versionfor == 'android') {
            $app_upload = DB::table('app_upload')
                    ->orderBy('app_upload_id', 'DESC')
                    ->first();
            $currency = DB::table('currency')
                    ->where("currency_id", $this->system_config['currency'])
                    ->first();
            $data['web_config']['currency'] = $currency->currency_code;
            $data['web_config']['currency_symbol'] = $currency->currency_symbol;
            if ($app_upload) {
                $array = ["currency_code" => $currency->currency_code, "currency_symbol" => $currency->currency_symbol, "banner_ads_show" => $this->system_config['banner_ads_show'], "fb_login" => $this->system_config['fb_login'], "google_login" => $this->system_config['google_login'], "firebase_otp" => $this->system_config['firebase_otp'], "version" => $app_upload->app_version, "force_update" => $app_upload->force_update, "force_logged_out" => $app_upload->force_logged_out, "url" => $this->base_url . '/' . $this->system_config['admin_photo'] . '/apk/' . $app_upload->app_upload, "description" => $app_upload->app_description];
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $array = ["currency_code" => $currency->currency_code, "currency_symbol" => $currency->currency_symbol, "banner_ads_show" => $this->system_config['banner_ads_show'], "fb_login" => $this->system_config['fb_login'], "google_login" => $this->system_config['google_login'], "firebase_otp" => $this->system_config['firebase_otp'], "version" => '1', "force_update" => "No", "force_logged_out" => "No", "url" => $this->base_url . '/' . $this->system_config['admin_photo'] . '/apk/', "description" => ''];
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
            }
        }
    }

    public function youTubeLink() {
        $data['youtube_links'] = DB::table('youtube_link')
                ->get();
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function changePlayerName(Request $request) {
        $validator = Validator::make($request->all(), [
                    'match_id' => 'required',
                    'member_id' => 'required',
                    'pubg_id' => 'required',
                    'match_join_member_id' => 'required'
                        ], [
                    'match_id.required' => trans('message.err_match_id'),
                    'member_id.required' => trans('message.err_member_id'),
                    'pubg_id.required' => trans('message.err_playername_req'),
                    'match_join_member_id.required' => trans('message.err_match_join_member_id_id'),
        ]);
        if ($validator->fails()) {
            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
        }
        $match = DB::table('match_join_member')
                ->where('match_id', $request->input('match_id'))
                ->where('pubg_id', $request->input('pubg_id'))
                ->where('match_join_member_id', '!=', $request->input('match_join_member_id'))
                ->count();
        if ($match < 0) {
            $array['status'] = false;
            $array['title'] = 'Error!';
            $array['message'] = trans('message.err_playername_already_join', ['playername' => $request->input('pubg_id')]);
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
        $data = [
            "pubg_id" => $request->input('pubg_id')
        ];
        if (DB::table('match_join_member')->where('match_join_member_id', $request->input('match_join_member_id'))->update($data)) {
            $array['status'] = true;
            $array['title'] = 'Success!';
            $array['message'] = trans('message.text_succ_playername_change');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $array['status'] = false;
            $array['title'] = 'Error!';
            $array['message'] = trans('message.text_err_playername_change');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function joinMatchProcess(Request $request) {
        if (($request->input('submit')) && $request->input('submit') == 'joinnow') {

            $validator = Validator::make($request->all(), [
                        'match_id' => 'required',
                        'member_id' => 'required',
                        'teamposition' => 'required',
                            ], [
                        'match_id.required' => trans('message.err_match_id'),
                        'member_id.required' => trans('message.err_member_id'),
                        'teamposition.required' => trans('message.err_team_position'),
            ]);
            if ($validator->fails()) {
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }

            $resp = '';
            $m = DB::table('matches')
                    ->where('m_id', $request->input('match_id'))
                    ->first();
            if ($m->no_of_player + count($request->input('teamposition')) > $m->number_of_position) {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.err_no_spot');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
            foreach ($request->input('teamposition') as $teamposition) {
                $match = DB::table('match_join_member')
                        ->where('match_id', $request->input('match_id'))
                        ->where('pubg_id', $teamposition['pubg_id'])
                        ->get();
                if (count($match) > 0) {
                    $resp .= trans('message.err_playername_already_join', ['playername' => $teamposition['pubg_id']]);
                }
            }
            if ($resp != '') {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trim($resp, ', ');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
            $resp1 = '';
            foreach ($request->input('teamposition') as $teamposition) {
                $member = DB::table('match_join_member')
                        ->where('match_id', $request->input('match_id'))
                        ->where('team', $teamposition['team'])
                        ->where('position', $teamposition['position'])
                        ->get();
                if (count($member) > 0) {
                    $resp1 .= trans('message.err_playername_already_join', ['teamname' => $teamposition['team'], 'position' => $teamposition['position']]);
                }
            }
            if ($resp1 != '') {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trim($resp1, ', ');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
            $member_data = DB::table('member')
                    ->where('member_id', $request->input('member_id'))
                    ->first();
            $match_data = DB::table('matches')
                    ->where('m_id', $request->input('match_id'))
                    ->first();
            $ar_len = count($request->input('teamposition'));
            $fee = $match_data->entry_fee * $ar_len;
            if ($member_data->wallet_balance + $member_data->join_money >= $fee) {

                $ar_len = count($request->input('teamposition'));
                $i = 1;
                foreach ($request->input('teamposition') as $teamposition) {
                    $match_join_member_data = [
                        'match_id' => $request->input('match_id'),
                        'member_id' => $request->input('member_id'),
                        'pubg_id' => $teamposition['pubg_id'],
                        'team' => $teamposition['team'],
                        'position' => $teamposition['position'],
                        'place' => 0,
                        'place_point' => 0,
                        'killed' => 0,
                        'win' => 0,
                        'win_prize' => 0,
                        'bonus' => 0,
                        'total_win' => 0,
                        'refund' => 0,
                        'entry_from' => '1',
                        'date_craeted' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                    ];
                    DB::table('match_join_member')->insert($match_join_member_data);

                    if ($match_data->match_type == '0' || $match_data->match_type == 0) {
                        if ($i == 1 && $request->input('join_status') == "false") {

                            if($member_data->pubg_id != ''){
                                $pubg_id = unserialize($member_data->pubg_id);
                            } else {
                                $pubg_id = $member_data->pubg_id;
                            }
                            
                            if (is_array($pubg_id)) {
                                // if ($pubg_id->getType() && $pubg_id->getType()->getName() === 'array') {
                                    if (array_key_exists($match_data->game_id, $pubg_id)) {
                                    $pubg_id[$match_data->game_id] = $teamposition['pubg_id'];
                                } else {
                                    $pubg_id[$match_data->game_id] = $teamposition['pubg_id'];
                                }
                                $pubg_id = serialize($pubg_id);
                                $data = array(
                                    'pubg_id' => $pubg_id,
                                );
                            } else {
                                $pubg = array(
                                    $match_data->game_id => $teamposition['pubg_id'],
                                );
                                $pubg_id = serialize($pubg);
                                $data = array(
                                    'pubg_id' => $pubg_id,
                                );
                            }
                            DB::table('member')->where('member_id', $request->input('member_id'))->update($data);
                        }
                        if ($ar_len == $i) {
                            $no_of_player = DB::table('match_join_member')
                                            ->where('match_id', $request->input('match_id'))
                                            ->select(DB::raw("COUNT(*) as no_of_player"))
                                            ->first()->no_of_player;
                            $data = [
                                'no_of_player' => $no_of_player];
                            DB::table('matches')->where('m_id', $request->input('match_id'))->update($data);
                            $array['status'] = true;
                            $array['title'] = 'Success!';
                            $array['message'] = trans('message.text_succ_join');
                            echo json_encode($array,JSON_UNESCAPED_UNICODE);
                            exit;
                        }
                    } else {
                        $row1 = DB::table('member')
                                ->where('member_id', $request->input('member_id'))
                                ->first();

                        if ($row1->join_money > $match_data->entry_fee) {
                            $join_money = $row1->join_money - $match_data->entry_fee;
                            $wallet_balance = $row1->wallet_balance;
                        } elseif ($row1->join_money < $match_data->entry_fee) {
                            $join_money = 0;
                            $amount1 = $match_data->entry_fee - $row1->join_money;
                            $wallet_balance = $row1->wallet_balance - $amount1;
                        } elseif ($row1->join_money == $match_data->entry_fee) {
                            $join_money = 0;
                            $wallet_balance = $row1->wallet_balance;
                        }
                        if ($i == 1 && $request->input('join_status') == "false") {

                            if($member_data->pubg_id != ''){
                                $pubg = unserialize($member_data->pubg_id);
                            } else {
                                $pubg = $member_data->pubg_id;
                            }

                            if(is_array($pubg)) {
                            // if ($pubg->getType() && $pubg->getType()->getName() === 'array') {
                                if (array_key_exists($match_data->game_id, $pubg)) {
                                    $pubg[$match_data->game_id] = $teamposition['pubg_id'];
                                } else {
                                    $pubg[$match_data->game_id] = $teamposition['pubg_id'];
                                }
                                $data = array(
                                    'pubg_id' => $pubg,
                                );
                            } else {

                                $pubg = array(
                                    $match_data->game_id => $teamposition['pubg_id'],
                                );
                                $pubg_id = serialize($pubg);
                                $data = array(
                                    'pubg_id' => $pubg_id,
                                );
                            }
                            $pubg_id = serialize($pubg);
                            $data = [
                                'join_money' => $join_money,
                                'wallet_balance' => $wallet_balance,
                                'pubg_id' => $pubg_id,
                            ];
                        } else {
                            $data = [
                                'join_money' => $join_money,
                                'wallet_balance' => $wallet_balance,
                            ];
                        }
                        $browser = '';
                        $agent = new Agent();
                        if ($agent->isMobile()) {
                            $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                        } elseif ($agent->isDesktop()) {
                            $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                        } elseif ($agent->isRobot()) {
                            $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                        }
                        $ip = $this->getIp();
                        $acc_data = [
                            'member_id' => $request->input('member_id'),
                            'pubg_id' => $teamposition['pubg_id'],
                            'match_id' => $request->input('match_id'),
                            'deposit' => 0,
                            'withdraw' => $match_data->entry_fee,
                            'join_money' => $join_money,
                            'win_money' => $wallet_balance,
                            'note' => 'Match Joined',
                            'note_id' => '2',
                            'entry_from' => '1',
                            'ip_detail' => $ip,
                            'browser' => $browser,
                            'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                        ];
                        DB::table('accountstatement')->insert($acc_data);

                        DB::table('member')->where('member_id', $request->input('member_id'))->update($data);

                        if ($row1->member_package_upgraded == 0 && (float)$match_data->entry_fee >= (float)$this->system_config['referral_min_paid_fee']) {                            
                            $wallet_balance = (float)$wallet_balance + (float)$this->system_config['referral']; //$SYSTEM_CONFIG['referral'];
                            
                            $data = [
                                'member_package_upgraded' => '1',
                            ];
                            DB::table('member')->where('member_id', $request->input('member_id'))->update($data);
                            if ($row1->referral_id != 0 && $this->system_config['active_referral'] == '1') {
                                $row2 = DB::table('member')
                                        ->where('member_id', $row1->referral_id)
                                        ->first();
                                if ($row2->member_package_upgraded == 1) {
                                    $join_money2 = $row2->join_money + $this->system_config['referral_level1']; //$SYSTEM_CONFIG['referral_level1'];
                                    $data = [
                                        'join_money' => $join_money2,];
                                    DB::table('member')->where('member_id', $row1->referral_id)->update($data);

                                    $referral_data = [
                                        'member_id' => $row2->member_id,
                                        'from_mem_id' => $request->input('member_id'),
                                        'referral_amount' => $this->system_config['referral_level1'], //$SYSTEM_CONFIG['referral']
                                        'referral_status' => '0',
                                        'entry_from' => '1',
                                         'referral_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                                    ];
                                    DB::table('referral')->insert($referral_data);
                                    $browser = '';
                                    $agent = new Agent();
                                    if ($agent->isMobile()) {
                                        $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                                    } elseif ($agent->isDesktop()) {
                                        $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                                    } elseif ($agent->isRobot()) {
                                        $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                                    }
                                    $ip = $this->getIp();
                                    $acc_data = [
                                        'member_id' => $row2->member_id,
                                        'pubg_id' => $row2->pubg_id,
                                        'from_mem_id' => $request->input('member_id'),
                                        'deposit' => $this->system_config['referral_level1'],
                                        'withdraw' => 0,
                                        'join_money' => $join_money2,
                                        'win_money' => $row2->wallet_balance,
                                        'note' => 'Referral',
                                        'note_id' => '4',
                                        'entry_from' => '1',
                                        'ip_detail' => $ip,
                                        'browser' => $browser,
                                         'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                                    ];
                                    DB::table('accountstatement')->insert($acc_data);
                                }
                            }
                        }
                        if ($ar_len == $i) {
                            $no_of_player = DB::table('match_join_member')
                                            ->where('match_id', $request->input('match_id'))
                                            ->select(DB::raw("COUNT(*) as no_of_player"))
                                            ->first()->no_of_player;
                            $data = [
                                'no_of_player' => $no_of_player];
                            DB::table('matches')->where('m_id', $request->input('match_id'))->update($data);
                            $array['status'] = true;
                            $array['title'] = 'Success!';
                            $array['message'] = trans('message.text_succ_join');
                            echo json_encode($array,JSON_UNESCAPED_UNICODE);
                            exit;
                        }
                    }
                    $i++;
                }
            } else {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.err_balance_low');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }

    public function getPayment() {
        $data['payment'] = array();
        $payments = DB::table('pg_detail')
                        ->leftJoin('currency as c', 'c.currency_id', '=', 'pg_detail.currency')
                        ->where('pg_detail.status', '1')->select('pg_detail.*', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')
                        ->orderBy('id', 'ASC')->get();
        $i = 0;
        foreach ($payments as $payment) {
            $data['payment'][$i]['payment_name'] = $payment->payment_name;
            $data['payment'][$i]['payment_status'] = $payment->payment_status;
            if ($payment->payment_name == 'PayTm') {
                $data['payment'][$i]['mid'] = $payment->mid;
                $data['payment'][$i]['mkey'] = $payment->mkey;
                $data['payment'][$i]['wname'] = $payment->wname;
                $data['payment'][$i]['ityp'] = $payment->itype;
            } else if ($payment->payment_name == 'PayPal') {
                $data['payment'][$i]['client_id'] = $payment->mid;
            } else if ($payment->payment_name == 'Offline') {
                $data['payment'][$i]['payment_description'] = $payment->payment_description;
            } else if ($payment->payment_name == 'PayStack') {
                $data['payment'][$i]['secret_key'] = $payment->mid;
                $data['payment'][$i]['public_key'] = $payment->mkey;
            } else if ($payment->payment_name == 'Instamojo') {
                $data['payment'][$i]['client_id'] = $payment->mid;
                $data['payment'][$i]['client_key'] = $payment->mkey;
            } else if ($payment->payment_name == 'Razorpay') {
                $data['payment'][$i]['api_secret'] = $payment->mkey;
                $data['payment'][$i]['key_id'] = $payment->mid;
            } else if ($payment->payment_name == 'Cashfree') {
                $data['payment'][$i]['secret_key'] = $payment->mkey;
                $data['payment'][$i]['app_id'] = $payment->mid;
            } else if ($payment->payment_name == 'Google Pay') {
                $data['payment'][$i]['upi_id'] = $payment->mid;
            } elseif ($payment->payment_name == 'PayU') {                
                $data['payment'][$i]['mkey'] = $payment->mkey;
                $data['payment'][$i]['salt'] = $payment->wname;               
            } 
            $data['payment'][$i]['currency_name'] = $payment->currency_name;
            $data['payment'][$i]['currency_point'] = $payment->currency_point;
            $data['payment'][$i]['currency_code'] = $payment->currency_code;
            $data['payment'][$i]['currency_symbol'] = $payment->currency_symbol;
            $i++;
        }

        $data['min_addmoney'] = $this->system_config['min_addmoney'];
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function getPaymentDetails() {
        $row = DB::table('pg_detail')->where('id', $this->system_config['payment'])->first();
        $data['client_id'] = $row->mid;
        $data['status'] = $row->payment_status;
        $data['min_addmoney'] = $this->system_config['min_addmoney'];
        $data['payment_description'] = '';
        $data['secret_key'] = $row->mid;
        $data['public_key'] = $row->mkey;
        $data['payment'] = $row->payment_name;
        $data['app_id'] = '';
        $data['secret_key'] = '';
        if ($row->payment_name == 'Offline') {
            $data['payment_description'] = $row->payment_description;
        } elseif ($row->payment_name == 'Cashfree') {
            $data['app_id'] = $row->mid;
            $data['secret_key'] = $row->mkey;
        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function addMoney(Request $request) {
        Cache::flush();
//        $currency = DB::table('currency')
//                ->where("currency_id", $this->system_config['currency'])
//                ->first();
        $row = DB::table('pg_detail')
                        ->leftJoin('currency as c', 'c.currency_id', '=', 'pg_detail.currency')
                        ->where('payment_name', $request->input('payment_name'))
                        ->select('pg_detail.*', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')->first();
        $data['web_config']['currency'] = $row->currency_code;
        
            $validator = Validator::make($request->all(), [
                'payment_name' => 'required',
                'TXN_AMOUNT' => 'required|numeric|min:' . $this->system_config['min_addmoney'],
                'CUST_ID' => 'required'], ['TXN_AMOUNT.required' => trans('message.err_amount_req'),
                'TXN_AMOUNT.min' => trans('message.err_amount_min', ['currency' => $row->currency_symbol, 'amount' => $this->system_config['min_addmoney']]),
                'CUST_ID.required' => trans('message.err_member_id')]);
       
        if ($validator->fails()) {
            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
        }
//        $row = DB::table('pg_detail')->where('payment_name', $request->input('payment_name'))->first();
        if ($row->payment_name == 'Instamojo') {
            if ($row->payment_status == 'Test')
                $api_url = 'https://test.instamojo.com/';
            else
                $api_url = 'https://api.instamojo.com/';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url . 'oauth2/token/');
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

            $payload = Array(
                'grant_type' => 'client_credentials',
                'client_id' => $row->mid,
                'client_secret' => $row->mkey,
            );

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            $response1 = json_decode(curl_exec($ch), true);
            curl_close($ch);
            $access_token = $response1["access_token"];

            $deposit_data = [
                'member_id' => $request->input('CUST_ID'),
                'deposit_amount' => $request->input('TXN_AMOUNT'),
                'deposit_status' => '0',
                'deposit_by' => $request->input('payment_name'),
                'entry_from' => '1',
                'deposit_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
            ];
                
            $deposit_id = DB::table('deposit')->insertGetId($deposit_data);

            $member = DB::table('member')
                    ->where('member_id', $request->input('CUST_ID'))
                    ->first();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url . 'v2/payment_requests/');
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
            $payload = array(
                'buyer_name' => $member->user_name,
                'email' => $member->email_id,
                'phone' => $member->mobile_no,
                'purpose' => 'Add to Wallet',
                'amount' => $request->input('TXN_AMOUNT') / $row->currency_point,
                'redirect_url' => $api_url . 'integrations/android/redirect/',
                'send_email' => false,
                'allow_repeated_payments' => false
            );

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            $response2 = json_decode(curl_exec($ch), true);
            curl_close($ch);
            
            if(isset($response2['id'])) {
                $payment_request_id = $response2['id'];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url . 'v2/gateway/orders/payment-request/');
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
                $payload = Array(
                    'id' => $payment_request_id
                );
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
                $response3 = json_decode(curl_exec($ch), true);
                curl_close($ch);

                $array['status'] = true;
                $array['title'] = 'Success!';
                $array['message'] = 'success';
                $array['order_id'] = $response3['order_id'];
                $array['deposit_id'] = $deposit_id;
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = 'error';
                $array['order_id'] = '0';
                $array['deposit_id'] = $deposit_id;
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
        }elseif ($row->payment_name == 'Razorpay') {
            $api = new Api($row->mid, $row->mkey);
            $deposit_data = [
                'member_id' => $request->input('CUST_ID'),
                'deposit_amount' => ($request->input('TXN_AMOUNT')) / 100,
                'deposit_status' => '0',
                'deposit_by' => $request->input('payment_name'),
                'entry_from' => '1',
                'deposit_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
            ];
            $id = DB::table('deposit')->insertGetId($deposit_data);

            $order = $api->order->create(array(
                'receipt' => $id,
                'amount' => $request->input('TXN_AMOUNT') / $row->currency_point,
                'payment_capture' => 1,
                'currency' => $row->currency_code
                    )
            );

            $array['status'] = true;
            $array['title'] = 'Success!';
            $array['message'] = 'success';
            $array['order_id'] = $order['id'];
            $array['receipt'] = $id;
            $array['currency'] = $row->currency_code;
            $array['key_id'] = $row->mid;
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        } elseif ($row->payment_name == 'Cashfree') {
            $deposit_data = [
                'member_id' => $request->input('CUST_ID'),
                'deposit_amount' => $request->input('TXN_AMOUNT'),
                'deposit_status' => '0',
                'deposit_by' => $request->input('payment_name'),
                'entry_from' => '1',
                'deposit_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
            ];
            $id = DB::table('deposit')->insertGetId($deposit_data);
            $cashfreeParams = array(
                'orderId' => $id,
                'orderAmount' => (double) $request->input('TXN_AMOUNT') / $row->currency_point,
                'orderCurrency' => $row->currency_code,
            );
            $postData = json_encode($cashfreeParams);
            $connection = curl_init();
            if ($row->payment_status == 'Production')
                $transactionURL = "https://api.cashfree.com/api/v2/cftoken/order"; // for production
            else
                $transactionURL = "https://test.cashfree.com/api/v2/cftoken/order";
            curl_setopt($connection, CURLOPT_URL, $transactionURL);
            curl_setopt($connection, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($connection, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($connection, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "x-client-id: " . $row->mid,
                "x-client-secret: " . $row->mkey
            ));
            $response = curl_exec($connection);
            curl_close($connection);
            $response = json_decode($response, true);
            $array = array();
            if ($response['status'] == 'OK') {
                $send_resp = array(
                    'order_id' => $id,
                    'cftoken' => $response['cftoken'],
                );
                $array['status'] = true;
                $array['title'] = 'success!';
                $array['message'] = $send_resp;
            } else {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = $response;
            }
            echo json_encode($array, JSON_UNESCAPED_SLASHES);
        } elseif ($row->payment_name == 'Google Pay') {
            $bank_transection_no = str_replace(".", "", microtime(true)) . rand(000, 999);
            $deposit_data = [
                'member_id' => $request->input('CUST_ID'),
                'deposit_amount' => $request->input('TXN_AMOUNT'),
                'bank_transection_no' => $bank_transection_no,
                'deposit_status' => '0',
                'deposit_by' => $request->input('payment_name'),
                'entry_from' => '1',
                'deposit_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
            ];
            $id = DB::table('deposit')->insertGetId($deposit_data);
            if ($id > 0) {
                $array['status'] = true;
                $array['title'] = 'success!';
                $array['order_id'] = $id;
                $array['transection_no'] = $bank_transection_no;
                $array['message'] = 'success';
            } else {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = 'fail';
            }
            echo json_encode($array, JSON_UNESCAPED_SLASHES);
        } elseif ($row->payment_name == 'PayU') {

            $transaction_id = str_replace(".", "", microtime(true)) . rand(000, 999);
                                                 
            $deposit_data = [
                'member_id' => $request->input('CUST_ID'),
                'deposit_amount' => $request->input('TXN_AMOUNT'),
                'deposit_status' => '0',
                'deposit_by' => $request->input('payment_name'),
                'entry_from' => '1',
                'deposit_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
            ];
            $id = DB::table('deposit')->insertGetId($deposit_data);
            if ($id > 0) {
                $array['status'] = true;
                $array['title'] = 'success!';
                $array['order_id'] = $id;                
                $array['transaction_id'] = $transaction_id;
                $array['message'] = 'success';
            } else {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = 'fail';
            }
            echo json_encode($array, JSON_UNESCAPED_SLASHES);
        } elseif ($row->payment_name == 'Tron') {                

                    $deposit_data = [
                        'member_id' => $request->input('CUST_ID'),
                        'deposit_amount' => $request->input('TXN_AMOUNT'),
                        'deposit_status' => '0',
                        'deposit_by' => $request->input('payment_name'),                
                        'entry_from' => '1',
                        'deposit_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                    ];

                    $id = DB::table('deposit')->insertGetId($deposit_data);

                    include ('./tron/vendor/autoload.php');                                            
                    
                    if($row->payment_status == 'Test') {
                        $tron_api_url = 'https://api.shasta.trongrid.io';
                    } else {
                        $tron_api_url = 'https://api.trongrid.io';
                    }
        
                    $fullNode = new \IEXBase\TronAPI\Provider\HttpProvider($tron_api_url);
                    $solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider($tron_api_url);
                    $eventServer = new \IEXBase\TronAPI\Provider\HttpProvider($tron_api_url);
        
                    try {
                        $tron = new Tron($fullNode, $solidityNode, $eventServer, null, null); 
                    } catch (\IEXBase\TronAPI\Exception\TronException $e) {                                                                                                
                        $array['status'] = false;
                        $array['title'] = 'Error!';
                        $array['message'] = $e->getMessage(); 
                        echo json_encode($array, JSON_UNESCAPED_SLASHES);  
                        exit;             
                    }

                    try {

                        $account = $tron->createAccount(); 
            
                        $data['wallet_address'] = $account->getAddress(true);
                        $data['address_hex']    = $account->getAddress();
                        $data['private_key']    = $account->getPrivateKey();
                        $data['public_key']     = $account->getPublicKey();
                        
                        $update_deposit = array(
                            'wallet_address' => $data['wallet_address'],
                            'address_hex' => $data['address_hex'],                        
                            'private_key' => $data['private_key'],
                            'public_key' => $data['public_key'],                                
                        );
                        
                        $res = DB::table('deposit')->where('deposit_id', $id)->update($update_deposit);                            
                        
                        $array['status'] = true;
                        $array['order_id'] = $id;
                        $array['wallet_address'] = $data['wallet_address'];
                        $array['title'] = 'Success!';
                        $array['message'] = trans('message.text_money_requested');
                        echo json_encode($array, JSON_UNESCAPED_SLASHES);
                        exit;
                                                        
                    } catch (\IEXBase\TronAPI\Exception\TronException $e) {                                                 
                        $array['status'] = false;
                        $array['title'] = 'Error!';
                        $array['message'] = $e->getMessage();   
                        echo json_encode($array, JSON_UNESCAPED_SLASHES);
                        exit;
                    }                  
        } else {
            $deposit_data = [
                'member_id' => $request->input('CUST_ID'),
                'deposit_amount' => $request->input('TXN_AMOUNT'),
                'deposit_status' => '0',
                'deposit_by' => $request->input('payment_name'),
                'entry_from' => '1',
                'deposit_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
            ];
            $id = DB::table('deposit')->insertGetId($deposit_data);
            if ($row->payment_name == 'Offline') {
                if ($id != 0) {
                    $array['status'] = true;
                    $array['title'] = 'Success!';
                    $array['message'] = trans('message.text_money_requested');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {
                $_POST['CUST_ID'] = $request->input('CUST_ID');
                $_POST['TXN_AMOUNT'] = (float) sprintf('%.2F', $request->input('TXN_AMOUNT') / $row->currency_point);
                $_POST['CALLBACK_URL'] = $request->input('CALLBACK_URL');
                $_POST['CHANNEL_ID'] = $request->input('CHANNEL_ID');
                $_POST['ORDER_ID'] = $id;
                $_POST['MID'] = $row->mid;
                $_POST['INDUSTRY_TYPE_ID'] = $row->itype;
                $_POST['WEBSITE'] = $row->wname;
                define('PAYTM_MERCHANT_KEY', $row->mkey);
                define('PAYTM_MERCHANT_ID', $row->mid);
                header("Pragma: no-cache");
                header("Cache-Control: no-cache");
                header("Expires: 0");
                require_once "./lib/encdec_paytm.php";
                $checkSum = "";
                $findme = 'REFUND';
                $findmepipe = '|';
                $paramList = array();
                $paramList["MID"] = '';
                $paramList["ORDER_ID"] = '';
                $paramList["CUST_ID"] = '';
                $paramList["INDUSTRY_TYPE_ID"] = '';
                $paramList["CHANNEL_ID"] = '';
                $paramList["TXN_AMOUNT"] = '';
                $paramList["WEBSITE"] = '';
                foreach ($_POST as $key => $value) {
                    $pos = strpos($value, $findme);
                    $pospipe = strpos($value, $findmepipe);
                    if ($pos === false || $pospipe === false) {
                        $paramList[$key] = $value;
                    }
                }
                $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);
                $array = array();
                $array['status'] = true;
                $array['title'] = 'success!';
                $array['message'] = array("CHECKSUMHASH" => $checkSum, "ORDER_ID" => $_POST["ORDER_ID"], "MID" => $_POST["MID"], "INDUSTRY_TYPE_ID" => $_POST["INDUSTRY_TYPE_ID"], "WEBSITE" => $_POST["WEBSITE"], "payt_STATUS" => "1");
                echo json_encode($array, JSON_UNESCAPED_SLASHES);
            }
        }
    }
    
    public function payuSuccFail(Request $request) {
        echo '<center><h3>Please Wait</h3></center>';die();
    }
    
    public function payuResponse(Request $request) {
        $validator = Validator::make($request->all(), [
                    'member_id' => 'required',
                    'amount' => 'required|numeric|min:' . $this->system_config['min_addmoney'],
                    'order_id' => 'required',
                    'status' => 'required',
        ]);
        if ($validator->fails()) {

            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;

        }
        $pg_detail = DB::table('pg_detail')->where('payment_name', $request->input('payment_name'))
                        ->leftJoin('currency as c', 'c.currency_id', '=', 'pg_detail.currency')
                        ->select('pg_detail.*', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')->first();
        $order = DB::table('deposit')
                ->where('deposit_id', $request->input('order_id'))
                ->first();
        if ($request->input('status') == "true") {
            
            if ($pg_detail->payment_status == 'Production'){
                $url = "https://info.payu.in/merchant/postservice.php?form=2";
            } else {
                $url = "https://test.payu.in/merchant/postservice.php?form=2";
            }
                    
            $hash = hash('SHA512',$pg_detail->mkey . '|verify_payment|' . $request->input('custom_transaction_id') . '|' . $pg_detail->wname);
            
            $param = ["key"=>$pg_detail->mkey,"command"=>"verify_payment","var1"=>$request->input('custom_transaction_id'),"hash" => $hash];
		
    	    $ch = curl_init ( $url );
    	    curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
    	    curl_setopt ( $ch, CURLOPT_HEADER, array(
                "accept : application/json",
                "Content-Type : application/x-www-form-urlencoded"
            ) );
    	    curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
    	    curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 120 );
    	    curl_setopt ( $ch, CURLOPT_TIMEOUT, 120 );
    	    curl_setopt ( $ch, CURLOPT_MAXREDIRS, 10 );
    	    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
    	    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
    	    curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
    	    curl_setopt ($ch, CURLOPT_HEADER, 0);
    	    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );        	    
    	    curl_setopt($ch, CURLOPT_POST, 1);
    	    curl_setopt($ch, CURLOPT_POSTFIELDS,  http_build_query($param));
    	    $checking_result = curl_exec ( $ch );    	    
    	    $errorNo = curl_errno ( $ch );
    	    $errorMsg = curl_error ( $ch );
    	    curl_close ( $ch );
    	    
    	    $checking_result = json_decode($checking_result,true); 
    	    
    	    if($checking_result['status'] == 1 && $checking_result['transaction_details'][$request->input('custom_transaction_id')]['status'] == 'success') {
    	        
                if($order->deposit_status == 0) {
                    $deposit_data = [
                        'deposit_status' => '1','bank_transection_no' => $request->input('transaction_no')];
                    $res = DB::table('deposit')->where('deposit_id', $request->input('order_id'))->update($deposit_data);
                    $row = DB::table('member')
                            ->where('member_id', $request->input('member_id'))
                            ->first();
                    $join_money = $row->join_money + ($request->input('amount'));
                    $browser = '';
                    $agent = new Agent();
                    if ($agent->isMobile()) {
                        $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                    } elseif ($agent->isDesktop()) {
                        $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                    } elseif ($agent->isRobot()) {
                        $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                    }
                    $ip = $this->getIp();
                    $acc_data = [
                        'member_id' => $request->input('member_id'),
                        'pubg_id' => $row->pubg_id,
                        'deposit' => $request->input('amount'),
                        'withdraw' => 0,
                        'join_money' => $join_money,
                        'win_money' => $row->wallet_balance,
                        'note' => 'Add Money to Join Wallet',
                        'note_id' => '0',
                        'entry_from' => '1',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                    ];
                    DB::table('accountstatement')->insertGetId($acc_data);
    
                    $upd_data = [
                        'join_money' => $join_money];
                    DB::table('member')->where('member_id', $request->input('member_id'))->update($upd_data);
    
                    $array['status'] = true;
                    $array['title'] = 'Success!';
                    $array['message'] = trans('message.text_succ_balance_added');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {
                 
                $deposit_data = [
                'deposit_status' => '2','bank_transection_no' => $request->input('transaction_no')];
                $res = DB::table('deposit')->where('deposit_id', $request->input('order_id'))->update($deposit_data);
    
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.text_err_balance_not_add');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            } 
        } else {
            
            $deposit_data = [
                'deposit_status' => '2','bank_transection_no' => $request->input('transaction_no')];
            $res = DB::table('deposit')->where('deposit_id', $request->input('order_id'))->update($deposit_data);

            $array['status'] = false;
            $array['title'] = 'Error!';
            $array['message'] = trans('message.text_err_balance_not_add');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    
    public function verifyChecksum(Request $request) {

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");

        $pg_detail = DB::table('pg_detail')
                ->first();
        define('PAYTM_MERCHANT_KEY', $pg_detail->mkey);
        define('PAYTM_MERCHANT_ID', $pg_detail->mid);
        require_once "./lib/encdec_paytm.php";

        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = FALSE;

        $paramList = $_POST;
        $return_array = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg
        $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


        $return_array["IS_CHECKSUM_VALID"] = $isValidChecksum ? "Y" : "N";
        unset($return_array["CHECKSUMHASH"]);

        $encoded_json = htmlentities(json_encode($return_array));
        echo '<html>' .
        '<head>' .
        '<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-I">' .
        '<title>Paytm</title>' .
        '<script type="text/javascript">' .
        'function response(){' .
        'return document.getElementById("response").value;' .
        '}' .
        '</script>' .
        '</head>' .
        '<body>' .
        'Redirect back to the app<br>' .
        '<form name="frm" method="post">' .
        '<input type="hidden" id="response" name="responseField" value="' . $encoded_json . '">' .
        '</form>' .
        '</body>' .
        '</html>';
    }

    public function paytmResponse(Request $request) {
        $validator = Validator::make($request->all(), [
                    'status' => 'required',
                    'order_id' => 'required',
                    'banktransectionno' => 'required',
                    'reason' => 'required',
                    'amount' => 'required',
        ]);
        if ($validator->fails()) {
            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
        }
        if ($request->input('status') == '1' || $request->input('status') == 1) {
            $data = DB::table('deposit')
                    ->where('deposit_id', $request->input('order_id'))
                    ->first();
            $pg_detail = DB::table('pg_detail')
                    ->leftJoin('currency as c', 'c.currency_id', '=', 'pg_detail.currency')
                    ->where('payment_name', 'PayTm')
                    ->select('pg_detail.*', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')
                    ->first();
            define('PAYTM_MERCHANT_KEY', $pg_detail->mkey);
            define('PAYTM_MERCHANT_ID', $pg_detail->mid);
            require_once("./lib/encdec_paytm.php");

            $orderId = $request->input('order_id');
            $merchantMid = PAYTM_MERCHANT_ID;
            $merchantKey = PAYTM_MERCHANT_KEY;
            $paytmParams["MID"] = $merchantMid;
            $paytmParams["ORDERID"] = $orderId;
            $paytmChecksum = getChecksumFromArray($paytmParams, $merchantKey);
            $paytmParams['CHECKSUMHASH'] = urlencode($paytmChecksum);
            $postData = "JsonData=" . json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
            $connection = curl_init(); // initiate curl
            if ($pg_detail->payment_status == 'Production')
                $transactionURL = "https://securegw.paytm.in/merchant-status/getTxnStatus"; // for production
            else
                $transactionURL = "https://securegw-stage.paytm.in/merchant-status/getTxnStatus";
            curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($connection, CURLOPT_URL, $transactionURL);
            curl_setopt($connection, CURLOPT_POST, true);
            curl_setopt($connection, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($connection, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $responseReader = curl_exec($connection);
            $responseData = json_decode($responseReader, true);
            if (($responseData['STATUS'] == 'TXN_SUCCESS')) {
                if ($data->deposit_status == '0' || $data->deposit_status == '2') {
                    $deposit_data = [
                        'bank_transection_no' => $request->input('banktransectionno'),
                        'deposit_status' => '1',
                        'reason' => $request->input('reason')];
                    DB::table('deposit')->where('deposit_id', $request->input('order_id'))->update($deposit_data);

                    $row = DB::table('member')
                            ->where('member_id', $data->member_id)
                            ->first();
                    $join_money = $row->join_money + $request->input('amount');
                    $browser = '';
                    $agent = new Agent();
                    if ($agent->isMobile()) {
                        $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                    } elseif ($agent->isDesktop()) {
                        $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                    } elseif ($agent->isRobot()) {
                        $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                    }
                    $ip = $this->getIp();
                    $acc_data = [
                        'member_id' => $data->member_id,
                        'pubg_id' => $row->pubg_id,
                        'deposit' => $request->input('amount'),
                        'withdraw' => 0,
                        'join_money' => $join_money,
                        'win_money' => $row->wallet_balance,
                        'note' => 'Add Money to Join Wallet',
                        'note_id' => '0',
                        'entry_from' => '1',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                    ];
                    DB::table('accountstatement')->insertGetId($acc_data);

                    $upd_data = [
                        'join_money' => $join_money];
                    DB::table('member')->where('member_id', $row->member_id)->update($upd_data);

                    $array['status'] = true;
                    $array['title'] = 'Success!';
                    $array['message'] = trans('message.text_succ_balance_added');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                } else {
                    $array['status'] = false;
                    $array['title'] = 'Error!';
                    $array['message'] = trans('message.text_err_balance_already_added');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {
                $deposit_data = [
                    'bank_transection_no' => $request->input('banktransectionno'),
                    'deposit_status' => '2',
                ];
                DB::table('deposit')->where('deposit_id', $request->input('order_id'))->update($deposit_data);

                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.text_err_balance_not_add');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
        } else {
            $deposit_data = [
                'bank_transection_no' => $request->input('banktransectionno'),
                'deposit_status' => '2',
            ];
            DB::table('deposit')->where('deposit_id', $request->input('order_id'))->update($deposit_data);

            $array['status'] = false;
            $array['title'] = 'Error!';
            $array['message'] = trans('message.text_err_balance_not_add');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function paypalResponse(Request $request) {
        $validator = Validator::make($request->all(), [
                    'member_id' => 'required',
                    'state' => 'required',
                    'id' => 'required',
                    'amount' => 'required|numeric|min:' . $this->system_config['min_addmoney'],
        ]);
        if ($validator->fails()) {
            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
        }
        $pg_detail = DB::table('pg_detail')
                ->leftJoin('currency as c', 'c.currency_id', '=', 'pg_detail.currency')
                ->where('payment_name', 'PayPal')
                ->select('pg_detail.*', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')
                ->first();
        if ($request->input('state') == 'approved') {
            $deposit_data = [
                'member_id' => $request->input('member_id'),
                'deposit_amount' => $request->input('amount'),
                'bank_transection_no' => $request->input('id'),
                'deposit_status' => '1',
                'deposit_by' => 'PayPal',
                'entry_from' => '1',
                'deposit_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
            ];
            DB::table('deposit')->insert($deposit_data);

            $row = DB::table('member')
                    ->where('member_id', $request->input('member_id'))
                    ->first();
            $join_money = $row->join_money + ($request->input('amount'));
            $browser = '';
            $agent = new Agent();
            if ($agent->isMobile()) {
                $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
            } elseif ($agent->isDesktop()) {
                $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
            } elseif ($agent->isRobot()) {
                $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
            }
            $ip = $this->getIp();
            $acc_data = [
                'member_id' => $request->input('member_id'),
                'pubg_id' => $row->pubg_id,
                'deposit' => $request->input('amount'),
                'withdraw' => 0,
                'join_money' => $join_money,
                'win_money' => $row->wallet_balance,
                'note' => 'Add Money to Join Wallet',
                'note_id' => '0',
                'entry_from' => '1',
                'ip_detail' => $ip,
                'browser' => $browser,
                'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
            ];
            DB::table('accountstatement')->insertGetId($acc_data);

            $upd_data = [
                'join_money' => $join_money];
            DB::table('member')->where('member_id', $request->input('member_id'))->update($upd_data);

            $array['status'] = true;
            $array['title'] = 'Success!';
            $array['message'] = trans('message.text_succ_balance_added');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $deposit_data = [
                'member_id' => $request->input('member_id'),
                'deposit_amount' => $request->input('amount'),
                'bank_transection_no' => $request->input('id'),
                'deposit_status' => '2',
                'entry_from' => '1',
                'deposit_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
            ];
            DB::table('deposit')->insert($deposit_data);

            $array['status'] = false;
            $array['title'] = 'Error!';
            $array['message'] = trans('message.text_err_balance_not_add');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function paystackResponse(Request $request) {
        $pg_detail = DB::table('pg_detail')->where('payment_name', 'PayStack')
                        ->leftJoin('currency as c', 'c.currency_id', '=', 'pg_detail.currency')
                        ->select('pg_detail.*', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')->first();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $request->input('reference'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $pg_detail->mid
            ),
        ));
        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);
        if ($response['status'] == true) {
            $deposit_data = [
                'member_id' => Auth::user()->member_id,
                'deposit_amount' => $request->input('amount'),
                'bank_transection_no' => $request->input('reference'),
                'deposit_status' => '1',
                'deposit_by' => 'PayStack',
                'entry_from' => '1',
                'deposit_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
            ];
            DB::table('deposit')->insert($deposit_data);

            $row = DB::table('member')
                    ->where('member_id', Auth::user()->member_id)
                    ->first();
            $join_money = $row->join_money + $request->input('amount');
            $browser = '';
            $agent = new Agent();
            if ($agent->isMobile()) {
                $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
            } elseif ($agent->isDesktop()) {
                $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
            } elseif ($agent->isRobot()) {
                $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
            }
            $ip = $this->getIp();
            $acc_data = [
                'member_id' => Auth::user()->member_id,
                'pubg_id' => $row->pubg_id,
                'deposit' => $request->input('amount'),
                'withdraw' => 0,
                'join_money' => $join_money,
                'win_money' => $row->wallet_balance,
                'note' => 'Add Money to Join Wallet',
                'note_id' => '0',
                'entry_from' => '1',
                'ip_detail' => $ip,
                'browser' => $browser,
                'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
            ];
            DB::table('accountstatement')->insertGetId($acc_data);

            $upd_data = [
                'join_money' => $join_money];
            DB::table('member')->where('member_id', Auth::user()->member_id)->update($upd_data);

            $array['status'] = true;
            $array['title'] = 'Success!';
            $array['message'] = trans('message.text_succ_balance_added');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $deposit_data = [
                'member_id' => Auth::user()->member_id,
                'deposit_amount' => $response['data']['amount'] / $pg_detail->currency_point,
                'bank_transection_no' => $request->input('reference'),
                'deposit_status' => '2',
                'deposit_by' => 'PayStack',
                'entry_from' => '1',
                'deposit_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
            ];
            DB::table('deposit')->insert($deposit_data);

            $array['status'] = false;
            $array['title'] = 'Error!';
            $array['message'] = trans('message.text_err_balance_not_add');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function instamojoResponse(Request $request) {
        $validator = Validator::make($request->all(), [
                    'member_id' => 'required',
                    'amount' => 'required|numeric|min:' . $this->system_config['min_addmoney'],
                    'status' => 'required',
                    'payment_id' => 'required',
                    'order_id' => 'required',
        ]);
        if ($validator->fails()) {
            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
        }
        if ($request->input('status') == 'Credit') {
            $pg_detail = DB::table('pg_detail')->where('payment_name', 'Instamojo')
                            ->leftJoin('currency as c', 'c.currency_id', '=', 'pg_detail.currency')
                            ->select('pg_detail.*', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')->first();
            $data = DB::table('deposit')
                    ->where('deposit_id', $request->input('order_id'))
                    ->first();
            if ($data->deposit_status == '0' || $data->deposit_status == '2') {
                $deposit_data = [
                    'bank_transection_no' => $request->input('payment_id'),
                    'deposit_status' => '1',];
                DB::table('deposit')->where('deposit_id', $request->input('order_id'))->update($deposit_data);

                $row = DB::table('member')
                        ->where('member_id', $request->input('member_id'))
                        ->first();
                $join_money = $row->join_money + ($request->input('amount'));
                $browser = '';
                $agent = new Agent();
                if ($agent->isMobile()) {
                    $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                } elseif ($agent->isDesktop()) {
                    $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                } elseif ($agent->isRobot()) {
                    $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                }
                $ip = $this->getIp();
                $acc_data = [
                    'member_id' => $request->input('member_id'),
                    'pubg_id' => $row->pubg_id,
                    'deposit' => $request->input('amount'),
                    'withdraw' => 0,
                    'join_money' => $join_money,
                    'win_money' => $row->wallet_balance,
                    'note' => 'Add Money to Join Wallet',
                    'note_id' => '0',
                    'entry_from' => '1',
                    'ip_detail' => $ip,
                    'browser' => $browser,
                    'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                ];
                DB::table('accountstatement')->insertGetId($acc_data);

                $upd_data = [
                    'join_money' => $join_money];
                DB::table('member')->where('member_id', $request->input('member_id'))->update($upd_data);

                $array['status'] = true;
                $array['title'] = 'Success!';
                $array['message'] = trans('message.text_succ_balance_added');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.text_err_balance_already_added');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
        } else {
            $deposit_data = [
                'bank_transection_no' => $request->input('payment_id'),
                'deposit_status' => '2',];
            DB::table('deposit')->where('deposit_id', $request->input('order_id'))->update($deposit_data);

            $array['status'] = false;
            $array['title'] = 'Error!';
            $array['message'] = trans('message.text_err_balance_not_add');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function razorpayResponse(Request $request) {
        $validator = Validator::make($request->all(), [
                    'member_id' => 'required',
                    'amount' => 'required|numeric|min:' . $this->system_config['min_addmoney'],
                    'receipt' => 'required',
                    'razorpay_order_id' => 'required',
                    'razorpay_payment_id' => 'required',
                    'razorpay_signature' => 'required',
                    'status' => 'required',
        ]);
        if ($validator->fails()) {
            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
        }
        $pg_detail = DB::table('pg_detail')->where('payment_name', 'Razorpay')
                        ->leftJoin('currency as c', 'c.currency_id', '=', 'pg_detail.currency')
                        ->select('pg_detail.*', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')->first();
        try {
            $api = new Api($pg_detail->mid, $pg_detail->mkey);
            $attributes = array(
                'razorpay_signature' => $request->input('razorpay_signature'),
                'razorpay_payment_id' => $request->input('razorpay_payment_id'),
                'razorpay_order_id' => $request->input('razorpay_order_id')
            );
            $order = $api->utility->verifyPaymentSignature($attributes);
            if ($request->input('status') == "true") {
                $data = DB::table('deposit')
                        ->where('deposit_id', $request->input('receipt'))
                        ->first();
                if ($data->deposit_status == '0' || $data->deposit_status == '2') {
                    $deposit_data = [
                        'bank_transection_no' => $request->input('razorpay_payment_id'),
                        'deposit_status' => '1',];
                    DB::table('deposit')->where('deposit_id', $request->input('receipt'))->update($deposit_data);

                    $row = DB::table('member')
                            ->where('member_id', $request->input('member_id'))
                            ->first();
                    $join_money = $row->join_money + ($request->input('amount'));
                    $browser = '';
                    $agent = new Agent();
                    if ($agent->isMobile()) {
                        $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                    } elseif ($agent->isDesktop()) {
                        $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                    } elseif ($agent->isRobot()) {
                        $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                    }
                    $ip = $this->getIp();
                    $acc_data = [
                        'member_id' => $request->input('member_id'),
                        'pubg_id' => $row->pubg_id,
                        'deposit' => $request->input('amount'),
                        'withdraw' => 0,
                        'join_money' => $join_money,
                        'win_money' => $row->wallet_balance,
                        'note' => 'Add Money to Join Wallet',
                        'note_id' => '0',
                        'entry_from' => '1',
                        'ip_detail' => $ip,
                        'browser' => $browser,
                        'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                    ];
                    DB::table('accountstatement')->insertGetId($acc_data);

                    $upd_data = [
                        'join_money' => $join_money];
                    DB::table('member')->where('member_id', $request->input('member_id'))->update($upd_data);

                    $array['status'] = true;
                    $array['title'] = 'Success!';
                    $array['message'] = trans('message.text_succ_balance_added');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                } else {
                    $array['status'] = false;
                    $array['title'] = 'Error!';
                    $array['message'] = trans('message.text_err_balance_already_added');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {
                $deposit_data = [
                    'bank_transection_no' => $request->input('razorpay_payment_id'),
                    'deposit_status' => '2',
                ];
                DB::table('deposit')->where('deposit_id', $request->input('receipt'))->update($deposit_data);

                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.text_err_balance_not_add');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
        } catch (Exception $e) {
            $array['status'] = false;
            $array['title'] = 'Error!';
            $array['message'] = trans('message.text_err_balance_not_add');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function cashFreeResponse(Request $request) {
        $validator = Validator::make($request->all(), [
                    'txStatus' => 'required',
                    'orderId' => 'required',
                    'referenceId' => 'required',
                    'txMsg' => 'required',
                    'orderAmount' => 'required',
        ]);
        if ($validator->fails()) {
            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
        }
        if ($request->input('txStatus') == 'SUCCESS') {
            $pg_detail = DB::table('pg_detail')->where('payment_name', 'Cashfree')
                            ->leftJoin('currency as c', 'c.currency_id', '=', 'pg_detail.currency')
                            ->select('pg_detail.*', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')->first();
            $data = DB::table('deposit')
                    ->where('deposit_id', $request->input('orderId'))
                    ->first();
            if ($data->deposit_status == '0' || $data->deposit_status == '2') {
                $deposit_data = [
                    'bank_transection_no' => $request->input('referenceId'),
                    'deposit_status' => '1',
                    'reason' => $request->input('txMsg')];
                DB::table('deposit')->where('deposit_id', $request->input('orderId'))->update($deposit_data);

                $row = DB::table('member')
                        ->where('member_id', $data->member_id)
                        ->first();
                $join_money = $row->join_money + $data->deposit_amount;
                $browser = '';
                $agent = new Agent();
                if ($agent->isMobile()) {
                    $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                } elseif ($agent->isDesktop()) {
                    $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                } elseif ($agent->isRobot()) {
                    $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                }
                $ip = $this->getIp();
                $acc_data = [
                    'member_id' => $data->member_id,
                    'pubg_id' => $row->pubg_id,
                    'deposit' => $data->deposit_amount,
                    'withdraw' => 0,
                    'join_money' => $join_money,
                    'win_money' => $row->wallet_balance,
                    'note' => 'Add Money to Join Wallet',
                    'note_id' => '0',
                    'entry_from' => '1',
                    'ip_detail' => $ip,
                    'browser' => $browser,
                    'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                ];
                DB::table('accountstatement')->insertGetId($acc_data);

                $upd_data = [
                    'join_money' => $join_money];
                DB::table('member')->where('member_id', $row->member_id)->update($upd_data);

                $array['status'] = true;
                $array['title'] = 'Success!';
                $array['message'] = trans('message.text_succ_balance_added');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.text_err_balance_already_added');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
        } else {
            $deposit_data = [
                'bank_transection_no' => $request->input('referenceId'),
                'deposit_status' => '2',
            ];
            DB::table('deposit')->where('deposit_id', $request->input('orderId'))->update($deposit_data);

            $array['status'] = false;
            $array['title'] = 'Error!';
            $array['message'] = 'Transaction failed';
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function googlePayResponse(Request $request) {
        $validator = Validator::make($request->all(), [
                    'member_id' => 'required',
                    'amount' => 'required|numeric|min:' . $this->system_config['min_addmoney'],
                    'transaction_id' => 'required',
                    'order_id' => 'required',
                    'status' => 'required',
        ]);
        if ($validator->fails()) {
            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
        }
        $pg_detail = DB::table('pg_detail')->where('payment_name', 'Cashfree')
                        ->leftJoin('currency as c', 'c.currency_id', '=', 'pg_detail.currency')
                        ->select('pg_detail.*', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')->first();
        $order = DB::table('deposit')
                ->where('bank_transection_no', $request->input('transaction_id'))
                ->where('deposit_id', $request->input('order_id'))
                ->first();
        if ($request->input('status') == "true" && $order->deposit_status == 0) {
            $deposit_data = [
                'deposit_status' => '1',];
            $res = DB::table('deposit')->where('bank_transection_no', $request->input('transection_no'))->where('deposit_id', $request->input('order_id'))->update($deposit_data);
            $row = DB::table('member')
                    ->where('member_id', $request->input('member_id'))
                    ->first();
            $join_money = $row->join_money + ($request->input('amount'));
            $browser = '';
            $agent = new Agent();
            if ($agent->isMobile()) {
                $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
            } elseif ($agent->isDesktop()) {
                $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
            } elseif ($agent->isRobot()) {
                $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
            }
            $ip = $this->getIp();
            $acc_data = [
                'member_id' => $request->input('member_id'),
                'pubg_id' => $row->pubg_id,
                'deposit' => $request->input('amount'),
                'withdraw' => 0,
                'join_money' => $join_money,
                'win_money' => $row->wallet_balance,
                'note' => 'Add Money to Join Wallet',
                'note_id' => '0',
                'entry_from' => '1',
                'ip_detail' => $ip,
                'browser' => $browser,
                'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
            ];
            DB::table('accountstatement')->insertGetId($acc_data);

            $upd_data = [
                'join_money' => $join_money];
            DB::table('member')->where('member_id', $request->input('member_id'))->update($upd_data);

            $array['status'] = true;
            $array['title'] = 'Success!';
            $array['message'] = trans('message.text_succ_balance_added');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $array['status'] = false;
            $array['title'] = 'Error!';
            $array['message'] = trans('message.text_err_balance_not_add');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function notificationList($game_id) {
        $member_id = Auth::user()->member_id;
        
        $data['notifications'] = DB::table('notifications')
                ->where("member_id", $member_id)
				->where("game_id", $game_id)
                ->orderBy('date_created', 'DESC')
                ->get();
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    public function ludoLeaderBoard($game_id) {
        $member_id = Auth::user()->member_id;
        
        $data['list'] = DB::table('ludo_challenge as l')
                ->select('m.member_id','m.first_name','m.last_name', DB::raw('(CASE 
                WHEN profile_image = "" THEN ""
                ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/profile_image/thumb/100x100_", profile_image) 
                END) AS profile_image'),DB::raw('SUM(winning_price) as total_amount'),DB::raw('count(ludo_challenge_id) as total_challenge'))
                ->leftJoin('member as m', 'm.member_id', '=', 'l.winner_id')
                ->where('challenge_status','3')
				->where('game_id',$game_id)
                ->groupBy('winner_id')
                ->orderBy('total_amount', 'DESC')
                ->get();
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
	
	public function budyList($game_id) {
        $member_id = Auth::user()->member_id;
        
        $member_data = DB::table('member')
                ->where("member_id", $member_id)
                ->select("*")
                ->first();
        
        if($member_data->budy_list == '' || $member_data->budy_list == null) {
            $data['member_list'] = array();
        } else {
            $budy_list = unserialize($member_data->budy_list);
            			
            $data['member_list'] = DB::table('member')
                                    ->whereIn("member_id", $budy_list[$game_id])
                                    ->select("member_id","first_name","last_name","ludo_username",DB::raw('(CASE 
                                        WHEN profile_image = "" THEN ""
                                        ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/profile_image/thumb/100x100_", profile_image) 
                                        END) AS profile_image'))
                                    ->get();
        }
        
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
	
	public function budyPlayRequest($to_member_id,$game_id) {
        
        $member_id = Auth::user()->member_id;
        
        $auth_mem_data = DB::table('member')
                ->where("member_id", $member_id)
                ->select("*")
                ->first();
                
        $mem_data = DB::table('member')
                ->where("member_id", $to_member_id)
                ->select("*")
                ->first();
		
		$game_data = DB::table('game')
                ->where("game_id", $game_id)
                ->select("*")
                ->first();
        
        $heading_msg = 'Request to Play';
        $content_msg = $auth_mem_data->first_name . " " . $auth_mem_data->last_name ." Send Play Request to You for Play" . $game_data->game_name . ".";
        
        if($mem_data->push_noti == 1 || $mem_data->push_noti == '1'){
            if($this->send_onesignal_noti($heading_msg,$content_msg,$mem_data->player_id,$mem_data->member_id,$game_id)){                            
                $array['status'] = true;
                $array['title'] = 'Success!';
                $array['message'] = trans('message.succ_budy_request');
                echo json_encode($array,JSON_UNESCAPED_SLASHES);
                exit;
            } else {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.err_budy_request');
                echo json_encode($array,JSON_UNESCAPED_SLASHES);
                exit;
            }
        } else {
            $array['status'] = false;
            $array['title'] = 'Error!';
            $array['message'] = trans('message.err_budy_request');
            echo json_encode($array,JSON_UNESCAPED_SLASHES);
            exit;
        }
                                                                            
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
	
	public function liveChallengeList($game_id) {
        $member_id = Auth::user()->member_id;
        
        $data['challenge_list'] = DB::table('ludo_challenge as l')
                ->leftJoin('member as m', 'm.member_id', '=', 'l.member_id')
                ->leftjoin("challenge_result_upload as cr1",function($join){
                    $join->on('cr1.member_id', '=', 'l.member_id')
                        ->on('cr1.ludo_challenge_id', '=', 'l.ludo_challenge_id');
                })
                ->leftjoin("challenge_result_upload as cr2",function($join1){
                    $join1->on('cr2.member_id', '=', 'l.accepted_member_id')
                        ->on('cr2.ludo_challenge_id', '=', 'l.ludo_challenge_id');
                })
                ->select('l.*','m.first_name','m.last_name', DB::raw('(CASE 
                WHEN profile_image = "" THEN ""
                ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/profile_image/thumb/100x100_", profile_image) 
                END) AS profile_image'),\DB::raw("(SELECT CONCAT(first_name,' ',last_name) FROM member
                          WHERE member.member_id = l.accepted_member_id
                        ) as accepted_member_name"),
                        \DB::raw("(SELECT ". DB::raw('(CASE 
                        WHEN profile_image = "" THEN ""
                        ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/profile_image/thumb/100x100_", profile_image) 
                        END) AS profile_image') ." FROM member
                          WHERE member.member_id = l.accepted_member_id
                        ) as accepted_profile_image"),'cr1.result_status as added_result','cr2.result_status as accepted_result','m.player_id',\DB::raw("(SELECT player_id FROM member
                          WHERE member.member_id = l.accepted_member_id
                        ) as accepted_player_id")
                        )
                ->where('challenge_status','1')
                ->where('accept_status','0')
				->where('game_id',$game_id)
                ->where("l.member_id","!=", $member_id)
                ->Where("l.accepted_member_id","!=", $member_id)
                ->orderBy('date_created', 'DESC')
                ->get();
        
        $member = DB::table('member')
                ->where("member_id", $member_id)
                ->select("ludo_username")
                ->first();
        
        if($member->ludo_username != ''){
            $ludo_username = unserialize($member->ludo_username);
        } else {
            $ludo_username = $member->ludo_username;
        }        
		
        $data['ludo_game_name'] = '';
        if (is_array($ludo_username) && array_key_exists($game_id, $ludo_username)) {
            $data['ludo_game_name'] = $ludo_username[$game_id];
        }
		
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    public function myChallengeList($game_id) {
        $member_id = Auth::user()->member_id;
        
        $where_in = ['1','4'];
        
        $data['challenge_list'] = DB::table('ludo_challenge as l')
                ->leftJoin('member as m', 'm.member_id', '=', 'l.member_id')
                ->leftjoin("challenge_result_upload as cr1",function($join){
                    $join->on('cr1.member_id', '=', 'l.member_id')
                        ->on('cr1.ludo_challenge_id', '=', 'l.ludo_challenge_id');
                })
                ->leftjoin("challenge_result_upload as cr2",function($join1){
                    $join1->on('cr2.member_id', '=', 'l.accepted_member_id')
                        ->on('cr2.ludo_challenge_id', '=', 'l.ludo_challenge_id');
                })
                ->select('l.*','m.first_name','m.last_name', DB::raw('(CASE 
                WHEN profile_image = "" THEN ""
                ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/profile_image/thumb/100x100_", profile_image) 
                END) AS profile_image'),\DB::raw("(SELECT CONCAT(first_name,' ',last_name) FROM member
                          WHERE member.member_id = l.accepted_member_id
                        ) as accepted_member_name"),
                        \DB::raw("(SELECT ". DB::raw('(CASE 
                        WHEN profile_image = "" THEN ""
                        ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/profile_image/thumb/100x100_", profile_image) 
                        END) AS profile_image') ." FROM member
                          WHERE member.member_id = l.accepted_member_id
                        ) as accepted_profile_image"),'cr1.result_status as added_result','cr2.result_status as accepted_result','m.player_id',\DB::raw("(SELECT player_id FROM member
                          WHERE member.member_id = l.accepted_member_id
                        ) as accepted_player_id")
                        )
                ->whereIn('challenge_status',$where_in)
                ->Where(function ($query) use ($member_id){
                    $query->where("l.member_id", $member_id)
                      ->orWhere("l.accepted_member_id",$member_id);
                })
				->where('game_id',$game_id)
                ->orderBy('date_created', 'DESC')
                ->get();
        
        $member = DB::table('member')
                ->where("member_id", $member_id)
                ->select("ludo_username")
                ->first();
        
        if($member->ludo_username != ''){
            $ludo_username = unserialize($member->ludo_username);
        } else {
            $ludo_username = $member->ludo_username;
        }		
		
        $data['ludo_game_name'] = '';
        if (is_array($ludo_username) && array_key_exists($game_id, $ludo_username)) {
            $data['ludo_game_name'] = $ludo_username[$game_id];
        }
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
	
	public function challengeResultList($game_id) {
        $member_id = Auth::user()->member_id;
        
        $where_in = ['2','3'];
        $data['challenge_list'] = DB::table('ludo_challenge as l')
                ->leftJoin('member as m', 'm.member_id', '=', 'l.member_id')
                ->leftjoin("challenge_result_upload as cr1",function($join){
                    $join->on('cr1.member_id', '=', 'l.member_id')
                        ->on('cr1.ludo_challenge_id', '=', 'l.ludo_challenge_id');
                })
                ->leftjoin("challenge_result_upload as cr2",function($join1){
                    $join1->on('cr2.member_id', '=', 'l.accepted_member_id')
                        ->on('cr2.ludo_challenge_id', '=', 'l.ludo_challenge_id');
                })
                ->select('l.*','m.first_name','m.last_name', DB::raw('(CASE 
                WHEN profile_image = "" THEN ""
                ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/profile_image/thumb/100x100_", profile_image) 
                END) AS profile_image'),\DB::raw("(SELECT CONCAT(first_name,' ',last_name) FROM member
                          WHERE member.member_id = l.accepted_member_id
                        ) as accepted_member_name"),
                        \DB::raw("(SELECT ". DB::raw('(CASE 
                        WHEN profile_image = "" THEN ""
                        ELSE CONCAT ("' . $this->base_url . '/' . $this->system_config['admin_photo'] . '/profile_image/thumb/100x100_", profile_image) 
                        END) AS profile_image') ." FROM member
                          WHERE member.member_id = l.accepted_member_id
                        ) as accepted_profile_image"),'cr1.result_status as added_result','cr2.result_status as accepted_result','m.player_id',\DB::raw("(SELECT player_id FROM member
                          WHERE member.member_id = l.accepted_member_id
                        ) as accepted_player_id")
                        )
                ->whereIn('challenge_status',$where_in)
                ->Where(function ($query) use ($member_id){
                    $query->where("l.member_id", $member_id)
                      ->orWhere("l.accepted_member_id",$member_id);
                })
				->where('game_id',$game_id)
                ->orderBy('date_created', 'DESC')
                ->get();
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
	
	function generate_ludo_auto_id($ludo_pre) {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $r_str = '';
        for ($i = 0; $i < 8; $i++) {
            $r_str .= substr($chars, rand(0, strlen($chars)), 1);
        }
        $new_user_name = $ludo_pre . $r_str;
        $data = DB::table('ludo_challenge')
                ->where('auto_id', $new_user_name)
                ->first();
        if ($data) {
            $this->generate_ludo_auto_id($ludo_pre);
        } else {
            return $new_user_name;
        }
    }
    
    public function addChallenge(Request $request) {       
        
        if (($request->input('submit')) && $request->input('submit') == 'addChallenge') {

            $validator = Validator::make($request->all(), [
                        'member_id' => 'required',
                        'game_id' => 'required',
                        'ludo_king_username' => 'required',
                        'with_password' => 'required',
                        'coin' => 'required|numeric|min:10|max:100000',
                            ], [
                        'member_id.required' => trans('message.err_member_id'),
                        'game_id.required' => trans('message.err_game_id'),
                        'ludo_king_username.required' => trans('message.err_ludo_username'),
                        'with_password.required' => trans('message.err_with_password'),
                        'coin.required' => trans('message.err_coin_req'),
                        'coin.min' => trans('message.err_coin_min'),
                        'coin.max' => trans('message.err_coin_max'),
            ]);
            if ($validator->fails()) {
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }

            
            if ($request->input('coin') % 10 != 0) {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.err_coin_multiply');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            if ($request->input('with_password') && $request->input('challenge_password') == '') {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.err_password_req');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }

           $member_data = DB::table('member')
                    ->where('member_id', $request->input('member_id'))
                    ->first();
                    
            if ($member_data->wallet_balance + $member_data->join_money >= $request->input('coin')) {
                
                if($request->input('coin') > 100) {
                    $winning_price = ($request->input('coin') * 2) - ((($request->input('coin') * 2) * $this->system_config['coin_up_to_hundrade']) / 100);
                } else {
                    $winning_price = ($request->input('coin') * 2) - ((($request->input('coin') * 2) * $this->system_config['coin_under_hundrade']) / 100);
                }

                // $auto_id = $this->generate_ludo_auto_id('LGTC_');
                
                    $ludo_challenge_data = [
                                            'member_id' => $request->input('member_id'),
                                            'ludo_king_username' => $request->input('ludo_king_username'),
                                            'with_password' => $request->input('with_password'),
                                            'challenge_password' => $request->input('challenge_password'),
                                            'coin' => $request->input('coin'),
                                            'winning_price' => $winning_price,
                                            'game_id' => $request->input('game_id'),                                            
                                            // 'auto_id' => $auto_id
                                            'date_created' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                                        ];
                    
                    $id =DB::table('ludo_challenge')->insertGetId($ludo_challenge_data);
                    
					$game_data = DB::table('game')
                    ->where('game_id', $request->input('game_id'))
                    ->first();
				
                    $auto_id = $game_data->id_prefix . '_' . $id;
                    
                    $ludo_challenge_update_data = [
                                            'auto_id' => $auto_id
                                        ];
                                        
                    DB::table('ludo_challenge')->where('ludo_challenge_id', $id)->update($ludo_challenge_update_data);
                                        
                        if ($member_data->join_money > $request->input('coin')) {
                            $join_money = $member_data->join_money - $request->input('coin');
                            $wallet_balance = $member_data->wallet_balance;
                        } elseif ($member_data->join_money < $request->input('coin')) {
                            $join_money = 0;
                            $amount1 = $request->input('coin') - $member_data->join_money;
                            $wallet_balance = $member_data->wallet_balance - $amount1;
                        } elseif ($member_data->join_money == $request->input('coin')) {
                            $join_money = 0;
                            $wallet_balance = $member_data->wallet_balance;
                        }
                        
                        if($member_data->ludo_username != ''){
                            $ludo_username = unserialize($member_data->ludo_username);
                        } else {
                            $ludo_username = $member_data->ludo_username;
                        }

                            if (is_array($ludo_username)) {
                                if (array_key_exists($request->input('game_id'), $ludo_username)) {
                                    $ludo_username[$request->input('game_id')] = $request->input('ludo_king_username');
                                } else {
                                    $ludo_username[$request->input('game_id')] = $request->input('ludo_king_username');
                                }

                                $ludo_username = serialize($ludo_username);                                
                            } else {
                                $ludo_username = array(
                                    $request->input('game_id') => $request->input('ludo_king_username'),
                                );

                                $ludo_username = serialize($ludo_username);                                
                            }

                        $member_update_data = [
                            'ludo_username' => $ludo_username,
                            'join_money' => $join_money,
                            'wallet_balance' => $wallet_balance,
                        ];
                        
                        DB::table('member')->where('member_id', $request->input('member_id'))->update($member_update_data);
                        
                        $browser = '';
                        $agent = new Agent();
                        if ($agent->isMobile()) {
                            $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                        } elseif ($agent->isDesktop()) {
                            $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                        } elseif ($agent->isRobot()) {
                            $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                        }
                        
                        $ip = $this->getIp();
                        $acc_data = [
                            'member_id' => $request->input('member_id'),
                            'pubg_id' => $member_data->pubg_id,
                            'deposit' => 0,
                            'withdraw' => $request->input('coin'),
                            'join_money' => $join_money,
                            'win_money' => $wallet_balance,
                            'note' => 'Add '. $game_data->game_name .' Challenge #' . $id,
                            'note_id' => '14',
                            'entry_from' => '1',
                            'ip_detail' => $ip,
                            'browser' => $browser,
                            'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                        ];
                        DB::table('accountstatement')->insert($acc_data);                                                

                        $follower = json_decode($game_data->follower,true);
                        
                        $follower_data = DB::table('member')
                                        ->select('member_id','player_id')
                                        ->where('member_status', '1')
                                        ->where('push_noti', '1')
                                        ->where('player_id','!=', '')
                                        ->whereIn('member_id', $follower)
                                        ->where('member_id','!=', $request->input('member_id'))
                                        ->get();

                        $player_ids = array();
                        $member_ids = array();

                        foreach($follower_data as $mem){                                            
                            array_push($player_ids,$mem->player_id);                                
                            array_push($member_ids,$mem->member_id);                            
                        }

                        if(!empty($player_ids)){

                            $heading_msg = 'New Challenge Available';
                            $content_msg = 'New Challenge "' . $auto_id . '" available in '. $game_data->game_name . '. If you interested then accept the challenge.';                                                                            
                                                
                            $this->send_onesignal_noti($heading_msg,$content_msg,$player_ids,$member_ids,$request->input('game_id'),true);
                        }

                        $array['status'] = true;
                        $array['title'] = 'Success!';
                        $array['message'] = trans('message.succ_challenge_added');
                        echo json_encode($array,JSON_UNESCAPED_UNICODE);
                        exit;
            } else {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.err_balance_low');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }
    
    public function acceptChallenge(Request $request) {
        
        if (($request->input('submit')) && $request->input('submit') == 'acceptChallenge') {

            $validator = Validator::make($request->all(), [
                        'ludo_challenge_id' => 'required',
                        'accepted_member_id' => 'required',
                        'ludo_king_username' => 'required',
                        'coin' => 'required|numeric|min:10|max:10000',
                            ], [
                        'ludo_challenge_id.required' => trans('message.err_ludo_challenge_id'),
                        'accepted_member_id.required' => trans('message.err_member_id'),
                        'ludo_king_username.required' => trans('message.err_ludo_username'),
                        'coin.required' => trans('message.err_coin_req'),
                        'coin.min' => trans('message.err_coin_min'),
                        'coin.max' => trans('message.err_coin_max'),
            ]);
            if ($validator->fails()) {
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }

            
            if ($request->input('coin') % 10 != 0) {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.err_coin_multiply');
                echo json_encode($array,JSON_UNESCAPED_SLASHES);
                exit;
            }
           
           $ludo_challenge_data = DB::table('ludo_challenge')
                    ->where('ludo_challenge_id', $request->input('ludo_challenge_id'))
                    ->first();
            
            if($ludo_challenge_data->accept_status == '1') {
                return response()->json(['status' => false, 'title' => 'Error!', 'message' => 'Match Already Accepted !']);
                exit;
            }

            if ($ludo_challenge_data->with_password) {
                if($request->input('challenge_password') == ''){
                    $array['status'] = false;
                    $array['title'] = 'Error!';
                    $array['message'] = trans('message.err_password_req');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                } else {
                    if($request->input('challenge_password') != $ludo_challenge_data->challenge_password) {
                        $array['status'] = false;
                        $array['title'] = 'Error!';
                        $array['message'] = trans('message.text_err_pass_incorrect');
                        echo json_encode($array,JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                }
            }
            
           $member_data = DB::table('member')
                    ->where('member_id', $request->input('accepted_member_id'))
                    ->first();
                  
            if ($member_data->wallet_balance + $member_data->join_money >= $request->input('coin')) {
                
                    $update_ludo_challenge_data = [
                                            'accepted_member_id' => $request->input('accepted_member_id'),
                                            'accepted_ludo_king_username' => $request->input('ludo_king_username'),
                                            'accept_status' => '1',                                            
                                            'accepted_date' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s'),
                                        ];
                                        
                    DB::table('ludo_challenge')->where('ludo_challenge_id', $request->input('ludo_challenge_id'))->update($update_ludo_challenge_data);

                        if ($member_data->join_money > $request->input('coin')) {
                            $join_money = $member_data->join_money - $request->input('coin');
                            $wallet_balance = $member_data->wallet_balance;
                        } elseif ($member_data->join_money < $request->input('coin')) {
                            $join_money = 0;
                            $amount1 = $request->input('coin') - $member_data->join_money;
                            $wallet_balance = $member_data->wallet_balance - $amount1;
                        } elseif ($member_data->join_money == $request->input('coin')) {
                            $join_money = 0;
                            $wallet_balance = $member_data->wallet_balance;
                        }
                        
                        if($member_data->ludo_username != ''){
                            $ludo_username = unserialize($member_data->ludo_username);
                        } else {
                            $ludo_username = $member_data->ludo_username;
                        }

                            if (is_array($ludo_username)) {
                                if (array_key_exists($ludo_challenge_data->game_id, $ludo_username)) {
                                    $ludo_username[$ludo_challenge_data->game_id] = $request->input('ludo_king_username');
                                } else {
                                    $ludo_username[$ludo_challenge_data->game_id] = $request->input('ludo_king_username');
                                }
                                
                                $ludo_username = serialize($ludo_username);                                
                            } else {
                                $ludo_username = array(
                                    $ludo_challenge_data->game_id => $request->input('ludo_king_username'),
                                );

                                $ludo_username = serialize($ludo_username);                                
                            }

                        $member_update_data = [
                            'ludo_username' => $ludo_username,
                            'join_money' => $join_money,
                            'wallet_balance' => $wallet_balance,
                        ];                        
                        
                        DB::table('member')->where('member_id', $request->input('accepted_member_id'))->update($member_update_data);
                        
                        $browser = '';
                        $agent = new Agent();
                        if ($agent->isMobile()) {
                            $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                        } elseif ($agent->isDesktop()) {
                            $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                        } elseif ($agent->isRobot()) {
                            $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                        }
                        
                        $game_data = DB::table('game')
                        ->where('game_id', $ludo_challenge_data->game_id)
                        ->first();

                        $ip = $this->getIp();
                        $acc_data = [
                            'member_id' => $request->input('accepted_member_id'),
                            'pubg_id' => $member_data->pubg_id,
                            'deposit' => 0,
                            'withdraw' => $request->input('coin'),
                            'join_money' => $join_money,
                            'win_money' => $wallet_balance,
                            'note' => 'Accept '. $game_data->game_name .' Challenge #' . $request->input('ludo_challenge_id'),
                            'note_id' => '15',
                            'entry_from' => '1',
                            'ip_detail' => $ip,
                            'browser' => $browser,
                            'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s'),
                        ];
                        DB::table('accountstatement')->insert($acc_data);
            
                       $not_member_data = DB::table('member')
                                ->where('member_id', $ludo_challenge_data->member_id)
                                ->first();
                        
                        if($not_member_data->budy_list != ''){
                            $budy_list = unserialize($not_member_data->budy_list);
                        } else {
                            $budy_list = $not_member_data->budy_list;
                        }						

                            if (is_array($budy_list)) {
                                if (array_key_exists($ludo_challenge_data->game_id, $budy_list)) {

                                    if (!in_array($member_data->member_id, $budy_list[$ludo_challenge_data->game_id])) {
                                        array_push($budy_list[$ludo_challenge_data->game_id], $member_data->member_id);                                                                                
                                    }
                                    
                                } else {
                                    $budy_list[$ludo_challenge_data->game_id] = array($member_data->member_id);
                                }
                                
                                $budy_list = serialize($budy_list);                                
                            } else {
                                $budy_list = array(
                                    $ludo_challenge_data->game_id => array($member_data->member_id),
                                );

                                $budy_list = serialize($budy_list);                                
                            }
				
                        
                            DB::table('member')->where('member_id', $not_member_data->member_id)->update(array(
                                'budy_list' => $budy_list
                            ));
                       
                        // update accepted user budy list

                        if($member_data->budy_list != ''){
                            $accepted_budy_list = unserialize($member_data->budy_list);
                        } else {
                            $accepted_budy_list = $member_data->budy_list;
                        }
                        

                            if (is_array($accepted_budy_list)) {
                                if (array_key_exists($ludo_challenge_data->game_id, $accepted_budy_list)) {

                                    if (!in_array($not_member_data->member_id, $accepted_budy_list[$ludo_challenge_data->game_id])) {
                                        array_push($accepted_budy_list[$ludo_challenge_data->game_id], $not_member_data->member_id);                                                                                
                                    }
                                    
                                } else {
                                    $accepted_budy_list[$ludo_challenge_data->game_id] = array($not_member_data->member_id);
                                }
                                
                                $accepted_budy_list = serialize($accepted_budy_list);                                
                            } else {
                                $accepted_budy_list = array(
                                    $ludo_challenge_data->game_id => array($not_member_data->member_id),
                                );

                                $accepted_budy_list = serialize($accepted_budy_list);                                
                            }
                        
                       
                            DB::table('member')->where('member_id', $member_data->member_id)->update(array(
                                'budy_list' => $accepted_budy_list
                            ));
                       
        
                        $heading_msg = 'Challenge Accepted';
                        $content_msg = $request->input('ludo_king_username') ." has accepted your ". $ludo_challenge_data->auto_id ." Challenge.";
                        
                        if($not_member_data->push_noti == '1' || $not_member_data->push_noti == 1) {
                            $this->send_onesignal_noti($heading_msg,$content_msg,$not_member_data->player_id,$not_member_data->member_id,$ludo_challenge_data->game_id);
                        }

                        $array['status'] = true;
                        $array['title'] = 'Success!';
                        $array['message'] = trans('message.succ_challenge_accepted');
                        echo json_encode($array,JSON_UNESCAPED_SLASHES);
                        exit;
            } else {
                $array['status'] = false;
                $array['title'] = 'Error!';
                $array['message'] = trans('message.err_balance_low');
                echo json_encode($array,JSON_UNESCAPED_SLASHES);
                exit;
            }
        }
    }
    
    public function cancelChallenge(Request $request) {
        if (($request->input('submit')) && $request->input('submit') == 'cancelChallenge') {

            $validator = Validator::make($request->all(), [
                        'ludo_challenge_id' => 'required',
                        'member_id' => 'required',
                            ], [
                        'ludo_challenge_id.required' => trans('message.err_ludo_challenge_id'),
                        'member_id.required' => trans('message.err_member_id'),
                    ]);
                    
            if ($validator->fails()) {
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }
           
           $ludo_challenge_data = DB::table('ludo_challenge')
                    ->where('ludo_challenge_id', $request->input('ludo_challenge_id'))
                    ->first();
            
            $game_data = DB::table('game')
                        ->where('game_id', $ludo_challenge_data->game_id)
                        ->first();

            if($ludo_challenge_data->challenge_status == '2') {                
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = trans('message.err_already_cancel');echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }   
            
                    $member_data = DB::table('member')
                    ->where('member_id', $request->input('member_id'))
                    ->first();
            
                    $ludo_challenge_update_data = [
                                            'challenge_status' => '2',
                                            'canceled_by' => $request->input('member_id'),
                                        ];
                                        
                    DB::table('ludo_challenge')->where('ludo_challenge_id', $request->input('ludo_challenge_id'))->update($ludo_challenge_update_data);

                        $join_money = $member_data->join_money + $ludo_challenge_data->coin;
                        
                        $member_update_data = [
                            'join_money' => $join_money,
                        ];
                        
                        DB::table('member')->where('member_id', $request->input('member_id'))->update($member_update_data);
                        
                        $browser = '';
                        $agent = new Agent();
                        if ($agent->isMobile()) {
                            $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                        } elseif ($agent->isDesktop()) {
                            $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                        } elseif ($agent->isRobot()) {
                            $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                        }
                        
                        $ip = $this->getIp();
                        $acc_data = [
                            'member_id' => $request->input('member_id'),
                            'pubg_id' => $member_data->pubg_id,
                            'deposit' => $ludo_challenge_data->coin,
                            'withdraw' => 0,
                            'join_money' => $join_money,
                            'win_money' => $member_data->wallet_balance,
                            'note' => 'Cancel '. $game_data->game_name.' Challenge #' . $request->input('ludo_challenge_id'),
                            'note_id' => '16',
                            'entry_from' => '1',
                            'ip_detail' => $ip,
                            'browser' => $browser,
                            'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s'),
                        ];
                        DB::table('accountstatement')->insert($acc_data);
                            
                        if($ludo_challenge_data->accept_status == 1 && $request->input('canceled_by_flag') == 0){
                            
                            $other_member_data = DB::table('member')
                            ->where('member_id', $ludo_challenge_data->accepted_member_id)
                            ->first();
                            
                            $other_join_money = $other_member_data->join_money + $ludo_challenge_data->coin;
                                
                                $accepted_member_update_data = [
                                    'join_money' => $other_join_money,
                                ];
                                
                                DB::table('member')->where('member_id', $ludo_challenge_data->accepted_member_id)->update($accepted_member_update_data);
                                
                                $browser = '';
                                $agent = new Agent();
                                if ($agent->isMobile()) {
                                    $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                                } elseif ($agent->isDesktop()) {
                                    $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                                } elseif ($agent->isRobot()) {
                                    $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                                }
                                
                                $ip = $this->getIp();
                                $accepted_acc_data = [
                                    'member_id' => $ludo_challenge_data->accepted_member_id,
                                    'pubg_id' => $other_member_data->pubg_id,
                                    'deposit' => $ludo_challenge_data->coin,
                                    'withdraw' => 0,
                                    'join_money' => $other_join_money,
                                    'win_money' => $other_member_data->wallet_balance,
                                    'note' => 'Cancel '. $game_data->game_name.' Challenge #' . $request->input('ludo_challenge_id'),
                                    'note_id' => '16',
                                    'entry_from' => '1',
                                    'ip_detail' => $ip,
                                    'browser' => $browser,
                                    'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s'),
                                ];
                                DB::table('accountstatement')->insert($accepted_acc_data);
                                
                        }
                        
                        if($request->input('canceled_by_flag') == 1){
                            
                            $other_member_data = DB::table('member')
                            ->where('member_id', $ludo_challenge_data->member_id)
                            ->first();
                            
                            $other_join_money = $other_member_data->join_money + $ludo_challenge_data->coin;
                                
                                $accepted_member_update_data = [
                                    'join_money' => $other_join_money,
                                ];
                                
                                DB::table('member')->where('member_id', $ludo_challenge_data->member_id)->update($accepted_member_update_data);
                                
                                $browser = '';
                                $agent = new Agent();
                                if ($agent->isMobile()) {
                                    $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                                } elseif ($agent->isDesktop()) {
                                    $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                                } elseif ($agent->isRobot()) {
                                    $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                                }
                                
                                $ip = $this->getIp();
                                $accepted_acc_data = [
                                    'member_id' => $ludo_challenge_data->member_id,
                                    'pubg_id' => $other_member_data->pubg_id,
                                    'deposit' => $ludo_challenge_data->coin,
                                    'withdraw' => 0,
                                    'join_money' => $other_join_money,
                                    'win_money' => $other_member_data->wallet_balance,
                                    'note' => 'Cancel '. $game_data->game_name.' Challenge #' . $request->input('ludo_challenge_id'),
                                    'note_id' => '16',
                                    'entry_from' => '1',
                                    'ip_detail' => $ip,
                                    'browser' => $browser,
                                    'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s'),
                                ];
                                DB::table('accountstatement')->insert($accepted_acc_data);
                                
                        }
                        
                        if(isset($other_member_data)) {
                            
                            $heading_msg = 'Contest Canceled';
                            $content_msg = $ludo_challenge_data->auto_id ." is canceled by " . $member_data->first_name . " ". $member_data->last_name .".";                                                                                     
                            
                            if($other_member_data->push_noti == '1' || $other_member_data->push_noti == 1) {
                                $this->send_onesignal_noti($heading_msg,$content_msg,$other_member_data->player_id,$other_member_data->member_id,$ludo_challenge_data->game_id);
                            }

                            $array['status'] = true;
                            $array['title'] = 'Success!';
                            $array['message'] = trans('message.succ_cancel_challenge');
                            echo json_encode($array,JSON_UNESCAPED_UNICODE);
                            exit;
                        
                        } else {
                            $array['status'] = true;
                            $array['title'] = 'Success!';
                            $array['message'] = trans('message.succ_cancel_challenge');
                            echo json_encode($array,JSON_UNESCAPED_UNICODE);
                            exit;
                        }
        }
    }

    public function updataChallengeRoom(Request $request) {
        if (($request->input('submit')) && $request->input('submit') == 'updateRoom') {

            $validator = Validator::make($request->all(), [
                        'ludo_challenge_id' => 'required',
                        'room_code' => 'required',
                            ], [
                        'ludo_challenge_id.required' => trans('message.err_ludo_challenge_id'),
                        'room_code.required' => trans('message.err_room_code'),
                    ]);
            if ($validator->fails()) {
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }
            
            $is_result_uploaded = DB::table('challenge_result_upload')
                    ->where('ludo_challenge_id', $request->input('ludo_challenge_id'))
                    ->first();
            
            if($is_result_uploaded != ''){                
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = trans('message.err_cant_update_room_code');echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }
            
            $ludo_challenge_data = DB::table('ludo_challenge')
                    ->where('ludo_challenge_id', $request->input('ludo_challenge_id'))
                    ->first();
            
           $member_data = DB::table('member')
                    ->where('member_id', $ludo_challenge_data->accepted_member_id)
                    ->first();
                    
                
                    $update_ludo_challenge_data = [
                                            'room_code' => $request->input('room_code'),
                                        ];
                                        
                    DB::table('ludo_challenge')->where('ludo_challenge_id', $request->input('ludo_challenge_id'))->update($update_ludo_challenge_data);
                    
                    $challenge_room_code_data = [
                                            'challenge_id' => $request->input('ludo_challenge_id'),
                                            'room_code' => $request->input('room_code'),                                            
                                            'date_created' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s'),
                                        ];
                    
                    DB::table('challenge_room_code')->insertGetId($challenge_room_code_data);
                    
                    $heading_msg = 'Challenge Room Code Updated';
                    $content_msg = "Room Code of " . $ludo_challenge_data->auto_id ." is " . $request->input('room_code') . ".Join your room on Ludo to play & earn";                                        
                                           
                    if($member_data->push_noti == '1' || $member_data->push_noti == 1) {
                        $this->send_onesignal_noti($heading_msg,$content_msg,$member_data->player_id,$member_data->member_id,$ludo_challenge_data->game_id);
                    }

                    $array['status'] = true;
                    $array['title'] = 'Success!';
                    $array['message'] = trans('message.succ_room_updated');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                    
        }
    }
    
    public function challengeResultUpload(Request $request) {
        if (($request->input('submit')) && $request->input('submit') == 'uploadResult') {
    
            if($request->input('result_status') == 2 || $request->input('result_status') == '2') {
                
                $validator = Validator::make($request->all(), [
                                'reason' => 'required',
                                'ludo_challenge_id' => 'required',
                                'member_id' => 'required',
                            ], [
                                'reason.required' => trans('message.err_reason'),
                                'ludo_challenge_id.required' => trans('message.err_ludo_challenge_id'),
                                'member_id.required' => trans('message.err_member_id'),
                            ]);
                
                if ($validator->fails()) {
                    $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
                }
            } else {
                $validator = Validator::make($request->all(), [
                                'ludo_challenge_id' => 'required',
                                'member_id' => 'required',
                            ], [
                                'ludo_challenge_id.required' => trans('message.err_ludo_challenge_id'),
                                'member_id.required' => trans('message.err_member_id'),
                            ]);
                
                if ($validator->fails()) {
                    $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
                }
            }
    
            $same_user_result_exist = DB::table('challenge_result_upload')
                    ->where('ludo_challenge_id', $request->input('ludo_challenge_id'))
                    ->where('member_id', $request->input('member_id'))
                    ->first();
            
            if($same_user_result_exist != ''){                
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = trans('message.err_result_already');echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }   
            
            $ludo_challenge_data = DB::table('ludo_challenge')
                    ->where('ludo_challenge_id', $request->input('ludo_challenge_id'))
                    ->first();
            
            $game_data = DB::table('game')
                        ->where('game_id', $ludo_challenge_data->game_id)
                        ->first();

            if($ludo_challenge_data->challenge_status == '2' || $ludo_challenge_data->challenge_status == '3') {                
                $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = trans('message.err_result_decided');echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
            }
               
            // win
            if($request->input('result_status') == '0') {
                
                if($request->input('result_image') != '') {
                    
                    $exist_result = DB::table('challenge_result_upload')
                    ->where('ludo_challenge_id', $request->input('ludo_challenge_id'))
                    ->first();
                    
                    $challenge_result_upload_data = [
                                            'member_id' => $request->input('member_id'),
                                            'ludo_challenge_id' => $request->input('ludo_challenge_id'),
                                            'result_uploded_by_flag' => $request->input('result_uploded_by_flag'),
                                            'result_image' => $request->input('result_image'),
                                            'result_status' => '0',                                          
                                            'date_created' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s'),
                                        ];
                    
                $id =DB::table('challenge_result_upload')->insertGetId($challenge_result_upload_data);
                
                if($request->input('result_uploded_by_flag') == 0) {
                    $member_data = DB::table('member')
                    ->where('member_id', $ludo_challenge_data->accepted_member_id)
                    ->first();
                } else {
                    $member_data = DB::table('member')
                    ->where('member_id', $ludo_challenge_data->member_id)
                    ->first();
                }
                
                if($exist_result != '' && ($exist_result->result_status == 0 || $exist_result->result_status == 2)) {
                    $ludo_challenge_update = [
                                            'challenge_status' => '4',
                                        ];
                                        
                    DB::table('ludo_challenge')->where('ludo_challenge_id', $request->input('ludo_challenge_id'))->update($ludo_challenge_update);
                }
                
                if($exist_result != '' && $exist_result->result_status == 1) {
                    $ludo_challenge_update = [
                                            'winner_id' => $request->input('member_id'),
                                            'challenge_status' => '3',
                                        ];
                                        
                    DB::table('ludo_challenge')->where('ludo_challenge_id', $request->input('ludo_challenge_id'))->update($ludo_challenge_update);                                                
                    }
                    
                    if($exist_result != '' && ($exist_result->result_status == 0)) {
                        $heading_msg = 'Win Request Generated';
                        $content_msg = "Your Opponent Submit Win Request for " . $ludo_challenge_data->auto_id . " Challenge.So please wait for final result";
                    } else {
                        $heading_msg = 'Win Request Generated';
                        $content_msg = "Your Opponent Submit Win Request for " . $ludo_challenge_data->auto_id . " Challenge.So upload your result immediatly if you still not upload";
                    }
                                                      
                    if($member_data->push_noti == '1' || $member_data->push_noti == 1) {
                        $this->send_onesignal_noti($heading_msg,$content_msg,$member_data->player_id,$member_data->member_id,$ludo_challenge_data->game_id);
                    }

                } else {
                    $array['status'] = false;
                    $array['title'] = 'Error!';
                    $array['message'] = trans('message.err_req_result_image');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                }
                    
            }
            
            //  lost       
            if($request->input('result_status') == '1') {
                
                $exist_result = DB::table('challenge_result_upload')
                    ->where('ludo_challenge_id', $request->input('ludo_challenge_id'))
                    ->first();
                    
                $challenge_result_upload_data = [
                                            'member_id' => $request->input('member_id'),
                                            'ludo_challenge_id' => $request->input('ludo_challenge_id'),
                                            'result_uploded_by_flag' => $request->input('result_uploded_by_flag'),
                                            'result_status' => '1',                                           
                                            'date_created' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s'),
                                        ];
                    
                $id =DB::table('challenge_result_upload')->insertGetId($challenge_result_upload_data);
                
                if($request->input('result_uploded_by_flag') == 0) {
                            $member_data = DB::table('member')
                            ->where('member_id', $ludo_challenge_data->accepted_member_id)
                            ->first();
                } else {
                            $member_data = DB::table('member')
                            ->where('member_id', $ludo_challenge_data->member_id)
                            ->first();
                }
                        
                if($exist_result != '' && $exist_result->result_status == '2') {
                    $ludo_challenge_update = [
                                            'challenge_status' => '4',
                                        ];
                                        
                    DB::table('ludo_challenge')->where('ludo_challenge_id', $request->input('ludo_challenge_id'))->update($ludo_challenge_update);
                    
                    $heading_msg = 'Lost Request Generated';
                    $content_msg = "Your Opponent Submit Lost Request for " . $ludo_challenge_data->auto_id . " Challenge. So wait for final result";
                        
                } else {
                        
                        $ludo_challenge_update = [
                                                    'winner_id' => $member_data->member_id,
                                                    'challenge_status' => '3',
                                                ];
                                                
                            DB::table('ludo_challenge')->where('ludo_challenge_id', $request->input('ludo_challenge_id'))->update($ludo_challenge_update);
        
                                $wallet_balance = $member_data->wallet_balance + $ludo_challenge_data->winning_price;
                                
                                $member_update_data = [
                                    'wallet_balance' => $wallet_balance,
                                ];
                                
                                DB::table('member')->where('member_id', $member_data->member_id)->update($member_update_data);
                                
                                $browser = '';
                                $agent = new Agent();
                                if ($agent->isMobile()) {
                                    $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                                } elseif ($agent->isDesktop()) {
                                    $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                                } elseif ($agent->isRobot()) {
                                    $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                                }
                                
                                $ip = $this->getIp();
                                $acc_data = [
                                    'member_id' => $member_data->member_id,
                                    'pubg_id' => $member_data->pubg_id,
                                    'deposit' => $ludo_challenge_data->winning_price,
                                    'withdraw' => 0,
                                    'join_money' => $member_data->join_money,
                                    'win_money' => $wallet_balance,
                                    'note' => 'Win '. $game_data->game_name .' Challenge #' . $request->input('ludo_challenge_id'),
                                    'note_id' => '17',
                                    'entry_from' => '1',
                                    'ip_detail' => $ip,
                                    'browser' => $browser,
                                        'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s'),
                                    ];
                                DB::table('accountstatement')->insert($acc_data);
                                
                        $heading_msg = 'Lost Request Generated';
                        $content_msg = "Your Opponent Submit Lost Request for " . $ludo_challenge_data->auto_id . " Challenge. So You are winner";
                }                                        
                                                
                    if($member_data->push_noti == '1' || $member_data->push_noti == 1) {
                        $this->send_onesignal_noti($heading_msg,$content_msg,$member_data->player_id,$member_data->member_id,$ludo_challenge_data->game_id);
                    }
            }
            
            // error
            
             if($request->input('result_status') == '2') {
                  
                 $exist_result = DB::table('challenge_result_upload')
                    ->where('ludo_challenge_id', $request->input('ludo_challenge_id'))
                    ->first();
                
                if($request->input('result_image') != '') {
                    $challenge_result_upload_data = [
                                            'member_id' => $request->input('member_id'),
                                            'ludo_challenge_id' => $request->input('ludo_challenge_id'),
                                            'result_uploded_by_flag' => $request->input('result_uploded_by_flag'),
                                            'reason' => $request->input('reason'),
                                            'result_image' => $request->input('result_image'),
                                            'result_status' => '2',                                           
                                            'date_created' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s'),
                                        ];
                } else {
                    $challenge_result_upload_data = [
                                            'member_id' => $request->input('member_id'),
                                            'ludo_challenge_id' => $request->input('ludo_challenge_id'),
                                            'result_uploded_by_flag' => $request->input('result_uploded_by_flag'),
                                            'reason' => $request->input('reason'),
                                            'result_status' => '2',                                           
                                            'date_created' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s'),
                                        ];
                }  
                
                    
                $id =DB::table('challenge_result_upload')->insertGetId($challenge_result_upload_data);
                
                if($request->input('result_uploded_by_flag') == 0) {
                    $member_data = DB::table('member')
                    ->where('member_id', $ludo_challenge_data->accepted_member_id)
                    ->first();
                } else {
                    $member_data = DB::table('member')
                    ->where('member_id', $ludo_challenge_data->member_id)
                    ->first();
                }
                
                if($exist_result != '' && $exist_result->result_status == 0) {
                    $ludo_challenge_update = [
                                            'challenge_status' => '4',
                                        ];
                                        
                    DB::table('ludo_challenge')->where('ludo_challenge_id', $request->input('ludo_challenge_id'))->update($ludo_challenge_update);
                }
                
                if($exist_result != '' && $exist_result->result_status == 2) {
                    $ludo_challenge_update = [
                                            'challenge_status' => '2',
                                        ];
                                        
                    DB::table('ludo_challenge')->where('ludo_challenge_id', $request->input('ludo_challenge_id'))->update($ludo_challenge_update);
                    
                        $join_money = $member_data->join_money + $ludo_challenge_data->coin;
                        
                        $member_update_data = [
                            'join_money' => $join_money,
                        ];
                        
                        DB::table('member')->where('member_id', $member_data->member_id)->update($member_update_data);
                        
                        $browser = '';
                        $agent = new Agent();
                        if ($agent->isMobile()) {
                            $browser = $agent->platform() . ' ' . $agent->device() . ' ' . $agent->version($agent->device());
                        } elseif ($agent->isDesktop()) {
                            $browser = $agent->platform() . ' ' . $agent->browser() . ' ' . $agent->version($agent->browser());
                        } elseif ($agent->isRobot()) {
                            $browser = $agent->platform() . ' ' . $agent->robot() . ' ' . $agent->version($agent->robot());
                        }
                        
                        $ip = $this->getIp();
                        $acc_data = [
                            'member_id' => $member_data->member_id,
                            'pubg_id' => $member_data->pubg_id,
                            'deposit' => $ludo_challenge_data->coin,
                            'withdraw' => 0,
                            'join_money' => $join_money,
                            'win_money' => $member_data->wallet_balance,
                            'note' => 'Cancel '. $game_data->game_name .' Challenge #' . $request->input('ludo_challenge_id'),
                            'note_id' => '16',
                            'entry_from' => '1',
                            'ip_detail' => $ip,
                            'browser' => $browser,
                            'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s'),
                        ];
                        DB::table('accountstatement')->insert($acc_data);
                        
                            $other_member_data = DB::table('member')
                            ->where('member_id', $request->input('member_id'))
                            ->first();
                            
                            $join_money = $other_member_data->join_money + $ludo_challenge_data->coin;
                                
                                $other_member_update_data = [
                                    'join_money' => $join_money,
                                ];
                                
                                DB::table('member')->where('member_id', $request->input('member_id'))->update($other_member_update_data);
                                
                                $other_acc_data = [
                                    'member_id' => $request->input('member_id'),
                                    'pubg_id' => $other_member_data->pubg_id,
                                    'deposit' => $ludo_challenge_data->coin,
                                    'withdraw' => 0,
                                    'join_money' => $join_money,
                                    'win_money' => $other_member_data->wallet_balance,
                                    'note' => 'Cancel '. $game_data->game_name .' Challenge #' . $request->input('ludo_challenge_id'),
                                    'note_id' => '16',
                                    'entry_from' => '1',
                                    'ip_detail' => $ip,
                                    'browser' => $browser,
                                    'accountstatement_dateCreated' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s'),
                                ];
                                DB::table('accountstatement')->insert($other_acc_data);
                }
                
                        $heading_msg = 'Error Request Generated';
                        $content_msg = "Your Opponent Submit Error Request for " . $ludo_challenge_data->auto_id . " Challenge.So upload your result immediatly if you still not upload";                                                                                       
                    
                    if($member_data->push_noti == '1' || $member_data->push_noti == 1) {
                        $this->send_onesignal_noti($heading_msg,$content_msg,$member_data->player_id,$member_data->member_id,$ludo_challenge_data->game_id);
                    }
            }
            
            $array['status'] = true;
            $array['title'] = 'Success!';
            $array['message'] = trans('message.succ_result_uploaded');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    
    public function followUnfollowGame(Request $request) {
                
        $validator = Validator::make($request->all(), [
                    'member_id' => 'required',
                    'game_id' => 'required',
                    'status' => 'required',
                    ], [
                    'member_id.required' => trans('message.err_member_id'),
                    'game_id.required' => trans('message.err_game_id'),
                    'status.required' => trans('message.err_status'),                        
                    ]);
        if ($validator->fails()) {
            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;
        }
         
        $member_id = $request->input('member_id');
        $game_data = DB::table('game')
                ->where('game_id', $request->input('game_id'))
                ->first();
                                           
		$follower = json_decode($game_data->follower,true);

        $new_follower = array();
        if($request->input('status')) {

            if (in_array($member_id, $follower)) {
                $new_follower = $follower;
            } else {
                array_push($new_follower, $member_id);
            }

            $array['message'] = trans('message.succ_follow');
            
        } else {
            if (in_array($member_id, $follower)) {
                foreach ($follower as $row) {
                    if ($row !== $member_id) {
                        $new_follower[] = $row;
                    }
                }
            } else {
                $new_follower = $follower;
            }            

            $array['message'] = trans('message.succ_unfollow');
        }
        
        $new_follower = json_encode($new_follower);
        
		DB::table('game')->where('game_id', $request->input('game_id'))->update(array(
            'follower' => $new_follower
        ));                    
        
        $array['status'] = true;
        $array['title'] = 'Success!';
        echo json_encode($array,JSON_UNESCAPED_SLASHES);
        exit;              
    }

    public function getGameFollowStatus($game_id,$member_id) {
        
        $game_data = DB::table('game')
                ->where('game_id', $game_id)
                ->first();

		$follower = json_decode($game_data->follower,true);

        if (in_array($member_id, $follower)) {
            $array['is_follower'] = true;
        } else {
            $array['is_follower'] = false;
        }

        $array['status'] = true;
        $array['title'] = 'Success!';
        echo json_encode($array,JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function send_onesignal_noti($heading_msg,$content_msg,$player_id,$member_id,$game_id,$multi = false){
        
        if($this->system_config['one_signal_notification']){
            $msg = array(
                    'body'  => $content_msg,
                    'title' => $heading_msg,
                    // 'icon'  => 'myicon',/*Default Icon*/        
                    'icon'  => 'Default',                   
                );
            
            if($multi){
                $fields = array (
                    'registration_ids' => $player_id,
                    'notification' => $msg,        
                    );
            } else {
                $fields = array (
                    'to' => $player_id,
                    'notification' => $msg,        
                    );
            }        
                            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization:key=' . $this->system_config['app_id']));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            
            $not_response = curl_exec($ch);
            
            curl_close($ch);
            
            $not_response = json_decode($not_response,true);
                              
            if (isset($not_response['success'])) {
                if($multi){
                    foreach($member_id as $key => $val) {
                        $notification_data = [
                            'member_id' => $val,
                            'id' => $not_response['multicast_id'],
                            'heading' => $heading_msg,
                            'content' => $content_msg,
                            'game_id' => $game_id,
                            'date_created' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                        ];
                        
                        DB::table('notifications')->insert($notification_data);
                    }
                } else {
                    $notification_data = [
                        'member_id' => $member_id,
                        'id' => $not_response['multicast_id'],
                        'heading' => $heading_msg,
                        'content' => $content_msg,
                        'game_id' => $game_id,
                        'date_created' => Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'), 'UTC')->setTimezone($this->timezone)->format('Y-m-d H:i:s')
                    ];
                    
                    DB::table('notifications')->insert($notification_data);
                }
                return true;            
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_name' => 'required',
                    'password' => 'required',
                        ], [
                    'user_name.required' => trans('message.err_username_req'),
                    'password.required' => trans('message.err_password_req'),
        ]);
        if ($validator->fails()) {
            $array['status'] = false;$array['title'] = 'Error!'; $array['message'] = $validator->errors()->first();echo json_encode($array,JSON_UNESCAPED_UNICODE);exit;            
            exit;
        }
        $user = DB::table('member')->where('user_name', $request->input('user_name'))->first();
        if ($user) {
            if (md5($request->input('password')) == $user->password) {
                if ($user->member_status == '1') {
                    if ($user->api_token == '') {
                        $api_token = uniqid() . base64_encode(str_random(40));
                        $member_data = [
                            'api_token' => $api_token];
                        DB::table('member')->where('member_id', $user->member_id)->update($member_data);
                        $user->api_token = $api_token;
                    }
                    
                        $player_id_data = [
                            'player_id' => $request->input('player_id')];
                        DB::table('member')->where('member_id', $user->member_id)->update($player_id_data);
                    
                    $user_data = DB::table('member')->where('member_id', $user->member_id)->first();
                    
                    $array['status'] = true;
                    $array['title'] = trans('message.text_succ_login');
                    $array['message'] = $user_data;
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                } else {
                    $array['status'] = false;
                    $array['title'] = 'Login failed!';
                    $array['message'] = trans('message.text_block_acc');
                    echo json_encode($array,JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {
                $array['status'] = false;
                $array['title'] = 'Login failed!';
                $array['message'] = trans('message.text_err_pass_incorrect');
                echo json_encode($array,JSON_UNESCAPED_UNICODE);
                exit;
            }
        } else {
            $array['status'] = false;
            $array['title'] = 'Login failed!';
            $array['message'] = trans('message.text_err_username_incorrect');
            echo json_encode($array,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

}
