<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\ActivityLog;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader\PdfReaderException;

class DocumentController extends Controller
{
    private $types = [
        'sop', 'wi', 'form', 'internal memo', 'skm', 'manual book', 'opl'
    ];

    private $departments = [
        'PRODUKSI', 'HRGA', 'PPIC', 'WAREHOUSE',
        'QUALITY AND DEVELOPMENT', 'FINANCE', 'PURCHASING', 'SALES AND MARKETING',
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

        $documents = $query->orderBy('department', 'asc')->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'view_index',
            'description' => 'Melihat daftar dokumen',
            'ip_address' => request()->ip(),
        ]);

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $types = $this->types;
        $departments = $this->departments;
        return view('documents.create', compact('types', 'departments'));
    }

    public function store(Request $request)
    {
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'document_number' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', $this->types),
            'department' => 'required|in:' . implode(',', $this->departments),
            'document_date' => 'required|date',
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'status' => 'required|in:berlaku,tidak_berlaku',
        ]);

        // SIMPAN FILE KE public/documents
        $filename = uniqid() . '_' . $request->file('file')->getClientOriginalName();
        $request->file('file')->move(public_path('documents'), $filename);
        $path = 'documents/' . $filename;

        $doc = Document::create([
            'document_number' => $request->document_number,
            'department' => $request->department,
            'document_date' => $request->document_date,
            'name' => $request->name,
            'type' => $request->type,
            'file_path' => $path,
            'status' => $request->status,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'description' => "Upload dokumen: {$doc->name}",
            'document_id' => $doc->id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil ditambahkan');
    }

    public function dashboard()
    {
        $jenisList = $this->types;
        $departments = Document::select('department')->distinct()->pluck('department');

        $departmentStats = [];
        foreach ($departments as $dept) {
            $departmentStats[$dept] = [
                'total' => Document::where('department', $dept)->count(),
                'berlaku' => Document::where('department', $dept)->where('status', 'berlaku')->count(),
                'tidak_berlaku' => Document::where('department', $dept)->where('status', 'tidak_berlaku')->count(),
            ];
            foreach ($jenisList as $jenis) {
                $departmentStats[$dept][$jenis] = Document::where('department', $dept)->where('type', $jenis)->count();
            }
        }
        ksort($departmentStats);

        $totalDocument = Document::count();
        $totalBerlaku = Document::where('status', 'berlaku')->count();
        $totalTidakBerlaku = Document::where('status', 'tidak_berlaku')->count();

        $jenisStats = [];
        foreach ($jenisList as $jenis) {
            $jenisStats[$jenis] = Document::where('type', $jenis)->count();
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'view_dashboard',
            'description' => 'Melihat dashboard dokumen',
            'ip_address' => request()->ip(),
        ]);

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
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $document = Document::findOrFail($id);
        $types = $this->types;
        $departments = $this->departments;
        return view('documents.edit', compact('document', 'types', 'departments'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
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
            // Hapus file lama dari public/documents
            if (file_exists(public_path($document->file_path))) {
                unlink(public_path($document->file_path));
            }
            $filename = uniqid() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('documents'), $filename);
            $document->file_path = 'documents/' . $filename;
        }

        $document->update([
            'document_number' => $request->document_number,
            'department' => $request->department,
            'document_date' => $request->document_date,
            'name' => $request->name,
            'type' => $request->type,
            'status' => $request->status,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'description' => "Update dokumen: {$document->name}",
            'document_id' => $document->id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diperbarui');
    }

    public function download($id)
    {
        $doc = Document::findOrFail($id);

        if ($doc->status != 'berlaku') {
            abort(403, 'Dokumen tidak berlaku tidak bisa diunduh');
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'download',
            'description' => "Download dokumen: {$doc->name}",
            'document_id' => $doc->id,
            'ip_address' => request()->ip(),
        ]);

        // Download file langsung dari public/documents
        $filePath = public_path($doc->file_path);
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }
        return response()->download($filePath, $doc->name . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
    }

    public function destroy($id)
    {
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $document = Document::findOrFail($id);

        // Hapus file dari public/documents
        if (file_exists(public_path($document->file_path))) {
            unlink(public_path($document->file_path));
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'description' => "Hapus dokumen: {$document->name}",
            'document_id' => $document->id,
            'ip_address' => request()->ip(),
        ]);

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus');
    }

    public function preview($id)
    {
        $doc = Document::findOrFail($id);

        if ($doc->status != 'berlaku') {
            abort(403, 'Dokumen tidak berlaku tidak bisa dilihat');
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'preview',
            'description' => "Preview dokumen: {$doc->name}",
            'document_id' => $doc->id,
            'ip_address' => request()->ip(),
        ]);

        $ext = pathinfo($doc->file_path, PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), ['pdf'])) {
            return redirect()->back()->with('error', 'Preview hanya tersedia untuk file PDF');
        }

        return view('documents.preview', compact('doc'));
    }

    public function stream($id)
    {
        $doc = Document::findOrFail($id);

        if ($doc->status != 'berlaku') {
            abort(403, 'Dokumen tidak berlaku tidak bisa diakses');
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'stream',
            'description' => "Stream dokumen: {$doc->name}",
            'document_id' => $doc->id,
            'ip_address' => request()->ip(),
        ]);

        $filePath = public_path($doc->file_path);
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$doc->name.'.pdf"',
        ]);
    }
}
