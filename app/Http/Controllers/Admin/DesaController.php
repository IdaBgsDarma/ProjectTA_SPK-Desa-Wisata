<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use DataTables;

use App\Models\KabupatenModel;
use App\Models\DesaModel;
use App\Models\AlternatifModel;
use App\Models\NilaiModel;
use App\Models\TotalnilaiModel;
class DesaController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            if ($request->id) {
                $id = $request->id;
                Cache::rememberForever('data_desa_'.$id.'', function () use($id) {
                    return DesaModel::find($id);
                });
                return response()->json(['result' => Cache::get('data_desa_'.$id.'')]);
            };
            Cache::rememberForever('desa', function () {
                return DesaModel::with('kabupaten')->orderBy('id','desc')->get();
            });
            return Datatables::of(Cache::get('desa'))
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
        $data['kabupaten'] = KabupatenModel::orderBy('nama','asc')->get();
        return view('admin.desa',$data);
    }

    public function store(Request $request) {
        if ($request->ajax()) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'id_kabupaten' => 'required',
                    'nama' => 'required|max:255',
                    'foto' => 'required|mimes:jpg,jpeg,png|max:2048',
                    'lokasi' => 'required',
                ],
            );

            $attribute = [
                'id_kabupaten' => 'Kabupaten',
                'nama' => 'Nama Desa',
                'foto' => 'Foto',
                'lokasi' => 'Lokasi',
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
                    $foto->move('assets/images/desa',$name);
                }
                $data = DesaModel::create([
                    'id_kabupaten' => $request->id_kabupaten,
                    'nama' => $request->nama,
                    'foto' => $name,
                    'lokasi' => $request->lokasi,
                    'status' => $request->status,
                ]);

                AlternatifModel::create([
                    'nama' => $request->nama,
                    'status' => $request->status,
                ]);
                Cache::forget('desa');
                return response()->json(['code' => 1]);
            }
        }
    }

    public function update(Request $request){
        if ($request->ajax()) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'id_kabupaten' => 'required',
                    'nama' => 'required|max:255',
                    'foto' => 'mimes:jpg,jpeg,png|max:2048',
                    'lokasi' => 'required',
                ],
            );

            $attribute = [
                'id_kabupaten' => 'Kabupaten',
                'nama' => 'Nama Desa',
                'foto' => 'Foto',
                'lokasi' => 'Lokasi',
            ];
            $validator->setAttributeNames($attribute);

            if (!$validator->passes()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $data = DesaModel::find($request->id);
                $alternatif = AlternatifModel::where('nama',$data->nama)->first();
                $foto = $request->file('foto');
                if($request->Hasfile('foto')){
                    @unlink('assets/images/desa/'. $data->foto);
                    $filename = $foto->getClientOriginalName();
                    $extension = $foto->getClientOriginalExtension();
                    $name= uniqid().'.'.$extension;
                    $foto->move('assets/images/desa',$name);

                    $data->update([
                        'id_kabupaten' => $request->id_kabupaten,
                        'nama' => $request->nama,
                        'foto' => $name,
                        'lokasi' => $request->lokasi,
                        'status' => $request->status,
                    ]);

                    $alternatif->update([
                        'nama' => $request->nama,
                        'status' => $request->status,
                    ]);
                } else {
                    $data->update([
                        'id_kabupaten' => $request->id_kabupaten,
                        'nama' => $request->nama,
                        'lokasi' => $request->lokasi,
                        'status' => $request->status,
                    ]);

                    $alternatif->update([
                        'nama' => $request->nama,
                        'status' => $request->status,
                    ]);
                }

                Cache::forget('data_desa_'.$request->id);
                Cache::forget('desa');
                return response()->json(['code' => 1]);
            }
        }
    }

    public function delete(Request $request){
        if ($request->ajax()) {
            $data = DesaModel::find($request->id);
            $alternatif = AlternatifModel::where('nama',$data->nama)->first();
            $nilai = NilaiModel::where('id_alternatif',$alternatif->id);
            $totalnilai = TotalnilaiModel::where('id_alternatif',$alternatif->id);

            $alternatif->update([
                'status' => "Tidak Aktif",
            ]);
            $data->update([
                'status' => "Tidak Aktif",
            ]);

            Cache::forget('data_desa_'.$request->id);
            Cache::forget('desa');
            return response()->json(['code' => 1]);
        }
    }
}

