<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index()
    {
        $method=$this->request->method();
        $data=$this->request->post();
        $data['date']=date('Y-m-d', time());
        $result=Db::table('student')->insert($data);
        if($result){
        return json([
            'code'=>config('code.success'),
            'msg'=>'插入成功'
        ]);
        }
        else{
            return json([
                'code'=>config('code.fail'),
                'msg'=>'插入失败'
            ]);
        }
    }

    public function query(){
        $data=$this->request->get();
        $names=$data['names'];
        if(isset($data['date1'])&&!empty($data['date1'])){
            $date=['like','%'.$data['date1'].'%'];
        }else{
            $date=['like','%'];
        }
        if(isset($data['content'])&&!empty($data['content'])){
            $content=['like','%'.$data['content'].'%'];
        }else{
            $content=['like','%'];
        }
//
        if(isset($data['page'])&&!empty($data['page'])){
            $page=$data['page'];
        }else{
            $page=1;
        }
        if(isset($data['limit'])&&!empty($data['limit'])){
            $limit=$data['limit'];
        }else{
            $limit=1;
        }

        if(isset($data['order'])&&!empty($data['order'])){
            $order=$data['order'];
        }else{
            $order='id';
        }
        if(isset($data['ordertype'])&&!empty($data['ordertype'])){
            $ordertype=$data['ordertype'];
        }else{
            $ordertype='desc';
        }
        $sarr=['names'=>$names,
            'content'=>$content,
            'date'=>$date,
            ];

//        var_dump($sarr);
//        $offset=($page-1)*$limit;
//        $result=Db::table('student')->where($sarr)->limit($offset,$limit)->select();
        $result=Db::table('student')->where($sarr)->page($page,$limit)->order($order,$ordertype)->select();

        $res=Db::table('student')->where($sarr)->select();
        $count=count($res);

        if($count>0&&count($result)){
            return json([
               'code'=>config('code.success'),
                'msg'=>'获取成功',
                'data'=>$result,
                'count'=>$count
            ]);
        }else{
            return json([
                'code'=>config('code.success'),
                'msg'=>'暂无数据',
                'data'=>$result,
                'count'=>$count
            ]);
        }

    }
}
