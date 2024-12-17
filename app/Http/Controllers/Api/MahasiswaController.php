<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="API Mahasiswa",
 *     version="1.0.0",
 *     description="Dokumentasi API Mahasiswa dengan Bearer Token"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class MahasiswaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/mahasiswa",
     *     tags={"Mahasiswa"},
     *     summary="Ambil semua data mahasiswa",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Berhasil mengambil data"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        $data = Mahasiswa::all();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'data kosong',
                'pesan' => 'Tidak ada data mahasiswa.'
            ], 200);
        }

        return response()->json([
            'status' => 'sukses',
            'pesan' => 'Data mahasiswa berhasil diambil.',
            'data' => $data
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/mahasiswa",
     *     tags={"Mahasiswa"},
     *     summary="Tambah data mahasiswa",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nim", "nama_mahasiswa", "fakultas", "jurusan"},
     *             @OA\Property(property="nim", type="string", example="12345678"),
     *             @OA\Property(property="nama_mahasiswa", type="string", example="Rizal"),
     *             @OA\Property(property="fakultas", type="string", example="Teknik"),
     *             @OA\Property(property="jurusan", type="string", example="Informatika")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Berhasil menambahkan data"),
     *     @OA\Response(response=400, description="Validasi gagal"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswas',
            'nama_mahasiswa' => 'required',
            'fakultas' => 'required',
            'jurusan' => 'required',
        ]);

        $mahasiswa = Mahasiswa::create($request->all());

        return response()->json([
            'status' => 'sukses',
            'pesan' => 'Data mahasiswa berhasil ditambahkan.',
            'data' => $mahasiswa
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/mahasiswa/{id}",
     *     tags={"Mahasiswa"},
     *     summary="Ambil data mahasiswa berdasarkan ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID mahasiswa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Berhasil mengambil data"),
     *     @OA\Response(response=404, description="Data tidak ditemukan"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show($id)
    {
        $mahasiswa = Mahasiswa::find($id);

        if (!$mahasiswa) {
            return response()->json([
                'status' => 'data tidak ada',
                'pesan' => 'Data mahasiswa tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'sukses',
            'pesan' => 'Data mahasiswa berhasil diambil.',
            'data' => $mahasiswa
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/mahasiswa/{id}",
     *     tags={"Mahasiswa"},
     *     summary="Perbarui data mahasiswa",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID mahasiswa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_mahasiswa", type="string", example="Rizal Baru"),
     *             @OA\Property(property="fakultas", type="string", example="Ekonomi"),
     *             @OA\Property(property="jurusan", type="string", example="Manajemen")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Berhasil memperbarui data"),
     *     @OA\Response(response=404, description="Data tidak ditemukan"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::find($id);

        if (!$mahasiswa) {
            return response()->json([
                'status' => 'data tidak ada',
                'pesan' => 'Data mahasiswa tidak ditemukan.',
            ], 404);
        }

        $mahasiswa->update($request->all());

        return response()->json([
            'status' => 'sukses',
            'pesan' => 'Data mahasiswa berhasil diperbarui.',
            'data' => $mahasiswa
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/mahasiswa/{id}",
     *     tags={"Mahasiswa"},
     *     summary="Hapus data mahasiswa",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID mahasiswa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Berhasil menghapus data"),
     *     @OA\Response(response=404, description="Data tidak ditemukan"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::find($id);

        if (!$mahasiswa) {
            return response()->json([
                'status' => 'data tidak ada',
                'pesan' => 'Data mahasiswa tidak ditemukan.',
            ], 404);
        }

        $mahasiswa->delete();

        return response()->json([
            'status' => 'sukses',
            'pesan' => 'Data mahasiswa berhasil dihapus.'
        ], 200);
    }
}
