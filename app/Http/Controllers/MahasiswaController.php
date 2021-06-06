<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Mahasiswa_Matakuliah;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //fungsi eloquent menampilkan data menggunakan pagination
        // $mahasiswas = Mahasiswa::all(); // Mengambil semua isi tabel
        // $mahasiswas = Mahasiswa::paginate(5);
        $mahasiswas = Mahasiswa::with('kelas')->where([
            ['Nama', '!=', Null],
            [function ($query) use ($request) {
                if (($term = $request->term)) {
                    $query->orWhere('Nama', 'LIKE', '%' . $term . '%')->get();
                }
            }]
        ])
            ->orderBy("Nim", "asc")
            ->paginate(5);
        $pagination = Mahasiswa::orderBy('NIM', 'asc')->paginate(3);
        return view('mahasiswas.index', ['mahasiswas' => $mahasiswas, 'paginate'=>$pagination]);
    }
    public function create()
    {
        $kelas = Kelas::all();
        return view('mahasiswas.create', ['kelas' => $kelas]);
    }
    public function store(Request $request)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
        ]);
        $Mahasiswa = new Mahasiswa;
        $Mahasiswa->nim = $request->get('Nim');
        $Mahasiswa->nama = $request->get('Nama');
        $Mahasiswa->email = $request->get('email');
        $Mahasiswa->jurusan = $request->get('Jurusan');
        $Mahasiswa->tanggallahir = $request->get('tanggallahir');
        $Mahasiswa->no_handphone = $request->get('No_Handphone');

        $kelas = new kelas;
        $kelas->id=$request->get('Kelas');

        //fungsi eloquent untuk menambah data dengan relasi belongsto
        $Mahasiswa->kelas()->associate($kelas);
        $Mahasiswa->save();
        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('mahasiswas.index')
            ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }
    public function show($Nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        $Mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first();
        return view('mahasiswas.detail', compact('Mahasiswa'));
    }
    public function edit($Nim)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
        $Mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first();
        $kelas = Kelas::all();
        return view('mahasiswas.edit', compact('Mahasiswa', 'kelas'));
    }
    public function update(Request $request, $Nim)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
        ]);
        //fungsi eloquent untuk mengupdate data inputan kita
        $Mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first();
        $Mahasiswa->nim = $request->get('Nim');
        $Mahasiswa->nama = $request->get('Nama');
        $Mahasiswa->email = $request->get('email');
        $Mahasiswa->jurusan = $request->get('Jurusan');
        $Mahasiswa->tanggallahir = $request->get('tanggallahir');
        $Mahasiswa->no_handphone = $request->get('No_Handphone');

        $kelas = new kelas;
        $kelas->id=$request->get('Kelas');

        //fungsi eloquent untuk menambah data dengan relasi belongsto
        $Mahasiswa->kelas()->associate($kelas);
        $Mahasiswa->save();
        //jika data berhasil diupdate, akan kembali ke halaman utama
        return redirect()->route('mahasiswas.index')
            ->with('success', 'Mahasiswa Berhasil Diupdate');
    }
    public function destroy($Nim)
    {
        //fungsi eloquent untuk menghapus data
        Mahasiswa::find($Nim)->delete();
        return redirect()->route('mahasiswas.index')
            ->with('success', 'Mahasiswa Berhasil Dihapus');
    }

    public function nilai($nim)
    {
        $mahasiswa = Mahasiswa::with('kelas')->where('nim', $nim)->first();
        $nilai = Mahasiswa_Matakuliah::with('matakuliah')->where('mahasiswa_id', $mahasiswa->Nim)->get();
        return view('mahasiswas.khs', compact('mahasiswa', 'nilai'));
    }
};
