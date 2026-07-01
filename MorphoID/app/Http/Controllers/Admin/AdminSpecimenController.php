<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Specimen;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DatabaseExport;
use Illuminate\Support\Facades\DB;

class AdminSpecimenController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new DatabaseExport, 'MorphoID_Database_' . date('Y_m_d_His') . '.xlsx');
    }

    private function buildSpecimenTree($specimens, $parentId = null, $level = 0, &$result = [])
    {
        foreach ($specimens as $specimen) {
            if ($specimen->parent_id == $parentId) {
                $specimen->level = $level;
                $specimen->has_children = $specimens->filter(function($s) use ($specimen) { 
                    return $s->parent_id == $specimen->id; 
                })->count() > 0;
                $result[] = $specimen;
                $this->buildSpecimenTree($specimens, $specimen->id, $level + 1, $result);
            }
        }
        return $result;
    }

    public function admin()
    {
        $allSpecimens = Specimen::with('category')->orderBy('nama_spesimen', 'asc')->get();
        $result = [];
        $this->buildSpecimenTree($allSpecimens, null, 0, $result);
        $specimens = collect($result);

        $kategori_list = Category::whereNotNull('parent_id')->get();
        $induk_list = Category::whereNull('parent_id')->get();
        return view('admin.specimens', compact('specimens', 'kategori_list', 'induk_list'));
    }

    public function index()
    {
        $kategori_induk = Category::whereNull('parent_id')->with(['children', 'specimens'])->get();
        return view('index', compact('kategori_induk'));
    }

    public function store(Request $request)
  {
      try {
          DB::beginTransaction();
  
          // 1. Logik Upload Gambar (Sama untuk dua-dua form)
          $publicUrls = [];
          $supabaseUrl = env('SUPABASE_URL');
          $supabaseKey = env('SUPABASE_KEY');
          $cleanBaseUrl = str_replace('/rest/v1', '', rtrim($supabaseUrl, '/'));

          // Upload Cover Image
          $coverImageUrl = '-';
          if ($request->hasFile('cover_image')) {
              $file = $request->file('cover_image');
              $fileName = time() . '_cover_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
              $safeFileName = rawurlencode($fileName);
              $endpoint = $cleanBaseUrl . '/storage/v1/object/natey/uploads/' . $safeFileName;
  
              $response = \Illuminate\Support\Facades\Http::withHeaders([
                  'Authorization' => 'Bearer ' . $supabaseKey,
                  'apikey'        => $supabaseKey,
                  'Content-Type'  => $file->getMimeType(),
              ])->withBody(file_get_contents($file), $file->getMimeType())->post($endpoint);
  
              if ($response->failed()) {
                  throw new \Exception('Cover Image failed to upload: ' . $response->body());
              }
              $coverImageUrl = $cleanBaseUrl . '/storage/v1/object/public/natey/uploads/' . $safeFileName;
          }

          if ($request->hasFile('gambar')) {
              $files = is_array($request->file('gambar')) ? $request->file('gambar') : [$request->file('gambar')];
  
              foreach ($files as $file) {
                  $fileName = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                  $safeFileName = rawurlencode($fileName);
                  $endpoint = $cleanBaseUrl . '/storage/v1/object/natey/uploads/' . $safeFileName;
  
                  $response = \Illuminate\Support\Facades\Http::withHeaders([
                      'Authorization' => 'Bearer ' . $supabaseKey,
                      'apikey'        => $supabaseKey,
                      'Content-Type'  => $file->getMimeType(),
                  ])->withBody(file_get_contents($file), $file->getMimeType())->post($endpoint);
  
                  if ($response->failed()) {
                      throw new \Exception('Image failed to upload: ' . $response->body());
                  }
                  $publicUrls[] = $cleanBaseUrl . '/storage/v1/object/public/natey/uploads/' . $safeFileName;
              }
          }

          // Jika tak ada cover image (cthnya form lama), ambil gambar pertama dalam galeri
          if ($coverImageUrl === '-' && count($publicUrls) > 0) {
              $coverImageUrl = $publicUrls[0];
          }
          $publicUrl = $coverImageUrl;

        // 2. CHECK: Adakah ni SUB-SPESIMEN?
        if ($request->filled('specimen_parent_id')) {
            // Automatik warisi Kategori dari Specimen Induk
            $parentSpecimen = Specimen::find($request->specimen_parent_id);
            if ($parentSpecimen) {
                $bapak_id = $parentSpecimen->category_id;
            } else {
                throw new \Exception("Parent specimen not found.");
            }
        } else {
            // B. LOGIK BORANG UTAMA: Proses Atuk & Bapak (Kod asal kau)

            // Tentukan ATUK
            $atuk_id = null;
            if (str_starts_with($request->parent_id, 'NEW_')) {
                $nama_kategori = substr($request->parent_id, 4);
                $atuk = Category::whereRaw('LOWER(nama_kategori) = LOWER(?)', [$nama_kategori])->whereNull('parent_id')->first();
                if (!$atuk) {
                    $atuk = Category::create(['nama_kategori' => $nama_kategori, 'parent_id' => null]);
                }
                $atuk_id = $atuk->id;
            } elseif ($request->parent_id === 'new' && !empty($request->kad_besar_baru)) {
                $atuk = Category::create(['nama_kategori' => trim($request->kad_besar_baru), 'parent_id' => null]);
                $atuk_id = $atuk->id;
            } else {
                $atuk_id = $request->parent_id;
            }

            // Tentukan BAPAK
            $nama_kategori_input = trim($request->kategori);
            $nama_kategori_baru_input = trim($request->Category_baru);

            if ($nama_kategori_input === 'new_Category' || !empty($nama_kategori_baru_input)) {
                $bapak = Category::create(['nama_kategori' => $nama_kategori_baru_input, 'parent_id' => $atuk_id]);
                $bapak_id = $bapak->id;
            } elseif (!empty($nama_kategori_input)) {
                $bapak = Category::whereRaw('LOWER(nama_kategori) = LOWER(?)', [$nama_kategori_input])->first();
                if ($bapak) {
                    if ($bapak->parent_id != $atuk_id) { $bapak->parent_id = $atuk_id; $bapak->save(); }
                    $bapak_id = $bapak->id;
                } else {
                    $bapak = Category::create(['nama_kategori' => $nama_kategori_input, 'parent_id' => $atuk_id]);
                    $bapak_id = $bapak->id;
                }
            } else {
                throw new \Exception("Please select or create a Parent Category.");
            }
        }

        // 3. Simpan Spesimen (Sama untuk dua-dua form)
        $specimen = new Specimen();
        $specimen->nama_spesimen = $request->nama_spesimen;
        $specimen->penerangan = $request->Description;
        $specimen->ciri_ciri = $request->ciri_ciri;
        $specimen->category_id = $bapak_id;
        $specimen->gambar = $publicUrl;
        $specimen->galeri = $publicUrls;

        // Kalau sub-spesimen, set parent_id
        if ($request->filled('specimen_parent_id')) {
            $specimen->parent_id = $request->specimen_parent_id;
        }

        $specimen->created_at = now();
        $specimen->updated_at = now();

        $specimen->save();

        DB::commit();
        return back()->with('success', 'Awesome! Specimen successfully recorded.');

    } catch (\Exception $e) {
        DB::rollBack();
        // Kalau error, kau boleh tahu sebab apa kat sini
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}

    public function explore(Request $request)
    {
        $query = Specimen::whereNull('parent_id');
        if ($request->has('kategori')) {
            $kategori_nama = $request->kategori;
            $query->whereHas('category', function($q) use ($kategori_nama) {
                $q->where('nama_kategori', $kategori_nama);
            });
        }
        $specimens = $query->get();
        $semua_ciri = $specimens->pluck('ciri_ciri')->toArray();
        $tags_array = [];
        foreach ($semua_ciri as $ciri) {
            if (!empty($ciri)) {
                $pecahan = explode(',', $ciri);
                foreach ($pecahan as $tag) { $tags_array[] = trim($tag); }
            }
        }
        $senarai_tag = array_unique($tags_array);
        sort($senarai_tag);
        $kategori_semasa = $request->kategori ?? 'All';
        return view('explore', compact('specimens', 'senarai_tag', 'kategori_semasa'));
    }

    public function show($id)
    {
        $item_semasa = Specimen::with(['category', 'children'])->findOrFail($id);
        $breadcrumbs = [];
        array_unshift($breadcrumbs, (object)['nama' => $item_semasa->nama_spesimen]);
        if ($item_semasa->category) {
            array_unshift($breadcrumbs, (object)['nama' => $item_semasa->category->nama_kategori, 'url' => '/explore?kategori='.$item_semasa->category->nama_kategori]);
            if ($item_semasa->category->parent) {
                array_unshift($breadcrumbs, (object)['nama' => $item_semasa->category->parent->nama_kategori]);
            }
        }
        $sub_items = $item_semasa->children;
        return view('show_specimen', compact('item_semasa', 'sub_items', 'breadcrumbs'));
    }

    public function destroy(Request $request, $id)
    {
        $specimen = Specimen::find($id);
        if ($specimen) {
            $specimen->delete();
        }
        if ($request->has('redirect_to_manage')) {
            return redirect('/admin')->with('success', 'Specimen successfully deleted.');
        }
        return back()->with('success', 'Specimen successfully deleted.');
    }

public function compare(Request $request)
{
    // Gunakan 'with' untuk mengambil sub-spesimen (children)
    $specimenA = Specimen::with('children')->findOrFail($request->id_a);
    $specimenB = Specimen::with('children')->findOrFail($request->id_b);

    // Sekarang, ambil sub-spesimen dari model yang dah di-load
    $subA = $specimenA->children;
    $subB = $specimenB->children;

    return view('compare', compact('specimenA', 'specimenB', 'subA', 'subB'));
}

    public function edit($id)
    {
        $specimen = Specimen::findOrFail($id);
        $kategori_list = Category::whereNotNull('parent_id')->get();
        $induk_list = Category::whereNull('parent_id')->get();
        return view('admin.edit_specimen', compact('specimen', 'kategori_list', 'induk_list'));
    }

    public function update(Request $request, $id)
{
    try {
        $specimen = Specimen::findOrFail($id);

        // 1. Update maklumat asas
        $specimen->nama_spesimen = $request->nama_spesimen;
        $specimen->penerangan = $request->Description;
        $specimen->ciri_ciri = $request->ciri_ciri;

        // 2 & 3. Update Kad Besar (Atuk/Parent) & Kategori (Bapak)
        if ($request->has('parent_id')) {
            $atuk_id = null;
            if ($request->parent_id === 'new' && !empty($request->kad_besar_baru)) {
                $atuk = Category::create(['nama_kategori' => trim($request->kad_besar_baru), 'parent_id' => null]);
                $atuk_id = $atuk->id;
            } else {
                $atuk_id = $request->parent_id;
            }
            $specimen->parent_id = $atuk_id;

            // Kategori (Bapak) depend on Atuk
            if ($request->has('kategori')) {
                $nama_kategori_input = trim($request->kategori);
                $nama_kategori_baru_input = trim($request->Category_baru);

                if ($nama_kategori_input === 'new_Category' || !empty($nama_kategori_baru_input)) {
                    $bapak = Category::create(['nama_kategori' => $nama_kategori_baru_input, 'parent_id' => $atuk_id]);
                    $specimen->category_id = $bapak->id;
                } else {
                    $specimen->category_id = $nama_kategori_input;
                }
            }
        } else {
            // Fallback kalau tak ubah parent_id langsung
            if ($request->has('kategori')) {
                $specimen->category_id = $request->kategori;
            }
        }

        // 4. Update Gambar
        if ($request->has('remove_image') && $request->remove_image == '1') {
            $specimen->gambar = null;
            $specimen->galeri = [];
        } else {
            $supabaseUrl = env('SUPABASE_URL');
            $supabaseKey = env('SUPABASE_KEY');
            
            if (!$supabaseUrl || !$supabaseKey) {
                throw new \Exception("SUPABASE_URL or SUPABASE_KEY is missing from environment variables.");
            }
            
            $cleanBaseUrl = str_replace('/rest/v1', '', rtrim($supabaseUrl, '/'));
            
            $coverImageUrl = null;

            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $fileName = time() . '_cover_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $safeFileName = rawurlencode($fileName);
                $endpoint = $cleanBaseUrl . '/storage/v1/object/natey/uploads/' . $safeFileName;

                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . $supabaseKey,
                    'apikey'        => $supabaseKey,
                    'Content-Type'  => $file->getMimeType(),
                ])->withBody(file_get_contents($file), $file->getMimeType())->post($endpoint);

                if ($response->failed()) {
                    throw new \Exception('Cover Image failed to upload: ' . $response->body());
                }
                $coverImageUrl = $cleanBaseUrl . '/storage/v1/object/public/natey/uploads/' . $safeFileName;
                $specimen->gambar = $coverImageUrl;
            }

            if ($request->hasFile('gambar')) {
                $files = is_array($request->file('gambar')) ? $request->file('gambar') : [$request->file('gambar')];
                $publicUrls = [];
        
                foreach ($files as $file) {
                    $fileName = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    $safeFileName = rawurlencode($fileName);
                    $endpoint = $cleanBaseUrl . '/storage/v1/object/natey/uploads/' . $safeFileName;
        
                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'Authorization' => 'Bearer ' . $supabaseKey,
                        'apikey'        => $supabaseKey,
                        'Content-Type'  => $file->getMimeType(),
                    ])->withBody(file_get_contents($file), $file->getMimeType())->post($endpoint);
        
                    if ($response->failed()) {
                        throw new \Exception('Image failed to upload: ' . $response->body());
                    }
                    $publicUrls[] = $cleanBaseUrl . '/storage/v1/object/public/natey/uploads/' . $safeFileName;
                }
                
                if (empty($specimen->gambar) && count($publicUrls) > 0) {
                    $specimen->gambar = $publicUrls[0];
                }
                $specimen->galeri = $publicUrls;
            }
        }

        $specimen->save();
        return redirect('/admin')->with('success', 'Successfully updated!');
        
    } catch (\Exception $e) {
        return back()->with('error', 'Update Failed: ' . $e->getMessage());
    }
}
    //Tambah fungsi ni tepat sebelum fungsi updateCategory
    public function storeCategory(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id' // Id Atuk/Induk jika dipilih
        ], [
            'nama_kategori.required' => 'Please enter Category/Sub name!',
        ]);

        // 2. Simpan kategori baharu ke dalam database
        Category::create([
            'nama_kategori' => trim($request->nama_kategori),
            'parent_id' => $request->parent_id // Kalau tak pilih, dia akan jadi null (Kategori Induk)
        ]);

        // 3. Hantar mesej berjaya
        return back()->with('success', 'Awesome! New Category/Sub successfully saved.');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->nama_kategori = $request->nama_kategori;
        $category->save();
        return back()->with('success', 'Category name successfully updated!');
    }

    public function destroyCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            Specimen::where('category_id', $id)->delete();
            $category->delete();
        }
        return back()->with('success', 'Category and all its contents successfully deleted.');
    }
    
    // ==========================================
    // COMMENTS
    // ==========================================
    
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);
        
        $user = auth()->guard('admin')->user() ?? auth()->guard('web')->user();
        if (!$user) {
            return back()->with('error', 'You must be logged in to comment.');
        }

        // Determine the role based on which guard was used
        $role = auth()->guard('admin')->check() ? 'Admin' : (ucfirst($user->role) ?? 'Pelajar');
        
        Comment::create([
            'specimen_id' => $id,
            'nama' => $user->name,
            'role' => $role,
            'comment' => $request->comment,
            'likes' => 0
        ]);
        
        return back()->with('success', 'Comment posted successfully!');
    }
    
    public function likeComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->increment('likes');
        
        return response()->json(['success' => true, 'likes' => $comment->likes]);
    }
    
    public function updateComment(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string']);
        $comment = Comment::findOrFail($id);
        
        $user = auth()->guard('admin')->user() ?? auth()->guard('web')->user();
        if (!$user) {
            return back()->with('error', 'Unauthorized.');
        }

        // Allow if Admin OR if the user's name matches the commenter's name
        if (auth()->guard('admin')->check() || $user->name === $comment->nama) {
            $comment->update(['comment' => $request->comment]);
            return back()->with('success', 'Comment updated successfully.');
        }
        
        return back()->with('error', 'You do not have permission to edit this comment.');
    }

    public function destroyComment($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $user = auth()->guard('admin')->user() ?? auth()->guard('web')->user();
            if (auth()->guard('admin')->check() || ($user && $user->name === $comment->nama)) {
                $comment->delete();
                return back()->with('success', 'Comment deleted successfully.');
            }
            return back()->with('error', 'You do not have permission to delete this comment.');
        }
        return back()->with('error', 'Comment not found.');
    }
}
