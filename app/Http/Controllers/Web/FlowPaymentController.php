<?php 

use App\Models\Flow\Flow;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanUserFlow;
use App\Http\Controllers\Controller;

class FlowPaymentController extends Controller
{
    public function __construct()
    {
        $this->instanciateFlow('sandbox');
    }

    /**
     *  Make an instance of Flow on Production.
     *
     *  @param  $environment  ('production', 'sandbox')
     */
    private function instanciateFlow($environment)
    {
        $this->flow = Flow::make($environment, [
            /*  Credentials for FLOW platform */
            'apiKey' => config('flow.sandbox.apiKey'),
            'secret' => config('flow.sandbox.secret'),
        ]);
    }
}
