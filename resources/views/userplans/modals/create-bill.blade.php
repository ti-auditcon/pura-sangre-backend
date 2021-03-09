<div class="modal fade" id="add-bill" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-l" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar pago</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="add-bill-form" action="{{ route('payments.store') }}" method="POST"
            >
                @csrf

                <input id="plan-user-id" type="text" name="plan_user_id" hidden/>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 form-group mt-1">
                            <label class="form-control-label">Tipo de pago</label>

                            <select name="payment_type_id" class="form-control" required>
                                <option value="">Eliga un tipo de pago</option>

                                @foreach (\App\Models\Bills\PaymentType::humanList() as $key => $payment)
                                    <option value="{{ $key }}">{{ $payment }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 mt-1">
                            <label class="form-control-label">Fecha de pago</label>

                            <div class="input-group date">
                                <span class="input-group-addon bg-white">
                                    <i class="la la-calendar"></i>
                                </span>

                                <input class="form-control"
                                        name="date"
                                        type="text"
                                        autocomplete="off"
                                        value="{{ today()->format('d-m-Y') }}"
                                        required/>
                            </div>

                        </div>
                        <div class="form-group col-md-6 mt-1">
                            <label class="form-control-label">Fecha inico del plan</label>

                            <div class="input-group date">
                                <span class="input-group-addon bg-white">
                                    <i class="la la-calendar"></i>
                                </span>

                                <input class="form-control"
                                id="bill-start-date"
                                name="start_date"
                                type="text"
                                autocomplete="off"
                                        required>
                            </div>

                        </div>
                        <div class="form-group col-md-6 mt-1">
                            <label class="form-control-label">Fecha término del plan</label>

                            <div class="input-group date">
                                <span class="input-group-addon bg-white">
                                    <i class="la la-calendar"></i>
                                </span>

                                <input class="form-control"
                                        name="finish_date"
                                        id="bill-finish-date"
                                        type="text"
                                        autocomplete="off"
                                        required>
                            </div>

                        </div>
                        <div class="col-12 form-group mt-1">
                            <label>Monto</label>

                            <input class="form-control col-12"
                                    type="text"
                                    name="amount"
                                    autocomplete="off"
                                    required/>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">
                        Registrar pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
