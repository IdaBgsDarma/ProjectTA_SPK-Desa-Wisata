<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use DataTables;

use App\Models\KriteriaModel;
use App\Models\CripsModel;
class CripsController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            if ($request->id) {
                $id = $request->id;
                Cache::rememberForever('data_crips_'.$id.'', function () use($id) {
                    return CripsModel::find($id);
                });
                return response()->json(['result' => Cache::get('data_crips_'.$id.'')]);
            };
            Cache::rememberForever('crips', function () {
                return CripsModel::with('kriteria')->orderBy('id','desc')->get();
            });
            return Datatables::of(Cache::get('crips'))
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                    <a href="javascript: void(0);" class="action-icon btn_edit" id="btn_edit" data-id="'.$row->id.'" style="color:green"><i class="ti ti-pencil"></i></a>
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
        return view('admin.crips',$data);
    }

    public function store(Request $request) {
        if ($request->ajax()) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'id_kriteria' => 'required',
                    'nama' => 'required|max:255',
                    'bobot' => 'required',
                ],
            );

            $attribute = [
                'id_kriteria' => 'Kriteria',
                'nama' => 'Nama crips',
                'bobot' => 'Bobot',
            ];
            $validator->setAttributeNames($attribute);

            if (!$validator->passes()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $data = CripsModel::create([
                    'id_kriteria' => $request->id_kriteria,
                    'nama' => $request->nama,
                    'bobot' => $request->bobot,
                    'status' => $request->status,
                ]);

                Cache::forget('crips');
                return response()->json(['code' => 1]);
            }
        }
    }

    public function update(Request $request){
        if ($request->ajax()) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'id_kriteria' => 'required',
                    'nama' => 'required|max:255',
                    'bobot' => 'required',
                ],
            );

            $attribute = [
                'id_kriteria' => 'Kriteria',
                'nama' => 'Nama crips',
                'bobot' => 'Bobot',
            ];
            $validator->setAttributeNames($attribute);

            if (!$validator->passes()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $data = CripsModel::find($request->id);

                $data->update([
                    'id_kriteria' => $request->id_kriteria,
                    'nama' => $request->nama,
                    'bobot' => $request->bobot,
                    'status' => $request->status,
                ]);

                Cache::forget('data_crips_'.$request->id);
                Cache::forget('crips');
                return response()->json(['code' => 1]);
            }
        }
    }

    public function delete(Request $request){
        if ($request->ajax()) {
            $data = CripsModel::find($request->id);
            $data->update([
                'status' => 'Tidak Aktif',
            ]);

            Cache::forget('data_crips_'.$request->id);
            Cache::forget('crips');
            return response()->json(['code' => 1]);
        }
    }
}
