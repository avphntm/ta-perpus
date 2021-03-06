<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Buku;

class bukuController extends Controller
{

    public function __construct()
    {
        # code...
        $this->middleware('auth');
    }

    public function index()
    {
        $bukux = DB::table('buku')->orderBy('judul')
            ->join('kategori', 'kategori.id_category', '=', 'buku.jenis_id')
            ->get();
        $category = DB::table('kategori')->get();
        $last = DB::table('buku')->get()->count();
        $idnya = 0;
        if ($last == 0) {
            # code...
            $idnya = 1;
        } else {
            $idnya = DB::table('buku')->orderBy('id_buku', 'desc')->value('id_buku');
        }
        //dd($idnya);
        return view('isi.viewBuku', compact('bukux', 'category', 'idnya'));
    }

    public function insert()
    {
        $param =  json_decode(request()->getContent(), true);
        $input = array(
            'kodebuku' => $param['kode'],
            'jenis_id' => $param['jenis_id'],
            'judul' => $param['judul'],
            'penulis' => $param['penulis'],
            'penerbit' => $param['penerbit'],
            'nmcat' => $param['nmcat']
        );

        $result = DB::table('buku')->insert($input);
    }

    public function editBuku($id)
    {
        $result = Buku::find($id);
        $cat = DB::table('kategori')->get();

        return view('isi.editBuku', compact('cat', 'result'));
    }

    public function update(Request $request, Buku $id)
    {
        # code...
        $id->update($request->only('kodebuku', 'jenis_id', 'judul', 'penulis', 'penerbit'));
        return redirect('viewBuku');
    }

    public function delete($id)
    {
        $bukux = Buku::find($id);
        $bukux->delete();
        return back();
    }
}
