@extends('layouts.app')

@section('sidebar')
  @include('layouts.sidebar',['page'=>'home'])
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
          <div class="ibox ibox-fullheight">
                                      <div class="ibox-head">
                                          <div class="ibox-title">LATEST ORDERS</div>
                                          <div class="ibox-tools">
                                              <a class="dropdown-toggle" data-toggle="dropdown"><i class="ti-more-alt"></i></a>
                                              <div class="dropdown-menu dropdown-menu-right">
                                                  <a class="dropdown-item"> <i class="ti-pencil"></i>Create</a>
                                                  <a class="dropdown-item"> <i class="ti-pencil-alt"></i>Edit</a>
                                                  <a class="dropdown-item"> <i class="ti-close"></i>Remove</a>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="ibox-body">
                                          <div class="flexbox mb-4">
                                              <div class="flexbox">
                                                  <span class="flexbox mr-3">
                                                      <span class="mr-2 text-muted">Paid</span>
                                                      <span class="h3 mb-0 text-primary font-strong">310</span>
                                                  </span>
                                                  <span class="flexbox">
                                                      <span class="mr-2 text-muted">Unpaid</span>
                                                      <span class="h3 mb-0 text-pink font-strong">105</span>
                                                  </span>
                                              </div>
                                              <a class="flexbox" href="ecommerce_orders_list.html" target="_blank">VIEW ALL<i class="ti-arrow-circle-right ml-2 font-18"></i></a>
                                          </div>
                                          <div class="ibox-fullwidth-block">
                                              <table class="table table-hover">
                                                  <thead class="thead-default thead-lg">
                                                      <tr>
                                                          <th>Alumno</th>
                                                          <th>Plan</th>
                                                          <th>Status</th>
                                                          <th class="pr-4" style="width:91px;">Date</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      <tr>
                                                          <td class="pl-4">
                                                              <a href="ecommerce_order_details.html" target="_blank">#1254</a>
                                                          </td>
                                                          <td>Becky Brooks</td>
                                                          <td>$457.00</td>
                                                          <td>
                                                              <span class="badge badge-success badge-pill">Shipped</span>
                                                          </td>
                                                          <td class="pr-4">17.05.2018</td>
                                                      </tr>
                                                      <tr>
                                                          <td class="pl-4">
                                                              <a href="ecommerce_order_details.html" target="_blank">#1253</a>
                                                          </td>
                                                          <td>Emma Johnson</td>
                                                          <td>$1200.00</td>
                                                          <td>
                                                              <span class="badge badge-success badge-pill">Shipped</span>
                                                          </td>
                                                          <td class="pr-4">17.05.2018</td>
                                                      </tr>
                                                      <tr>
                                                          <td class="pl-4">
                                                              <a href="ecommerce_order_details.html" target="_blank">#1252</a>
                                                          </td>
                                                          <td>Noah Williams</td>
                                                          <td>$780.00</td>
                                                          <td>
                                                              <span class="badge badge-primary badge-pill">Pending</span>
                                                          </td>
                                                          <td class="pr-4">17.05.2018</td>
                                                      </tr>
                                                      <tr>
                                                          <td class="pl-4">
                                                              <a href="ecommerce_order_details.html" target="_blank">#1251</a>
                                                          </td>
                                                          <td>Sophia Jones</td>
                                                          <td>$105.60</td>
                                                          <td>
                                                              <span class="badge badge-success badge-pill">Completed</span>
                                                          </td>
                                                          <td class="pr-4">17.05.2018</td>
                                                      </tr>
                                                      <tr>
                                                          <td class="pl-4">
                                                              <a href="ecommerce_order_details.html" target="_blank">#1250</a>
                                                          </td>
                                                          <td>Jacob Brown</td>
                                                          <td>$40.00</td>
                                                          <td>
                                                              <span class="badge badge-primary badge-pill">Pending</span>
                                                          </td>
                                                          <td class="pr-4">17.05.2018</td>
                                                      </tr>
                                                      <tr>
                                                          <td class="pl-4">
                                                              <a href="ecommerce_order_details.html" target="_blank">#1249</a>
                                                          </td>
                                                          <td>James Davis</td>
                                                          <td>$78.00</td>
                                                          <td>
                                                              <span class="badge badge-danger badge-pill">Canceled</span>
                                                          </td>
                                                          <td class="pr-4">17.05.2018</td>
                                                      </tr>
                                                  </tbody>
                                              </table>
                                          </div>
                                      </div>
                                  </div>
        </div>
    </div>

@endsection
