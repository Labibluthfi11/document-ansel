<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // List jenis & departemen
    private $types = [
        'sop', 'wi', 'form', 'internal memo', 'skm', 'manual book', 'opl'
    ];

    private $departments = [
        'PRODUKSI',
        'HRGA',
        'PPIC',
        'WAREHOUSE',
        'QUALITY AND DEVELOPMENT',
        'FINANCE',
        'PURCHASING',
        'SALES AND MARKETING',
    ];

    public function index(Request $request)
    {
        $query = Document::query();

        if ($request->filled('department')) {
            $query->where('department', 'like', '%' . $request->department . '%');
        }
        if ($request->filled('document_number')) {
            $query->where('document_number', 'like', '%' . $request->document_number . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('document_date')) {
            $query->where('document_date', $request->document_date);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        $types = $this->types;
        $departments = $this->departments;
        return view('documents.create', compact('types', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_number' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', $this->types),
            'department' => 'required|in:' . implode(',', $this->departments),
            'document_date' => 'required|date',
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'status' => 'required|in:berlaku,tidak_berlaku',
        ]);

        $path = $request->file('file')->store('documents');

        Document::create([
            'document_number' => $request->document_number,
            'department' => $request->department,
            'document_date' => $request->document_date,
            'name' => $request->name,
            'type' => $request->type,
            'file_path' => $path,
            'status' => $request->status,
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil ditambahkan');
    }

    public function dashboard()
    {
        $jenisList = $this->types;

        // Ambil semua departemen unik
        $departments = \App\Models\Document::select('department')->distinct()->pluck('department');

        // Siapkan struktur statistik per departemen & jenis
        $departmentStats = [];
        foreach ($departments as $dept) {
            $departmentStats[$dept] = [
                'total' => \App\Models\Document::where('department', $dept)->count(),
                'berlaku' => \App\Models\Document::where('department', $dept)->where('status', 'berlaku')->count(),
                'tidak_berlaku' => \App\Models\Document::where('department', $dept)->where('status', 'tidak_berlaku')->count(),
            ];
            foreach ($jenisList as $jenis) {
                $departmentStats[$dept][$jenis] = \App\Models\Document::where('department', $dept)->where('type', $jenis)->count();
            }
        }

        // Statistik keseluruhan
        $totalDocument = \App\Models\Document::count();
        $totalBerlaku = \App\Models\Document::where('status', 'berlaku')->count();
        $totalTidakBerlaku = \App\Models\Document::where('status', 'tidak_berlaku')->count();

        // Statistik per jenis dokumen keseluruhan (seluruh departemen)
        $jenisStats = [];
        foreach ($jenisList as $jenis) {
            $jenisStats[$jenis] = \App\Models\Document::where('type', $jenis)->count();
        }

        return view('dashboard', [
            'departmentStats' => $departmentStats,
            'jenisList' => $jenisList,
            'totalDocument' => $totalDocument,
            'totalBerlaku' => $totalBerlaku,
            'totalTidakBerlaku' => $totalTidakBerlaku,
            'jenisStats' => $jenisStats,
        ]);
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        $types = $this->types;
        $departments = $this->departments;
        return view('documents.edit', compact('document', 'types', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $request->validate([
            'document_number' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', $this->types),
            'department' => 'required|in:' . implode(',', $this->departments),
            'document_date' => 'required|date',
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'status' => 'required|in:berlaku,tidak_berlaku',
        ]);

        if ($request->hasFile('file')) {
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }
            $path = $request->file('file')->store('documents');
            $document->file_path = $path;
        }

        $document->update([
            'document_number' => $request->document_number,
            'department' => $request->department,
            'document_date' => $request->document_date,
            'name' => $request->name,
            'type' => $request->type,
            'status' => $request->status,
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diperbarui');
    }

    public function download($id)
    {
        $doc = Document::findOrFail($id);

        if ($doc->status != 'berlaku') {
            abort(403, 'Dokumen tidak berlaku tidak bisa diunduh');
        }

        return Storage::download($doc->file_path, $doc->name . '.' . pathinfo($doc->file_path, PATHINFO_EXTENSION));
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus');
    }
}
