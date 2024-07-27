<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Models\Reports\Download;
use App\Http\Controllers\Controller;

class DownloadsController extends Controller
{
    public function index()
    {
        return view('reports.downloads.students');
    }

    public function getFiles(Request $request)
    {
        $downloads = Download::orderBy('created_at', 'desc')->get();
        
        $fileDetails = $downloads->map(function ($download) {
            return [
                'name' => $download->status === 'completado' ? $download->file_name : 'Procesando...',
                'size' => $download->size ? ($download->size / 1024) . ' KB' : 'N/A',
                'url' => $download->url ?? '#',
                'created_at' => $download->created_at->format('Y-m-d H:i:s'),
                'status' => $download->status,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => count($fileDetails),
            'recordsFiltered' => count($fileDetails),
            'data' => $fileDetails->values()->toArray(),
        ]);
    }
}
