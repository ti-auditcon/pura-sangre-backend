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


    public function finishFlowPayment(Request $request)
    {
        $this->makeFlowPayment($request);

        return redirect('flow');
    }

    /**
     *  Check if the payment is corrctly done, and make the bill,
     *  then return true is all was correct
     *
     *  @param   Request  $request
     *
     *  @return  bool
     */
    public function makeFlowPayment(Request $request)
    {
        /**  get the payment data  */
        $payment = $this->flow->payment()->get($request->token);
        
        $planUserFlow = PlanUserFlow::find((int) $payment->commerceOrder);

        /* Plan has been paid already */
        if ($planUserFlow->isPaid()) {
            return true;
        }

        /* Plan wasn't paid, then anuul payment */
        if ($payment->paymentData['date'] === null) {
            $planUserFlow->annul('Error fecha desde flow. Posiblemente error en el pago');

            return false;
        }

        /*  Chage status plan user flow to paid  */
        $planUserFlow->changeStatusToPaid('Pago realizado desde web');
        $user = User::find($planUserFlow->user_id);

        /** Register Plan User on system */
        $plan_user = PlanUser::makePlanUser($planUserFlow, $user);

        $this->bill->makeFlowBill($plan_user, $payment->paymentData);

        return true;
    }
}
