<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use DataTables;

use App\Models\AlternatifModel;
use App\Models\KriteriaModel;
use App\Models\CripsModel;
use App\Models\NilaiModel;
use App\Models\TotalnilaiModel;
class NilaiController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            if ($request->id) {
                $id = $request->id;
                Cache::rememberForever('data_nilai_'.$id.'', function () use($id) {
                    return NilaiModel::find($id);
                });
                return response()->json(['result' => Cache::get('data_nilai_'.$id.'')]);
            };
            if ($request->id_kriteria) {
                $crips = CripsModel::where('id_kriteria',$request->id_kriteria)->orderBy('nama','asc')->get();
                return response()->json(['result' => $crips]);
            }
            Cache::rememberForever('nilai', function () {
                return NilaiModel::with('kriteria','alternatif','crips')->orderBy('id_alternatif','asc')->orderBy('id_kriteria','asc')->orderBy('id','desc')->get();
            });
            return Datatables::of(Cache::get('nilai'))
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                    <a href="javascript: void(0);" class="action-icon btn_delete" id="btn_delete" data-id="'.$row->id.'" style="color:red"> <i class="ti ti-trash"></i></a>';
                    return $actionBtn;
                })
                ->addColumn('status', function($row){
                    if ($row->status == "Aktif") {
                        $status = '<span class="badge bg-success" style="color:white">'.$row->status.'</span>';
                    } else {
                        $status = '<span class="badge bg-danger" style="color:white">'.$row->status.'</span>';
                    }
                    return $status;
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }
        $data['kriteria'] = KriteriaModel::orderBy('nama')->get();
        $data['alternatif'] = AlternatifModel::orderBy('nama')->get();
        $data['crips'] = CripsModel::orderBy('nama')->get();
        return view('admin.nilai',$data);
    }

    public function store(Request $request) {
        if ($request->ajax()) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'id_alternatif' => 'required',
                    'id_kriteria' => 'required',
                    'id_crips' => 'required',
                ],
            );

            $attribute = [
                'id_alternatif' => 'Alternatif',
                'id_kriteria' => 'Kriteria',
                'id_crips' => 'Crips',
            ];
            $validator->setAttributeNames($attribute);

            if (!$validator->passes()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $check_bobot = KriteriaModel::where('id',$request->id_kriteria)->value('bobot');
                $total_nilai = 0;
                foreach ($request->id_crips as $data_crips) {
                    $get_bobot = CripsModel::where('id',$data_crips)->value('bobot');
                    $total_nilai += $get_bobot;
                }
                if ($total_nilai > $check_bobot) {
                    return response(['code' => 2]);
                } else {
                    NilaiModel::where('id_kriteria',$request->id_kriteria)->where('id_alternatif',$request->id_alternatif)->delete();
                    TotalnilaiModel::where('id_kriteria',$request->id_kriteria)->where('id_alternatif',$request->id_alternatif)->delete();
                    foreach ($request->id_crips as $data_crips) {
                        $nilai = CripsModel::where('id',$data_crips)->value('bobot');
                        NilaiModel::create([
                            'id_alternatif' => $request->id_alternatif,
                            'id_kriteria' => $request->id_kriteria,
                            'id_crips' => $data_crips,
                            'nilai' => $nilai,
                        ]);
                    }
                    TotalnilaiModel::create([
                        'id_alternatif' => $request->id_alternatif,
                        'id_kriteria' => $request->id_kriteria,
                        'nilai' => $total_nilai,
                    ]);
                    Cache::forget('nilai');
                    return response()->json(['code' => 1]);
                }
            }
        }
    }

    public function update(Request $request){
        if ($request->ajax()) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'id_alternatif' => 'required',
                    'id_kriteria' => 'required',
                    'id_crips' => 'required',
                ],
            );

            $attribute = [
                'id_alternatif' => 'Alternatif',
                'id_kriteria' => 'Kriteria',
                'id_crips' => 'Crips',
            ];
            $validator->setAttributeNames($attribute);

            if (!$validator->passes()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $data = NilaiModel::find($request->id);

                $data->update([
                    'id_alternatif' => $request->id_alternatif,
                    'id_kriteria' => $request->id_kriteria,
                    'id_crips' => $request->id_crips,
                ]);

                Cache::forget('data_nilai_'.$request->id);
                Cache::forget('nilai');
                return response()->json(['code' => 1]);
            }
        }
    }

    public function delete(Request $request){
        if ($request->ajax()) {
            $data = NilaiModel::find($request->id);
            $total_nilai = TotalnilaiModel::where('id_alternatif',$data->id_alternatif);

            $data->delete();
            $total_nilai->delete();

            Cache::forget('data_nilai_'.$request->id);
            Cache::forget('nilai');
            return response()->json(['code' => 1]);
        }
    }
}
