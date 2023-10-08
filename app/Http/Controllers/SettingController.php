<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;

class SettingController extends ParentController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->id;
        
        $category_list = DB::table('categories')
                            ->join('user_categories as uc', 'uc.category_id','=','categories.id')
                            ->where('uc.user_id',$user)
                            ->select('categories.id', 'categories.name', 'uc.color')
                            ->get();

        $color = Setting::where('key','colors')->first()->value;
        $color = explode(",", $color);
        $list_color = preg_replace("/[^\w\d\s]/",'',$color);

        $query = Setting::join('user_settings as us','us.setting_id','=','settings.id')
                        ->where('us.user_id', $user)
                        ->select("settings.key", 'us.value')
                        ->get();

        foreach($query as $q){
            if($q->key == 'send notif')
                $email_status = $q->value;
            else
                $reminder = $q->value;
        }

        $reminder = explode(":", $reminder);
        $minute = $reminder[0];

        return parent::view('settings.index', compact('category_list', 'list_color', 'email_status', 'minute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }

    public function updateData(Request $request){
        $userId = Auth::user()->id;

        if($request->tab_type == 'profile'){
            
            $data = [
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ];
    
            User::where('id', $userId)->update($data);
        }
        else if($request->tab_type == 'color'){
            $cat = DB::table('categories')->get();
            
            foreach($cat as $c){
                $name = 'category_color_'.$c->id;
               
                DB::table('user_categories')->where('user_id', $userId)
                            ->where('category_id', $c->id)
                            ->update(['color'=> $request->$name]);
         
            }
          
        }
        else{
            $email_status = $request->email_status ?? '0';
            $setting = Setting::where('key', '!=', 'color')->get();

            foreach($setting as $s){
                if($s->key == 'reminder')
                    $value = $request->time;
                else
                    $value = $email_status;

                DB::table('user_settings')->where("user_id", $userId)
                            ->where('setting_id', $s->id)
                            ->update(['value'=>$value]);
            }
           
        }
        
        return redirect()->back()->with(['success_message'=>'Successfully updated!']);
    }
}
