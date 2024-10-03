@extends('layout')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h3>Input Pembayaran PDAM</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('water_usages.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="customer_name" class="form-label">Nama Pelanggan</label>
                <input type="text" name="customer_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="usage_date" class="form-label">Tanggal Penggunaan</label>
                <input type="date" name="usage_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Golongan Rumah</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">Pilih Golongan</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                                data-rate-0-10="{{ $category->rate_0_10 }}" 
                                data-rate-11-20="{{ $category->rate_11_20 }}" 
                                data-rate-21-plus="{{ $category->rate_21_plus }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="meter_size" class="form-label">Ukuran Meter Air</label>
                <select name="meter_size" id="meter_size" class="form-control" required>
                    <option value="">Pilih Ukuran Meter</option>
                    <option value="1">½”</option>
                    <option value="2">¾”</option>
                    <option value="3">1”</option>
                    <option value="4">1 ½”</option>
                    <option value="5">2”</option>
                    <option value="6">3”</option>
                </select>
            </div>            
            <div class="mb-3">
                <label for="water_usage" class="form-label">Penggunaan Air (m³)</label>
                <input type="number" name="water_usage" step="0.01" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="payment_delay" class="form-label">Keterlambatan Pembayaran (bulan)</label>
                <input type="number" name="payment_delay" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="total_payment" class="form-label">Total Pembayaran</label>
                <input type="text" name="total_payment" id="total_payment" class="form-control" readonly>
                <!-- Tambahkan hidden input agar tetap terkirim -->
                <input type="hidden" name="hidden_total_payment" id="hidden_total_payment">
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>
</div>

<script>
    const categorySelect = document.getElementById('category');
    const waterUsageInput = document.querySelector('input[name="water_usage"]');
    const totalPaymentInput = document.getElementById('total_payment');
    const hiddenTotalPaymentInput = document.getElementById('hidden_total_payment'); // hidden input untuk total payment

    function calculateTotal() {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const usage = parseFloat(waterUsageInput.value) || 0;

        let total = 0;

        // Ambil tarif berdasarkan kategori
        const rate0_10 = parseFloat(selectedOption.getAttribute('data-rate-0-10')) || 0;
        const rate11_20 = parseFloat(selectedOption.getAttribute('data-rate-11-20')) || 0;
        const rate21Plus = parseFloat(selectedOption.getAttribute('data-rate-21-plus')) || 0;

        // Hitung total berdasarkan pemakaian
        if (usage <= 10) {
            total = usage * rate0_10;
        } else if (usage <= 20) {
            total = (10 * rate0_10) + ((usage - 10) * rate11_20);
        } else {
            total = (10 * rate0_10) + (10 * rate11_20) + ((usage - 20) * rate21Plus);
        }

        totalPaymentInput.value = total.toFixed(2);
        hiddenTotalPaymentInput.value = total.toFixed(2); // set hidden input value
    }

    categorySelect.addEventListener('change', calculateTotal);
    waterUsageInput.addEventListener('input', calculateTotal);
</script>
@endsection
