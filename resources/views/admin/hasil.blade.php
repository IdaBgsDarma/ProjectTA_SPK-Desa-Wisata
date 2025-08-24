@extends('admin.layouts.layouts')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Hasil Akhir</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Alternatif</th>
                                    <th><strong>V</strong></th>
                                    <th>Ranking</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($hasil_akhir as $data_hasilakhir => $value)
                                    <tr>
                                        <td><strong>{{ $data_hasilakhir }}</strong></td>
                                        <td>{{ $value }}</td>
                                        <td>{{ $i }}</td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Kriteria</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    @foreach ($kriteria as $data_kriteria)
                                        <th><strong>{{ $data_kriteria->nama }}</strong></th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Cost / Benefit</strong></td>
                                    @foreach ($kriteria as $data_kriteria)
                                        <td>{{ $data_kriteria->cost_benefit }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>Bobot</strong></td>
                                    @foreach ($kriteria as $data_kriteria)
                                        <td>{{ $data_kriteria->bobot }}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Matrix Alternatif - Kriteria</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Alternatif / Kriteria</th>
                                    @foreach ($kriteria as $data_kriteria)
                                        <th><strong>{{ $data_kriteria->nama }}</strong></th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $row => $rowcolumns)
                                <tr>
                                    <td><strong>{{ $row }}</strong></td>
                                    @foreach($rowcolumns as $kriteria2 => $nilai)
                                    <td>{{ $nilai }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Nilai minimum dan maximal kriteria</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    @foreach($columns as $column)
                                    <td><strong>{{ $column }}</strong></td>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Nilai Minimal</strong></td>
                                    @foreach ($min as $data_min)
                                        <td>{{ $data_min->nilai }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>Nilai Maximal</strong></td>
                                    @foreach ($max as $data_max)
                                        <td>{{ $data_max->nilai }}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Matrix Alternatif - Kriteria</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Alternatif / Kriteria</th>
                                    @foreach ($kriteria as $data_kriteria)
                                        <th><strong>{{ $data_kriteria->nama }}</strong></th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $x = 0;
                                    $j = 0;
                                @endphp
                                @for ($i = 0; $i <=($jumlah_alternatif)-1; $i++)
                                    <tr>
                                        <td><strong>{{ $alternatif[$i]->nama }}</strong></td>
                                        @while ($x < count($mt_normalisasi))
                                        <td>{{ round($mt_normalisasi[$x],3) }}</td>
                                        @php
                                            $x++;
                                            $j++;
                                        @endphp
                                        @if ($j == ($jumlah_kriteria))
                                            @php
                                                $j = 0;
                                            @endphp
                                            @break
                                        @endif
                                        @endwhile
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Matrix Terbobot</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Alternatif / Kriteria</th>
                                    @foreach ($kriteria as $data_kriteria)
                                        <th><strong>{{ $data_kriteria->nama }}</strong></th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $x = 0;
                                    $j = 0;
                                @endphp
                                @for ($i = 0; $i <=($jumlah_alternatif)-1; $i++)
                                    <tr>
                                        <td><strong>{{ $alternatif[$i]->nama }}</strong></td>
                                        @while ($x < count($mt_terbobot))
                                        <td>{{ round($mt_terbobot[$x],3) }}</td>
                                        @php
                                            $x++;
                                            $j++;
                                        @endphp
                                        @if ($j == ($jumlah_kriteria))
                                            @php
                                                $j = 0;
                                            @endphp
                                            @break
                                        @endif
                                        @endwhile
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
