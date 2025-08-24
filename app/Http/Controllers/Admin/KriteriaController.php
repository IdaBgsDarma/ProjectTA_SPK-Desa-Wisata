<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use DataTables;

use App\Models\KriteriaModel;
class KriteriaController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            if ($request->id) {
                $id = $request->id;
                Cache::rememberForever('data_kriteria_'.$id.'', function () use($id) {
                    return KriteriaModel::find($id);
                });
                return response()->json(['result' => Cache::get('data_kriteria_'.$id.'')]);
            };
            Cache::rememberForever('kriteria', function () {
                return KriteriaModel::orderBy('id','desc')->get();
            });
            return Datatables::of(Cache::get('kriteria'))
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                    <a href="javascript: void(0);" class="action-icon btn_edit" id="btn_edit" data-id="'.$row->id.'" style="color:green"><i class="ti ti-pencil"></i></a>
                    <a href="javascript: void(0);" class="action-icon btn_delete" id="btn_delete" data-id="'.$row->id.'" style="color:red"> <i class="ti ti-trash"></i></a>';
                    return $actionBtn;
                })
                ->addColumn('cost_benefit', function($row){
                    if ($row->cost_benefit == "Benefit") {
                        $status = '<span class="badge bg-success" style="color:white">'.$row->cost_benefit.'</span>';
                    } else {
                        $status = '<span class="badge bg-danger" style="color:white">'.$row->cost_benefit.'</span>';
                    }
                    return $status;
                })
                ->addColumn('status', function($row){
                    if ($row->status == "Aktif") {
                        $status = '<span class="badge bg-success" style="color:white">'.$row->status.'</span>';
                    } else {
                        $status = '<span class="badge bg-danger" style="color:white">'.$row->status.'</span>';
                    }
                    return $status;
                })
                ->rawColumns(['action','cost_benefit','status'])
                ->make(true);
        }
        return view('admin.kriteria');
    }

    public function store(Request $request) {
        if ($request->ajax()) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'nama' => 'required|max:255',
                    'bobot' => 'required',
                    'cost_benefit' => 'required|max:255',
                ],
            );

            $attribute = [
                'nama' => 'Nama kriteria',
                'bobot' => 'Bobot',
                'cost_benefit' => 'Benefit',
            ];
            $validator->setAttributeNames($attribute);

            if (!$validator->passes()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $data = KriteriaModel::create([
                    'nama' => $request->nama,
                    'bobot' => $request->bobot,
                    'cost_benefit' => $request->cost_benefit,
                    'status' => $request->status,
                ]);

                Cache::forget('kriteria');
                return response()->json(['code' => 1]);
            }
        }
    }

    public function update(Request $request){
        if ($request->ajax()) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'nama' => 'required|max:255',
                    'bobot' => 'required',
                    'cost_benefit' => 'required|max:255',
                ],
            );

            $attribute = [
                'nama' => 'Nama kriteria',
                'bobot' => 'Bobot',
                'cost_benefit' => 'Benefit',
            ];
            $validator->setAttributeNames($attribute);

            if (!$validator->passes()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $data = KriteriaModel::find($request->id);

                $data->update([
                    'nama' => $request->nama,
                    'bobot' => $request->bobot,
                    'cost_benefit' => $request->cost_benefit,
                    'status' => $request->status,
                ]);

                Cache::forget('data_kriteria_'.$request->id);
                Cache::forget('kriteria');
                return response()->json(['code' => 1]);
            }
        }
    }

    public function delete(Request $request){
        if ($request->ajax()) {
            $data = KriteriaModel::find($request->id);
            $data->update([
                    'status' => 'Tidak Aktif',
            ]);

            Cache::forget('data_kriteria_'.$request->id);
            Cache::forget('kriteria');
            return response()->json(['code' => 1]);
        }
    }
}
