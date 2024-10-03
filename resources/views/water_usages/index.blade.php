@extends('layout')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h3>Data Penggunaan Air</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('water_usages.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label for="month" class="form-label">Pilih Bulan</label>
                    <select name="month" class="form-select">
                        <option value="">Semua Bulan</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="year" class="form-label">Pilih Tahun</label>
                    <select name="year" class="form-select">
                        <option value="">Semua Tahun</option>
                        @for ($y = date('Y'); $y >= 2000; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success">Filter</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Penggunaan Air (m³)</th>
                    <th>Biaya Pemeliharaan</th>
                    <th>Denda Keterlambatan</th>
                    <th>Total Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usages as $usage)
                <tr>
                    <td>{{ $usage->customer_name }}</td>
                    <td>{{ \Carbon\Carbon::create()->month($usage->month)->format('F') }}</td>
                    <td>{{ $usage->year }}</td>
                    <td>{{ $usage->water_usage }}</td>
                    <td>{{ number_format($usage->maintenance_fee, 0, ',', '.') }}</td>
                    <td>{{ number_format($usage->late_fee, 0, ',', '.') }}</td>
                    <td>{{ number_format($usage->total_payment, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data penggunaan air</td>
                </tr>
                @endforelse
            </tbody>
        </table>        
        <a href="{{ route('water_usages.export') }}" class="btn btn-success mb-3">Export ke Excel</a>
        <!-- Pagination links -->
        {{ $usages->links() }}
    </div>
</div>
@endsection
