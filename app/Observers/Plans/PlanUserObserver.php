<?php

namespace App\Observers\Plans;

use App\Models\Plans\PlanUser;

/**
 * [PlanUserObserver description]
 */
class PlanUserObserver
{
    /**
     * Handle the plan user "created" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function created(PlanUser $planUser)
    {
      $plan = $planUser->plan;
      $periodo = $plan->number;
      if($periodo!=null){
        for ($i=0; $i < $periodo ; $i++) {
          PlanUser::create($request->all());
          //crear contador
          //agre
          ////dejarlo adentro de plan user el counter------
          ////
        }
      }
    }


    /**
     * Handle the plan user "updated" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function updated(PlanUser $planUser)
    {
        //
    }

    /**
     * Handle the plan user "deleted" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function deleted(PlanUser $planUser)
    {
        //
    }

    /**
     * Handle the plan user "restored" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function restored(PlanUser $planUser)
    {
        //
    }

    /**
     * Handle the plan user "force deleted" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function forceDeleted(PlanUser $planUser)
    {
        //
    }
}


// switch ($clientService->type->name){
//       //cuota unica
//       case "Unico":
//         $fee  = new ClientServiceFee;
//         $fee->client_service_id = $clientService->id;
//         $fee->client_service_fee_state_id = 1;
//         $fee->month = $clientService->month;
//         $fee->amount = $clientService->amount;
//         $fee->expiration_date = Carbon::create($clientService->year, $clientService->month+1 , 20);
//         $fee->save();
//         break;
//       //cuota mensual
//       case "Mensual":
//         for ($i=$clientService->month; $i <= 12 ; $i++) {
//           $fee  = new ClientServiceFee;
//           $fee->client_service_id = $clientService->id;
//           $fee->client_service_fee_state_id = 1;
//           $fee->month = $i;
//           $fee->amount = $clientService->amount;
//           $fee->expiration_date = Carbon::create($clientService->year, $i+1 , 20);
//           $fee->save();
//         }
//         break;
//
//     }
//     Session::flash('success','El Servicio ha sido creado satisfactoriamente');
//     //End
