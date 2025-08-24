<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use DataTables;

use App\Models\AlternatifModel;
class AlternatifController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            if ($request->id) {
                $id = $request->id;
                Cache::rememberForever('data_alternatif_'.$id.'', function () use($id) {
                    return AlternatifModel::find($id);
                });
                return response()->json(['result' => Cache::get('data_alternatif_'.$id.'')]);
            };
            Cache::rememberForever('alternatif', function () {
                return AlternatifModel::orderBy('id','desc')->get();
            });
            return Datatables::of(Cache::get('alternatif'))
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                    <a href="javascript: void(0);" class="action-icon btn_edit" id="btn_edit" data-id="'.$row->id.'" style="color:green"><i class="ti ti-pencil"></i></a>
                    <a href="javascript: void(0);" class="action-icon btn_delete" id="btn_delete" data-id="'.$row->id.'" style="color:red"> <i class="ti ti-trash"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.alternatif');
    }

    public function store(Request $request) {
        if ($request->ajax()) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'nama' => 'required|max:255',
                ],
            );

            $attribute = [
                'nama' => 'Nama alternatif',
            ];
            $validator->setAttributeNames($attribute);

            if (!$validator->passes()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $data = AlternatifModel::create([
                    'nama' => $request->nama,
                ]);

                Cache::forget('alternatif');
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
                ],
            );

            $attribute = [
                'nama' => 'Nama alternatif',
            ];
            $validator->setAttributeNames($attribute);

            if (!$validator->passes()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $data = AlternatifModel::find($request->id);

                $data->update([
                    'nama' => $request->nama,
                ]);

                Cache::forget('data_alternatif_'.$request->id);
                Cache::forget('alternatif');
                return response()->json(['code' => 1]);
            }
        }
    }

    public function delete(Request $request){
        if ($request->ajax()) {
            $data = AlternatifModel::find($request->id);
            $data->delete();

            Cache::forget('data_alternatif_'.$request->id);
            Cache::forget('alternatif');
            return response()->json(['code' => 1]);
        }
    }
}
