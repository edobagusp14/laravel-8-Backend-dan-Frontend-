<?php

namespace App\Http\Controllers;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;


class KaryawanController extends Controller
{
    public function __construct()
    {
        // Pastikan user harus login untuk mengakses fungsi di controller ini
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Karyawan::paginate(5); // 10 data per halaman
        return response()->json($data);

        $karyawan = Karyawan::latest()->get();
        return response()->json([
            "success" => true,
            "message" => "data karyawan berhasil ditampilkan",
            "data" => $karyawan
        ]);
        
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:karyawans,nama',
            'jeniskelamin' => 'required',
            'jabatan'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Nama karyawan sudah ada.',
                'errors' => ['nama' => ['Nama karyawan harus unik']]
            ], 422);
            
        }
        $user = auth()->user();
        $karyawan = $user->karyawans()->create([
            'nama' => $request->nama,
            'jeniskelamin' => $request->jeniskelamin,
            'jabatan' => $request->jabatan,
            'alamat' => $request->alamat,
            'tgl_lahir' => $request->tgl_lahir,
        ]);
        return response()->json([
            "success" => true,
            "message" => "data karyawan berhasil ditambahkan",
            "data" => $karyawan
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $karyawan = Karyawan::find($id);
        return response()->json([
            "success" => true,
            "message" => "data karyawan berhasil ditampilkan",
            "data" => $karyawan
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $karyawan = Karyawan::find($id);
        $karyawan->nama = $request->nama;
        $karyawan->jeniskelamin = $request->jeniskelamin;
        $karyawan->jabatan = $request->jabatan;
        $karyawan->alamat = $request->alamat;
        $karyawan->tgl_lahir = $request->tgl_lahir;
        $karyawan->save();

        return response()->json([
            "success" => true,
            "message" => "data karyawan berhasil di ubah",
            "data" => $karyawan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::find($id);
        $karyawan->delete();

        return response()->json([
            "success" => true,
            "message" => "data karyawan berhasil dihapus",
            "data" => $karyawan
        ]);
    }


    public function search(Request $request)
    {
        $query = $request->input('query'); // Ambil parameter pencarian

        // Query data dengan pencarian fleksibel (LIKE)
        $karyawan = Karyawan::where('nama', 'LIKE', "%{$query}%")
            ->orWhere('jabatan', 'LIKE', "%{$query}%")
            ->orWhere('alamat', 'LIKE', "%{$query}%")
            ->get();

        // Kembalikan hasil dalam format JSON
        return response()->json(['data' => $karyawan]);
    }
}

