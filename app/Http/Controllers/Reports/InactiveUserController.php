<?php

namespace App\Http\Controllers\Reports;

use App\Traits\ExpiredPlans;
use Illuminate\Http\Request;
use App\Models\Reports\Download;
use App\Exports\InactiveUsersExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\ExportInactiveStudentsToExcel;

class InactiveUserController extends Controller
{
    use ExpiredPlans;

    /**
     * Show all the users who has expired plans
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('reports.inactives_users');
    }

    public function inactiveUsers(Request $request)
    {
        return $this->ExpiredPlan($request);
    }

    /**
     * Export Excel of Inactive System Users
     * 
     * @return Maatwebsite\Excel\Facades\Excel
     */
    public function export()
    {
        $download = Download::create(['status' => 'procesando']);
        ExportInactiveStudentsToExcel::dispatch($download);

        return redirect()->back()->with('success', 'El proceso de exportación se ha iniciado. El archivo estará disponible para descargar en la sección de descargas en breve.');
    }
}