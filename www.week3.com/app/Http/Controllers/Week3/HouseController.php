<?php

namespace App\Http\Controllers\Week3;

use App\Models\House;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $data = House::dest();
//        $list = (new House())->fordest($data);
        $list = House::get()->toArray();
        return view('week3.houselist',compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $data = House::get()->toArray();
       $data = (new House())->fordest($data);
        return view('week3.housecreate',compact('data'));
    }

    public function info(Request $request){
       if($request->hasFile('files')){
           $icon  = $request->file('files')->store('','icon');
       };
       return ['status'=>200,'url'=>$icon];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = Validator::make($request->all(),[
            'attress'=>'unique:houses',
        ],[
            'attress.unique'=>'房源属性不可重复'
        ]);

        if ($post->fails()) {
            return ['code'=>1,'data'=>'','msg'=> $post->errors()->first()];
        }

        $data = House::create($request->except(['file','_token']));
        if($data){
            return ['code'=>0,'data'=>'','msg'=> '添加成功'];
        }else{
            return ['code'=>1,'data'=>'','msg'=> '添加失败'];
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function show(House $house)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function edit(House $house)
    {
        $data = House::get()->toArray();
        $data = (new House())->fordest($data);
        return view('week3.houseedit',compact('house','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, House $house)
    {
        $data = House::where('id',$house['id'])->update($request->except(['_token','id']));
        if($data){
            return ['code'=>0,'data'=>$data,'msg'=>'修改成功'];
        }else{
            return ['code'=>1,'data'=>$data,'msg'=>'修改失败'];
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function destroy(House $house)
    {
        $haveid = House::where('pid',$house['id'])->get();
        if($haveid){
            return ['code'=>1,'data'=>$house,'msg'=>'该字段下有子集'];
        }

        $housedel = House::where('id',$house['id'])->delete();
        if($housedel){
            return ['code'=>0,'data'=>$house,'msg'=>'删除成功'];
        }else{
            return ['code'=>1,'data'=>$house,'msg'=>'删除失败'];
        }

    }
}
