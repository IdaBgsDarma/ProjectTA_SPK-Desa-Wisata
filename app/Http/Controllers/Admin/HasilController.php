<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\KriteriaModel;
use App\Models\CripsModel;
use App\Models\AlternatifModel;
use App\Models\NilaiModel;
use App\Models\TotalnilaiModel;
class HasilController extends Controller
{
    public function index(Request $request) {
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
        return view('admin.hasil',[
            'records' => $records,
            'rows' => $rows,
            'columns' => $columns,
            'min' => $min,
            'max' => $max,
            'mt_normalisasi' => $mt_normalisasi,
            'mt_terbobot' => $mt_terbobot,
            'hasil_akhir' => $hasil_akhir,
        ],$data);
    }
}
