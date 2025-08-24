<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use DataTables;

use App\Models\KabupatenModel;
class KabupatenController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            if ($request->id) {
                $id = $request->id;
                Cache::rememberForever('data_kabupaten_'.$id.'', function () use($id) {
                    return KabupatenModel::find($id);
                });
                return response()->json(['result' => Cache::get('data_kabupaten_'.$id.'')]);
            };
            Cache::rememberForever('kabupaten', function () {
                return KabupatenModel::orderBy('id','desc')->get();
            });
            return Datatables::of(Cache::get('kabupaten'))
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
        return view('admin.kabupaten');
    }

    public function store(Request $request) {
        if ($request->ajax()) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'nama' => 'required|max:255',
                    'foto' => 'required|mimes:jpg,jpeg,png|max:2048',
                ],
            );

            $attribute = [
                'nama' => 'Nama Kabupaten',
                'foto' => 'Foto',
            ];
            $validator->setAttributeNames($attribute);

            if (!$validator->passes()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $foto = $request->file('foto');
                if($request->Hasfile('foto')){
                    $filename = $foto->getClientOriginalName();
                    $extension = $foto->getClientOriginalExtension();
                    $name= uniqid().'.'.$extension;
                    $foto->move('assets/images/kabupaten',$name);
                }
                $data = KabupatenModel::create([
                    'nama' => $request->nama,
                    'foto' => $name,
                    'status' => $request->status,
                ]);

                Cache::forget('kabupaten');
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
                    'foto' => 'mimes:jpg,jpeg,png|max:2048',
                ],
            );

            $attribute = [
                'nama' => 'Nama Kabupaten',
                'foto' => 'Foto',
            ];
            $validator->setAttributeNames($attribute);

            if (!$validator->passes()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $data = KabupatenModel::find($request->id);
                $foto = $request->file('foto');
                if($request->Hasfile('foto')){
                    @unlink('assets/images/kabupaten/'. $data->foto);
                    $filename = $foto->getClientOriginalName();
                    $extension = $foto->getClientOriginalExtension();
                    $name= uniqid().'.'.$extension;
                    $foto->move('assets/images/kabupaten',$name);

                    $data->update([
                        'nama' => $request->nama,
                        'foto' => $name,
                        'status' => $request->status,
                    ]);
                } else {
                    $data->update([
                        'nama' => $request->nama,
                        'status' => $request->status,
                    ]);
                }

                Cache::forget('data_kabupaten_'.$request->id);
                Cache::forget('kabupaten');
                return response()->json(['code' => 1]);
            }
        }
    }

    public function delete(Request $request){
        if ($request->ajax()) {
            $data = KabupatenModel::find($request->id);
            $data->update([
                'status' => 'Tidak Aktif',
            ]);

            Cache::forget('data_kabupaten_'.$request->id);
            Cache::forget('kabupaten');
            return response()->json(['code' => 1]);
        }
    }
}

