<?php

namespace App\Http\Controllers;

use App\DanToc;
use App\QuocTich;
use App\TTHonNhan;
use Illuminate\Http\Request;

use App\Districts;
use App\Towns;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class TTHonNhanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $thang = isset($inputs['thang']) ? $inputs['thang'] : date('m');
            $nam = isset($inputs['nam']) ? $inputs['nam'] : date('Y');
            if(session('admin')->level == 'T') {
                $huyendf = Districts::first()->mahuyen;
                $huyen = isset($inputs['mahuyen']) ? $inputs['mahuyen'] : $huyendf;
                $xadf = Towns:: where('mahuyen', $huyen)->first()->maxa;
                if(isset($inputs['maxa'])) {
                    if ($inputs['maxa'] == "all")
                        $xa = $xadf;
                    else
                        $xa = $inputs['maxa'];
                }else {
                    $xa = $xadf;
                }

            }elseif(session('admin')->level == 'H'){
                $huyen = isset($inputs['mahuyen']) ? $inputs['mahuyen'] : session('admin')->mahuyen;
                $xadf = Towns:: where('mahuyen', $huyen)->first()->maxa;
                $xa = isset($inputs['maxa']) ? $inputs['maxa'] : $xadf;
            }else{
                $huyen = isset($inputs['mahuyen']) ? $inputs['mahuyen'] : session('admin')->mahuyen;
                $xa = isset($inputs['maxa']) ? $inputs['maxa'] : session('admin')->maxa;
            }

            $huyens = listHuyen();
            $xas = array();
            if($huyen != 'all'){
                $xas = listXa($huyen);
            }

            $model = TTHonNhan::whereMonth('ngayxn', $thang)
                ->whereYear('ngayxn', $nam);
            if($huyen != 'all' && $huyen != ''){
                $model = $model->where('mahuyen', $huyen);
            }
            if($xa != 'all' && $xa != ''){
                $model = $model->where('maxa', $xa);
            }else{
                $model = $model->where('maxa', $xa);
            }
            $model = $model->get();

            $count = $model->count();

            return view('manage.tthonnhan.index')
                -> with('huyens', $huyens)
                -> with('xas', $xas)
                -> with('mahuyen', $huyen)
                -> with('maxa', $xa)
                -> with('thang',$thang)
                -> with('nam',$nam)
                -> with('model', $model)
                -> with('pageTitle','Thông tin tình trạng hôn nhân ('.$count.' hồ sơ)');
        }else
            return view('errors.notlogin');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Session::has('admin')) {
            if (session('admin')->level == 'T') {
                $huyens = Districts::all();
                $huyendf = Districts::first()->mahuyen;
                $xas = Towns::where('mahuyen', $huyendf)
                    ->get();
                $xadf = $xas->first()->maxa;
            } elseif (session('admin')->level == 'H') {
                $huyens = Districts::all();
                $huyendf = Districts::where('mahuyen', session('admin')->mahuyen)->first()->mahuyen;
                $xas = Towns::where('mahuyen', $huyendf)
                    ->get();
                $xadf = $xas->first()->maxa;
            } else {
                $huyens = Districts::all();
                $huyendf = Districts::where('mahuyen', session('admin')->mahuyen)->first()->mahuyen;
                $xas = Towns::where('mahuyen', $huyendf)
                    ->get();
                $xadf = $xas->where('maxa', session('admin')->maxa)->first()->maxa;
            }

            $dantocs = DanToc::all();
            $quoctichs = QuocTich::all();

            return view('manage.tthonnhan.create')
                ->with('action','create')
                ->with('huyens', $huyens)
                ->with('mahuyen', $huyendf)
                ->with('xas', $xas)
                ->with('maxa', $xadf)
                ->with('dantocs', $dantocs)
                ->with('quoctichs', $quoctichs)
                ->with('pageTitle', 'Thêm mới tình trạng hôn nhân');
        }else
            return view('errors.notlogin');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['matinh'] = getmatinh();
            $inputs['mahs'] = $inputs['mahuyen'].$inputs['maxa'].getdate()[0];
            $inputs['ngayxn'] = date('Y-m-d', strtotime(str_replace('/', '-', $inputs['ngayxn'])));
            $inputs['tungay'] = date('Y-m-d', strtotime(str_replace('/', '-', $inputs['tungay'])));
            $inputs['denngay'] = date('Y-m-d', strtotime(str_replace('/', '-', $inputs['denngay'])));
            $inputs['ngaysinh'] = date('Y-m-d', strtotime(str_replace('/', '-', $inputs['ngaysinh'])));
            $inputs['trangthai'] = 'Chờ duyệt';
            $model = new TTHonNhan();
            $model->create($inputs);

            return redirect('tthonnhan');

        }else
            return view('errors.notlogin');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Session::has('admin')) {

            $model = TTHonNhan::find($id);
            $xa = Towns::where('maxa',$model->maxa)->first()->tenxa;
            $huyen = Districts::where('mahuyen',$model->mahuyen)->first()->tenhuyen;
            $thongtinthaydoi = ThongTinThayDoi::where('mahs',$model->mahs)->get();
            return view('manage.tthonnhan.show')
                ->with('model',$model)
                ->with('xa',$xa)
                ->with('huyen',$huyen)
                ->with('thongtinthaydoi',$thongtinthaydoi)
                ->with('pageTitle', 'Thông tin tình trạng hôn nhân');
        }else
            return view('errors.notlogin');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Session::has('admin')) {

            $model = TTHonNhan::find($id);
            $huyens = Districts::all();
            $huyendf = $model->mahuyen;
            $xas = Towns::where('mahuyen', $huyendf)
                ->get();
            $xadf = $model->maxa;

            $dantocs = DanToc::all();
            $quoctichs = QuocTich::all();

            return view('manage.tthonnhan.edit')
                ->with('action','edit')
                ->with('huyens', $huyens)
                ->with('mahuyen', $huyendf)
                ->with('xas', $xas)
                ->with('maxa', $xadf)
                ->with('dantocs', $dantocs)
                ->with('quoctichs', $quoctichs)
                ->with('model',$model)
                ->with('pageTitle', 'Thay đổi tình trạng hôn nhân');
        }else
            return view('errors.notlogin');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $inputs['ngayxn'] = date('Y-m-d', strtotime(str_replace('/', '-', $inputs['ngayxn'])));
            $inputs['tungay'] = date('Y-m-d', strtotime(str_replace('/', '-', $inputs['tungay'])));
            $inputs['denngay'] = date('Y-m-d', strtotime(str_replace('/', '-', $inputs['denngay'])));
            $inputs['ngaysinh'] = date('Y-m-d', strtotime(str_replace('/', '-', $inputs['ngaysinh'])));
            $model = TTHonNhan::find($id);
            $model->update($inputs);
            return redirect('tthonnhan');

        }else
            return view('errors.notlogin');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (Session::has('admin')) {
            $inputs = $request->all();
            $id = $inputs['iddelete'];
            $model = TTHonNhan::find($id);
            $model->delete();
            return redirect('tthonnhan');

        }else
            return view('errors.notlogin');
    }
    public function duyet(Request $request){
        if (Session::has('admin')) {
            $inputs = $request->all();
            $id = $inputs['idduyet'];
            $model = TTHonNhan::find($id);
            $model->trangthai = 'Duyệt';
            $model->save();
            return redirect('tthonnhan');

        }else
            return view('errors.notlogin');
    }

    public function prints($id){
        if (Session::has('admin')) {
            $model = TTHonNhan::find($id);
            $xa = Towns::where('maxa',$model->maxa)->first()->tenxa;
            $huyen = Districts::where('mahuyen',$model->mahuyen)->first()->tenhuyen;
                return view('reports.tthonnhan.print')
                    ->with('model',$model)
                    ->with('xa',$xa)
                    ->with('huyen',$huyen)
                    ->with('pageTitle','In giấy xác nhận tình trạng hôn nhân');

        }else
            return view('errors.notlogin');
    }
}
