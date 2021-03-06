@extends('adminLayout')
@section('content')
    <style>
        .table>tbody>tr>td,
        .table>tfoot>tr>td,
        .table>thead>tr>td {
            border-top: 1px solid #e0e0e0;
            border-right: 1px solid #e0e0e0;
            border-left: 1px solid #e0e0e0;
        }

        .checkout-table .item-name-col figure {
            width: 100px;
            float: left;
            margin-right: 20px;
            height: 120px;
        }

        .table>caption+thead>tr:first-child>th,
        .table>colgroup+thead>tr:first-child>th,
        .table>thead:first-child>tr:first-child>th,
        .table>caption+thead>tr:first-child>td,
        .table>colgroup+thead>tr:first-child>td,
        .table>thead:first-child>tr:first-child>td {
            border-top: 1px solid #ddd;
            background: #efefef;
            border-right: 1px solid #ddd;
            border-left: 1px solid #ddd;
            text-align: center;
        }

    </style>
    <?php
    $ordersModel = new App\Http\Models\Admin\Orders();
    $orderTax = $ordersModel->getOrderTax($order->id);
    $isReverseCalculated = false;
    foreach ($orderTax->products as $orderProduct) {
        $roomCode = $orderProduct->room_code;
        $BRoom = DB::table('products')
            ->where('room_code', $roomCode)
            ->first();
//dd($BRoom);
        if ($BRoom)
        {
            if ($BRoom->reverse_tax_calculation == '1') {
                $isReverseCalculated = true;
            }
        }
    }
    if ($isReverseCalculated) {
        $TaxRate = DB::table('gst_rates')->get();
        /* print_r($TaxRate);
         exit; */
    }
    function reverseCalculation($totalBill, $TaxRate)
    {
        return $totalBill / (1 + intval($TaxRate[0]->rate) / 100);
    }
    if ($order->payment_method == 'Credit Card') {
        $creditCardInfo = DB::table('customerBilling')
            ->where('order_id', $order->id)
            ->first();
    }
    //dd($order, $orderTax);
    //echo '<pre>';print_r($orderTax);
    //exit;
    ?>
    <div id="page-wrapper">
        <div class="page-header-breadcrumb">
            <div class="page-heading hidden-xs">
                <h1 class="page-title">Orders</h1>
            </div>

            <ol class="breadcrumb page-breadcrumb">
                <li><i class="fa fa-home"></i>&nbsp;<a href="{{ url('web88cms/dashboard') }}">Dashboard</a>&nbsp; <i
                            class="fa fa-angle-right"></i>&nbsp;</li>
                <li>Orders &nbsp;<i class="fa fa-angle-right"></i>&nbsp;</li>
                <li><a href="{{ url('web88cms/orders') }}">Orders Listing</a> &nbsp;<i class="fa fa-angle-right"></i>&nbsp;
                </li>
                <li class="active">View Order - Details</li>
            </ol>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-lg-12">
                    <h2>View Order <i class="fa fa-angle-right"></i> Details</h2>
                    <div class="clearfix"></div>
                    @if ($success)
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" data-dismiss="alert" aria-hidden="true"
                                    class="close">&times;</button>
                            <i class="fa fa-check-circle"></i> <strong>Success!</strong>
                            <p>{{ $success }}</p>
                        </div>
                    @endif

                    @if ($warning)
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" data-dismiss="alert" aria-hidden="true"
                                    class="close">&times;</button>
                            <i class="fa fa-times-circle"></i> <strong>Error!</strong>
                            <p>{{ $warning }}</p>
                        </div>
                    @endif


                    <h4 class="block-heading pull-right"><B style="color:#2EFE2E;">
                            <font color="green">Booking ID:</font>
                        </B> <span class="text-red">{{ $bookid }}</span></h4>


                    <div class="pull-left"> Last updated: <span
                                class="text-blue">{{ date('d M, Y @ h:iA', strtotime($order->modifydate)) }}</span>
                    </div>
                    <div class="clearfix"></div>
                    <p></p>
                    {{-- {{ dump($order) }} --}}
                    <h3 class="block-heading pull-left">Order ID: #{{ $order->order_id }}</h3>
                    <h5 class="block-heading pull-right">Total: <span class="text-red">RM
                            {{ number_format($order->totalPrice, 2) }}</span></h5>
                    <div class="clearfix"></div>
                    <ul id="myTab" class="nav nav-tabs">
                        <li class="active"><a href="#overview" data-toggle="tab">Overview</a></li>
                        <li><a href="#item-details" data-toggle="tab">Item Details</a></li>
                        <li><a href="#customer-info" data-toggle="tab">Customer Information</a></li>
                    </ul>

                    <div id="myTabContent" class="tab-content">
                        <div id="overview" class="tab-pane fade in active">
                            <div class="portlet">
                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <!-- order information start -->
                                            <h4 class="block-heading"><i class="fa fa-shopping-cart"></i> Order Information
                                            </h4>
                                            <div class="md-margin-2x"></div>
                                            <div class="form-group">
                                                <label for="inputFirstName" class="col-md-4 control-label">Order ID:
                                                </label>
                                                <div class="col-md-8">
                                                    <p><strong>#{{ $order->order_id }}</strong></p>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="form-group">
                                                <label for="inputFirstName" class="col-md-4 control-label">Order Date:
                                                </label>
                                                <div class="col-md-8">
                                                    <p><strong>{{ date('dS M, Y', strtotime($order->createdate)) }}</strong>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            @if ($orderTax->check_date)
                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Check in
                                                        date: </label>
                                                    <div class="col-md-8">
                                                        <p><strong>{{ date('dS M, Y', strtotime($orderTax->check_date->date_checkin)) }}
                                                            </strong></p>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Check out
                                                        Date: </label>
                                                    <div class="col-md-8">
                                                        <p><strong>{{ date('dS M, Y', strtotime($orderTax->check_date->date_checkout)) }}
                                                            </strong></p>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <label for="inputFirstName" class="col-md-4 control-label">Customer
                                                    Name:</label>
                                                <div class="col-md-8">
                                                    @if ($customer)
                                                        <p><a
                                                                    href="{{ url('web88cms/customers/view/' . $customer->id) }}"><strong>{{ $customer->first_name . ' ' . $customer->last_name }}</strong></a>,<br />
                                                            <a
                                                                    href="{{ url('web88cms/customers/view/' . $customer->id) }}"><strong>{{ $customer->email }}</strong></a>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputFirstName" class="col-md-4 control-label">IP Address:
                                                </label>
                                                <div class="col-md-8">
                                                    <p><strong>{{ $order->ip_address }}</strong></p>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="form-group">
                                                <label for="inputFirstName" class="col-md-4 control-label">Total Amount:
                                                </label>
                                                <div class="col-md-8">
                                                    <p class="text-red"><strong>RM
                                                            {{ number_format($order->totalPrice, 2) }}</strong></p>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="form-group">
                                                <label for="inputFirstName" class="col-md-4 control-label">Item Number(s):
                                                </label>
                                                <div class="col-md-8">
                                                    <p><strong>{{ $totalOrderItems }}</strong></p>
                                                </div>
                                            </div>
                                            @if (count($packages) > 0)
                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Package Name:
                                                    </label>
                                                    <div class="col-md-8">
                                                        <p><strong>{{ $packages[0]->package_name }}</strong></p>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Package Code:
                                                    </label>
                                                    <div class="col-md-8">
                                                        <p><strong>{{ $packages[0]->package_code }}</strong></p>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Value Added
                                                        Services: </label>
                                                    <div class="col-md-8">
                                                        <p>
                                                            @foreach (explode(',', $packages[0]->value_added_service) as $service)
                                                                <strong>&gt;&gt; {{ $service }}</strong>
                                                                <br>
                                                        @endforeach
                                                        <!-- <br>
                                                            <strong>
                                                                <b>Minimum Stay : {{ $packages[0]->minimum_stay }} </b>
                                                            </strong>
                                                            <br>
                                                            <strong>
                                                                <b>Check In : </b>
                                                                @if ($packages[0]->checkin_mo == 1)
                                                            Monday
@endif
                                                        @if ($packages[0]->checkin_tu == 1)
                                                            Tuesday
@endif
                                                        @if ($packages[0]->checkin_we == 1)
                                                            Wednesday
@endif
                                                        @if ($packages[0]->checkin_th == 1)
                                                            Thursday
@endif
                                                        @if ($packages[0]->checkin_fr == 1)
                                                            Friday
@endif
                                                        @if ($packages[0]->checkin_sa == 1)
                                                            Saturday
@endif
                                                        @if ($packages[0]->checkin_su == 1)
                                                            Sunday
@endif
                                                                </strong>
                                                                <br>
                                                                <strong>
                                                                    <b>Check Out : </b>
@if ($packages[0]->checkout_mo == 1)
                                                            Monday
@endif
                                                        @if ($packages[0]->checkout_tu == 1)
                                                            Tuesday
@endif
                                                        @if ($packages[0]->checkout_we == 1)
                                                            Wednesday
@endif
                                                        @if ($packages[0]->checkout_th == 1)
                                                            Thursday
@endif
                                                        @if ($packages[0]->checkout_fr == 1)
                                                            Friday
@endif
                                                        @if ($packages[0]->checkout_sa == 1)
                                                            Saturday
@endif
                                                        @if ($packages[0]->checkout_su == 1)
                                                            Sunday
@endif
                                                                </strong> -->
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="clearfix"></div>
                                            <div class="lg-margin"></div>

                                            <h4 class="block-heading"><i class="fa fa-truck"></i> Shipping Address</h4>
                                            <div class="md-margin-2x"></div>
                                            <div class="form-group">
                                                <label for="inputFirstName" class="col-md-4 control-label">Ship To: </label>
                                                <div class="col-md-8">
                                                    <p><strong>{{ $order->shipping_first_name }}
                                                            {{ $order->shipping_last_name }} </strong></p>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="form-group">
                                                <label for="inputFirstName" class="col-md-4 control-label">Email: </label>
                                                <div class="col-md-8">
                                                    <p><strong>{{ $order->shipping_email }}</strong></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputFirstName" class="col-md-4 control-label">Telephone:
                                                </label>
                                                <div class="col-md-8">
                                                    <p><strong>{{ $order->shipping_telephone }}</strong></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputFirstName" class="col-md-4 control-label">Address: </label>
                                                <div class="col-md-8">
                                                    <p><strong>{{ $order->shipping_address }},
                                                            {{ $order->shipping_post_code }}
                                                            {{ $order->shipping_city }},
                                                            {{ $order->shipping_state_name }},
                                                            {{ $order->shipping_country_name }}</strong></p>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="form-actions text-center">
                                                <a class="btn btn-success" data-target="#modal-edit-shipping"
                                                   data-toggle="modal">Edit &nbsp;<i class="fa fa-pencil"></i></a>&nbsp;
                                            </div>
                                            <!--Modal Edit shipping address start-->
                                            <div id="modal-edit-shipping" tabindex="-1" role="dialog"
                                                 aria-labelledby="modal-login-label" aria-hidden="true"
                                                 class="modal fade">
                                                <div class="modal-dialog modal-wide-width">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                    class="close">&times;</button>
                                                            <h4 id="modal-login-label2" class="modal-title">Edit
                                                                Shipping Address</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form">
                                                                <form class="form-horizontal" id="edit-shipping-address">
                                                                    <div class="form-group">
                                                                        <label for="inputFirstName"
                                                                               class="col-md-4 control-label">Ship To First
                                                                            Name </label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control"
                                                                                   name="shipping_first_name"
                                                                                   value="{{ $order->shipping_first_name }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="clearfix"></div>

                                                                    <div class="form-group">
                                                                        <label for="inputFirstName"
                                                                               class="col-md-4 control-label">Ship To Last
                                                                            Name </label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control"
                                                                                   name="shipping_last_name"
                                                                                   value="{{ $order->shipping_last_name }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="clearfix"></div>

                                                                    <div class="form-group">
                                                                        <label for="inputFirstName"
                                                                               class="col-md-4 control-label">Email </label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control"
                                                                                   name="shipping_email"
                                                                                   value="{{ $order->shipping_email }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="inputFirstName"
                                                                               class="col-md-4 control-label">Telephone
                                                                        </label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control"
                                                                                   name="shipping_telephone"
                                                                                   value="{{ $order->shipping_telephone }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="inputFirstName"
                                                                               class="col-md-4 control-label">Address </label>
                                                                        <div class="col-md-6">
                                                                            <textarea name="shipping_address"
                                                                                      class="form-control">{{ $order->shipping_address }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-actions">
                                                                        <div class="col-md-offset-5 col-md-8">
                                                                            <a href="javascript:void(0)"
                                                                               id="save-shipping-address"
                                                                               class="btn btn-red">
                                                                                Save &nbsp;<i class="fa fa-floppy-o"></i>
                                                                            </a>&nbsp;
                                                                            <a href="javascript:void(0)"
                                                                               data-dismiss="modal"
                                                                               class="btn btn-green">
                                                                                Cancel &nbsp;<i
                                                                                        class="glyphicon glyphicon-ban-circle"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="_token"
                                                                           value="{{ csrf_token() }}">
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="lg-margin"></div>

                                            <!-- billing address start -->
                                            <h4 class="block-heading"><i class="fa fa-tag"></i> Billing Address</h4>
                                            <div class="md-margin-2x"></div>
                                            <div class="form-group">
                                                <label for="inputFirstName" class="col-md-4 control-label">Bill To:
                                                </label>
                                                <div class="col-md-8">
                                                    <p><strong>{{ $order->billing_first_name . ' ' . $order->billing_last_name }}</strong>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="form-group">
                                                <label for="inputFirstName" class="col-md-4 control-label">Email: </label>
                                                <div class="col-md-8">
                                                    <p><strong>{{ $order->billing_email }}</strong></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputFirstName" class="col-md-4 control-label">Telephone:
                                                </label>
                                                <div class="col-md-8">
                                                    <p><strong>{{ $order->billing_telephone }}</strong></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputFirstName" class="col-md-4 control-label">Address:
                                                </label>
                                                <div class="col-md-8">
                                                    <p><strong>{{ $order->billing_address }},
                                                            {{ $order->billing_post_code }}
                                                            {{ $order->billing_city }},
                                                            {{ $order->billing_state_name }},
                                                            {{ $order->billing_country_name }}</strong></p>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="form-actions text-center">
                                                <a class="btn btn-success" data-target="#modal-edit-billing"
                                                   data-toggle="modal">Edit &nbsp;<i class="fa fa-pencil"></i></a>&nbsp;
                                            </div>
                                            <!--Modal Edit billing address start-->
                                            <div id="modal-edit-billing" tabindex="-1" role="dialog"
                                                 aria-labelledby="modal-login-label" aria-hidden="true"
                                                 class="modal fade">
                                                <div class="modal-dialog modal-wide-width">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                    class="close">&times;</button>
                                                            <h4 id="modal-login-label2" class="modal-title">Edit Billing
                                                                Address</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form">
                                                                <form class="form-horizontal" id="edit-billing-address">
                                                                    <div class="form-group">
                                                                        <label for="inputFirstName"
                                                                               class="col-md-4 control-label">Bill To First
                                                                            Name </label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control"
                                                                                   name="billing_first_name"
                                                                                   value="{{ $order->billing_first_name }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="clearfix"></div>

                                                                    <div class="form-group">
                                                                        <label for="inputFirstName"
                                                                               class="col-md-4 control-label">Bill To Last
                                                                            Name </label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control"
                                                                                   name="billing_last_name"
                                                                                   value="{{ $order->billing_last_name }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="clearfix"></div>

                                                                    <div class="form-group">
                                                                        <label for="inputFirstName"
                                                                               class="col-md-4 control-label">Email </label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control"
                                                                                   name="billing_email"
                                                                                   value="{{ $order->billing_email }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="inputFirstName"
                                                                               class="col-md-4 control-label">Telephone
                                                                        </label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control"
                                                                                   name="billing_telephone"
                                                                                   value="{{ $order->billing_telephone }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="inputFirstName"
                                                                               class="col-md-4 control-label">Address </label>
                                                                        <div class="col-md-6">
                                                                            <textarea name="billing_address"
                                                                                      class="form-control">{{ $order->billing_address }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-actions">
                                                                        <div class="col-md-offset-5 col-md-8">
                                                                            <a href="javascript:void(0)"
                                                                               id="save-billing-address"
                                                                               class="btn btn-red">
                                                                                Save &nbsp;<i class="fa fa-floppy-o"></i>
                                                                            </a>&nbsp;
                                                                            <a href="javascript:void(0)"
                                                                               data-dismiss="modal"
                                                                               class="btn btn-green">
                                                                                Cancel &nbsp;<i
                                                                                        class="glyphicon glyphicon-ban-circle"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="_token"
                                                                           value="{{ csrf_token() }}">
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--END MODAL Edit billing address -->

                                            <!-- end billing address -->
                                            <div class="lg-margin"></div>

                                            <!-- notes start -->
                                            <h4 class="block-heading"><i class="fa fa-pencil"></i> Notes</h4>
                                            <div class="md-margin-2x"></div>
                                            <form id="frm-notes">
                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Customer
                                                        Notes </label>
                                                    <div class="col-md-8"> <textarea name="customer_notes" rows="3"
                                                                                     class="form-control"
                                                                                     id="customer_notes">{{ $order->customer_notes }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="xs-margin"></div>
                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Staff Notes
                                                    </label>
                                                    <div class="col-md-8"> <textarea name="staff_notes" rows="3"
                                                                                     class="form-control"
                                                                                     id="staff_notes">{{ $order->staff_notes }}</textarea></div>
                                                </div>
                                            </form>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">Order Status</div>
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <label for="inputFirstName"
                                                               class="col-md-4 control-label"><strong>Status:</strong>
                                                        </label>
                                                        <div class="col-md-8">
                                                            <?php
                                                            $class = '';
                                                            $text = '';
                                                            ?>
                                                            <div class="btn-group">
                                                                @if ($order->status == 'Processing')
                                                                    <?php
                                                                    $class = 'btn-info';
                                                                    $text = 'Processing';
                                                                    ?>
                                                                @elseif($order->status == 'New Order')
                                                                    <?php
                                                                    $class = 'btn-warning';
                                                                    $text = 'New Order';
                                                                    ?>
                                                                @elseif($order->status == 'Ready To Ship')
                                                                    <?php
                                                                    $class = 'btn-info';
                                                                    $text = 'Ready To Ship';
                                                                    ?>
                                                                @elseif($order->status == 'Shipped')
                                                                    <?php
                                                                    $class = 'btn-blue';
                                                                    $text = 'Shipped';
                                                                    ?>
                                                                @elseif($order->status == 'Completed')
                                                                    <?php
                                                                    $class = 'btn-success';
                                                                    $text = 'Completed';
                                                                    ?>
                                                                @elseif($order->status == 'Declined')
                                                                    <?php
                                                                    $class = 'btn-red';
                                                                    $text = 'Declined';
                                                                    ?>
                                                                @elseif($order->status == 'Cancelled')
                                                                    <?php
                                                                    $class = 'btn-primary';
                                                                    $text = 'Cancelled';
                                                                    ?>
                                                                @endif

                                                                <button id="order-status" type="button"
                                                                        class="btn {{ $class }}">{{ $text }}</button>
                                                                <button type="button" data-toggle="dropdown"
                                                                        class="btn {{ $class }} dropdown-toggle"><span
                                                                            class="caret"></span><span
                                                                            class="sr-only">Toggle
                                                                        Dropdown</span></button>
                                                                <ul role="menu" class="dropdown-menu">
                                                                    @foreach ($orderStatus as $status)
                                                                        @if ($order->status != $status)
                                                                            <li><a href="javascript:void(0)"
                                                                                   data-status="{{ $status }}"
                                                                                   class="order-status">{{ $status }}</a>
                                                                            </li>
                                                                        @endif
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="xs-margin"></div>
                                                    <div style="padding-left: 15px;">
                                                        <input name="notify_customer_order_status" type="checkbox"
                                                               value="on" checked="checked"> Notify customer of the order
                                                        status.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-primary">
                                                <div class="panel-heading">Payment Status</div>
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <h6 class="block-heading">Payment Information</h6>
                                                        <div class="form-group">
                                                            <label for="inputFirstName"
                                                                   class="col-md-4 control-label"><strong>Method:
                                                                </strong></label>
                                                            <div class="col-md-8">{{ $order->payment_method }}
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <hr>

                                                        <label for="inputFirstName"
                                                               class="col-md-4 control-label"><strong>Status:</strong>
                                                        </label>
                                                        <div class="col-md-8">
                                                            <div class="btn-group">
                                                                @if ($order->payment_status == 'Processing')
                                                                    <?php
                                                                    $class = 'btn-info';
                                                                    $text = 'Processing';
                                                                    ?>
                                                                @elseif($order->payment_status == 'Paid')
                                                                    <?php
                                                                    $class = 'btn-success';
                                                                    $text = 'Paid';
                                                                    ?>
                                                                @elseif($order->payment_status == 'Payment Error')
                                                                    <?php
                                                                    $class = 'btn-red';
                                                                    $text = 'Payment Error';
                                                                    ?>
                                                                @elseif($order->payment_status == 'Cancelled')
                                                                    <?php
                                                                    $class = 'btn-primary';
                                                                    $text = 'Cancelled';
                                                                    ?>
                                                                @endif
                                                                <button type="button" id="payment-status"
                                                                        class="btn {{ $class }}">{{ $text }}</button>
                                                                <button type="button" data-toggle="dropdown"
                                                                        class="btn {{ $class }} dropdown-toggle">
                                                                    <span class="caret"></span><span
                                                                            class="sr-only">Toggle Dropdown</span>
                                                                </button>
                                                                <ul role="menu" class="dropdown-menu">
                                                                    @foreach ($paymentStatus as $status)
                                                                        @if ($order->payment_status != $status)
                                                                            <li><a href="javascript:void(0)"
                                                                                   class="payment-status"
                                                                                   data-status="{{ $status }}">{{ $status }}</a>
                                                                            </li>
                                                                        @endif
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="xs-margin"></div>
                                                    <div style="padding-left: 15px;">
                                                        <input name="notify_customer_payment_status" type="checkbox"
                                                               value="on" checked="checked">
                                                        Notify customer of the payment status.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-primary">
                                                <div class="panel-heading">Shipment</div>
                                                <div class="panel-body">

                                                    <h6 class="block-heading">Shipping Information</h6>
                                                    <div class="form-group">
                                                        <label for="inputFirstName"
                                                               class="col-md-4 control-label"><strong>Method:
                                                            </strong></label>
                                                        <div class="col-md-8">{{ $order->shipping_method }}</div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="xs-margin"></div>
                                                    <?php
                                                    $shipments = 0;
                                                    if ($order->status == 'Ready To Ship' || $order->status == 'Shipped') {
                                                        $shipments = 1;
                                                    }
                                                    ?>
                                                    <div style="padding-left: 15px;" class="pull-right"><a
                                                                href="{{ url('web88cms/orders/shipmentDetail/' . $order->id) }}">View
                                                            shipments ({{ $shipments }})</a></div>
                                                    <div class="clearfix"></div>
                                                    <div class="sm-margin"></div>

                                                    <div class="form-actions text-center">
                                                        <a href="javascript:void(0)" data-target="#modal-add-shipment"
                                                           data-toggle="modal" class="btn btn-success">Add New Shipment
                                                            &nbsp;<i class="fa fa-truck"></i></a>&nbsp;
                                                    </div>

                                                    <div id="modal-add-shipment" tabindex="-1" role="dialog"
                                                         aria-labelledby="modal-login-label" aria-hidden="true"
                                                         class="modal fade">
                                                        <div class="modal-dialog modal-wide-width">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" data-dismiss="modal"
                                                                            aria-hidden="true"
                                                                            class="close">&times;</button>
                                                                    <h4 id="modal-login-label2" class="modal-title">Add
                                                                        New Shipment</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form">
                                                                        <form class="form-horizontal" id="new-shipment">
                                                                            <div class="form-group">
                                                                                <label for="inputFirstName"
                                                                                       class="col-md-4 control-label">Shipping
                                                                                    Method </label>
                                                                                <div class="col-md-6">
                                                                                    <select class="form-control"
                                                                                            name="shipping_method">
                                                                                        <option value="">- Select option -
                                                                                        </option>
                                                                                        @foreach ($shipping_options as $ship)
                                                                                            <?php $selected = $order->shipping_method == $ship['csv']->title ? 'selected="selected"' : ''; ?>
                                                                                            <option
                                                                                                    value="{{ $ship['csv']->id }}"
                                                                                                    {{ $selected }}>
                                                                                                {{ $ship['csv']->title }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                        <option
                                                                                            <?php $order->shipping_method == 'Self Collection' ? 'selected="selected"' : ''; ?>value="0">
                                                                                            Self Collection</option>
                                                                                    </select>
                                                                                    <input type="hidden"
                                                                                           name="shipping_state"
                                                                                           value="{{ $order->shipping_state }}">
                                                                                    <input type="hidden" name="total_weight"
                                                                                           value="{{ $order->total_weight }}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="clearfix"></div>
                                                                            <div class="form-group">
                                                                                <label for="inputFirstName"
                                                                                       class="col-md-4 control-label">Tracking
                                                                                    Number </label>
                                                                                <div class="col-md-6">
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="tracking_number"
                                                                                           value="{{ $order->tracking_number }}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="inputFirstName"
                                                                                       class="col-md-4 control-label">Comments
                                                                                </label>
                                                                                <div class="col-md-6">
                                                                                    <textarea class="form-control"
                                                                                              name="comments">{{ $order->comments }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="inputFirstName"
                                                                                       class="col-md-4 control-label">Order
                                                                                    Status </label>
                                                                                <div class="col-md-6">
                                                                                    <select class="form-control"
                                                                                            name="status">
                                                                                        <option value="">Do not change
                                                                                        </option>
                                                                                        @foreach ($orderStatus as $status)
                                                                                            <option
                                                                                                    value="{{ $status }}">
                                                                                                {{ $status }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                    <div class="help-block">Please note
                                                                                        that the notification of changing
                                                                                        the status will be sent depending on
                                                                                        the settings of this status </div>
                                                                                    <input type="checkbox"
                                                                                           name="send_shipment_notification"
                                                                                           value="on"> Send shipment
                                                                                    notification to customer
                                                                                </div>

                                                                            </div>
                                                                            <div class="form-actions">
                                                                                <div class="col-md-offset-5 col-md-8">
                                                                                    <a id="add-new-shipment"
                                                                                       href="javascript:void(0)"
                                                                                       class="btn btn-red">Save
                                                                                        &nbsp;<i
                                                                                                class="fa fa-floppy-o"></i></a>&nbsp;
                                                                                    <a href="javascript:void(0)"
                                                                                       data-dismiss="modal"
                                                                                       class="btn btn-green">Cancel
                                                                                        &nbsp;<i
                                                                                                class="glyphicon glyphicon-ban-circle"></i></a>
                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" name="_token"
                                                                                   value="{{ csrf_token() }}">
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-primary">
                                                <div class="panel-heading">Invoice</div>
                                                <div class="panel-body">
                                                    <div class="form-actions text-center">
                                                        <a href="{{ url('web88cms/orders/invoice/' . $order->id) }}"
                                                           class="btn btn-warning">
                                                            View Invoice &nbsp;<i class="fa fa-search"></i>
                                                        </a>&nbsp;
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-primary">
                                                <div class="panel-heading">Assignment Status</div>
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <h6 class="block-heading">Assignment Information</h6>
                                                        <div class="form-group">
                                                            <label for="inputFirstName"
                                                                   class="col-md-4 control-label"><strong>Partner:
                                                                </strong></label>
                                                            <div class="col-md-8 partner_assign">
                                                                @if($order->partner_id != null)
                                                                    <?php
                                                                    $partner= DB::table('partners')->where('id',$order->partner_id)->first();
                                                                    echo $partner->first_name . '' . $partner->last_name;
                                                                    ?>
                                                                @else
                                                                    Nill
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <hr>

                                                        <label for="inputFirstName"
                                                               class="col-md-4 control-label"><strong>Partner:</strong>
                                                        </label>

                                                        <div class="col-md-8">
                                                            <div class="btn-group">

                                                                <button type="button"
                                                                        class="btn btn-info" id="selectedPartner">Choose</button>
                                                                <button type="button" data-toggle="dropdown"
                                                                        class="btn btn-info dropdown-toggle" id="assignment-status">
                                                                    <span class="caret"></span><span class="sr-only">Toggle Dropdown</span>
                                                                </button>
                                                                <ul role="menu" class="dropdown-menu" >
                                                                    @foreach ($partners as $partner)
                                                                        <li><a href="javascript:void(0)" class="assignment-status" data-partner_id="{{ $partner->id}}" data-partner_fname="{{ $partner->first_name}}" data-partner_lname="{{ $partner->last_name}}">{{ $partner->first_name }} {{$partner->last_name}}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="xs-margin"></div>
                                                    <div style="padding-left: 15px;">
                                                        <input name="notify_partner_assignment_status" type="checkbox"
                                                               value="on">
                                                        Notify partner of the assignment status.
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="md-margin"></div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-actions text-center">
                                    <a href="javascript:void(0)" id="save-note" class="btn btn-red">Save &nbsp;<i
                                                class="fa fa-search"></i></a>&nbsp;
                                    <a href="javascript:void(0)" onclick="$('#frm-notes')[0].reset()"
                                       class="btn btn-green">Cancel &nbsp;<i
                                                class="glyphicon glyphicon-ban-circle"></i></a>
                                </div>

                            </div>
                        </div>

                        <div id="item-details" class="tab-pane fade">
                            <div class="portlet">
                                <div class="portlet-body">
                                    <table class="table checkout-table table-responsive">
                                        <thead>
                                        <tr>
                                            <th class="table-title">TYPES</th>
                                            <!-- <th class="table-title" style="text-align: right;">Qty</th> -->
                                            <th></th>
                                            <th class="table-title">ROOM CODE</th>
                                            <th class="table-title" style="text-align: center;" colspan="2">UNIT
                                                PRICE/NIGHT (NETT)</th>
                                            {{-- <th class="table-title" style="text-align: right;">Tax (RM)</th> --}}
                                            <th class="table-title" style="text-align: center;">SUBTOTAL</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $tax = 0; ?>
                                        @foreach ($orderTax->products as $orderProduct)
                                            <?php
                                            if ($orderTax->payment_method == 'PayPal') {
                                                $tax = ($tax + $orderProduct->gst) * $orderTax->rooms;
                                            } else {
                                                // $tax = $tax + $orderProduct->gst;
                                                $tax = $tax + ($orderProduct->amount * $orderProduct->gst_rate) / 100;
                                            }

                                            if ($booking_date->price_details != '') {
                                                $roomDates = json_decode($booking_date->price_details);
                                            } else {
                                                $checkAvailModel = new App\Http\Models\Front\CheckAvail();
                                                $roomDates = $checkAvailModel->getPriceByDates($orderProduct->id, $booking_date->date_checkin, $booking_date->date_checkout);
                                            }
                                            $priceByDates = '';

                                            foreach ($roomDates as $pd) {
                                                if ($orderTax->ota_checklist_id) {
                                                    $pd->sale_price = $orderProduct->amount_sold_at;
                                                }
                                                $priceByDates .= '<span>' . date('l', strtotime($pd->date)) . ', ' . date('d/M/Y', strtotime($pd->date)) . ' MYR ' . number_format($pd->sale_price, 2) . ' </span><br/>';
                                            }
                                            $gstData = DB::table('orders')
                                                ->where('id', '=', $orderProduct->order_id)
                                                ->first();
                                            if ($priceByDates == '') {
                                                $dates = DB::table('product_room_prices')
                                                    ->where('date', '>=', $booking_date->date_checkin)
                                                    ->where('date', '<', $booking_date->date_checkout)
                                                    ->where('product_id', $orderProduct->id)
                                                    ->orderBy('date')
                                                    ->get();
                                                foreach ($dates as $pd) {
                                                    if ($orderTax->ota_checklist_id) {
                                                        $pd->sale_price = $orderProduct->amount_sold_at;
                                                    }
                                                    $priceByDates .= '<span>' . date('l', strtotime($pd->date)) . ', ' . date('d/M/Y', strtotime($pd->date)) . ' MYR ' . number_format($pd->sale_price, 2) . ' </span><br/>';
                                                }
                                            }
                                            $propertyName = DB::table('property')
                                                ->where('property_id', '=', $orderProduct->property_id)
                                                ->first();
                                            ?>
                                            <tr>
                                                <td class="item-name-col" style="border-right: 0px !important;"
                                                    colspan="1">
                                                    <figure><a
                                                                href="{{ url('web88cms/products/editProduct/' . $orderProduct->product_id) }}">
                                                            @if ($orderProduct->product_thumb != null)
                                                                <img src="{{ asset('/public/admin/products/medium/' . $orderProduct->product_thumb) }}"
                                                                     alt="{{ $orderProduct->product_type }}"
                                                                     class="img-responsive">
                                                            @else
                                                                <img src="{{ asset('/public/admin/products/medium/' . $orderProduct->thumbnail_image_1) }}"
                                                                     alt="{{ $orderProduct->type }}"
                                                                     class="img-responsive">
                                                            @endif
                                                        </a>
                                                        <?php
                                                        $packages = DB::table('product_to_quantity_discount')->where('product_id', $orderProduct->product_id)->get();
                                                        ?>

                                                        @if (count($packages) > 0)
                                                            {{-- <span class="label" style="background-color: orangered;top: -13px !important;position: absolute !important;left: 50px !important;height: 30px;font-size: large;">This is the package</span> --}}
                                                            <span class="label" style="background-color: green;position: absolute !important;font-size:8px">{{ $packages[0]->package_name }}</span>
                                                        @endif

                                                        @if ($orderProduct->product_promo_behaviour != null)
                                                            @if ($orderProduct->product_promo_behaviour === 'sale')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/sale_label.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->product_promo_behaviour ===
                                                                'hot')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/hot_label.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->product_promo_behaviour ===
                                                                'new')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/new_label.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->product_promo_behaviour ===
                                                                'pwp')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/pwp_label.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->product_promo_behaviour ===
                                                                'last_minute')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/last_minute.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->product_promo_behaviour ===
                                                                '24hoursale')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/24hour_sale.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->product_promo_behaviour ===
                                                                'popular')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/popular.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->product_promo_behaviour ===
                                                                'early_bird')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/early_bird.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">

                                                            @elseif($orderProduct->product_promo_behaviour ===
                                                                'black_friday')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/black_friday.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->product_promo_behaviour ===
                                                                'singles_day')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/singles_day.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->product_promo_behaviour ===
                                                                'merdeka')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/merdeka.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->product_promo_behaviour ===
                                                                'valentines')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/valentine.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">

                                                            @endif
                                                        @else
                                                            @if ($orderProduct->promo_behaviour === 'sale')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/sale_label.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->promo_behaviour === 'hot')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/hot_label.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->promo_behaviour === 'new')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/new_label.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->promo_behaviour === 'pwp')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/pwp_label.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->promo_behaviour ===
                                                                'last_minute')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/last_minute.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->promo_behaviour === '24hoursale')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/24hour_sale.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->promo_behaviour === 'popular')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/popular.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->promo_behaviour === 'early_bird')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/early_bird.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">


                                                            @elseif($orderProduct->promo_behaviour ===
                                                                'black_friday')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/black_friday.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->promo_behaviour ===
                                                                'singles_day')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/singles_day.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->promo_behaviour === 'merdeka')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/merdeka.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @elseif($orderProduct->promo_behaviour === 'valentines')
                                                                <img class="promo"
                                                                     src="{{ asset('public/promo/valentine.png') }}"
                                                                     style="width: 35px !important;height: 35px !important;position: relative !important;top: -86px !important;left: 63px !important;">
                                                            @endif

                                                        @endif
                                                    </figure>

                                                    <header class="item-name">
                                                        @if ($orderProduct->product_type != null)
                                                            <a style="color:#A68A3A;font-weight: 600;font-size: 15px;"
                                                               href="{{ url('web88cms/products/editProduct/' . $orderProduct->product_id) }}">{{ $orderProduct->product_type }}</a>
                                                        @else
                                                            <a
                                                                    href="{{ url('web88cms/products/editProduct/' . $orderProduct->product_id) }}">{{ $orderProduct->type }}</a>
                                                        @endif
                                                        @if (!is_null($orderProduct->pwp_price))
                                                            <span class="pwp-item">PWP ITEM</span>
                                                        @endif
                                                    </header>
                                                    <ul>
                                                        @if ($orderProduct->color_name)
                                                            <li>Color: {{ $orderProduct->color_name }}</li>
                                                        @endif

                                                        @if ($orderProduct->event_type)
                                                            <li><i class="fa fa-gift text-red"></i> <span
                                                                        class="text-red"><b>For:
                                                                            {{ $orderProduct->event_type }}</b></span>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                    {{-- </td> --}}
                                                    {{-- <td colspan="2"> --}}
                                                    {{-- Property: <b>{{$propertyName->name}}</b><br> --}}
                                                    <div>
                                                        <div>
                                                            <i class="fa fa-bed"></i> <b>BED:</b><span
                                                                    class="text-black">
                                                                    {{ isset($orderTax->products[0]->product_bed) ? $orderTax->products[0]->product_bed : $orderTax->products[0]->bed }}
                                                                </span>
                                                        </div>
                                                        <div>
                                                            <i class="fa fa-user"></i> <b>GUEST:</b><span
                                                                    class="text-black">
                                                                    {{ isset($orderTax->products[0]->product_guest) ? $orderTax->products[0]->product_guest : $orderTax->products[0]->guest }}
                                                                </span>
                                                        </div>
                                                        <div>
                                                            <i class="fa fa-cutlery"></i> <b>MEAL:</b><span
                                                                    class="text-black">
                                                                    {{ isset($orderTax->products[0]->product_meal) ? $orderTax->products[0]->product_meal : $orderTax->products[0]->meal }}
                                                                </span>
                                                        </div>

                                                        @if ($orderTax->ota_checklist_id)
                                                            <div>
                                                                <i class="fa  fa-dot-circle-o"></i><span
                                                                        class="text-black lowest-price">
                                                                        <b>Lowest price available</b>
                                                                    </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td style="border-left: 0px !important;"></td>
                                                <td class="item-code" align="center">
                                                    {{ $orderProduct->product_code ? $orderProduct->product_code : $orderProduct->room_code }}
                                                </td>
{{--                                                <td colspan="2">--}}
{{--                                                    <div>--}}
{{--                                                        <span class="text-black">Property:</span><b>{{ $propertyName->name }}</b>--}}
{{--                                                    </div>--}}
{{--                                                    <div>--}}
{{--                                                        <span class="text-black">{{$prceByDates}}</span>--}}
{{--                                                    </div>--}}

{{--                                                    <div>--}}
{{--                                                        <span>Check-in:</span><b>{{ date('dS M, Y', strtotime($orderTax->check_date->date_checkin)) }}</b>--}}
{{--                                                    </div>--}}
{{--                                                    <div>--}}
{{--                                                        <span>Check-out:</span><b>{{ date('dS M, Y', strtotime($orderTax->check_date->date_checkout)) }}</b>--}}
{{--                                                    </div>--}}
{{--                                                    <div>--}}
{{--                                                        <span>Rooms:</span><b>{{$orderTax->rooms}}</b>--}}
{{--                                                    </div>--}}
{{--                                                    <div>--}}
{{--                                                        <span>Adutls:</span><b>{{$orderTax->adults}}</b>--}}
{{--                                                    </div>--}}
{{--                                                    <div>--}}
{{--                                                        <span>Children:</span><b>{{$orderTax->children}}</b>--}}
{{--                                                    </div>--}}
{{--                                                    --}}{{-- {{ ($isReverseCalculated)?number_format(reverseCalculation($orderProduct->subtotal*$orderTax->rooms,$TaxRate), 2):number_format($orderProduct->subtotal*$orderTax->rooms, 2) }} --}}
{{--                                                </td>--}}
                                                {{-- <td class="item-total-col" align="right"> --}}
                                                {{-- <span class="item-price-special"> --}}
                                                {{-- {{ ($isReverseCalculated)?number_format($orderProduct->subtotal*$orderTax->rooms-reverseCalculation($orderProduct->subtotal*$orderTax->rooms,$TaxRate), 2):number_format($tax,2) }} --}}
                                                {{-- </span> --}}
                                                {{-- </td> --}}
                                                <td class="item-total-col" align="center"><span
                                                            class="item-price-special">
                                                            RM
                                                            {{ $isReverseCalculated ? number_format(reverseCalculation($orderProduct->subtotal * $orderTax->rooms, $TaxRate), 2) : number_format($orderTax->subtotal, 2) }}
                                                        </span>
                                                </td>
                                            </tr>
                                        @endforeach

                                        <tr style="border-bottom:1px solid #ddd;">
                                            <td colspan="3"></td>
                                            <td style="text-align: left;" colspan="2">SUBTOTAL:</td>
                                            <td align="center" colspan="2">
                                                <?php
                                                $subtotalPrice = $isReverseCalculated ? number_format(reverseCalculation($orderProduct->subtotal * $orderTax->rooms, $TaxRate), 2) : number_format($orderTax->subtotal, 2);
                                                ?>
                                                RM {{ $subtotalPrice }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $rate = explode(',', $gstData->tax_name);
                                            $tax_name = $gstData->tax_name;
                                            $taxRate0 = isset($TaxRate[0]) ? $TaxRate[0]->rate : '';
                                            ?>
                                            <td colspan="3"></td>
                                            <td style="text-align: left;" colspan="2">
                                                {{ isset($rate[0]) ? $rate[0] : $tax_name }}
                                                ({{ isset($rate[1]) ? $rate[1] : $taxRate0 }}%):</td>
                                            {{-- <td align="right"> --}}
                                            {{-- {{ ($isReverseCalculated)?intval($TaxRate[0]->rate): number_format((( $tax*100)/($orderTax->subtotal*$orderTax->rooms)),2) }} --}}
                                            {{-- % --}}
                                            {{-- </td> --}}

                                            <?php
                                            $tax = $isReverseCalculated ? number_format($orderProduct->subtotal * $orderTax->rooms - reverseCalculation($orderProduct->subtotal * $orderTax->rooms, $TaxRate), 2) : number_format($tax, 2);
                                            ?>
                                            <td align="center" colspan="2">RM <span
                                                        class="item-price-special">{{ $tax }}</span></td>
                                        </tr>

                                        @if (isset($TaxRate) && isset($TaxRate[1]->rate))
                                            <tr>
                                                <td style="text-align: left;" colspan="2">
                                                    {{ isset($TaxRate[0]->name) ? $TaxRate[1]->name : 'TAX' }}:</td>
                                                <td align="center" colspan="2">RM <span
                                                            class="item-price-special"><?php if ($isReverseCalculated) {
                                                            $totalPrice = $orderProduct->subtotal * $orderTax->rooms;
                                                            $cal1 = $totalPrice / (1 + $TaxRate[1]->rate);
                                                            $tax = $cal1;
                                                            echo $cal1;
                                                        } else {
                                                            echo '0.00';
                                                        }
                                                        ?></span></td>
                                            </tr>
                                        @endif
                                        <tr style="border-bottom:1px solid #ddd;">
                                            <td colspan="3"></td>
                                            <td style="text-align: left;" class="text-red" colspan="2">
                                                DISCOUNT:</td>
                                            <td class="text-red" colspan="2" align="center"> RM
                                                {{ number_format($orderTax->discount, 2) }}</td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td style="border: none; text-transform: none; text-align: left;"
                                                colspan="2"><b>Total:</b></td>
                                            <td align="center" colspan="2">
                                                <b style="color:#A68A3A;">
                                                    RM
                                                    <?php
                                                    $subtotalPrice = (float)str_replace(",","",$subtotalPrice);
                                                    $v1 = number_format((($subtotalPrice * $orderTax->rooms) + $tax) - $orderTax->discount, 2);
                                                    echo $v1;
                                                    ?>
                                                    </br>
                                            </td>
                                        </tr>
                                        <tr>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    <div class="clearfix"></div>

                                    @if (isset($order->special_requests))
                                        <div class="row result-container">
                                            <div class="col-md-12 review-comment-form box-radius"
                                                 style="padding:15px;border: 10px solid #f2f2f2; border: 10px solid #ededed;padding: 30px;font-weight: bold;margin-bottom: 30px;">
                                                <h4>Special Requests</h4>
                                                <p>Please write requests in English or the property's language</p>
                                                <textarea class="form-control" name="special_requests"
                                                          id="special_requests" rows="5" readonly><?php echo $order->special_requests; ?></textarea>
                                                <div class="clearfix"></div><br />
                                                <div class="alert alert-warning" role="alert">
                                                    <strong>Note:</strong> Don't disclose any additional personal or payment
                                                    information in your request.
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="single-room list mobile-extend">
                                                <div class="room-info">
                                                    <div class="room-description clearfix">

                                                        @if (isset($orderProduct->terms_and_conditions))
                                                            <h4 class="text-uppercase">Terms and Conditions</h4>
                                                            <?php echo $orderProduct->terms_and_conditions; ?>
                                                        @endif

                                                        <br />

                                                        @if (isset($orderProduct->cancellation_policy))
                                                            <h4 class="margin-top">Cancellation Policy</h4>
                                                            <?php echo $orderProduct->cancellation_policy; ?>
                                                        @endif

                                                        <div class="clearfix"></div>

                                                        <hr>

                                                        <div class="text-center">
                                                            <a href="{{ route('orderDetailsBack', ['id' => $bookid]) }}"
                                                               class="btn btn-default btn-sm">Back</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @if ($customer)
                            <div id="customer-info" class="tab-pane fade">
                                <div class="portlet">
                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- customer information start -->
                                                <h4 class="block-heading"><i class="fa fa-user"></i> Customer
                                                    Information</h4>
                                                <div class="md-margin-2x"></div>
                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Customer
                                                        Name:</label>
                                                    <div class="col-md-8">
                                                        <p><a
                                                                    href="{{ url('web88cms/customers/view/' . $customer->id) }}"><strong>{{ $customer->first_name . ' ' . $customer->last_name }}</strong></a>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Email:
                                                    </label>
                                                    <div class="col-md-8">
                                                        <p><strong>{{ $customer->email }}</strong></p>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Telephone:
                                                    </label>
                                                    <div class="col-md-8">
                                                        <p><strong>{{ $customer->telephone }}</strong></p>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Birth Date:
                                                    </label>
                                                    <div class="col-md-8">
                                                        <p><strong>{{ date('dS F, Y', strtotime($customer->birth_date)) }}</strong>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Address:
                                                    </label>
                                                    <div class="col-md-8">
                                                        <p><strong>{{ $customer->billing_address }},
                                                                {{ $customer->billing_post_code }}
                                                                {{ $customer->billing_city }},
                                                                {{ $customer->billing_state_name }},
                                                                {{ $customer->billing_country_name }}</strong></p>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>

                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Past Orders:
                                                    </label>
                                                    <div class="col-md-8">
                                                        <p><strong><span
                                                                        class="badge badge-red">{{ $customerTotalOrders - 1 }}</span></strong>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>

                                                <div class="form-actions text-center">
                                                    <a href="{{ url('web88cms/customers/view/' . $customer->id) }}"
                                                       class="btn btn-success">View / Edit &nbsp;<i
                                                                class="fa fa-pencil"></i></a>&nbsp;
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="lg-margin"></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4 class="block-heading"><i class="fa fa-truck"></i> Shipping
                                                    Information (Default)</h4>
                                                <div class="md-margin-2x"></div>

                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Delivery
                                                        Address Name:</label>
                                                    <div class="col-md-8">
                                                        <p><a
                                                                    href="{{ url('web88cms/customers/view/' . $customer->id) }}"><strong>{{ $customer->shipping_first_name . ' ' . $customer->shipping_last_name }}</strong></a>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>

                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Email:
                                                    </label>
                                                    <div class="col-md-8">
                                                        <p><strong>{{ $customer->shipping_email }}</strong></p>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Telephone:
                                                    </label>
                                                    <div class="col-md-8">
                                                        <p><strong>{{ $customer->shipping_telephone }}</strong></p>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputFirstName" class="col-md-4 control-label">Address:
                                                    </label>
                                                    <div class="col-md-8">
                                                        <p><strong>{{ $customer->shipping_address }},
                                                                {{ $customer->shipping_post_code }}
                                                                {{ $customer->shipping_city }},
                                                                {{ $customer->shipping_state_name }},
                                                                {{ $customer->shipping_country_name }}</strong></p>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>

                                                <div class="form-actions text-center">
                                                    <a href="{{ url('web88cms/customers/view/' . $customer->id) }}"
                                                       class="btn btn-success">View / Edit &nbsp;<i
                                                                class="fa fa-pencil"></i></a>&nbsp;
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>

                                            <div class="col-md-6">
                                                @if ($order->payment_method == 'Credit Card')
                                                    <h4 class="block-heading"><i class="fa fa-tag"></i> Billing
                                                        Information (Default)</h4>
                                                    <div class="md-margin-2x"></div>
                                                    <div class="form-group">
                                                        <label for="inputFirstName" class="col-md-4 control-label">Credit
                                                            Card Type :</label>
                                                        <div class="col-md-8">
                                                            <p><strong>{{ $creditCardInfo->card_type }}</strong><img
                                                                        src="{{ $creditCardInfo->card_type == 'Visa' ? asset('public/images/checkout/visa.png') : asset('public/images/checkout/img_mastercard.png') }}">
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputFirstName" class="col-md-4 control-label">Credit
                                                            Card Holder :</label>
                                                        <div class="col-md-8">
                                                            <p>{{-- <a href="{{ url('web88cms/customers/view/' . $creditCardInfo->cus_id)}}"> --}}<strong>{{ $creditCardInfo->cus_name }}</strong>{{-- </a> --}}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>

                                                    <div class="form-group">
                                                        <label for="inputFirstName" class="col-md-4 control-label">Card
                                                            Number : </label>
                                                        <div class="col-md-8">
                                                            <p><strong>{{ $creditCardInfo->card_num }}</strong></p>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputFirstName" class="col-md-4 control-label">CVC/CVV2
                                                            : </label>
                                                        <div class="col-md-8">
                                                            <p><strong>{{ $creditCardInfo->cvc_cvv2 }}</strong></p>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputFirstName"
                                                               class="col-md-4 control-label">Expiration Date : </label>
                                                        <div class="col-md-8">
                                                            <p><strong>
                                                                    {{ $creditCardInfo->exp_month < 10 ? '0' . $creditCardInfo->exp_month : $creditCardInfo->exp_month }}-{{ $creditCardInfo->exp_year }}
                                                                </strong></p>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="clearfix"></div>
                                            <div class="form-actions text-center">
                                                <a href="{{ url('web88cms/customers/view/'.$creditCardInfo->cus_id)}}" class="btn btn-success">View / Edit &nbsp;<i class="fa fa-pencil"></i></a>&nbsp;
                                            </div>
                                            <div class="clearfix"></div> --}}
                                                @else
                                                    <h4 class="block-heading"><i class="fa fa-tag"></i> Billing
                                                        Information (Default)</h4>
                                                    <div class="md-margin-2x"></div>
                                                    <div class="form-group">
                                                        <label for="inputFirstName" class="col-md-4 control-label">Billing
                                                            Name:</label>
                                                        <div class="col-md-8">
                                                            <p><a
                                                                        href="{{ url('web88cms/customers/view/' . $customer->id) }}"><strong>{{ $customer->billing_first_name . ' ' . $customer->billing_last_name }}</strong></a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>

                                                    <div class="form-group">
                                                        <label for="inputFirstName" class="col-md-4 control-label">Email:
                                                        </label>
                                                        <div class="col-md-8">
                                                            <p><strong>{{ $customer->billing_email }}</strong></p>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputFirstName"
                                                               class="col-md-4 control-label">Telephone: </label>
                                                        <div class="col-md-8">
                                                            <p><strong>{{ $customer->billing_telephone }}</strong></p>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputFirstName" class="col-md-4 control-label">Address:
                                                        </label>
                                                        <div class="col-md-8">
                                                            <p><strong>
                                                                    {{ $customer->billing_address }},
                                                                    {{ $customer->billing_post_code }}
                                                                    {{ $customer->billing_city }},
                                                                    {{ $customer->billing_state_name }},
                                                                    {{ $customer->billing_country_name }}
                                                                </strong></p>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="form-actions text-center">
                                                        <a href="{{ url('web88cms/customers/view/' . $customer->id) }}"
                                                           class="btn btn-success">View / Edit &nbsp;<i
                                                                    class="fa fa-pencil"></i></a>&nbsp;
                                                    </div>
                                                    <div class="clearfix"></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="page-footer">
            <div class="copyright">
                <span class="text-15px">2015 ?? <a href="http://www.webqom.com" target="_blank">Webqom Technologies Sdn
                        Bhd.</a> Any queries, please contact <a href="mailto:support@webqom.com">Webqom Support</a>.</span>
                <div class="pull-right"><img src="{{ asset('/public/admin/images/logo_webqom.png') }}"
                                             alt="Webqom Technologies Sdn Bhd"></div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $('#save-shipping-address').click(function() {
                var obj = $(this);
                $.ajax({
                    url: "{{ url('web88cms/orders/saveShippingAddress/' . $order->id) }}",
                    type: 'POST',
                    dataType: 'json',
                    data: $('#edit-shipping-address').serialize(),
                    beforeSend: function() {
                        obj.html('Saving... <i class="fa fa-floppy-o"></i>');
                    },
                    complete: function() {
                        obj.html('Save <i class="fa fa-floppy-o"></i>');
                    },
                    success: function(response) {
                        var html = '';

                        $('#warning-box').remove();
                        $('#success-box').remove();

                        if (response['error']) {
                            html +=
                                '<div id="warning-box" class="alert alert-danger alert-dismissable">';
                            html +=
                                '<button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>';
                            html +=
                                '<i class="fa fa-times-circle"></i> <strong>Error!</strong>';

                            for (var i = 0; i < response['error'].length; i++) {
                                html += '<p>' + response['error'][i] + '</p>';
                            }

                            html += '</div>';
                            $('#edit-shipping-address').before(html);
                        }

                        if (response['success']) {
                            window.location.reload();
                        }
                    }
                });
            });
        });

        $(function() {
            $('#save-billing-address').click(function() {
                var obj = $(this);
                $.ajax({
                    url: "{{ url('web88cms/orders/saveBillingAddress/' . $order->id) }}",
                    type: 'POST',
                    dataType: 'json',
                    data: $('#edit-billing-address').serialize(),
                    beforeSend: function() {
                        obj.html('Saving... <i class="fa fa-floppy-o"></i>');
                    },
                    complete: function() {
                        obj.html('Save <i class="fa fa-floppy-o"></i>');
                    },
                    success: function(response) {
                        var html = '';

                        $('#warning-box').remove();
                        $('#success-box').remove();

                        if (response['error']) {
                            html +=
                                '<div id="warning-box" class="alert alert-danger alert-dismissable">';
                            html +=
                                '<button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>';
                            html +=
                                '<i class="fa fa-times-circle"></i> <strong>Error!</strong>';

                            for (var i = 0; i < response['error'].length; i++) {
                                html += '<p>' + response['error'][i] + '</p>';
                            }

                            html += '</div>';
                            $('#edit-billing-address').before(html);
                        }

                        if (response['success']) {
                            window.location.reload();
                        }
                    }
                });
            });
        });

        $(function() {
            $('.order-status').click(function() {
                var obj = $(this);
                var status = obj.attr('data-status');
                var notify = $('input[name=notify_customer_order_status]:checked').val();

                $.ajax({
                    url: "{{ url('web88cms/orders/updateOrderStatus/' . $order->id) }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        status: status,
                        notify: notify,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        $('#order-status').html('Saving... ');
                    },
                    complete: function() {
                        $('#order-status').html('{{ $order->status }}');
                    },
                    success: function(response) {
                        if (response['success']) {
                            window.location.reload();
                        }
                    }
                });
            });
        });

        $(function() {
            $('.payment-status').click(function() {
                var obj = $(this);
                var status = obj.attr('data-status');
                console.log(status)
                var notify = $('input[name=notify_customer_payment_status]:checked').val();

                $.ajax({
                    url: "{{ url('web88cms/orders/updatePaymentStatus/' . $order->id) }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        status: status,
                        notify: notify,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        $('#payment-status').html('Saving... ');
                    },
                    complete: function() {
                        $('#payment-status').html('{{ $order->payment_status }}');
                    },
                    success: function(response) {
                        if (response['success']) {
                            window.location.reload();
                        }
                    }
                });
            });
        });

        $(function() {
            $('.assignment-status').click(function() {
                var obj = $(this);
                console.log(obj);
                var order_id = <?php echo $order->id; ?> ;
                var notify = $('input[name=notify_partner_assignment_status]:checked').val();
                var partnerId = obj.attr('data-partner_id');
                var partnerFname = obj.attr('data-partner_fname');
                var partnerLname = obj.attr('data-partner_lname');

                // var partnerId=356;
                console.log(partnerId)
                console.log(notify)
                $.ajax({
                    url: "{{ url('web88cms/orders/update-assignment-status') }}/"+ partnerId,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        order_id: order_id,
                        notify: notify,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        $('#selectedPartner').html('Saving... ');
                    },
                    complete: function() {
                        $('#selectedPartner').html(partnerFname + partnerLname);
                        $('#partner_assign').html(partnerFname + partnerLname);
                    },
                    success: function(response) {
                        console.log(response);
                        $('#partner_assign').html(partnerFname + partnerLname);
                        if (response['success']) {
                            {{--$('#partner_assign').html('{{ $partner->first_name }} {{ $partner->last_name }}');--}}
                            // window.location.reload();
                        }
                    }
                });
            });
        });

        $(function() {
            $('#add-new-shipment').click(function() {
                var obj = $(this);
                $('#warning-box').remove();

                $.ajax({
                    url: "{{ url('web88cms/orders/addNewShipment/' . $order->id) }}",
                    type: 'POST',
                    dataType: 'json',
                    data: $('#new-shipment').serialize(),
                    beforeSend: function() {
                        $('#add-new-shipment').html('Saving... ');
                    },
                    complete: function() {
                        $('#add-new-shipment').html(
                            'Save &nbsp;<i class="fa fa-floppy-o"></i>');
                    },
                    success: function(response) {
                        if (response['error']) {
                            var html = '';
                            html +=
                                '<div id="warning-box" class="alert alert-danger alert-dismissable">';
                            html +=
                                '<button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>';
                            html +=
                                '<i class="fa fa-times-circle"></i> <strong>Error!</strong>';

                            for (var i = 0; i < response['error'].length; i++) {
                                html += '<p>' + response['error'][i] + '</p>';
                            }

                            html += '</div>';
                            $('#new-shipment').before(html);
                        }

                        if (response['success']) {
                            window.location.reload();
                        }
                    }
                });
            });
        });

        $(function() {
            $('#save-note').click(function() {
                $('#success-box').remove();

                $.ajax({
                    url: "{{ url('web88cms/orders/editNote/' . $order->id) }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        customer_notes: $('#customer_notes').val(),
                        staff_notes: $('#staff_notes').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        $('#save-note').html('Saving... ');
                    },
                    complete: function() {
                        $('#save-note').html('Save &nbsp;<i class="fa fa-search"></i>');
                    },
                    success: function(response) {
                        if (response['success']) {
                            var html = '';
                            html += '<div class="alert alert-success alert-dismissable">';
                            html +=
                                '<button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>';
                            html +=
                                '<i class="fa fa-check-circle"></i> <strong>Success!</strong>';
                            html += '<p>' + response['success'] + '</p>';
                            html += '</div> ';

                            $('#myTab').before(html);
                            $('html, body').animate({
                                scrollTop: 0
                            }, 'fast');
                        }
                    }
                });
            });
        });
    </script>
@endsection
