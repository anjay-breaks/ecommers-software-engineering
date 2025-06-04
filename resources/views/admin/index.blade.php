@extends('layouts.admin')

@section('content')
{{-- <style>
    /* Pastikan style ini tidak bertabrakan dengan style global di layouts.admin Anda */
    /* Anda mungkin perlu menyesuaikan selector jika ada konflik */
    .admin-welcome-page {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 150px); /* Sesuaikan pengurangan tinggi header/footer jika ada */
        padding: 20px;
        background-color: #f4f6f9; /* Warna latar belakang admin yang umum */
        text-align: center;
    }

    .welcome-card {
        background-color: #ffffff;
        padding: 40px 50px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        max-width: 700px;
        width: 100%;
        animation: fadeInScale 0.8s ease-out forwards;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .welcome-card .icon {
        font-size: 48px; /* Ukuran ikon */
        color: #007bff; /* Warna primer, sesuaikan dengan tema admin Anda */
        margin-bottom: 20px;
        /* Anda bisa menggunakan pustaka ikon seperti Font Awesome atau SVG di sini */
        /* Contoh dengan teks (bisa diganti dengan tag <i> atau <img>) */
        display: inline-block; /* agar margin-bottom bekerja */
    }

    .welcome-card h1 {
        font-size: 2.5rem; /* Ukuran font lebih besar untuk judul */
        color: #343a40; /* Warna teks gelap */
        margin-bottom: 15px;
        font-weight: 600;
    }

    .welcome-card p {
        font-size: 1.1rem;
        color: #6c757d; /* Warna teks abu-abu */
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .welcome-actions a {
        display: inline-block;
        padding: 12px 25px;
        margin: 0 10px;
        border-radius: 25px; /* Tombol lebih bulat */
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary-custom {
        background-color: #007bff;
        color: white;
        border: 1px solid #007bff;
    }

    .btn-primary-custom:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
    }

    .btn-secondary-custom {
        background-color: transparent;
        color: #007bff;
        border: 1px solid #007bff;
    }

    .btn-secondary-custom:hover {
        background-color: #007bff;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
    }

    /* Untuk ikon, jika Anda menggunakan Font Awesome, pastikan sudah terpasang */
    /* @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css'); */

</style> --}}

{{-- <div class="admin-welcome-page">
    <div class="welcome-card">
        <div class="icon">🎉</div>

        <h1>Selamat Datang Kembali, Admin!</h1>
        <p>
            Ini adalah pusat kendali Anda. Dari sini, Anda dapat mengelola pengguna,
            melihat laporan, mengatur konten, dan menjalankan berbagai tugas administratif lainnya.
            Semoga hari Anda produktif!
        </p>
        <div class="welcome-actions">
            <a href="#" class="btn-primary-custom">Ke Dashboard Utama</a>
            <a href="#" class="btn-secondary-custom">Lihat Notifikasi</a>
        </div>
    </div>
</div> --}}

    <div class="main-content-inner">

        <div class="main-content-wrap">

            <div class="tf-section mb-30">

                <div class="wg-box">
                    <div class="flex items-center justify-between">
                        <h5>Recent orders</h5>
                        <div class="dropdown default">
                            <a class="btn btn-secondary dropdown-toggle" href="{{ route('admin.orders') }}">
                                <span class="view-all">View all</span>
                            </a>
                        </div>
                    </div>
                    <div class="wg-table table-all-user">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:70px">OrderNo</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Phone</th>
                                        <th class="text-center">Subtotal</th>
                                        <th class="text-center">Tax</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Order Date</th>
                                        <th class="text-center">Total Items</th>
                                        <th class="text-center">Delivered On</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="text-center">{{ $order->id }}</td>
                                            <td class="text-center">{{ $order->name }}</td>
                                            <td class="text-center">{{ $order->phone }}</td>
                                            <td class="text-center">${{ $order->subtotal }}</td>
                                            <td class="text-center">${{ $order->tax }}</td>
                                            <td class="text-center">${{ $order->total }}</td>
                                            <td class="text-center">
                                                @if ($order->status == 'delivered')
                                                    <span class="badge bg-success">Delivered</span>
                                                @elseif ($order->status == 'canceled')
                                                    <span class="badge bg-danger">Canceled</span>
                                                @else
                                                    <span class="badge bg-warning">Ordered</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $order->created_at }}</td>
                                            <td class="text-center">{{ $order->orderItem->count() }}</td>
                                            <td class="text-center">{{ $order->delivered_date }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.order.details', ['order_id' => $order->id]) }}">
                                                    <div class="list-icon-function view-icon">
                                                        <div class="item eye">
                                                            <i class="icon-eye"></i>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
{{--
@push('scripts')
<script>
        (function ($) {

            var tfLineChart = (function () {

                var chartBar = function () {

                    var options = {
                        series: [{
                            name: 'Total',
                            data: [{{ $AmountM }}]
                        }, {
                            name: 'Pending',
                            data: [{{ $OrderedAmountM }}]
                        },
                        {
                            name: 'Delivered',
                            data: [{{ $DeliveredAmountM }}]
                        }, {
                            name: 'Canceled',
                            data: [{{ $CanceledAmountM }}]
                        }],
                        chart: {
                            type: 'bar',
                            height: 325,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '10px',
                                endingShape: 'rounded'
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        legend: {
                            show: false,
                        },
                        colors: ['#2377FC', '#FFA500', '#078407', '#FF0000'],
                        stroke: {
                            show: false,
                        },
                        xaxis: {
                            labels: {
                                style: {
                                    colors: '#212529',
                                },
                            },
                            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        },
                        yaxis: {
                            show: false,
                        },
                        fill: {
                            opacity: 1
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return "$ " + val + ""
                                }
                            }
                        }
                    };

                    chart = new ApexCharts(
                        document.querySelector("#line-chart-8"),
                        options
                    );
                    if ($("#line-chart-8").length > 0) {
                        chart.render();
                    }
                };

                /* Function ============ */
                return {
                    init: function () { },

                    load: function () {
                        chartBar();
                    },
                    resize: function () { },
                };
            })();

            jQuery(document).ready(function () { });

            jQuery(window).on("load", function () {
                tfLineChart.load();
            });

            jQuery(window).on("resize", function () { });
        })(jQuery);
    </script>
@endpush --}}
