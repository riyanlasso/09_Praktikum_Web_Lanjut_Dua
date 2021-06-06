@extends('mahasiswas.layout')

@section('content')
<div class="container">
    <div class="text-center">
        <h3>JURUSAN TEKNOLOGI INFORMASI-POLITEKNIK NEGERI MALANG</h3>
        <br>
        <h2>KARTU HASIL STUDI (KHS)</h2>
        <br>
    </div>
    <div class="my-3">
        <p><strong>Nama :</strong> {{$mahasiswa->Nama}}</p>
        <p><strong>NIM :</strong> {{$mahasiswa->Nim}}</p>
        <p><strong>Kelas :</strong> {{$mahasiswa->kelas->nama_kelas}}</p>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Mata Kuliah</th>
                <th scope="col">SKS</th>
                <th scope="col">Semester</th>
                <th scope="col">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nilai as $khs)
            <tr>
                <th>{{__($khs->matakuliah->nama_matkul)}}</th>
                <th>{{__($khs->matakuliah->sks)}}</th>
                <th>{{__($khs->matakuliah->semester)}}</th>
                <td>{{__($khs->nilai)}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection