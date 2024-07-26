<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ExcelUsersController extends Controller
{

    public function index()
    {
    return view('reports.downloads.students');
    }

    public function getFiles(Request $request)
    {
        $fileDetails = [];

        // Get all files in the folder
        $files = Storage::disk('public')->files('downloads');
        // Filter only Excel files
        $excelFiles = array_filter($files, function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'xlsx';
        });

        // Prepare file details
        $fileDetails = array_map(function ($file) {
            return [
                'name' => basename($file),
                'size' => Storage::disk('public')->size($file),
                'url' => Storage::disk('public')->url($file),
                'created_at' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($file)),
            ];
        }, $excelFiles);

        // Handle search, sort, and pagination
        $columns = ['name', 'size', 'created_at', 'url'];
        $totalData = count($fileDetails);

        // Filter files based on search keyword
        if ($request->has('search') && $request->input('search.value') != '') {
            $search = $request->input('search.value');
            $fileDetails = array_filter($fileDetails, function ($file) use ($search) {
                return strpos(strtolower($file['name']), strtolower($search)) !== false;
            });
        }

        $totalFiltered = count($fileDetails);

        // Sort files
        if ($request->has('order')) {
            $order = $request->input('order.0');
            $columnIndex = $order['column'];
            $dir = $order['dir'];
            $columnName = $columns[$columnIndex];

            usort($fileDetails, function ($a, $b) use ($columnName, $dir) {
                if ($dir === 'asc') {
                    return $a[$columnName] <=> $b[$columnName];
                } else {
                    return $b[$columnName] <=> $a[$columnName];
                }
            });
        }

        // Paginate files
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $fileDetails = array_slice($fileDetails, $start, $length);

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => array_values($fileDetails),
        ]);
    }
}