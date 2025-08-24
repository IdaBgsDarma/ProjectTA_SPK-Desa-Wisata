<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Auth;
use DataTables;

use App\Models\User;
class AkunController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->id) {
                $data = User::select('id','email','name')->find($request->id);
                return response()->json(['result' => $data]);
            };
            $data = User::select('id','email','name')->whereNot('id', Auth::user()->id)->orderBy('id','desc')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $actionBtn = '
                <a href="javascript: void(0);" class="action-icon btn_delete" id="btn_delete" data-id="'.$row->id.'" style="color:red"> <i class="ti ti-trash"></i></a>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('admin.akun');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:8|confirmed',
                ],
            );

            $attribute = [
                'name' => 'Nama',
                'email' => 'Email',
                'password' => 'Password',
            ];
            $validator->setAttributeNames($attribute);

            if (!$validator->passes()) {
                return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $data = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);
                return response()->json(['code' => 1]);
            }
        }
    }

    public function delete(Request $request){
        if ($request->ajax()) {
            $data = User::find($request->id);
            $data->delete();
            return response()->json(['code' => 1]);
        }
    }
}
