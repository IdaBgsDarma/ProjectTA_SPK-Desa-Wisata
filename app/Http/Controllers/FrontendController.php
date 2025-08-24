<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\KriteriaModel;
use App\Models\AlternatifModel;
use App\Models\NilaiModel;
use App\Models\CripsModel;
use App\Models\KabupatenModel;
use App\Models\DesaModel;
use App\Models\TotalnilaiModel;

class FrontendController extends Controller
{
    public function index(Request $request) {
        Cache::rememberForever('kabupaten', function () {
            return KabupatenModel::orderBy('nama','asc')->get();
        });
        $data['kriteria'] = KriteriaModel::get();
        $data['jumlah_kriteria'] = KriteriaModel::count();
        $data['alternatif'] = AlternatifModel::get();
        $data['jumlah_alternatif'] = AlternatifModel::count();
        $records = TotalnilaiModel::with('kriteria','alternatif')->orderBy('id_alternatif')
        ->orderBy('id_kriteria')
        ->get();

        $total_nilai = 0;
        $i = 0;

        $rows = [];
        $columns = [];
        foreach($records as $index => $record) {
            if(!isset($rows[$record->alternatif->nama])) {
                $rows[$record->alternatif->nama] = [];
            }

            if(!in_array($record->kriteria->nama, $columns)) {
                $columns[] = $record->kriteria->nama;
            }

            $rows[$record->alternatif->nama][$record->kriteria->nama] = $record->nilai;
        }

        for ($i=0; $i <=($data['jumlah_kriteria'])-1 ; $i++) {
            $min[] = TotalnilaiModel::with('kriteria','alternatif')->where('id_kriteria',$records[$i]->id_kriteria)->orderBy('nilai','asc')->first();
        }
        for ($i=0; $i <=($data['jumlah_kriteria'])-1 ; $i++) {
            $max[] = TotalnilaiModel::with('kriteria','alternatif')->where('id_kriteria',$records[$i]->id_kriteria)->orderBy('nilai','desc')->first();
        }

        $j = 0;
        $mt_normalisasi = [];
        for ($i=0; $i <= ($records->count()-1) ; $i++) {
            $check_kriteria = KriteriaModel::where('id', $records[$i]->id_kriteria)->value('cost_benefit');
            if ($check_kriteria == "Benefit") {
                if (isset($max[$j]->nilai)) {
                    $mt_normalisasi[] = $records[$i]->nilai / $max[$j]->nilai;
                    $j++;
                    if ($j == count($max)) {
                        $j = 0;
                    }
                }
            } else {
                if (isset($min[$j]->nilai)) {
                    $mt_normalisasi[] = $min[$j]->nilai / $records[$i]->nilai;
                    $j++;
                    if ($j == count($min)) {
                        $j = 0;
                    }
                }
            }
        }

        $q = 0;
        $r = 0;
        for ($i = 0; $i <=$data['jumlah_alternatif']-1; $i++) {
            while ($q < count($mt_normalisasi)) {
                $mt_terbobot[] = $mt_normalisasi[$q] * $data['kriteria'][$r]->bobot;
                // $mt_terbobot[] = $mt_normalisasi[$q] * $data['kriteria'][$r]->bobot;
                $q++;
                $r++;
                if ($r == $data['jumlah_kriteria']) {
                    $r = 0;
                    break;
                }
            }
        }

        $x = 0;
        $j = 0;
        $count = 0;
        for ($i = 0; $i <=$data['jumlah_alternatif']-1; $i++) {
            $v[$i][0] = 0;
            while ($x < count($mt_terbobot)) {
                $v[$i][0] = $v[$i][0]+$mt_terbobot[$x];
                $x++;
                $count++;

                if ($count == $data['jumlah_kriteria']) {
                    $count = 0;
                    break;
                }
            }
            if ($i==0) {
                $hasil_akhir = array($data['alternatif'][$i]->nama=>round($v[$i][0], 3));
            } else {
                $hasil_akhir+= array($data['alternatif'][$i]->nama=>round($v[$i][0], 3));
            }
        }

        arsort($hasil_akhir);
        foreach ($hasil_akhir as $data_hasilakhir => $value){
            $check_empty = DesaModel::where('nama','like','%'.$data_hasilakhir.'%')->first();
            if ($check_empty != NULL) {
                $rekomendasi[] = DesaModel::where('nama','like','%'.$data_hasilakhir.'%')->first();
            }
        }
        $data['kabupaten'] = Cache::get('kabupaten');
        return view('index',[
            'rekomendasi' => $rekomendasi],$data);
    }

    public function kabupaten(Request $request) {
        $data['kabupaten'] = KabupatenModel::orderBy('nama','asc')->paginate(9);
        if ($request->ajax()) {
            $view = view('kabupatenPagination',$data)->render();
            return response()->json(['html'=>$view]);
        }
        return view('kabupaten',$data);
    }

    public function show_kabupaten(Request $request, $slug) {
        $data['kabupaten'] = KabupatenModel::where('slug', $slug)->first();
        $data['desa'] = DesaModel::where('id_kabupaten',$data['kabupaten']->id)->paginate(9);
        if ($request->ajax()) {
            $view = view('desaPagination',$data)->render();
            return response()->json(['html'=>$view]);
        }
        return view('show-kabupaten',$data);
    }

    public function show_desa(Request $request, $slug) {
        $data['desa'] = DesaModel::where('slug', $slug)->first();
        return view('show-desa',$data);
    }

    public function rekomendasi_desa(Request $request) {
        $data['kriteria'] = KriteriaModel::get();
        $data['jumlah_kriteria'] = KriteriaModel::count();
        $data['alternatif'] = AlternatifModel::get();
        $data['jumlah_alternatif'] = AlternatifModel::count();
        $records = TotalnilaiModel::with('kriteria','alternatif')->orderBy('id_alternatif')
        ->orderBy('id_kriteria')
        ->get();

        $total_nilai = 0;
        $i = 0;

        $rows = [];
        $columns = [];
        foreach($records as $index => $record) {
            if(!isset($rows[$record->alternatif->nama])) {
                $rows[$record->alternatif->nama] = [];
            }

            if(!in_array($record->kriteria->nama, $columns)) {
                $columns[] = $record->kriteria->nama;
            }

            $rows[$record->alternatif->nama][$record->kriteria->nama] = $record->nilai;
        }

        for ($i=0; $i <=($data['jumlah_kriteria'])-1 ; $i++) {
            $min[] = TotalnilaiModel::with('kriteria','alternatif')->where('id_kriteria',$records[$i]->id_kriteria)->orderBy('nilai','asc')->first();
        }
        for ($i=0; $i <=($data['jumlah_kriteria'])-1 ; $i++) {
            $max[] = TotalnilaiModel::with('kriteria','alternatif')->where('id_kriteria',$records[$i]->id_kriteria)->orderBy('nilai','desc')->first();
        }

        $j = 0;
        $mt_normalisasi = [];
        for ($i=0; $i <= ($records->count()-1) ; $i++) {
            $check_kriteria = KriteriaModel::where('id', $records[$i]->id_kriteria)->value('cost_benefit');
            if ($check_kriteria == "Benefit") {
                if (isset($max[$j]->nilai)) {
                    $mt_normalisasi[] = $records[$i]->nilai / $max[$j]->nilai;
                    $j++;
                    if ($j == count($max)) {
                        $j = 0;
                    }
                }
            } else {
                if (isset($min[$j]->nilai)) {
                    $mt_normalisasi[] = $min[$j]->nilai / $records[$i]->nilai;
                    $j++;
                    if ($j == count($min)) {
                        $j = 0;
                    }
                }
            }
        }

        $q = 0;
        $r = 0;
        for ($i = 0; $i <=$data['jumlah_alternatif']-1; $i++) {
            while ($q < count($mt_normalisasi)) {
                $mt_terbobot[] = $mt_normalisasi[$q] * $data['kriteria'][$r]->bobot;
                // $mt_terbobot[] = $mt_normalisasi[$q] * $data['kriteria'][$r]->bobot;
                $q++;
                $r++;
                if ($r == $data['jumlah_kriteria']) {
                    $r = 0;
                    break;
                }
            }
        }

        $x = 0;
        $j = 0;
        $count = 0;
        for ($i = 0; $i <=$data['jumlah_alternatif']-1; $i++) {
            $v[$i][0] = 0;
            while ($x < count($mt_terbobot)) {
                $v[$i][0] = $v[$i][0]+$mt_terbobot[$x];
                $x++;
                $count++;

                if ($count == $data['jumlah_kriteria']) {
                    $count = 0;
                    break;
                }
            }
            if ($i==0) {
                $hasil_akhir = array($data['alternatif'][$i]->nama=>round($v[$i][0], 3));
            } else {
                $hasil_akhir+= array($data['alternatif'][$i]->nama=>round($v[$i][0], 3));
            }
        }

        arsort($hasil_akhir);
        foreach ($hasil_akhir as $data_hasilakhir => $value){
            $check_empty = DesaModel::where('nama','like','%'.$data_hasilakhir.'%')->first();
            if ($check_empty != NULL) {
                $desa['desa'][] = DesaModel::where('nama','like','%'.$data_hasilakhir.'%')->first();
            }
        }
        return view('show_rekomendasidesa',[
            'hasil_akhir' => $hasil_akhir],$desa);
    }
}
