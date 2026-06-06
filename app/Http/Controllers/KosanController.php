<?php

namespace App\Http\Controllers;

use App\Models\Kosan;
use Illuminate\Http\Request;

class KosanController extends Controller
{
    public function dashboard()
    {
        $totalKosan        = Kosan::count();
        $dekatUsu          = Kosan::dekatUsu()->count();
        $dekatPolmed       = Kosan::dekatPolmed()->count();
        $dekatKeduanya     = Kosan::dekatUsu()->dekatPolmed()->count();
        $kosanTerbaru      = Kosan::orderBy('id', 'desc')->take(5)->get();
        $statistikKategori = Kosan::selectRaw('kategori_kosan, COUNT(*) as total')
                                ->groupBy('kategori_kosan')
                                ->get();

        return view('dashboard', compact(
            'totalKosan', 'dekatUsu', 'dekatPolmed',
            'dekatKeduanya', 'kosanTerbaru', 'statistikKategori'
        ));
    }

    public function index(Request $request)
    {
        $query = Kosan::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kosan', 'ilike', "%{$search}%")
                  ->orWhere('alamat_lengkap', 'ilike', "%{$search}%")
                  ->orWhere('kecamatan', 'ilike', "%{$search}%")
                  ->orWhere('kelurahan', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('kampus')) {
            if ($request->kampus === 'USU') {
                $query->dekatUsu();
            } elseif ($request->kampus === 'POLMED') {
                $query->dekatPolmed();
            } elseif ($request->kampus === 'Keduanya') {
                $query->dekatUsu()->dekatPolmed();
            }
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_kosan', $request->kategori);
        }

        if ($request->filled('wifi') && $request->wifi === 'Ada') {
            $query->where('wifi', 'Ada');
        }
        if ($request->filled('ac') && $request->ac === 'Ada') {
            $query->where('ac', 'Ada');
        }
        if ($request->filled('parkir') && $request->parkir === 'Ada') {
            $query->where('parkir_motor', 'Ada');
        }

        $sort  = $request->get('sort', 'nama_kosan');
        $order = $request->get('order', 'asc');
        if (in_array($sort, ['nama_kosan', 'rating', 'harga_sewa_bulan', 'jarak_ke_kampus'])) {
            $query->orderBy($sort, $order);
        } else {
            $query->orderBy('nama_kosan', 'asc');
        }

        $kosanList = $query->paginate(15)->withQueryString();

        return view('kosan.index', compact('kosanList'));
    }

    public function show($id)
    {
        $kosan = Kosan::findOrFail($id);
        return view('kosan.show', compact('kosan'));
    }

    public function maps(Request $request)
    {
        $query = Kosan::query();

        if ($request->filled('search')) {
            $query->where('nama_kosan', 'ilike', "%{$request->search}%");
        }

        if ($request->filled('kampus')) {
            if ($request->kampus === 'USU') {
                $query->dekatUsu();
            } elseif ($request->kampus === 'POLMED') {
                $query->dekatPolmed();
            } elseif ($request->kampus === 'Keduanya') {
                $query->dekatUsu()->dekatPolmed();
            }
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_kosan', $request->kategori);
        }

        $kosanList    = $query->get();
        $kosanGeoJson = $kosanList->map(function ($kosan) {
            return [
                'id'             => $kosan->id,
                'nama_kosan'     => $kosan->nama_kosan,
                'alamat_lengkap' => $kosan->alamat_lengkap,
                'kategori_kosan' => $kosan->kategori_kosan,
                'label_kampus'   => $kosan->label_kampus,
                'warna'          => $kosan->warna_peta,
                'rating'         => $kosan->rating,
                'harga'          => $kosan->harga_sewa_bulan,
                'wifi'           => $kosan->wifi,
                'ac'             => $kosan->ac,
                'parkir'         => $kosan->parkir_motor,
                'jarak'          => $kosan->jarak_ke_kampus,
                'latitude'       => (float) $kosan->latitude,
                'longitude'      => (float) $kosan->longitude,
            ];
        });

        return view('maps', compact('kosanList', 'kosanGeoJson'));
    }

    public function create()
    {
        return view('kosan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kosan'        => 'required|string|max:100',
            'deskripsi_singkat' => 'nullable|string',
            'jarak_ke_kampus'   => 'nullable|string|max:50',
            'latitude'          => 'required|numeric',
            'longitude'         => 'required|numeric',
            'alamat_lengkap'    => 'required|string|max:200',
            'kecamatan'         => 'nullable|string|max:100',
            'kelurahan'         => 'nullable|string|max:100',
            'kategori_kosan'    => 'required|string|max:50',
            'target_penghuni'   => 'nullable|string|max:100',
            'harga_sewa_bulan'  => 'nullable|string|max:50',
            'keterangan_harga'  => 'nullable|string|max:200',
            'no_telp_wa'        => 'nullable|string|max:50',
            'rating'            => 'nullable|numeric|min:0|max:5',
            'kasur_lemari'      => 'nullable|in:Ada,Tidak',
            'meja_kursi'        => 'nullable|in:Ada,Tidak',
            'kamar_mandi_dalam' => 'nullable|in:Ada,Tidak',
            'ac'                => 'nullable|in:Ada,Tidak',
            'air_panas'         => 'nullable|in:Ada,Tidak',
            'wifi'              => 'nullable|in:Ada,Tidak',
            'parkir_motor'      => 'nullable|in:Ada,Tidak',
            'dapur_bersama'     => 'nullable|in:Ada,Tidak',
            'laundry'           => 'nullable|in:Ada,Tidak',
            'cctv'              => 'nullable|in:Ada,Tidak',
        ]);

        $fasilitas = ['kasur_lemari','meja_kursi','kamar_mandi_dalam','ac','air_panas',
                      'wifi','parkir_motor','dapur_bersama','laundry','cctv'];
        foreach ($fasilitas as $f) {
            $validated[$f] = $validated[$f] ?? 'Tidak';
        }

        $validated['no'] = Kosan::max('no') + 1;

        Kosan::create($validated);

        return redirect()->route('kosan.index')
            ->with('success', "Kosan '{$validated['nama_kosan']}' berhasil ditambahkan!");
    }

    public function destroy($id)
    {
        $kosan = Kosan::findOrFail($id);
        $nama  = $kosan->nama_kosan;
        $kosan->delete();

        return redirect()->route('kosan.index')
            ->with('success', "Kosan '{$nama}' berhasil dihapus.");
    }

    public function apiKosan(Request $request)
    {
        $query = Kosan::query();

        if ($request->filled('search')) {
            $query->where('nama_kosan', 'ilike', "%{$request->search}%");
        }
        if ($request->filled('kampus')) {
            if ($request->kampus === 'USU') $query->dekatUsu();
            elseif ($request->kampus === 'POLMED') $query->dekatPolmed();
            elseif ($request->kampus === 'Keduanya') $query->dekatUsu()->dekatPolmed();
        }
        if ($request->filled('kategori')) {
            $query->where('kategori_kosan', $request->kategori);
        }

        $data = $query->get()->map(function ($k) {
            return [
                'id'             => $k->id,
                'nama_kosan'     => $k->nama_kosan,
                'alamat_lengkap' => $k->alamat_lengkap,
                'kategori_kosan' => $k->kategori_kosan,
                'label_kampus'   => $k->label_kampus,
                'warna'          => $k->warna_peta,
                'rating'         => $k->rating,
                'harga'          => $k->harga_sewa_bulan,
                'wifi'           => $k->wifi,
                'ac'             => $k->ac,
                'parkir'         => $k->parkir_motor,
                'jarak'          => $k->jarak_ke_kampus,
                'latitude'       => (float) $k->latitude,
                'longitude'      => (float) $k->longitude,
            ];
        });

        return response()->json(['data' => $data, 'total' => $data->count()]);
    }
}